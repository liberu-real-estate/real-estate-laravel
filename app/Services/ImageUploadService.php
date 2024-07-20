<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageUploadService
{
    public function uploadAndProcess(UploadedFile $file, int $width = 800, int $height = 600)
    {
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        $path = 'property-images/' . $filename;

        $image = Image::make($file)
            ->fit($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->encode('jpg', 80);

        Storage::put($path, $image->stream());

        return $path;
    }
}