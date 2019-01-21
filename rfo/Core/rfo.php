<?php function GetClassNumber($ssn) {
    require_once '../../database.php';
    //get class number for given SSN
    
    global $db;
    $query = "SELECT * FROM classroster WHERE SSN = " . $ssn;
    $classroster = $db->query($query); 
    
    //stores first row (only row) in $classNumber
    $classNumber = $classroster->fetch();
   
    return $classNumber['ClassNumber'];  
}
?>

<?php class RFO { 
    //add class members
    private $ssn;
    private $complete;
    
    private $airborne;
    private $HRAP;
    private $APFT;
    private $SecurityClearance;
    private $UCMJ;
    private $leave;
    private $POV;
    private $family;
    private $POR;
    private $profile;
    private $dentalcategory;
    private $PHA;
    private $travel;
    
    private static $RFOCount = 0;
    public static function GetRFOCount() { return self::$RFOCount; }


    public function __construct($ssn = '', $airborne = '', $HRAP = '', $APFT = '', $SecurityClearance = '', $UCMJ = '', $leave = '', $POV = '', $family = '', $POR = '', $profile = '', $dentalcategory = '', $PHA = '', $travel = '') {
        $this->ssn = $ssn;
        $this->airborne = $airborne;
        $this->HRAP = $HRAP;
        $this->APFT = $APFT;
        $this->SecurityClearance = $SecurityClearance;
        $this->UCMJ = $UCMJ;
        $this->leave = $leave;
        $this->POV = $POV;
        $this->family = $family;
        $this->POR = $POR;
        $this->profile = $profile;
        $this->dentalcategory = $dentalcategory;
        $this->PHA = $PHA;
        $this->travel = $travel;
        $this->complete = 0;
        
        self::$RFOCount++;
    }
    
    public function __destruct() {
        self::$RFOCount--;
    }

    public function AddToDB() {
        require_once '../../database.php';

        global $db;
        
        if($this->GetComponentFromDB() == 'RA') {
            //this service member is RA
            $query = "INSERT INTO rfo_ra (SSN, Airborne, HRAP, APFT, SecurityClearance, 
                UCMJ, TakingLeave, POV, Family, POR, Profile, DentalCategory, PHA)
                VALUES ('" . $this->ssn . "'";
        } else {
            //this service member is NG or ER
            $query = "INSERT INTO rfo_nger (SSN, Airborne, HRAP, APFT, SecurityClearance, 
                UCMJ, Profile, DentalCategory, PHA, Travel)
                VALUES ('" . $this->ssn . "'";
        }
        
        $query .= ", '" . $this->airborne . "'";
        $query .= ", '" . $this->HRAP . "'";
        $query .= ", '" . $this->APFT . "'";
        $query .= ", '" . $this->SecurityClearance . "'";
        $query .= ", '" . $this->UCMJ . "'";
        
        if($this->GetComponentFromDB() == 'RA') {
            $query .= ", '" . $this->leave . "'";
            $query .= ", '" . $this->POV . "'";
            $query .= ", '" . $this->family . "'";
            $query .= ", '" . $this->POR . "'";
        }
        
        $query .= ", '" . $this->profile . "'";
        $query .= ", '" . $this->dentalcategory . "'";
        $query .= ", '" . $this->PHA . "'";
        
        if($this->GetComponentFromDB() != 'RA') {
            $query .= ", '" . $this->travel . "'";
        }
        
        $query .= ")";
        $rowsaffected = $db->exec($query); 
        $this->complete = $rowsaffected;
        
        return $rowsaffected; 
    }
    
    public function RemoveFromDB() {
        require_once '../../database.php';
    
        global $db;
        if($this->GetComponentFromDB() == 'RA') {
            $query = "DELETE FROM rfo_ra WHERE SSN = '" . $this->ssn . "'";
            $num_rows_affected = $db->exec($query); 
            if($num_rows_affected) { $this->complete = 0; }
            return $num_rows_affected;
        } else {
            $query = "DELETE FROM rfo_nger WHERE SSN = '" . $this->ssn . "'";
            $num_rows_affected = $db->exec($query);
            if($num_rows_affected) { $this->complete = 0; }
            return $num_rows_affected;
        }
    }
    
    public function UpdateDB() {
        require_once '../../database.php';
    
        global $db;
        
        if($this->GetComponentFromDB() == 'RA') {
            $query = "UPDATE rfo_ra SET Airborne = '" . $this->airborne;
        } else {
            $query = "UPDATE rfo_nger SET Airborne = '" . $this->airborne;
        }
        
        $query .= "', HRAP = '" . $this->HRAP;
        $query .= "', APFT = '" . $this->APFT;
        $query .= "', SecurityClearance = '" . $this->SecurityClearance;
        $query .= "', UCMJ = '" . $this->UCMJ;
        
        if($this->GetComponentFromDB() == 'RA') {
            $query .= "', TakingLeave = '" . $this->leave;
            $query .= "', POV = '" . $this->POV;
            $query .= "', Family = '" . $this->family;
            $query .= "', POR = '" . $this->POR;
        }
        
        $query .= "', UCMJ = '" . $this->UCMJ;
        $query .= "', Profile = '" . $this->profile;
        $query .= "', DentalCategory = '" . $this->dentalcategory;
        $query .= "', PHA = '" . $this->PHA;
        
        if($this->GetComponentFromDB() != 'RA') {
            $query .= "', Travel = '" . $this->travel;
        }
        
        $query .= "' WHERE SSN = '" . $this->ssn;
        $query .= "'"; 
        $numrowsaffected = $db->exec($query);
        if($numrowsaffected) { $this->SetCompletion(1); }

        return $numrowsaffected;
    }
    
    public function SetFromDB($ssn) {
        require_once '../../database.php';
    
        global $db;
        
        if($this->GetComponentFromDB() == 'RA') {
            $query = "SELECT * FROM rfo_ra WHERE SSN = '" . $ssn . "'";
            $rfo = $db->query($query);  
            $rfo = $rfo->fetch();
            
            $this->leave = $rfo['TakingLeave'];
            $this->POV = $rfo['POV'];
            $this->family = $rfo['Family'];
            $this->POR = $rfo['POR'];
        } else {
            $query = "SELECT * FROM rfo_nger WHERE SSN = '" . $ssn . "'";
            $rfo = $db->query($query);  
            $rfo = $rfo->fetch();
            
            $this->travel = $rfo['Travel'];
        }
        
        $this->ssn = $rfo['SSN'];
        $this->airborne = $rfo['Airborne'];
        $this->HRAP = $rfo['HRAP'];
        $this->APFT = $rfo['APFT'];
        $this->SecurityClearance = $rfo['SecurityClearance'];
        $this->UCMJ = $rfo['UCMJ'];

        $this->profile = $rfo['Profile'];
        $this->dentalcategory = $rfo['DentalCategory'];
        $this->PHA = $rfo['PHA'];    

        $this->SetCompletion(1);
        return 1;
    }
    
    public function ChangeSSN_In_DB($toSSN) { //must have SSN already set in order to change
	require_once '../../database.php';
	global $db;
	
	if($this->GetComponentFromDB() == 'RA') {
	    $query = "UPDATE rfo_ra SET SSN = '" . $ssn;
	} else {
	    $query = "UPDATE rfo_nger SET SSN = '" . $ssn;
	}
	
        $query .= "' WHERE SSN = '" . $this->ssn . "'";
	$numrowsaffected = $db->exec($query);
	
	return numrowsaffected;
    }
    public function GetSSN() { return $this->ssn; }
    public function SetSSN($ssn) { $this->ssn = $ssn; }
    
    public function Set($airborne, $HRAP, $APFT, $SecurityClearance, $UCMJ, $leave, $POV, $family, $POR, $profile, $dentalcategory, $PHA, $travel = '') {
        $this->airborne = $airborne;
        $this->HRAP = $HRAP;
        $this->APFT = $APFT;
        $this->SecurityClearance = $SecurityClearance;
        $this->UCMJ = $UCMJ;
        $this->leave = $leave;
        $this->POV = $POV;
        $this->family = $family;
        $this->POR = $POR;
        $this->profile = $profile;
        $this->dentalcategory = $dentalcategory;
        $this->PHA = $PHA;
        $this->travel = $travel;
    }

    private function GetComponentFromDB() {
        require_once '../../database.php';
    
        global $db;
        $query = "SELECT * FROM alpha WHERE SSN = '" . $this->ssn . "'";
        $soldier = $db->query($query);
        $soldier = $soldier->fetch();
        return $soldier['Component'];
    }
    
    public function SetCompletion($value) { $this->complete = $value; }
    public function GetCompletion() { return $this->complete; }
    
    public function GetAirborne() { return $this->airborne; }
    public function GetHRAP() { return $this->HRAP; }
    public function GetAPFT() { return $this->APFT; }
    public function GetSecurityClearance() { return $this->SecurityClearance; }
    public function GetUCMJ() { return $this->UCMJ; }
    public function GetLeave() { return $this->leave; }
    public function GetPOV() { return $this->POV; }
    public function GetFamily() { return $this->family; }
    public function GetPOR() { return $this->POR; }
    public function GetProfile() { return $this->profile; }
    public function GetDentalCategory() { return $this->dentalcategory; }
    public function GetPHA() { return $this->PHA; }
    public function GetTravel() { return $this->travel; }
}
?>

<?php /*$rfo = new RFO;*/ ?>
