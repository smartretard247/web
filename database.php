<?php 

$localIP = $_SERVER['SERVER_ADDR'];
//$localIP = '192.168.1.100';

class Database {
    private static $dsn;
    private static $username = 'Jeezy';
    private static $password = 'BLiss20106=';
    private static $port = '3307';
    private static $db;

    public function __construct($dbname) {
        global $localIP;
        self::$dsn = "mysql:host=$localIP";
        self::$dsn .= ":" . self::$port;
        self::$dsn .= ";dbname=";
        
        try {
            $complete_dsn = self::$dsn . $dbname;
            self::$db = new PDO($complete_dsn, self::$username, self::$password);
        } catch (PDOException $e) {
            //$error_message = $e->getMessage();
            $_SESSION['error_message'] = $e->getMessage();
            include('index.php');
            exit();
        }
    }

    public static function GetDB() { return self::$db; }
    public static function Query($query) { return self::$db->query($query); }
    public static function Exec($query) { return self::$db->exec($query); }
    
    public static function SafeFetch($query, $aArgs = array()) {
        $numArgs = count($aArgs);
        
        $stmt = self::$db->prepare($query);
        
        for($i = 0; $i < $numArgs; $i++) {
            $stmt->bindParam(":" . strval($i), $aArgs[$i]);
        }
        
        if($stmt->execute()) {
            return $stmt->fetch();
        } else {
            return false;
        }
    }
    
    public static function SafeFetchAll($query, $aArgs = array()) {
        $numArgs = count($aArgs);
        
        $stmt = self::$db->prepare($query);
        
        for($i = 0; $i < $numArgs; $i++) {
            $stmt->bindParam(":" . strval($i), $aArgs[$i]);
        }
        
        if($stmt->execute()) {
            return $stmt->fetchAll();
        } else {
            return false;
        }
    }
    
    public static function SafeExec($query, $aArgs = array()) {
        $numArgs = count($aArgs);
        
        $stmt = self::$db->prepare($query);
        
        for($i = 0; $i < $numArgs; $i++) {
            $stmt->bindParam(":" . strval($i), $aArgs[$i]);
        }
        
        if($stmt->execute()) {
            return $stmt->rowCount();
        } else {
            return false;
        }
    }
    
    public function AddToDB($aArgs) { //1st arg in array must be table name, then column name, data, column name, data, etc.
        $numArgs = count($aArgs);
        
        if($numArgs % 2 && $numArgs > 2) { //table plus 2 per column is good (1)
            $pairs = ($numArgs-1)/2;
            
            $query = "INSERT INTO " . $aArgs[0] . " (";
            
            //store 2nd, 4th... params into array for titles
            $colTitles = array_pad(array(), $pairs, 0);
            for($i = 0; $i < $pairs; $i++) {
                $colTitles[$i] = $aArgs[$i*2+1];
            }
            
            //store 3rd, 5th... params into array for data
            $colData = array_pad(array(), $pairs, 0);
            for($i = 0; $i < $pairs; $i++) {
                $colData[$i] = $aArgs[$i*2+2];
            }
            
            //appends all titles to insert query
            $i = $pairs-1;
            foreach($colTitles as $title) {
                if($i) {
                    $query .= "$title, ";
                    $i--;
                } else {
                    $query .= "$title";
                }
            }
            
            $query .= ") VALUES (";
            
            for($i = 0; $i < $pairs; $i++) {
                if($i) {
                    $query .= ", :" . strval($i);
                } else {
                    $query .= ":" . strval($i);
                }
            }
            
            $query .= ")";
            
            return self::SafeExec($query,$colData);
        }
        
        return false; //arg count was incorrect
    }
    public function RemoveFromDBByID($table, $id, $colName = 'ID') {
        return self::SafeExec("DELETE FROM $table WHERE `$colName` = :0",array($id));
    }
    
    public function UpdateDB($table, $id, $colTitle, $colData) {
        $bindings = array($colData, $id);
        return self::SafeExec("UPDATE $table SET $colTitle = :0 WHERE `ID` = :1",$bindings);
    }
    
    public function UpdateMultipleColumnsDB($aArgs) { //1st arg in array must be table name, id, then column name, data, column name, data, etc.
        $numArgs = count($aArgs);
        
        if(!($numArgs % 2) && $numArgs >= 4) { //table, id, plus 2 per column is good (0)
            $pairs = ($numArgs-2)/2;
            
            $query = "UPDATE $aArgs[0] SET ";
            
            //store 2nd, 4th... params into array for titles
            $colTitles = array_pad(array(), $pairs, 0);
            for($i = 0; $i < $pairs; $i++) {
                $colTitles[$i] = $aArgs[$i*2+2];
            }
            
            //store 3rd, 5th... params into array for data
            $colData = array_pad(array(), $pairs, 0);
            for($i = 0; $i < $pairs; $i++) {
                $colData[$i] = $aArgs[$i*2+3];
            }

            /*//appends all titles and data to update query
            for($i = $pairs-1; $i >= 0; $i--) {
                if($i) {
                    $query .= "$colTitles[$i] = '$colData[$i]', ";
                } else {
                    $query .= "$colTitles[$i] = '$colData[$i]'";
                }
            }*/
            
            //appends all titles and data to update query
            for($i = 0; $i < $pairs; $i++) {
                if($i) {
                    $query .= ", $colTitles[$i] = :" . strval($i);
                } else {
                    $query .= "$colTitles[$i] = :" . strval($i);
                }
            }
            
            array_push($colData,$aArgs[1]); //append last value, for ID
            $query .=  " WHERE `ID` = :" . strval($pairs);
        }
        
        return self::SafeExec($query,$colData);
    }
    
    public function GetByID($table, $id, $orderby = '', $idColName = 'ID') {
        $query = "SELECT * FROM " . $table . " WHERE `$idColName` = '" . $id . "'";
        if($orderby != '') { $query .= " ORDER BY " . $orderby; }
        return self::$db->query($query)->fetch();
    }
    public function GetTableByQuery($query) {
      return self::$db->query($query);
    }
    public function GetTable($table, $orderby = '', $desc = false) {
        $query = 'SELECT * FROM ' . $table;
        if($orderby != '') { $query .= " ORDER BY " . $orderby; }
        if($desc) { $query .= " DESC"; }
        
        return self::$db->query($query);
    }
    public function GetTableWhere($table, $whereclause, $orderby = '', $desc = false) {
        $query = 'SELECT * FROM ' . $table . ' WHERE ' . $whereclause;
        if($orderby != '') { $query .= " ORDER BY " . $orderby; }
        if($desc) { $query .= " DESC"; }
        
        return self::$db->query($query);
    }
    public function GetTableWithGrouping($table, $groupby, $orderby = '', $whereClause = '') {
        $query = 'SELECT * FROM ' . $table;
        if($whereClause != '') { $query .= ' WHERE ' . $whereClause; }
        $query .= ' GROUP BY ' . $groupby;
        if($orderby != '') { $query .= ' ORDER BY ' . $orderby; }

        return self::$db->query($query);
    }
    public function GetSingleColumn($table, $colname, $orderby = '') {
        $query = 'SELECT ' . $colname . ' FROM ' . $table;
        if($orderby != '') { $query .= " ORDER BY " . $orderby; }

        return self::$db->query($query);
    }
    public function GetSingleColumnWhere($table, $colname, $whereclause, $orderby = '') {
        $query = 'SELECT ' . $colname . ' FROM ' . $table . ' WHERE ' . $whereclause;
        if($orderby != '') { $query .= " ORDER BY " . $orderby; }
        
        return self::$db->query($query);
    }
}