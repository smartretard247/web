<?php
    include_once $_SESSION['rootDir'] . 'database.php'; $db = new Database('ssa'); $dbServer = new Database('server2go');
    include_once $_SESSION['rootDir'] . 'core/account.php'; $cAccount = new Account;
    include_once $_SESSION['rootDir'] . 'core/shows.php'; $show = new Show;

    function ShowError() {
        //display error message
        if($_SESSION['error_message'] != '') {
            echo '<br/><b id=error>&nbsp;&nbsp;' . $_SESSION['error_message'] . '</b><br/><br/>'; 
            $_SESSION['error_message'] = '';
        }
    }
    function ShowMessage() {
        if($_SESSION['message'] != '') {
            echo '<br/><b id=success>&nbsp;&nbsp;' . $_SESSION['message'] . '</b><br/><br/>';
            $_SESSION['message'] = '';
        }
    }
    
    function StartTable() {
        echo '<table><tr>';
    }

    function EndTable() {
        echo "</tr></table>";
    }

    function TH($header, $span = '1') {
        echo '<th colspan="' . $span . '">' . $header . '</th>';
    }

    function TR($data, $span = '1') {
        echo '<tr colspan="' . $span . '">"' . $data . '</tr>';
    }

    function NoDataRow($array, $colspan) {
        if($array[0] == 0) {
            echo '<tr><td colspan="' . $colspan . '"><b>No data exists in the table.</b></td></tr>';
            }
    }