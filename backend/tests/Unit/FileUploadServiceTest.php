<?php

namespace Tests\Unit;

use App\Services\FileUploadService;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Tests\TestCase;

class FileUploadServiceTest extends TestCase
{
    public function test_stores_uploaded_image(): void
    {
        Storage::fake('public');
        $service = new FileUploadService(new ImageManager(\Intervention\Image\Drivers\Gd\Driver::class));

        $file = UploadedFile::fake()->image('pet.jpg', 800, 600);
        $name = $service->storeImage($file, 'pets');

        Storage::disk('public')->assertExists('pets/'.$name);
        $this->assertMatchesRegularExpression('/^[0-9a-f-]{36}\.jpg$/', $name);
    }

    public function test_stores_thumbnail_variant(): void
    {
        Storage::fake('public');
        $service = new FileUploadService(new ImageManager(\Intervention\Image\Drivers\Gd\Driver::class));

        // 2000px-wide source should produce a thumbnail capped at 400px.
        $image = (new ImageManager(\Intervention\Image\Drivers\Gd\Driver::class))
            ->createImage(2000, 1500)
            ->fill('#5a3d2b')
            ->encodeUsingMediaType('image/jpeg');

        $path = sys_get_temp_dir().'/thumb_upload_test.jpg';
        file_put_contents($path, (string) $image);

        $file = new UploadedFile($path, 'big.jpg', 'image/jpeg', null, true);
        $name = $service->storeImage($file, 'pets');

        Storage::disk('public')->assertExists('pets/thumbs/'.$name);

        $thumb = Storage::disk('public')->get('pets/thumbs/'.$name);
        $decoded = (new ImageManager(\Intervention\Image\Drivers\Gd\Driver::class))->decodeBinary($thumb);
        $this->assertLessThanOrEqual(FileUploadService::THUMB_WIDTH, $decoded->width());

        @unlink($path);
    }

    public function test_deletes_image_and_thumbnail(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('pets/old.jpg', 'data');
        Storage::disk('public')->put('pets/thumbs/old.jpg', 'data');
        $service = new FileUploadService(new ImageManager(\Intervention\Image\Drivers\Gd\Driver::class));

        $service->deleteImage('pets', 'old.jpg');
        Storage::disk('public')->assertMissing('pets/old.jpg');
        Storage::disk('public')->assertMissing('pets/thumbs/old.jpg');
    }

    public function test_uses_configured_media_disk(): void
    {
        config()->set('filesystems.media_disk', 'public');
        $service = new FileUploadService(new ImageManager(\Intervention\Image\Drivers\Gd\Driver::class));
        $this->assertSame('public', $service->disk());

        config()->set('filesystems.media_disk', 's3');
        $this->assertSame('s3', $service->disk());

        config()->set('filesystems.media_disk', 'public');
    }

    public function test_resizes_large_images(): void
    {
        Storage::fake('public');
        $service = new FileUploadService(new ImageManager(\Intervention\Image\Drivers\Gd\Driver::class));

        // Build a 2000px-wide JPEG
        $image = (new ImageManager(\Intervention\Image\Drivers\Gd\Driver::class))
            ->createImage(2000, 1500)
            ->fill('#5a3d2b')
            ->encodeUsingMediaType('image/jpeg');

        $path = sys_get_temp_dir().'/big_upload_test.jpg';
        file_put_contents($path, (string) $image);

        $file = new UploadedFile($path, 'big.jpg', 'image/jpeg', null, true);
        $name = $service->storeImage($file, 'pets');

        $stored = Storage::disk('public')->get('pets/'.$name);
        $decoded = (new ImageManager(\Intervention\Image\Drivers\Gd\Driver::class))->decodeBinary($stored);

        $this->assertLessThanOrEqual(1200, $decoded->width());
        @unlink($path);
    }

    public function test_deletes_existing_image(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('pets/old.jpg', 'data');
        $service = new FileUploadService(new ImageManager(\Intervention\Image\Drivers\Gd\Driver::class));

        $service->deleteImage('pets', 'old.jpg');
        Storage::disk('public')->assertMissing('pets/old.jpg');
    }
}
