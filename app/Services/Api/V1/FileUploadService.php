<?php

namespace App\Services\Api\V1;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    /**
     * Upload a file and return the stored path.
     *
     * @param  UploadedFile  $file  The file to upload.
     * @param  string  $directory  The directory to store the file in.
     * @param  string  $disk  The disk to use for storage.
     * @return string|false The path of the stored file, or false on failure.
     */
    public function upload(UploadedFile $file, string $directory = 'uploads', string $disk = 'public'): string|false
    {
        // Generate a unique filename using UUID
        $filename = Str::uuid().'.'.$file->getClientOriginalExtension();

        // Store the file and return the path
        return $file->storeAs($directory, $filename, $disk);
    }

    /**
     * Upload multiple files and return the stored paths.
     *
     * @param  array  $files  The files to upload.
     * @param  string  $directory  The directory to store the files in.
     * @param  string  $disk  The disk to use for storage.
     * @return array The paths of the stored files.
     */
    public function uploadMany(array $files, string $directory = 'uploads', string $disk = 'public'): array
    {
        $paths = [];

        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $path = $this->upload($file, $directory, $disk);
                if ($path) {
                    $paths[] = $path;
                }
            }
        }

        return $paths;
    }

    /**
     * Delete a file from storage.
     *
     * @param  string|null  $path  The path of the file to delete.
     * @param  string  $disk  The disk the file is stored on.
     * @return bool True if deleted, false otherwise.
     */
    public function delete(?string $path, string $disk = 'public'): bool
    {
        if (! $path) {
            return false;
        }

        if (Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->delete($path);
        }

        return false;
    }
}
