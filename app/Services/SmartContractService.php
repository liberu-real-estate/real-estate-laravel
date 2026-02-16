<?php

namespace App\Services;

use App\Models\SmartContract;
use App\Models\SmartContractTransaction;
use App\Models\LeaseAgreement;
use App\Models\Property;
use App\Models\Tenant;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SmartContractService
{
    protected $blockchainService;

    public function __construct(BlockchainService $blockchainService)
    {
        $this->blockchainService = $blockchainService;
    }

    /**
     * Create and deploy a smart contract for a lease agreement
     * 
     * @param LeaseAgreement $leaseAgreement
     * @return SmartContract
     */
    public function createSmartContract(LeaseAgreement $leaseAgreement): SmartContract
    {
        DB::beginTransaction();
        
        try {
            // Generate agreement hash
            $agreementContent = $this->generateAgreementContent($leaseAgreement);
            $agreementHash = $this->blockchainService->generateAgreementHash($agreementContent);

            // Get contract ABI and bytecode
            $abi = $this->getContractAbi();
            $bytecode = $this->getContractBytecode();

            // Prepare deployment parameters
            $params = [
                'tenant' => $leaseAgreement->tenant->ethereum_address ?? '0x0000000000000000000000000000000000000000',
                'rentAmount' => (float) $leaseAgreement->monthly_rent,
                'securityDeposit' => (float) ($leaseAgreement->security_deposit ?? 0),
                'leaseStartDate' => $leaseAgreement->start_date->timestamp,
                'leaseEndDate' => $leaseAgreement->end_date->timestamp,
                'propertyAddress' => $leaseAgreement->property->address ?? '',
                'agreementHash' => $agreementHash,
            ];

            // Deploy contract
            $deploymentResult = $this->blockchainService->deploySmartContract($abi, $bytecode, $params);

            // Create smart contract record
            $smartContract = SmartContract::create([
                'contract_address' => $deploymentResult['contract_address'],
                'contract_type' => 'rental_agreement',
                'lease_agreement_id' => $leaseAgreement->id,
                'property_id' => $leaseAgreement->property_id,
                'landlord_id' => $leaseAgreement->property->owner_id ?? auth()->id(),
                'tenant_id' => $leaseAgreement->tenant_id,
                'team_id' => $leaseAgreement->team_id,
                'rent_amount' => $leaseAgreement->monthly_rent,
                'security_deposit' => $leaseAgreement->security_deposit ?? 0,
                'lease_start_date' => $leaseAgreement->start_date,
                'lease_end_date' => $leaseAgreement->end_date,
                'status' => 'pending',
                'blockchain_network' => $this->blockchainService->getNetwork(),
                'transaction_hash' => $deploymentResult['transaction_hash'],
                'agreement_hash' => $agreementHash,
                'abi' => $abi,
                'deployed_at' => now(),
            ]);

            // Record deployment transaction
            $this->recordTransaction($smartContract, 'deploy', auth()->id(), null, 'Smart contract deployed', $deploymentResult);

            // Update lease agreement with contract address
            $leaseAgreement->update([
                'smart_contract_address' => $smartContract->contract_address,
                'contract_status' => 'pending',
                'agreement_hash' => $agreementHash,
                'blockchain_network' => $this->blockchainService->getNetwork(),
                'contract_deployed_at' => now(),
            ]);

            DB::commit();

            Log::info('Smart contract created', [
                'contract_id' => $smartContract->id,
                'contract_address' => $smartContract->contract_address,
                'lease_agreement_id' => $leaseAgreement->id,
            ]);

            return $smartContract;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to create smart contract', [
                'error' => $e->getMessage(),
                'lease_agreement_id' => $leaseAgreement->id,
            ]);
            throw $e;
        }
    }

    /**
     * Sign the smart contract (landlord or tenant)
     * 
     * @param SmartContract $smartContract
     * @param User|Tenant $signer
     * @param string $role 'landlord' or 'tenant'
     * @return SmartContractTransaction
     */
    public function signContract(SmartContract $smartContract, $signer, string $role): SmartContractTransaction
    {
        DB::beginTransaction();

        try {
            // Validate signer
            if ($role === 'landlord' && $smartContract->landlord_signed) {
                throw new Exception('Landlord has already signed this contract');
            }
            if ($role === 'tenant' && $smartContract->tenant_signed) {
                throw new Exception('Tenant has already signed this contract');
            }

            // Send signature transaction to blockchain
            $result = $this->blockchainService->sendTransaction(
                $smartContract->contract_address,
                'signContract',
                [],
                0
            );

            // Update smart contract
            $updateData = [];
            if ($role === 'landlord') {
                $updateData['landlord_signed'] = true;
            } else {
                $updateData['tenant_signed'] = true;
            }

            // Check if both parties have now signed
            if (($role === 'landlord' && $smartContract->tenant_signed) || 
                ($role === 'tenant' && $smartContract->landlord_signed)) {
                $updateData['status'] = 'active';
                $updateData['activated_at'] = now();
            }

            $smartContract->update($updateData);

            // Update lease agreement
            $smartContract->leaseAgreement->update([
                $role === 'landlord' ? 'landlord_signed' : 'tenant_signed' => true,
                'contract_status' => $smartContract->status,
            ]);

            // Record transaction
            $signerId = $signer instanceof User ? $signer->id : $signer->user_id;
            $transaction = $this->recordTransaction(
                $smartContract, 
                'sign', 
                $signerId, 
                null, 
                ucfirst($role) . ' signed the contract',
                $result
            );

            DB::commit();

            Log::info('Smart contract signed', [
                'contract_id' => $smartContract->id,
                'role' => $role,
                'signer_id' => $signerId,
            ]);

            return $transaction;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to sign smart contract', [
                'error' => $e->getMessage(),
                'contract_id' => $smartContract->id,
                'role' => $role,
            ]);
            throw $e;
        }
    }

    /**
     * Process rent payment through smart contract
     * 
     * @param SmartContract $smartContract
     * @param float $amount
     * @param int $userId
     * @return SmartContractTransaction
     */
    public function processRentPayment(SmartContract $smartContract, float $amount, int $userId): SmartContractTransaction
    {
        DB::beginTransaction();

        try {
            // Validate contract is active
            if (!$smartContract->isActive()) {
                throw new Exception('Contract is not active');
            }

            // Validate amount
            if ($amount != $smartContract->rent_amount) {
                throw new Exception('Payment amount does not match rent amount');
            }

            // Send payment transaction to blockchain
            $result = $this->blockchainService->sendTransaction(
                $smartContract->contract_address,
                'payRent',
                [],
                $amount
            );

            // Record rent payment
            $smartContract->recordRentPayment($amount);

            // Record transaction
            $transaction = $this->recordTransaction(
                $smartContract,
                'rent_payment',
                $userId,
                $amount,
                'Monthly rent payment',
                $result
            );

            DB::commit();

            Log::info('Rent payment processed', [
                'contract_id' => $smartContract->id,
                'amount' => $amount,
                'user_id' => $userId,
            ]);

            return $transaction;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to process rent payment', [
                'error' => $e->getMessage(),
                'contract_id' => $smartContract->id,
                'amount' => $amount,
            ]);
            throw $e;
        }
    }

    /**
     * Terminate smart contract
     * 
     * @param SmartContract $smartContract
     * @param int $userId
     * @return SmartContractTransaction
     */
    public function terminateContract(SmartContract $smartContract, int $userId): SmartContractTransaction
    {
        DB::beginTransaction();

        try {
            // Validate contract can be terminated
            if (!$smartContract->isActive()) {
                throw new Exception('Contract is not active');
            }

            // Send termination transaction to blockchain
            $result = $this->blockchainService->sendTransaction(
                $smartContract->contract_address,
                'terminateContract',
                [],
                0
            );

            // Terminate contract
            $smartContract->terminate();

            // Update lease agreement
            $smartContract->leaseAgreement->update([
                'contract_status' => 'terminated',
            ]);

            // Record transaction
            $transaction = $this->recordTransaction(
                $smartContract,
                'terminate',
                $userId,
                null,
                'Contract terminated',
                $result
            );

            DB::commit();

            Log::info('Smart contract terminated', [
                'contract_id' => $smartContract->id,
                'user_id' => $userId,
            ]);

            return $transaction;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to terminate smart contract', [
                'error' => $e->getMessage(),
                'contract_id' => $smartContract->id,
            ]);
            throw $e;
        }
    }

    /**
     * Record a transaction
     * 
     * @param SmartContract $smartContract
     * @param string $type
     * @param int $userId
     * @param float|null $amount
     * @param string $description
     * @param array $metadata
     * @return SmartContractTransaction
     */
    protected function recordTransaction(
        SmartContract $smartContract,
        string $type,
        int $userId,
        ?float $amount,
        string $description,
        array $metadata = []
    ): SmartContractTransaction {
        return SmartContractTransaction::create([
            'smart_contract_id' => $smartContract->id,
            'transaction_type' => $type,
            'transaction_hash' => $metadata['transaction_hash'] ?? null,
            'initiated_by' => $userId,
            'amount' => $amount,
            'description' => $description,
            'metadata' => $metadata,
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);
    }

    /**
     * Generate agreement content for hashing
     * 
     * @param LeaseAgreement $leaseAgreement
     * @return string
     */
    protected function generateAgreementContent(LeaseAgreement $leaseAgreement): string
    {
        return json_encode([
            'property_id' => $leaseAgreement->property_id,
            'tenant_id' => $leaseAgreement->tenant_id,
            'start_date' => $leaseAgreement->start_date->toDateString(),
            'end_date' => $leaseAgreement->end_date->toDateString(),
            'monthly_rent' => (float) $leaseAgreement->monthly_rent,
            'terms' => $leaseAgreement->terms,
            'content' => $leaseAgreement->content,
        ]);
    }

    /**
     * Get contract ABI (Application Binary Interface)
     * 
     * @return array
     */
    protected function getContractAbi(): array
    {
        // In production, this would be loaded from compiled contract
        // For now, return a simplified ABI structure
        return [
            [
                'type' => 'constructor',
                'inputs' => [
                    ['name' => '_tenant', 'type' => 'address'],
                    ['name' => '_rentAmount', 'type' => 'uint256'],
                    ['name' => '_securityDeposit', 'type' => 'uint256'],
                    ['name' => '_leaseStartDate', 'type' => 'uint256'],
                    ['name' => '_leaseEndDate', 'type' => 'uint256'],
                    ['name' => '_propertyAddress', 'type' => 'string'],
                    ['name' => '_agreementHash', 'type' => 'bytes32'],
                ],
            ],
            [
                'type' => 'function',
                'name' => 'signContract',
                'inputs' => [],
                'outputs' => [],
            ],
            [
                'type' => 'function',
                'name' => 'payRent',
                'inputs' => [],
                'outputs' => [],
                'payable' => true,
            ],
            [
                'type' => 'function',
                'name' => 'terminateContract',
                'inputs' => [],
                'outputs' => [],
            ],
        ];
    }

    /**
     * Get contract bytecode
     * 
     * @return string
     */
    protected function getContractBytecode(): string
    {
        // In production, this would be loaded from compiled contract
        // For simulated mode, just return a placeholder
        return '0x' . str_repeat('0', 100);
    }
}
