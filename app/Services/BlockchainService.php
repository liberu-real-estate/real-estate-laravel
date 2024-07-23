<?php

namespace App\Services;

use Web3\Web3;
use Web3\Contract;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\HttpRequestManager;

class BlockchainService
{
    protected $web3;
    protected $contract;

    public function __construct()
    {
        $this->web3 = new Web3(new HttpProvider(new HttpRequestManager(env('ETHEREUM_NODE_URL'))));
    }

    public function deploySmartContract($abi, $bytecode, $params)
    {
        $this->contract = new Contract($this->web3->provider, $abi);
        
        return $this->contract->deploy($bytecode, $params);
    }

    public function callContractMethod($contractAddress, $method, $params)
    {
        return $this->contract->at($contractAddress)->call($method, $params);
    }
}