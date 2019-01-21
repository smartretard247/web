<?php class MortgageHistory {
    private $id;
    private $lastYearOfRecord;
    private $inflation;
    
    private $currentEscrow;
    private $currentMortgage;

    private static $historyArray;
    
    public function GetID() { return $this->id; }
    public function SetID($to) { $this->id = $to; }
    
    public function GetInflation() { return $this->inflation; }
    public function SetInflation($to) { $this->inflation = $to; }
    
    public function GetMortgage($year) {
        if($year == $this->lastYearOfRecord+1 && $this->currentMortgage != self::$historyArray[$year]['Mortgage']) {
            $this->UpdateMortgageForYear($this->currentMortgage, $year);
            self::$historyArray[$year]['Mortgage'] = $this->currentMortgage;
        }
        
        return self::$historyArray[$year]['Mortgage'];
    }
    public function GetEscrow($year) {
        if($year == $this->lastYearOfRecord+1 && $this->currentEscrow != self::$historyArray[$year]['Escrow']) {
            $this->UpdateMortgageForYear($this->currentEscrow, $year);
            self::$historyArray[$year]['Escrow'] = $this->currentEscrow;
        }
        
        return self::$historyArray[$year]['Escrow'];
    }
    
    private function CalculateInflation($escrow, $mortgage) {
        //store the remaining years as incremental inflation
        for($i = $this->lastYearOfRecord+1; $i < $this->lastYearOfRecord+30; $i++) {
            $escrowDiff = $escrow * $this->inflation;
            $mortgage += $escrowDiff;
            $escrow *= (1.0+$this->inflation);
            
            self::$historyArray[$i] = array('Mortgage' => number_format($mortgage,2),'Escrow' => number_format($escrow,2));
        }
    }
    
    //db functions
    private function InsertMERecordForYear($year) {
        require_once $_SESSION['rootDir'] . '../../database.php';
        global $db;
        $mortgagevalues = array($this->id, $year);
        
        $record = $db->SafeFetch('SELECT ID FROM mortgagehistory WHERE AddressID = :0 AND Year = :1',$mortgagevalues);
        if($record['ID']) { return; }
        
        $mortgagevalues[] = $this->currentMortgage; //add mortgage to the end of values array
        $success = $db->SafeExec('INSERT INTO mortgagehistory (AddressID, Year, Mortgage) VALUES (:0, :1, :2)',$mortgagevalues);
        
        $escrowvalues = array($this->id, $year, $this->currentEscrow);
        $success = $db->SafeExec('INSERT INTO escrowhistory (AddressID, Year, Escrow) VALUES (:0, :1, :2)',$escrowvalues);
        
        if($success) { $this->lastYearOfRecord++; $this->CalculateInflation($this->currentEscrow, $this->currentMortgage);}
    }
    
    public function UpdateEscrowForYear($escrow, $forYear) {
        require_once $_SESSION['rootDir'] . '../../database.php';
        global $db;
        $values = array($escrow, $this->id, $forYear);
        return $db->SafeExec('UPDATE escrowhistory SET Escrow = :0 WHERE AddressID = :1 AND Year = :2',$values);
    }
    public function UpdateMortgageForYear($mortgage, $forYear) {
        require_once $_SESSION['rootDir'] . '../../database.php';
        global $db;
        $values = array($mortgage, $this->id, $forYear);
        return $db->SafeExec('UPDATE mortgagehistory SET Mortgage = :0 WHERE AddressID = :1 AND Year = :2',$values);
    }
    //end db functions

    public function __construct($id, $currentMortgage, $currentEscrow) {
        $this->inflation = 0.01;
        $this->currentEscrow = $currentEscrow;
        $this->currentMortgage = $currentMortgage;
        
        $this->SetFromDB($id);
    }
    public function __destruct() {
        
    }
    
    public function SetFromDB($id) {
        require_once $_SESSION['rootDir'] . '../../database.php';
        global $db;

        $this->SetID($id);
        
        //load values into mortgage array
        $dbItem = $db->SafeFetchAll("SELECT mortgagehistory.Year, mortgagehistory.Mortgage, escrowhistory.Escrow FROM mortgagehistory INNER JOIN escrowhistory ON mortgagehistory.AddressID=:0 AND escrowhistory.AddressID=:1 AND mortgagehistory.Year=escrowhistory.Year;",array($id,$id));
        foreach($dbItem as $row) {
            $year = $row['Year'];
            $mortgage = $row['Mortgage'];
            $escrow = $row['Escrow'];
            
            self::$historyArray[$year] = array('Mortgage' => $mortgage,'Escrow' => $escrow);
            $this->lastYearOfRecord = $year;
        }
        
        $currentYear = intval(date('Y'));
        if($currentYear == intval($this->lastYearOfRecord)+1) {
            $this->InsertMERecordForYear($currentYear);
        }
        
        $this->CalculateInflation($escrow, $mortgage);
    }
}