<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Services\DigitalSignatureService;
use Illuminate\Http\Request;

class DocumentSignatureController extends Controller
{
    protected $digitalSignatureService;

    public function __construct(DigitalSignatureService $digitalSignatureService)
    {
        $this->digitalSignatureService = $digitalSignatureService;
    }

    public function prepareForSigning(Request $request, Document $document)
    {
        try {
            $signingToken = $this->digitalSignatureService->prepareDocumentForSigning($document);
            return response()->json(['signing_token' => $signingToken]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function signDocument(Request $request, Document $document)
    {
        $request->validate([
            'signature_data' => 'required|string',
        ]);

        try {
            $signature = $this->digitalSignatureService->signDocument($request->user(), $document, $request->signature_data);
            return response()->json(['message' => 'Document signed successfully', 'signature_id' => $signature->id]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function verifySignature(Request $request, Document $document)
    {
        $signature = $document->digitalSignatures()->latest()->first();

        if (!$signature) {
            return response()->json(['error' => 'No signature found for this document'], 404);
        }

        $isValid = $this->digitalSignatureService->verifySignature($signature);

        return response()->json(['is_valid' => $isValid]);
    }
}