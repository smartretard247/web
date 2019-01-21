<?php $root = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT'); //get root folder for relative paths
    session_save_path($root . '/sessions'); session_start();
    
    include_once $_SESSION['rootDir'] . '../database.php'; $db = new Database('server2go');
    
    if($_SESSION['valid_user']) {
        $idOfEvent = filter_input(INPUT_POST, 'ID');
        $eventToProcess = filter_input(INPUT_POST, 'EVENT');
        $dateOfEvent = filter_input(INPUT_POST, 'DATE');
        $completed = (filter_input(INPUT_POST, 'COMPLETE')) ? 1 : 0;
        $details = filter_input(INPUT_POST, 'DETAILS');
            
        if(!$idOfEvent) {
            if($eventToProcess && $dateOfEvent && ($eventToProcess != "Type event here...")) {
                $db->SafeExec("INSERT INTO calendar (TheDate, Event) VALUES (:0, :1)", array($dateOfEvent, $eventToProcess));
            }
        } else { //we are editing event
            if($eventToProcess && $dateOfEvent) {
                $db->SafeExec("UPDATE calendar SET Event = :0, TheDate = :1, Complete = :2, Details = :3 WHERE ID = :4", array($eventToProcess, $dateOfEvent, $completed, $details, $idOfEvent));
            }
        }
    }

    header("location:../");
    exit();