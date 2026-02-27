<?php

namespace App\Supports\Utils;

use App\Models\File;
use Illuminate\Http\UploadedFile;

trait Upload
{
    public function upload(Array|string|UploadedFile $file, string $path): File
    {
        if ($file instanceof UploadedFile) {
            $path = $file->store($path, 'public');

            return File::create([
                'filename' => $file->hashName(),
                'original_filename' => $file->getClientOriginalName(),
                'extension' => $file->getClientOriginalExtension(),
                'storage_path' => $path,
            ]);
        }

        return File::whereUuid($file)->first();
    }
}