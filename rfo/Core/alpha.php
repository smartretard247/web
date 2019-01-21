<?php 
    include 'rfo.php';
    
class Soldier { 
    private $ssn;
    private $last, $first, $mi;
    private $classnumber;
    private $rank;
    private $comp;
    private $rfo;
    
    private static $SoldierCount = 0;
    public static function GetSoldierCount() { return self::$SoldierCount; }

    public function __construct($ssn = '', $last = '', $comp = 'RA') {
        $this->ssn = $ssn;
        $this->last = $last;
        //$this->first = $first;
        //$this->mi = $mi;
        $this->comp = $comp;
        //$this->rfo = $rfo;
        
        $this->rfo = new RFO;
        
        self::$SoldierCount++;
    }
    
    public function __destruct() {
        self::$SoldierCount--;
    }
    
    public function AddToDB() {
        require_once '../../database.php';
    
        if($this->IsValidSSN()) {
            if(!$this->IsDuplicateSSN()) {
                global $db;
                $query = "INSERT INTO alpha (SSN, LastName, ClassNumber, Rank, Component, RFO)
                    VALUES ('";
                $query .= $this->ssn . "'";
                $query .= ", '" . $this->last . "'";
                //$query .= ", '" . $this->first . "'";
				//$query .= ", '" . $this->mi . "'";
                $query .= ", '" . $this->classnumber . "'";
				$query .= ", '" . $this->rank . "'";
                $query .= ", '" . $this->comp . "'";
                $query .= ", '" . $this->rfo->GetCompletion() . "'";
                $query .= ")";
                
                $num_rows_affected = $db->exec($query);  
                if($num_rows_affected) {
                    $this->rfo->SetSSN($this->ssn);
                }
                
                return $num_rows_affected;
            } return 0; //double ssn
        }
    }
    
    public function RemoveFromDB() {
        require_once '../../database.php';
    
        global $db;
        
        $this->rfo->SetSSN($this->ssn);
        $this->rfo->RemoveFromDB();
        
        $query = "DELETE FROM alpha WHERE SSN = '" . $this->ssn . "'";
        $num_rows_affected = $db->exec($query);  

        return $num_rows_affected; 
    }
    
    public function UpdateDB() {
        require_once '../../database.php';
    
        global $db;
        $query = "UPDATE alpha SET LastName = '" . $this->last;
        //$query .= "', FirstName = '" . $this->first;
        //$query .= "', MiddleInit = '" . $this->mi;
		$query .= "', ClassNumber = '" . $this->classnumber;
		$query .= "', Rank = '" . $this->rank;
        $query .= "', Component = '" . $this->comp;
        $query .= "', RFO = '" . $this->rfo->GetCompletion();
        $query .= "' WHERE SSN = '" . $this->ssn;
        $query .= "'"; 
        $numrowsaffected = $db->exec($query);
        
        return $numrowsaffected;
    }
    
    public function SetFromDB($ssn) {
        require_once '../../database.php';
    
        global $db;
        $query = "SELECT * FROM alpha WHERE SSN = '$ssn'";
        $soldier = $db->query($query);  
        $soldier = $soldier->fetch();
        
        $this->SetSSN($ssn);
        $this->last = $soldier['LastName'];
        //$this->first = $soldier['FirstName'];
        //$this->mi = $soldier['MiddleInit'];
		$this->classnumber = $soldier['ClassNumber'];
		$this->rank = $soldier['Rank'];
        $this->comp = $soldier['Component'];
        if($soldier['RFO']) {
            $this->rfo->SetFromDB($this->ssn);
        }
        
        return $soldier;
    }
    
    public function GetComponent() { return $this->comp; }
    public function SetComponent($comp) { $this->comp = $comp; }
    
	//public function GetMiddle() { return $this->mi; }
	
    public function GetName() {
        //$concat = $this->last . ', ' . $this->first;
        
        return $this->last;
    }
    public function SetName($name) {
        $this->last = $name;
        //$this->first = $first;
        //$this->mi = $mi;
    }
	
	public function GetClassNumber() { return $this->classnumber; }
	public function SetClassNumber($classnum) { $this->classnumber = $classnum; }
	
	public function GetRank() { return $this->rank; }
	public function SetRank($rank) { $this->rank = $rank; }
    
    public function ChangeSSN_In_DB($toSSN) { //must have SSN already set in order to change
	require_once '../../database.php';
	global $db;
	
	$query = "UPDATE alpha SET SSN = '" . $ssn;
        $query .= "' WHERE SSN = '" . $this->ssn . "'";
	$numrowsaffected = $db->exec($query);
	if(numrowsaffected && $this->rfo->GetCompletion()) {
	    $this->rfo->ChangeSSN_In_DB($toSSN);
	}
	return $numrowsaffected;
    }
    public function GetSSN() { return $this->ssn; }
    public function SetSSN($ssn) { 
        $this->ssn = $ssn; 
        $this->rfo->SetSSN($ssn);
    }
    public function IsValidSSN() { 
        if($this->ssn > 999999 && $this->ssn <= 999999999) { return true; }
        else { return false; }
    }
    public function IsDuplicateSSN() {
        require_once '../../database.php';
    
        global $db;
        $query = "SELECT * FROM alpha";
        $soldier = $db->Query($query); 
        
	if($soldier) {
	    foreach($soldier as $row) {
		if($row['SSN'] == $this->ssn) {
		    return true;
		}
	    }
	}
        
        return false;
    }
    
    public function GetRFO() { return $this->rfo; }
    public function SetRFO($rfo) { $this->rfo = $rfo; }
}
?>

<?php $soldier = new Soldier; ?>
