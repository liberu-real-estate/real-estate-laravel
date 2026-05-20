<?php

namespace App\Filament\Staff\Resources\SmartContracts\Pages;

use App\Filament\Staff\Resources\SmartContracts\SmartContractResource;
use App\Services\SmartContractService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateSmartContract extends CreateRecord
{
    protected static string $resource = SmartContractResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // Get the lease agreement
        $leaseAgreement = \App\Models\LeaseAgreement::find($data['lease_agreement_id']);
        
        if (!$leaseAgreement) {
            throw new \Exception('Lease agreement not found');
        }

        // Use SmartContractService to create the contract
        $smartContractService = app(SmartContractService::class);
        
        return $smartContractService->createSmartContract($leaseAgreement);
    }
}
