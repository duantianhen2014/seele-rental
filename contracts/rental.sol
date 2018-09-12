pragma solidity ^0.4.0;

contract SeeleRental {

    address public owner;

    mapping(address => uint) public balances;

    struct Rental {
        address rentalOwner;
        uint charge;
        uint deposit;
        bool aConfirm;
        bool bConfirm;
        bool aCompleteConfirm;
    }

    mapping(address => Rental) public records;

    constructor() public {
        owner = msg.sender;
    }

    modifier ownerCheck() {
        require(owner == msg.sender, "403");
        _;
    }

    function() public payable {
        balances[msg.sender] += msg.value;
    }

    function queryBalance(address queryAddress) public view returns (uint) {
        return balances[queryAddress];
    }

    function queryRecords(address queryAddress) public view returns (address rentalOwner, uint charge, uint deposit, bool aConfirm, bool bConfirm, bool aCompleteConfirm) {
        rentalOwner = records[queryAddress].rentalOwner;
        charge = records[queryAddress].charge;
        deposit = records[queryAddress].deposit;
        aConfirm = records[queryAddress].aConfirm;
        bConfirm = records[queryAddress].bConfirm;
        aCompleteConfirm = records[queryAddress].aCompleteConfirm;
    }

    function withdraw(uint money) public returns (bool result, uint code) {
        if (money > balances[msg.sender]) {
            result = false;
            code = 1;
            return;
        }
        if (records[msg.sender].aConfirm != false) {
            result = false;
            code = 10;
            return;
        }
        balances[msg.sender] -= money;
        msg.sender.transfer(money);
        result = true;
        code = 0;
    }

    function aApply(address bAddress, uint charge) public returns (bool result) {
        if (records[msg.sender].aConfirm != false) {
            result = false;
        } else {
            records[msg.sender].rentalOwner = bAddress;
            records[msg.sender].deposit = 0;
            records[msg.sender].charge = charge;
            records[msg.sender].aConfirm = false;
            records[msg.sender].bConfirm = false;
            records[msg.sender].aCompleteConfirm = false;
        }
    }

    function bConfirm(address aAddress, uint charge, uint deposit, bool isAgree) public returns (bool result, uint code) {
        if (records[aAddress].rentalOwner != msg.sender) {
            result = false;
            code = 1;
            return;
        }
        if (records[aAddress].bConfirm != false) {
            result = false;
            code = 10;
            return;
        }
        if (isAgree == true) {
            records[aAddress].bConfirm = isAgree;
            records[aAddress].deposit = deposit;
            records[aAddress].charge = charge;
        } else {
            address newAddress;
            records[aAddress].rentalOwner = newAddress;
            records[aAddress].deposit = 0;
            records[aAddress].charge = 0;
            records[aAddress].aConfirm = false;
            records[aAddress].bConfirm = false;
            records[aAddress].aCompleteConfirm = false;
        }
        result = true;
        code = 0;
    }

    function aConfirm(bool isAgree) public returns (bool result, uint code) {
        if (records[msg.sender].bConfirm != true) {
            result = false;
            code = 1;
            return;
        }
        if (balances[msg.sender] < (records[msg.sender].deposit + records[msg.sender].charge)) {
            isAgree = false;
        }
        if (isAgree == true) {
            records[msg.sender].aConfirm = true;
        } else {
            records[msg.sender].deposit = 0;
            records[msg.sender].charge = 0;
            records[msg.sender].aConfirm = false;
            records[msg.sender].bConfirm = false;
            records[msg.sender].aCompleteConfirm = false;
        }
        result = true;
        code = 0;
    }

    function aComplete() public returns (bool result, uint code) {
        if (records[msg.sender].aConfirm != true || records[msg.sender].bConfirm != true) {
            result = false;
            code = 0;
            return;
        }
        records[msg.sender].aCompleteConfirm = true;
        result = true;
        code = 0;
    }

    function bComplete(address aAddress) public returns (bool result, uint code) {
        if (records[aAddress].rentalOwner != msg.sender) {
            result = false;
            code = 1;
            return;
        }
        if (records[aAddress].aCompleteConfirm != true) {
            result = false;
            code = 10;
            return;
        }
        // 增加b的余额
        balances[msg.sender] += records[aAddress].charge;
        balances[aAddress] -= records[aAddress].charge;
        // 恢复初始状态
        address newAddress;
        records[aAddress].rentalOwner = newAddress;
        records[aAddress].deposit = 0;
        records[aAddress].charge = 0;
        records[aAddress].aConfirm = false;
        records[aAddress].bConfirm = false;
        records[aAddress].aCompleteConfirm = false;

        result = true;
        code = 0;
    }

    function arbitrate(address aAddress, address bAddress, uint giveB) public ownerCheck {
        require(records[aAddress].bConfirm == true, "no effective rental");
        require(records[aAddress].rentalOwner == bAddress, "no relation");
        // 重置订单
        address newAddress;
        records[aAddress].rentalOwner = newAddress;
        records[aAddress].deposit = 0;
        records[aAddress].charge = 0;
        records[aAddress].aConfirm = false;
        records[aAddress].bConfirm = false;
        records[aAddress].aCompleteConfirm = false;

        balances[aAddress] -= giveB;
        balances[bAddress] += giveB;
    }
}