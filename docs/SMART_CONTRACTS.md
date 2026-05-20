# Smart Contracts for Rental Agreements

## Overview

This feature implements blockchain-based smart contracts to automate and secure rental agreements and transactions in the real estate platform. Smart contracts provide transparency, automation, and immutability for lease agreements between landlords and tenants.

## Features

### 1. Smart Contract Deployment
- Automatically deploy smart contracts when lease agreements are created
- Contracts are deployed to configurable blockchain networks (Ethereum, Polygon, or Simulated)
- Each contract has a unique blockchain address

### 2. Digital Signatures
- Both landlord and tenant must digitally sign the contract
- Contracts become active only after both parties sign
- Signature events are recorded on the blockchain

### 3. Automated Rent Payments
- Rent payments are processed through the smart contract
- Payment history is immutably recorded on the blockchain
- Automatic tracking of total rent paid and payment counts

### 4. Contract Termination
- Either party can terminate the contract
- Termination is recorded on the blockchain
- Security deposits can be automatically returned

### 5. Audit Trail
- All contract actions are logged as transactions
- Complete history of deployments, signatures, payments, and terminations
- Transparent and tamper-proof record keeping

## Architecture

### Components

1. **Smart Contract (Solidity)**
   - Location: `contracts/RentalAgreement.sol`
   - Implements the on-chain logic for rental agreements
   - Handles rent payments, signatures, and contract lifecycle

2. **Models**
   - `SmartContract`: Represents deployed contracts in the database
   - `SmartContractTransaction`: Tracks all blockchain transactions
   - `LeaseAgreement`: Enhanced with smart contract integration

3. **Services**
   - `BlockchainService`: Handles blockchain interactions
   - `SmartContractService`: Orchestrates smart contract lifecycle

4. **Admin Interface**
   - Filament resource for managing smart contracts
   - View contract details, signatures, and transactions
   - Sign contracts and process payments through the UI

## Configuration

The smart contract system is configured in `config/blockchain.php`:

```php
return [
    'network' => env('BLOCKCHAIN_NETWORK', 'simulated'),
    'ethereum_node_url' => env('ETHEREUM_NODE_URL', ''),
    // ... more settings
];
```

### Environment Variables

Add these to your `.env` file:

```env
# Blockchain Configuration
BLOCKCHAIN_NETWORK=simulated
BLOCKCHAIN_SIMULATED=true
ETHEREUM_NODE_URL=

# Optional: For production use
BLOCKCHAIN_GAS_LIMIT=3000000
BLOCKCHAIN_GAS_PRICE=20
```

### Supported Networks

- **simulated** (default): Simulated blockchain for development and testing
- **ethereum**: Ethereum Mainnet (requires Ethereum node)
- **polygon**: Polygon network
- **sepolia**: Sepolia testnet for testing

## Usage

### Creating a Smart Contract

When a lease agreement is created, you can deploy a smart contract:

```php
use App\Services\SmartContractService;

$smartContractService = app(SmartContractService::class);
$smartContract = $smartContractService->createSmartContract($leaseAgreement);
```

### Signing a Contract

Both landlord and tenant must sign:

```php
// Landlord signs
$smartContractService->signContract($smartContract, $landlord, 'landlord');

// Tenant signs
$smartContractService->signContract($smartContract, $tenant, 'tenant');

// Contract becomes active when both have signed
```

### Processing Rent Payments

```php
$amount = $smartContract->rent_amount;
$transaction = $smartContractService->processRentPayment(
    $smartContract,
    $amount,
    $userId
);
```

### Terminating a Contract

```php
$transaction = $smartContractService->terminateContract(
    $smartContract,
    $userId
);
```

## Database Schema

### smart_contracts Table

Stores deployed smart contracts:

- `contract_address`: Blockchain address of the contract
- `lease_agreement_id`: Associated lease agreement
- `property_id`, `landlord_id`, `tenant_id`: Contract parties
- `rent_amount`, `security_deposit`: Financial terms
- `status`: pending, active, completed, terminated
- `landlord_signed`, `tenant_signed`: Signature status
- `blockchain_network`: Network where contract is deployed

