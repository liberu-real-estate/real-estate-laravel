<?php

namespace App\Services;

use App\Models\DocumentTemplate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentGenerationService
{
    public function generateDocument(DocumentTemplate $template, array $customFields)
    {
        if (!$this->canAccessTemplate($template)) {
            throw new \Exception('Unauthorized access to template');
        }

        $content = $template->generateDocument($customFields);

        return $this->sanitizeContent($content);
    }

    public function saveGeneratedDocument($content, $fileName)
    {
        $path = 'generated_documents/' . Auth::id() . '/' . $fileName;
        Storage::put($path, $content);
        return $path;
    }

    private function canAccessTemplate(DocumentTemplate $template)
    {
        // Implement access control logic here
        // For example, check if the user's team has access to this template
        return Auth::user()->team_id === $template->team_id;
    }

    private function sanitizeContent($content)
    {
        // Implement content sanitization to prevent XSS attacks
        return htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
    }
}