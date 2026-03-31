<?php

namespace App\Actions\Attachment;

use App\Models\Attachment;
use App\Support\Logger;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class AttachFileAction
{
    public function execute($model, UploadedFile $file, string $folder)
    {
        $hash = hash_file('sha256', $file->getRealPath());
        $attachment = Attachment::where('hash', $hash)->first();
        if ($attachment) {
            Logger::error('Attachment already used in another payment', [
                'model_id' => $attachment->attachable_id,
                'model_type' => $attachment->attachable_type,
            ]);
            throw ValidationException::withMessages([
                'attachment' => "Este comprovante já foi utilizado em outro pagamento.",
            ]);
        }

        $disk = config('filesystems.default')
            ?? throw new RuntimeException('Nenhum disco de arquivos configurado!');

        $path = $file->store($folder, $disk);
        return $model->attachments()->create([
            'original_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'hash' => $hash,
        ]);
    }
}
