<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

class FileUploadService
{
    public function __construct(private readonly ImageManager $imageManager)
    {
    }

    /**
     * Store an uploaded image to the public disk under the given directory
     * with a random UUID filename. Large images are resized and re-encoded to
     * JPEG to keep the payload small.
     */
    public function storeImage(UploadedFile $file, string $directory): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = Str::uuid().'.'.$extension;

        $path = $this->optimizeAndSave($file, $directory.'/'.$filename);

        return basename($path);
    }

    /**
     * Resize (max 1200px wide), re-encode, and persist the image to the
     * public disk. Falls back to a plain store if the image cannot be read.
     */
    private function optimizeAndSave(UploadedFile $file, string $destination): string
    {
        try {
            $image = $this->imageManager->decodePath($file->getRealPath());

            if ($image->width() > 1200) {
                $image->scale(width: 1200);
            }

            $encoded = $image->encodeUsingFileExtension(
                pathinfo($destination, PATHINFO_EXTENSION),
                quality: 80,
            );

            Storage::disk('public')->put($destination, (string) $encoded);

            return $destination;
        } catch (\Throwable) {
            Storage::disk('public')->putFileAs(
                dirname($destination),
                $file,
                basename($destination),
            );

            return $destination;
        }
    }

    /**
     * Delete an image file from the public disk if it exists.
     */
    public function deleteImage(string $directory, ?string $filename): void
    {
        if (! $filename) {
            return;
        }

        Storage::disk('public')->delete($directory.'/'.$filename);
    }
}
