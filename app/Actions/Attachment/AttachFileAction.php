<?php

namespace App\Actions\Attachment;

use App\Models\Attachment;
use Illuminate\Http\UploadedFile;
use RuntimeException;

class AttachFileAction
{
    public function execute($model, UploadedFile $file, string $folder)
    {
        $hash = hash_file('sha256', $file->getRealPath());
        $attachment = Attachment::where('hash', $hash)->first();
        if ($attachment) {
            return $attachment;
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
