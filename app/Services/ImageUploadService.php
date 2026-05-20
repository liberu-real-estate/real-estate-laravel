<?php

namespace App\Services;

use App\Models\Property;
use Illuminate\Http\UploadedFile;

class ImageUploadService
{
    public function uploadAndProcess(UploadedFile $file, int $propertyId, int $width = 800, int $height = 600)
    {
        $property = Property::findOrFail($propertyId);

        $property->addMediaFromRequest('image')
            ->withResponsiveImages()
            ->toMediaCollection('images');

        return $property->getFirstMediaUrl('images');
    }
}