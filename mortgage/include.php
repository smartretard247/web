<?php 
    include_once $_SESSION['rootDir'] . '../database.php'; $db = new Database('mortgage');
    include_once $_SESSION['rootDir'] . 'class/mortgage.php'; $data = new Mortgage(1);
    
    $gInflation = 0.01;
    $oneMonth = new DateInterval('P1M');
    $dateFormat = 'd-M-y';
    
    function ShowError($code=0) {
        //display error message
        if($_SESSION['error_message'] != '') {
            echo '<p class="error">' . $_SESSION['error_message'] . ': ' . $code . '</p>';
            $_SESSION['error_message'] = '';
        }
    }
    function ShowMessage() {
        if($_SESSION['message'] != '') {
            echo '<p class="success">' . $_SESSION['message'] . '</p>';
            $_SESSION['message'] = '';
        }
    }
    function ShowAlert() {
        if($_SESSION['alert'] != '') {
            echo '<script type="text/javascript">alert("' . $_SESSION['alert'] . '")</script>';
            $_SESSION['alert'] = '';
        }
    }
    
    function NoDataRow($array, $colspan, $text = 'No data exists in the table.') {
        if($array == 0) {
            echo '<tr><td colspan="' . $colspan . '"><b>' . $text . '</b></td></tr>';
        }
    }
    
    function UpdateDBAndOutputText($table, $id, $colTitle, $colData, $text) {
        global $db;
        $numRowsAffected = $db->UpdateDB($table, $id, $colTitle, $colData);
        if($numRowsAffected) { echo '<p class=success>'; } else { $numRowsAffected = 0; echo '<p class=error>'; }
        echo $numRowsAffected . ' ' . $text . '</p>';
    }
    
    function RemoveFromDBByIDAndOutputText($table, $id, $text) {
        global $db;
        $numRowsAffected = $db->RemoveFromDBByID($table, $id);
        if($numRowsAffected) { echo '<p class=success>'; } else { $numRowsAffected = 0; echo '<p class=error>'; }
        echo $numRowsAffected . ' ' . $text . '</p>';
    }
