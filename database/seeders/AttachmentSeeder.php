<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attachment;
use Illuminate\Support\Str;

class AttachmentSeeder extends Seeder
{
    public function run(): void
    {
        Attachment::create([
            'uuid' => Str::uuid(),
            'disk' => 'public',
            'file_path' => 'attachments/example.pdf',
            'original_name' => 'example.pdf',
            'mime_type' => 'application/pdf',
            'size' => 102400,
            'hash' => hash('sha256', 'example-file')
        ]);
    }
}
