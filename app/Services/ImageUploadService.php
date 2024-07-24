<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use App\Models\Property;

class ImageUploadService
{
    public function uploadAndProcess(UploadedFile $file, Property $property)
    {
        $property->addMedia($file)
            ->withResponsiveImages()
            ->toMediaCollection('property_images');

        return $property->getFirstMediaUrl('property_images');
    }
}