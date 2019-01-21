<?php include_once $_SESSION['rootDir'] . 'class/mortgagehistory.php';

class Mortgage {
    private $id;
    private $address;
    private $originalLoan;
    private $downPayment;
    private $balance;
    private $interestRate;
    private $startDate;
    private $allotment;
    
    private $currentMortgage;
    private $currentEscrow;

    private $mortgageHistory;
    
    public function GetID() { return $this->id; }
    public function SetID($to) { $this->id = $to; }
    
    public function GetAddress() { return $this->address; }
    public function SetAddress($to) { $this->address = $to; }
    
    public function GetOriginalLoan() { return $this->originalLoan; }
    public function SetOriginalLoan($to) { $this->originalLoan = $to; }
    
    public function GetDownPayment() { return $this->downPayment; }
    public function SetDownPayment($to) { $this->downPayment = $to; }
    
    public function GetBalance() { return $this->balance; }
    public function SetBalance($to) { $this->balance = $to; }
    
    public function GetAdjustedLoanAmount() { return $this->originalLoan - $this->downPayment; }
    
    public function GetInterestRate() { return $this->interestRate; }
    public function SetInterestRate($to) { $this->interestRate = $to; }
    
    public function GetStartDate() { return $this->startDate; }
    public function SetStartDate($to) { $this->startDate = date_create($to); }
    
    public function GetAllotment() { return $this->allotment; }
    public function SetAllotment($to) { $this->allotment = $to; }
    
    public function GetCurrentMortgage() { return $this->currentMortgage; }
    public function SetCurrentMortgage($to) { $this->currentMortgage = $to; }

    public function GetCurrentEscrow() { return $this->currentEscrow; }
    public function SetCurrentEscrow($to) { $this->currentEscrow = $to; }
    
    public function UpdateCurrentMortgage($new_mortgage) {
        require_once $_SESSION['rootDir'] . '../../database.php';
        global $db;
        
        $values = array($new_mortgage, $this->GetID());
        if($db->SafeExec("UPDATE data SET CurrentMortgage = :0 WHERE `ID` = :1",$values)) {
            if($this->mortgageHistory->UpdateMortgageForYear($new_mortgage, intval(date('Y')))) {
                return 1;
            } else { return -2; }
        } else { return -1; }
    }
    public function UpdateCurrentEscrow($new_escrow) {
        require_once $_SESSION['rootDir'] . '../../database.php';
        global $db;
        
        $values = array($new_escrow, $this->GetID());
        if($db->SafeExec("UPDATE data SET CurrentEscrow = :0 WHERE `ID` = :1",$values)) {
            if($this->mortgageHistory->UpdateEscrowForYear($new_escrow, intval(date('Y')))) {
                return 1;
            } else { return -2; }
        } else { return -1; }
    }
    
    public function GetEscrow($year) { return $this->mortgageHistory->GetEscrow($year); }
    public function GetMortgage($year) { return $this->mortgageHistory->GetMortgage($year); }
    
    //payment functions
    public function GetInterestPortion($ofBalance) { return $ofBalance * $this->interestRate / 12; }
    public function GetPrinciplePortion($currBalance, $escrowWas, $mortgageWas) { return $mortgageWas - $escrowWas - $this->GetInterestPortion($currBalance); }

    public function __construct($id) {
        $this->SetFromDB($id);
    }
    public function __destruct() {
        unset($this->mortgageHistory);
    }
    
    public function SetFromDB($id) {
        require_once $_SESSION['rootDir'] . '../../database.php';
        global $db;

        $dbItem = $db->SafeFetch("SELECT * FROM data WHERE `ID` = :0",array($id));

        //set all member variables here...
        $this->SetID($id);
        $this->SetAddress($dbItem['Address']);
        $this->SetOriginalLoan($dbItem['OriginalLoan']);
        $this->SetDownPayment($dbItem['DownPayment']);
        $this->SetBalance($dbItem['Balance']);
        $this->SetInterestRate($dbItem['InterestRate']);
        $this->SetStartDate($dbItem['StartDate']);
        $this->SetAllotment($dbItem['Allotment']);
        $this->SetCurrentMortgage($dbItem['CurrentMortgage']);
        $this->SetCurrentEscrow($dbItem['CurrentEscrow']);
        
        $this->mortgageHistory = new MortgageHistory($id, $dbItem['CurrentMortgage'], $dbItem['CurrentEscrow']);
    }
}
