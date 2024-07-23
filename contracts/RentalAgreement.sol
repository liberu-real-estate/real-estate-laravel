pragma solidity ^0.8.0;

contract RentalAgreement {
    address public landlord;
    address public tenant;
    uint256 public rentAmount;
    uint256 public securityDeposit;
    uint256 public leaseStartDate;
    uint256 public leaseEndDate;
    bool public isActive;

    event RentPaid(address tenant, uint256 amount, uint256 date);
    event ContractTerminated(uint256 date);

    constructor(address _tenant, uint256 _rentAmount, uint256 _securityDeposit, uint256 _leaseStartDate, uint256 _leaseEndDate) {
        landlord = msg.sender;
        tenant = _tenant;
        rentAmount = _rentAmount;
        securityDeposit = _securityDeposit;
        leaseStartDate = _leaseStartDate;
        leaseEndDate = _leaseEndDate;
        isActive = true;
    }

    function payRent() public payable {
        require(msg.sender == tenant, "Only tenant can pay rent");
        require(msg.value == rentAmount, "Incorrect rent amount");
        require(isActive, "Lease is not active");

        payable(landlord).transfer(msg.value);
        emit RentPaid(tenant, msg.value, block.timestamp);
    }

    function terminateContract() public {
        require(msg.sender == landlord || msg.sender == tenant, "Only landlord or tenant can terminate the contract");
        require(isActive, "Lease is already terminated");

        isActive = false;
        emit ContractTerminated(block.timestamp);

        // Return security deposit to tenant
        payable(tenant).transfer(securityDeposit);
    }
}