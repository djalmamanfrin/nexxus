<?php

namespace App\Actions\Attachment;

class AttachFileAction
{
    public function execute($model, $file, string $folder = 'attachments')
    {
        $path = $file->store($folder, 'public');

        return $model->attachments()->create([
            'path' => $path,
            'filename' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);
    }
}
