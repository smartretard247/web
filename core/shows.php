<?php class Show { 
    private $ID;
    private $name;
    private $airs;
    private $titleNum;
    
    private static $ShowCount = 0;
    public static function GetShowCount() { return self::$ShowCount; }

    public function __construct($name = '', $airs = '0', $titleNum = '0000000') {
        //$this->ID = $ID;
        $this->name = $name;
        $this->airs = $airs;
        $this->titleNum = $titleNum;
        
        self::$ShowCount++;
    }
    
    public function __destruct() {
        self::$ShowCount--;
    }
    
    public function AddToDB() {
        require_once 'database.php';
    
        //if($this->IsValidID()) {
            //if(!$this->IsDuplicateID()) {
                global $db;
                $query = "INSERT INTO shows (Name, Airs, IMDBTitleNum, Quality, CurrentEpisode)
                    VALUES ('";
                //$query .= $this->ID . "'";
		$query .= $this->name . "'";
                $query .= ", '" . $this->airs . "'";
                $query .= ", '" . $this->titleNum . "'";
                $query .= ", '720p'";
                $query .= ", 'S01E01'";
                $query .= ")";
                
                $num_rows_affected = $db->exec($query);  
                
                return $num_rows_affected;
            //} return 0; //double ID
        //}
    }
    
    public static  function RemoveFromDB($ID) {
        require_once 'database.php';
    
        global $db;
        
        $query = "DELETE FROM shows WHERE ID = '" . $ID . "'";
        $num_rows_affected = $db->exec($query);  

        return $num_rows_affected; 
    }
    
    public function UpdateDB() {
        require_once 'database.php';
    
        global $db;
        $query = "UPDATE shows SET Name = '" . $this->name;
        $query .= "', Airs = '" . $this->airs;
        $query .= "', IMDBTitleNum = '" . $this->titleNum;
        $query .= "' WHERE ID = '" . $this->ID;
        $query .= "'"; 
        $numrowsaffected = $db->exec($query);
        
        return $numrowsaffected;
    }
    
    public function SetFromDB($ID) {
        require_once 'database.php';
    
        global $db;
        $query = "SELECT * FROM shows WHERE ID = '$ID'";
        $item = $db->query($query);  
        $item = $item->fetch();
        
        $this->SetID($ID);
	$this->name = $item['Name'];
        $this->airs = $item['Airs'];
        $this->titleNum = $item['IMDBTitleNum'];
        
        return $item;
    }
    
    public function GetName() { return $this->name; }
    public function SetName($name) { $this->name = $name; }
    
    public function GetAirs() { return $this->airs; }
    public function SetAirs($num) { $this->airs = $num; }
    
    public function GetTitleNum() { return $this->titleNum; }
    public function SetTitleNum($num) { $this->titleNum = $num; }
	
    public function ChangeID_In_DB($toID) { //must have ID already set in order to change
	require_once 'database.php';
	global $db;
	
	$query = "UPDATE shows SET ID = '" . $ID;
        $query .= "' WHERE ID = '" . $this->ID . "'";
	$numrowsaffected = $db->exec($query);

	return $numrowsaffected;
    }
    public function GetID() { return $this->ID; }
    public function SetID($ID) { 
        //$this->ID = $ID; 
    }
    public function IsValidID() { 
        if($this->ID > 1 && $this->ID != '') { return true; }
        else { return false; }
    }
    public function IsDuplicateID() {
        require_once 'database.php';
    
        global $db;
        $query = "SELECT * FROM shows";
        $item = $db->Query($query); 
        
	if($item) {
	    foreach($item as $row) {
		if($row['ID'] == $this->ID) {
		    return true;
		}
	    }
	}
        
        return false;
    }
}
?>
