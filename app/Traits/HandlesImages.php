<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

trait HandlesImages
{
    /**
     * Upload a new image
     */
    public function uploadImage(UploadedFile $file, string $folder = 'animals'): string
    {
        // Generate unique name
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        // Move the file to public folder
        $file->move(public_path($folder), $filename);

        return $folder . '/' . $filename;
    }

    /**
     * Update an existing image (delete old one if exists)
     */
    public function updateImage(?string $oldPath, UploadedFile $file, string $folder = 'animals'): string
    {
        // Delete old image if exists
        if ($oldPath && file_exists(public_path($oldPath))) {
            unlink(public_path($oldPath));
        }

        return $this->uploadImage($file, $folder);
    }
}
