// SPDX-License-Identifier: MIT
pragma solidity ^0.8.0;

/**
 * @title RentalAgreement
 * @dev Smart contract for managing rental agreements between landlord and tenant
 * @notice This contract automates rent payments, security deposits, and lease termination
 */
contract RentalAgreement {
    // State variables
    address public landlord;
    address public tenant;
    uint256 public rentAmount;
    uint256 public securityDeposit;
    uint256 public leaseStartDate;
    uint256 public leaseEndDate;
    bool public isActive;
    bool public landlordSigned;
    bool public tenantSigned;
    uint256 public lastRentPayment;
    uint256 public totalRentPaid;
    
    // Property details stored on-chain
    string public propertyAddress;
    bytes32 public agreementHash;

    // Events
    event ContractCreated(address indexed landlord, address indexed tenant, uint256 rentAmount);
    event ContractSigned(address indexed signer, bool isLandlord);
    event RentPaid(address indexed tenant, uint256 amount, uint256 date);
    event ContractTerminated(uint256 date, address terminatedBy);
    event SecurityDepositReturned(address indexed tenant, uint256 amount);
    event MaintenanceRequestLogged(string description, uint256 timestamp);

    // Modifiers
    modifier onlyLandlord() {
        require(msg.sender == landlord, "Only landlord can perform this action");
        _;
    }

    modifier onlyTenant() {
        require(msg.sender == tenant, "Only tenant can perform this action");
        _;
    }

    modifier onlyParties() {
        require(msg.sender == landlord || msg.sender == tenant, "Only contract parties can perform this action");
        _;
    }

    modifier contractActive() {
        require(isActive, "Contract is not active");
        _;
    }

    modifier fullyExecuted() {
        require(landlordSigned && tenantSigned, "Contract must be signed by both parties");
        _;
    }

    /**
     * @dev Constructor to create a new rental agreement
     */
    constructor(
        address _tenant, 
        uint256 _rentAmount, 
        uint256 _securityDeposit, 
        uint256 _leaseStartDate, 
        uint256 _leaseEndDate,
        string memory _propertyAddress,
        bytes32 _agreementHash
    ) {
        require(_tenant != address(0), "Invalid tenant address");
        require(_rentAmount > 0, "Rent amount must be greater than 0");
        require(_leaseEndDate > _leaseStartDate, "End date must be after start date");
        require(_leaseStartDate >= block.timestamp, "Start date must be in the future");

        landlord = msg.sender;
        tenant = _tenant;
        rentAmount = _rentAmount;
        securityDeposit = _securityDeposit;
        leaseStartDate = _leaseStartDate;
        leaseEndDate = _leaseEndDate;
        propertyAddress = _propertyAddress;
        agreementHash = _agreementHash;
        isActive = true;
        landlordSigned = false;
        tenantSigned = false;
        totalRentPaid = 0;

        emit ContractCreated(landlord, tenant, rentAmount);
    }

    /**
     * @dev Sign the contract (both landlord and tenant must sign)
     */
    function signContract() public onlyParties contractActive {
        if (msg.sender == landlord) {
            require(!landlordSigned, "Landlord has already signed");
            landlordSigned = true;
            emit ContractSigned(msg.sender, true);
        } else if (msg.sender == tenant) {
            require(!tenantSigned, "Tenant has already signed");
            tenantSigned = true;
            emit ContractSigned(msg.sender, false);
        }
    }

    /**
     * @dev Pay rent - tenant pays monthly rent
     */
    function payRent() public payable onlyTenant contractActive fullyExecuted {
        require(msg.value == rentAmount, "Incorrect rent amount");
        require(block.timestamp >= leaseStartDate, "Lease has not started yet");
        require(block.timestamp <= leaseEndDate, "Lease has ended");

        payable(landlord).transfer(msg.value);
        lastRentPayment = block.timestamp;
        totalRentPaid += msg.value;
        
        emit RentPaid(tenant, msg.value, block.timestamp);
    }

    /**
     * @dev Deposit security deposit - tenant deposits at contract start
     */
    function depositSecurity() public payable onlyTenant contractActive {
        require(msg.value == securityDeposit, "Incorrect security deposit amount");
        require(address(this).balance == msg.value, "Security deposit already paid");
    }

    /**
     * @dev Terminate contract - can be called by either party
     */
    function terminateContract() public onlyParties contractActive {
        isActive = false;
        emit ContractTerminated(block.timestamp, msg.sender);

        // Return security deposit to tenant if available
        uint256 balance = address(this).balance;
        if (balance > 0) {
            payable(tenant).transfer(balance);
            emit SecurityDepositReturned(tenant, balance);
        }
    }

    /**
     * @dev Log a maintenance request on-chain
     */
    function logMaintenanceRequest(string memory description) public onlyTenant contractActive fullyExecuted {
        emit MaintenanceRequestLogged(description, block.timestamp);
    }

    /**
     * @dev Get contract details
     */
    function getContractDetails() public view returns (
        address _landlord,
        address _tenant,
        uint256 _rentAmount,
        uint256 _securityDeposit,
        uint256 _leaseStartDate,
        uint256 _leaseEndDate,
        bool _isActive,
        bool _landlordSigned,
        bool _tenantSigned,
        uint256 _totalRentPaid
    ) {
        return (
            landlord,
            tenant,
            rentAmount,
            securityDeposit,
            leaseStartDate,
            leaseEndDate,
            isActive,
            landlordSigned,
            tenantSigned,
            totalRentPaid
        );
    }

    /**
     * @dev Check if contract is expired
     */
    function isExpired() public view returns (bool) {
        return block.timestamp > leaseEndDate;
    }

    /**
     * @dev Get remaining lease duration in seconds
     */
    function getRemainingLeaseDuration() public view returns (uint256) {
        if (block.timestamp >= leaseEndDate) {
            return 0;
        }
        return leaseEndDate - block.timestamp;
    }
}