<?php

namespace App\Services;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class ImageOptimizationService
{
    public function optimize($imagePath, $width = 800, $height = null, $quality = 80)
    {
        $image = Image::make(Storage::get($imagePath));

        $image->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $image->encode('webp', $quality);

        $newPath = pathinfo($imagePath, PATHINFO_DIRNAME) . '/' . pathinfo($imagePath, PATHINFO_FILENAME) . '.webp';
        Storage::put($newPath, $image->stream());

        return $newPath;
    }
}