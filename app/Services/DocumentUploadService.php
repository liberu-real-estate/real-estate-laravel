<?php

namespace App\Services;

use App\Models\Document;
use Illuminate\Http\UploadedFile;

class DocumentUploadService
{
    public function upload(UploadedFile $file, array $data)
    {
        $document = Document::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'file_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'team_id' => $data['team_id'],
            'property_id' => $data['property_id'] ?? null,
            'user_id' => auth()->id(),
        ]);

        $document->addMedia($file)
            ->toMediaCollection('documents');

        return $document;
    }
}