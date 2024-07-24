<?php

namespace App\Services;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class ImageOptimizationService
{
    public function optimize($imagePath, $width = 800, $height = null, $quality = 80)
    {
        $image = Image::make(Storage::get($imagePath));

        if ($height) {
            $image->fit($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
        } else {
            $image->resize($width, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        $image->encode('jpg', $quality);

        Storage::put($imagePath, (string) $image);

        return $imagePath;
    }
}