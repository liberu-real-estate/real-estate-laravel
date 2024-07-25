<?php

namespace App\Services;

use App\Models\DigitalSignature;
use App\Models\User;
use App\Models\Document;

class DigitalSignatureService
{
    public function signDocument(User $user, Document $document, $signatureData)
    {
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
        ]);

        if ($document instanceof LeaseAgreement) {
            $document->update(['is_signed' => true]);
        }

        return $signature;
    }

    public function verifySignature(DigitalSignature $signature)
    {
        // Implement signature verification logic here
        // This could involve checking with the third-party service
        // Ensure compliance with UK electronic signature regulations
        $isValid = $this->verifyWithThirdPartyService($signature);

        if ($isValid) {
            $signature->update(['verified_at' => now()]);
        }

        return $isValid;
    }

    private function verifyWithThirdPartyService(DigitalSignature $signature)
    {
        // Implement the actual verification logic here
        // This is a placeholder implementation
        return true;
    }
}