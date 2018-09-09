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

    modifier inputNumberCheck(uint money) {
        require(money < balances[msg.sender], "max balance");
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

    function withdraw(uint money) public inputNumberCheck(money) {
        require(records[msg.sender].aConfirm == false, "has rental record");
        balances[msg.sender] -= money;
        msg.sender.transfer(money);
    }

    function aApply(address bAddress, uint charge) public {
        require(records[msg.sender].aConfirm == false, "has rental record");
        records[msg.sender].rentalOwner = bAddress;
        records[msg.sender].deposit = 0;
        records[msg.sender].charge = charge;
        records[msg.sender].aConfirm = false;
        records[msg.sender].bConfirm = false;
        records[msg.sender].aCompleteConfirm = false;
    }

    function bConfirm(address aAddress, uint charge, uint deposit, bool isAgree) public {
        require(records[aAddress].rentalOwner == msg.sender, "no auth");
        require(records[aAddress].bConfirm == false, "repeat action");
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
    }

    function aConfirm(bool isAgree) public {
        require(records[msg.sender].bConfirm == true, "error2");
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
    }

    function aComplete() public {
        require(records[msg.sender].aConfirm == true && records[msg.sender].bConfirm == true, "no effective rental");
        records[msg.sender].aCompleteConfirm = true;
    }

    function bComplete(address aAddress) public {
        require(records[aAddress].rentalOwner == msg.sender, 'no auth');
        require(records[aAddress].aCompleteConfirm == true, "a no agree");
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