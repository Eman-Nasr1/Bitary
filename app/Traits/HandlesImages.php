<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HandlesImages
{
    /**
     * Upload a new image
     */
    public function uploadImage(UploadedFile $file, string $folder = 'animals'): string
    {
        // Store file in storage/app/public/{folder}
        $path = $file->store($folder, 'public');
        
        return $path;
    }

    /**
     * Update an existing image (delete old one if exists)
     */
    public function updateImage(?string $oldPath, UploadedFile $file, string $folder = 'animals'): string
    {
        // Delete old image if exists
        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        return $this->uploadImage($file, $folder);
    }
}
