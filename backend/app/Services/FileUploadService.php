<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

class FileUploadService
{
    public const THUMB_WIDTH = 400;

    public const MAIN_WIDTH = 1200;

    public function __construct(private readonly ImageManager $imageManager)
    {
    }

    /**
     * The disk used for media uploads — configurable via MEDIA_DISK
     * (defaults to the public disk for local; set to s3 in production).
     */
    public function disk(): string
    {
        return config('filesystems.media_disk', 'public');
    }

    /**
     * Store an uploaded image plus a small thumbnail under the given
     * directory. Returns the basename of the main image.
     */
    public function storeImage(UploadedFile $file, string $directory): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = Str::uuid().'.'.$extension;
        $path = $directory.'/'.$filename;

        $this->optimizeAndSave($file, $path);
        $this->createThumbnail($file, $path);

        return basename($filename);
    }

    /**
     * Public URL for a stored image (used by image_url accessors).
     * The local public disk uses the relative /storage path (works on both
     * the client and admin subdomains); object storage returns an absolute URL.
     */
    public function url(string $directory, string $filename): string
    {
        if ($this->disk() === 'public') {
            return '/storage/'.$directory.'/'.$filename;
        }

        return Storage::disk($this->disk())->url($directory.'/'.$filename);
    }

    /**
     * Whether a thumbnail exists for the given stored image.
     */
    public function thumbExists(string $directory, ?string $filename): bool
    {
        if (! $filename) {
            return false;
        }

        return Storage::disk($this->disk())->exists($directory.'/thumbs/'.$filename);
    }

    /**
     * Public URL for the thumbnail of a stored image.
     */
    public function thumbUrl(string $directory, string $filename): string
    {
        if ($this->disk() === 'public') {
            return '/storage/'.$directory.'/thumbs/'.$filename;
        }

        return Storage::disk($this->disk())->url($directory.'/thumbs/'.$filename);
    }

    /**
     * Resize (max 1200px wide), re-encode, and persist to the media disk.
     */
    private function optimizeAndSave(UploadedFile $file, string $destination): void
    {
        try {
            $image = $this->imageManager->decodePath($file->getRealPath());

            if ($image->width() > self::MAIN_WIDTH) {
                $image->scale(width: self::MAIN_WIDTH);
            }

            $encoded = $image->encodeUsingFileExtension(
                pathinfo($destination, PATHINFO_EXTENSION),
                quality: 80,
            );

            Storage::disk($this->disk())->put($destination, (string) $encoded);
        } catch (\Throwable) {
            Storage::disk($this->disk())->putFileAs(
                dirname($destination),
                $file,
                basename($destination),
            );
        }
    }

    /**
     * Generate a small thumbnail (max 400px) and persist it alongside the main
     * image under a `thumbs/` subdirectory. Non-image files skip this.
     */
    private function createThumbnail(UploadedFile $file, string $destination): void
    {
        try {
            $image = $this->imageManager->decodePath($file->getRealPath());

            if ($image->width() > self::THUMB_WIDTH) {
                $image->scale(width: self::THUMB_WIDTH);
            }

            $thumbPath = dirname($destination).'/thumbs/'.basename($destination);
            $encoded = $image->encodeUsingFileExtension(
                pathinfo($thumbPath, PATHINFO_EXTENSION),
                quality: 75,
            );

            Storage::disk($this->disk())->put($thumbPath, (string) $encoded);
        } catch (\Throwable) {
            // thumbnail is best-effort; main image is what matters
        }
    }

    /**
     * Delete a stored image (main + thumbnail) from the media disk.
     */
    public function deleteImage(string $directory, ?string $filename): void
    {
        if (! $filename) {
            return;
        }

        Storage::disk($this->disk())->delete($directory.'/'.$filename);
        Storage::disk($this->disk())->delete($directory.'/thumbs/'.$filename);
    }
}
