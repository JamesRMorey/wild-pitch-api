<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;

class UploadService
{
    static public function base64Image( string $base64, string $folder ): string
    {
        if (!self::validateBase64($base64)) {
            throw new \Error('Invalid Base64 string');
        }

        $manager = new ImageManager(Driver::class);
        $image = $manager->read($base64);
        $image->scaleDown(600);

        $path = $folder . '/' . Str::uuid() . '.webp';

        $uploaded = Storage::disk('s3')->put(
            path: $path,
            contents: $image->toWebp()
        );

        if (!$uploaded) {
           throw new \Error('Image failed to upload to s3');
        }

        Storage::disk('s3')->setVisibility(path: $path, visibility: 'public');

        return $path;
    }

    static public function validateBase64( string $base64 ): bool
    {
        return preg_match('/^data:image\/\w+;base64,/', $base64);
    }
}