### smart_contract_transactions Table

Tracks all contract transactions:

- `smart_contract_id`: Associated contract
- `transaction_type`: deploy, sign, rent_payment, terminate
- `transaction_hash`: Blockchain transaction hash
- `amount`: Transaction amount (for payments)
- `status`: pending, confirmed, failed

## Admin Interface

### Managing Smart Contracts

1. Navigate to **Contract Management > Smart Contracts**
2. View list of all deployed contracts
3. Filter by status, network, or signature status
4. Click on a contract to view details

### Contract Details View

- Contract address and blockchain network
- Signature status for both parties
- Financial details and payment history
- Lease period information
- Action buttons for signing and terminating

### Creating a New Contract

1. Go to Smart Contracts > Create
2. Select the lease agreement
3. System automatically deploys the contract
4. Contract appears in pending status
5. Both parties can now sign

## Smart Contract Code (Solidity)

The Solidity smart contract includes:

```solidity
// Key functions
function signContract() public
function payRent() public payable
function terminateContract() public
function getContractDetails() public view
```

See `contracts/RentalAgreement.sol` for full implementation.

## Security Features

1. **Access Control**: Only authorized parties can interact with contracts
2. **Signature Validation**: Both parties must sign before activation
3. **Payment Verification**: Rent amounts are validated before processing
4. **Immutable Records**: All actions are permanently recorded
5. **Agreement Hashing**: Contract terms are cryptographically hashed

## Testing

Run the smart contract tests:

```bash
php artisan test --filter SmartContractTest
```

Tests cover:
- Contract creation and deployment
- Digital signatures
- Rent payment processing
- Contract termination
- Blockchain service functionality
- Model helper methods

## Development Mode (Simulated Blockchain)

For development and testing, the system uses a simulated blockchain:

- No external blockchain connection required
- Instant transaction confirmation
- Deterministic contract addresses
- Perfect for local development

## Production Deployment

### Prerequisites

1. Ethereum node access (Infura, Alchemy, or self-hosted)
2. Wallet with sufficient ETH for gas fees
3. Compiled smart contract (ABI and bytecode)

### Steps

1. Compile the Solidity contract:
```bash
solc --abi --bin contracts/RentalAgreement.sol -o contracts/
```

2. Update environment:
```env
BLOCKCHAIN_NETWORK=ethereum
ETHEREUM_NODE_URL=https://mainnet.infura.io/v3/YOUR_PROJECT_ID
```

3. Deploy contracts through the admin interface

## Compliance

The smart contract implementation ensures:

- **UK Electronic Signatures Regulations 2002** compliance
- **GDPR** compliance for data handling
- **Tenancy Act** alignment for rental agreements
- Audit trail for regulatory requirements

## API Integration

Future enhancements can expose smart contract functionality via API:

```php
POST /api/smart-contracts
POST /api/smart-contracts/{id}/sign
POST /api/smart-contracts/{id}/pay-rent
POST /api/smart-contracts/{id}/terminate
```

## Troubleshooting

### Contract deployment fails
- Check blockchain network configuration
- Verify Ethereum node connectivity
- Ensure sufficient gas for deployment

### Signature not recording
- Verify user permissions
- Check contract status (must be pending)
- Ensure user is authorized party

### Payment not processing
- Verify contract is active and signed
- Check payment amount matches rent
- Ensure lease period is active

## Future Enhancements

1. Multi-signature requirements
2. Automated monthly rent collection
3. Maintenance request handling on-chain
4. Integration with DeFi protocols
5. NFT-based property ownership
6. Cross-chain deployment support

## Support

For issues or questions:
- Check the documentation: `/docs/SMART_CONTRACTS.md`
- Review test cases: `/tests/Unit/SmartContractTest.php`
- Examine Solidity code: `/contracts/RentalAgreement.sol`

## References

- Ethereum Documentation: https://ethereum.org/en/developers/docs/
- Solidity Documentation: https://docs.soliditylang.org/
- Web3.php Library: https://github.com/web3p/web3.php
- Smart Contract Best Practices: https://consensys.github.io/smart-contract-best-practices/
