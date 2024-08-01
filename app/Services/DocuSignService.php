<?php

namespace App\Services;

use DocuSign\eSign\Client\ApiClient;
use DocuSign\eSign\Configuration;
use DocuSign\eSign\Api\EnvelopesApi;

class DocuSignService
{
    protected $config;
    protected $apiClient;
    protected $accountId;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->apiClient = new ApiClient($this->getConfiguration());
        $this->accountId = $this->config['account_id'];
    }

    protected function getConfiguration(): Configuration
    {
        $config = new Configuration();
        $config->setHost($this->config['base_path']);
        $config->addDefaultHeader('Authorization', 'Bearer ' . $this->getAccessToken());
        return $config;
    }

    protected function getAccessToken(): string
    {
        // Implement OAuth 2.0 JWT flow to get access token
        // This is a placeholder and should be implemented properly
        return 'your_access_token_here';
    }

    public function createEnvelope($documentPath, $signerEmail, $signerName)
    {
        $envelopeDefinition = $this->createEnvelopeDefinition($documentPath, $signerEmail, $signerName);
        $envelopesApi = new EnvelopesApi($this->apiClient);
        $envelope = $envelopesApi->createEnvelope($this->accountId, $envelopeDefinition);
        return $envelope;
    }

    protected function createEnvelopeDefinition($documentPath, $signerEmail, $signerName)
    {
        // Implement the creation of envelope definition
        // This is a placeholder and should be implemented properly
        return new \DocuSign\eSign\Model\EnvelopeDefinition();
    }

    public function getEnvelopeStatus($envelopeId)
    {
        $envelopesApi = new EnvelopesApi($this->apiClient);
        return $envelopesApi->getEnvelope($this->accountId, $envelopeId);
    }
}