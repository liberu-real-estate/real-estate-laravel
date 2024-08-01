<?php

namespace App\Services;

use App\Models\DocumentTemplate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

    public function generatePdf($template, $data)
    {
        $pdf = PDF::loadView('pdf_templates.' . $template, $data);
        return $pdf->output();
    }

    public function generateExcel($template, $data)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Implement the logic to populate the spreadsheet with $data
        // This is a placeholder and should be replaced with actual data population logic
        $sheet->setCellValue('A1', 'Custom Report');
        $sheet->setCellValue('A2', 'Date: ' . now()->toDateString());

        $writer = new Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'excel');
        $writer->save($tempFile);

        return file_get_contents($tempFile);
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