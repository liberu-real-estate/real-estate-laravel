<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Blockchain Network Configuration
    |--------------------------------------------------------------------------
    |
    | This option controls which blockchain network is used for smart contracts.
    | Supported: "simulated", "ethereum", "polygon", "sepolia"
    |
    */

    'network' => env('BLOCKCHAIN_NETWORK', 'simulated'),

    /*
    |--------------------------------------------------------------------------
    | Ethereum Node URL
    |--------------------------------------------------------------------------
    |
    | The URL of the Ethereum node for real blockchain interactions.
    | Required when network is not 'simulated'.
    |
    */

    'ethereum_node_url' => env('ETHEREUM_NODE_URL', ''),

    /*
    |--------------------------------------------------------------------------
    | Contract Deployment Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for smart contract deployment
    |
    */

    'deployment' => [
        'gas_limit' => env('BLOCKCHAIN_GAS_LIMIT', 3000000),
        'gas_price' => env('BLOCKCHAIN_GAS_PRICE', 20), // in Gwei
    ],

    /*
    |--------------------------------------------------------------------------
    | Simulated Mode Settings
    |--------------------------------------------------------------------------
    |
    | Settings for simulated blockchain mode (development/testing)
    |
    */

    'simulated' => [
        'enabled' => env('BLOCKCHAIN_SIMULATED', true),
        'auto_confirm_transactions' => true,
        'confirmation_delay' => 0, // seconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Smart Contract Templates
    |--------------------------------------------------------------------------
    |
    | Paths to compiled smart contract templates
    |
    */

    'contracts' => [
        'rental_agreement' => [
            'abi_path' => base_path('contracts/RentalAgreement.abi'),
            'bytecode_path' => base_path('contracts/RentalAgreement.bin'),
            'sol_path' => base_path('contracts/RentalAgreement.sol'),
        ],
    ],
];
