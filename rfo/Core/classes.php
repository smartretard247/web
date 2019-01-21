<?php 
    
class Classes { 
    private $classnumber;
    private $grad_date;
    
    private static $ClassCount = 0;
    public static function GetClassCount() { return self::$ClassCount; }

    public function __construct($classnum = '999-99', $grad_date = '1900-01-01') {
        $this->classnumber = $classnum;
        $this->grad_date = $grad_date;
        
        self::$ClassCount++;
    }
    
    public function __destruct() {
        self::$ClassCount--;
    }
    
    public function AddToDB() {
        require_once '../../database.php';
    
        if(!$this->IsDuplicateClassNumber() && $this->classnumber != '') {
                global $db;
                $query = "INSERT INTO classes (ClassNumber, GradDate)
                    VALUES ('";
                $query .= $this->classnumber . "'";
                $query .= ", '" . $this->grad_date . "'";
                $query .= ")";
                
                $num_rows_affected = $db->exec($query);  
                
                return $num_rows_affected;
        } return 0; //double or empty class
    }
    
    public function RemoveFromDB() {
        require_once '../../database.php';
    
        global $db;
        
        $query = "DELETE FROM classes WHERE ClassNumber = '" . $this->classnumber . "'";
        $num_rows_affected = $db->exec($query);  

        return $num_rows_affected; 
    }
    
    public function UpdateDB() {
        require_once '../../database.php';
    
        global $db;
        $query = "UPDATE classes SET ClassNumber = '" . $this->classnumber;
        $query .= "', GradDate = '" . $this->grad_date;
        $query .= "' WHERE ClassNumber = '" . $this->classnumber;
        $query .= "'"; 
        $numrowsaffected = $db->exec($query);
        
        return $numrowsaffected;
    }
    
    public function SetFromDB($classnumber) {
        require_once '../../database.php';
    
        global $db;
        $query = "SELECT * FROM classes WHERE ClassNumber = '$classnumber'";
        $class = $db->query($query);  
        $class = $class->fetch();
        
        $this->SetClassNumber($classnumber);
        $this->classnumber = $class['ClassNumber'];
	$this->grad_date = $class['GradDate'];
        
        return $class;
    }    
	
    public function GetGradDate() { return $this->grad_date; }
    public function SetGradDate($grad_date) { $this->grad_date = $grad_date; }
    
    public static function FindGradDate($classnum) {
	require_once '../../database.php';
	global $db;
	
	$query = "SELECT * FROM `classes` WHERE ClassNumber = '" . $classnum . "'";
	$grad_date = $db->Query($query);
	$grad_date = $grad_date->fetch();
	
	return $grad_date['GradDate'];
    }
    
    public function ChangeClassNumber_In_DB($toClassNumber) { //must have ClassNumber already set in order to change
	require_once '../../database.php';
	global $db;
	
	$query = "UPDATE classes SET ClassNumber = '" . $classnumber;
        $query .= "' WHERE ClassNumber = '" . $this->classnumber . "'";
	$numrowsaffected = $db->exec($query);
	
	return $numrowsaffected;
    }
    
    public function GetClassNumber() { return $this->classnumber; }
    public function SetClassNumber($classnumber) { $this->classnumber = $classnumber; }
    
    public function IsDuplicateClassNumber() {
        require_once '../../database.php';
    
        global $db;
        $query = "SELECT * FROM classes";
        $class = $db->Query($query); 
        
	if($class) {
	    foreach($class as $row) {
		if($row['ClassNumber'] == $this->classnumber) {
		    return true;
		}
	    }
	}
        
        return false;
    }
}
?>

<?php $class = new Classes; ?>
