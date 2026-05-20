<?php

namespace App\Services;

use Exception;
use App\Models\DigitalSignature;
use App\Models\User;
use App\Models\Document;

class DigitalSignatureService
{
    protected $docuSignService;

    public function __construct(DocuSignService $docuSignService)
    {
        $this->docuSignService = $docuSignService;
    }

    public function signDocument(User $user, Document $document, $signatureData)
    {
        // Create an envelope and send it for signature using DocuSign
        $envelope = $this->docuSignService->createEnvelope(
            $document->file_path,
            $user->email,
            $user->name
        );
        if (!$document->isSignable()) {
            throw new Exception('This document is not signable.');
        }
        // Here you would integrate with a third-party service like DocuSign
        // Ensure compliance with UK electronic signature regulations
        $signature = DigitalSignature::create([
            'user_id' => $user->id,
            'document_id' => $document->id,
            'signature_data' => $signatureData,
            'signed_at' => now(),
            'team_id' => $user->team_id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'envelope_id' => $envelope->getEnvelopeId(),
        ]);

        if ($document instanceof LeaseAgreement) {
            $document->update(['is_signed' => true]);
        }

        return $signature;
    }

    public function verifySignature(DigitalSignature $signature)
    {
        $envelopeStatus = $this->docuSignService->getEnvelopeStatus($signature->envelope_id);
        $isValid = $envelopeStatus->getStatus() === 'completed';

        if ($isValid) {
            $signature->update(['verified_at' => now()]);
        }

        return $isValid;
    }
}