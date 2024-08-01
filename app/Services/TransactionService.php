<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Document;
use App\Services\DigitalSignatureService;
use App\Services\BlockchainService;

class TransactionService
{
    protected $digitalSignatureService;
    protected $blockchainService;

    public function __construct(DigitalSignatureService $digitalSignatureService, BlockchainService $blockchainService)
    {
        $this->digitalSignatureService = $digitalSignatureService;
        $this->blockchainService = $blockchainService;
    }

    public function createTransaction(array $data)
    {
        $transaction = Transaction::create($data);
        $transaction->calculateCommission();
        return $transaction;
    }

    public function updateTransactionStatus(Transaction $transaction, string $status)
    {
        $transaction->update(['status' => $status]);
        return $transaction;
    }

    public function generateDocument(DocumentTemplate $template, array $data)
    {
        // Validate and sanitize input data
        $validatedData = $this->validateAndSanitizeData($data);

        // Check user permissions
        if (!auth()->user()->can('generate-documents')) {
            throw new \Exception('User does not have permission to generate documents');
        }

        $content = $template->renderContent($validatedData);

        $document = new Document([
            'title' => $template->name . ' - ' . ($validatedData['property'] ?? 'Untitled'),
            'content' => $content,
            'file_type' => 'pdf',
            'size' => strlen($content), // Placeholder, actual PDF size would be different
            'user_id' => auth()->id(),
            'property_id' => $validatedData['property_id'] ?? null,
        ]);
        $document->save();

        // Here you would add logic to convert the content to a PDF
        // For example, using a package like barryvdh/laravel-dompdf

        // Log the document generation
        \Log::info('Document generated', [
            'user_id' => auth()->id(),
            'document_id' => $document->id,
            'template_id' => $template->id,
        ]);
    
        return $document;
    }
    
    private function validateAndSanitizeData(array $data)
    {
        // Implement validation and sanitization logic here
        // This is a placeholder implementation
        return array_map('strip_tags', $data);
    }
    
    public function generateContractualDocument(Transaction $transaction)
    {
        $documentTemplate = DocumentTemplate::where('type', 'sale_agreement')->first();
        return $this->generateDocument($documentTemplate, [
            'buyer' => $transaction->buyer->name,
            'seller' => $transaction->seller->name,
            'property' => $transaction->property->title,
            'property_id' => $transaction->property_id,
            'amount' => $transaction->transaction_amount,
            'date' => $transaction->transaction_date->format('Y-m-d'),
        ]);
    }

    public function signDocument(Document $document, $user, $signatureData)
    {
        $signature = $this->digitalSignatureService->signDocument($user, $document, $signatureData);
        
        // Record the signature on the blockchain
        $this->blockchainService->recordSignature($signature);

        return $signature;
    }
}