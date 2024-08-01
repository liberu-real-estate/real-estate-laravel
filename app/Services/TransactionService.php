<?php

namespace App\Services;

use App\Models\Transaction;
use App\Services\DigitalSignatureService;

class TransactionService
{
    protected $digitalSignatureService;

    public function __construct(DigitalSignatureService $digitalSignatureService)
    {
        $this->digitalSignatureService = $digitalSignatureService;
    }

    public function createTransaction(array $data)
    {
        $transaction = Transaction::create($data);
        $transaction->calculateCommission();
        $this->generateAndSignDocument($transaction);
        return $transaction;
    }

    public function updateTransactionStatus(Transaction $transaction, string $status)
    {
        $transaction->update(['status' => $status]);
        // Perform any additional actions based on the new status
    }

    protected function generateAndSignDocument(Transaction $transaction)
    {
        $document = $transaction->generateContractualDocument();
        $this->digitalSignatureService->requestSignature($document, [$transaction->buyer, $transaction->seller]);
    }
}