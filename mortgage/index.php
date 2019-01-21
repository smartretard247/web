<?php #$root = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT'); //get root folder for relative paths
    $lifetime = 60 * 60 * 3; //3 hours
    ini_set('session.use_only_cookies', true);
    ini_set('session.gc_probability', 1);
    ini_set('session.gc_divisor', 100);
    session_set_cookie_params($lifetime, '/'); //all paths, must be called before session_start()
    session_save_path(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/sessions'); session_start();
    date_default_timezone_set('America/New_York');

    #$_SESSION['rootDir'] = "/";
    $_SESSION['rootDir'] = "";
    include $_SESSION['rootDir'] . 'Mortgage/include.php';
    
    if(empty($_SESSION['valid_user'])) { $_SESSION['valid_user'] = false; }
    if(empty($_SESSION['admin_enabled'])) { $_SESSION['admin_enabled'] = false; }
    if(empty($_SESSION['debug'])) { $_SESSION['debug'] = false; }
    if(empty($_SESSION['error_message'])) { $_SESSION['error_message'] = ''; }
    if(empty($_SESSION['message'])) { $_SESSION['message'] = ''; }
    if(empty($_SESSION['alert'])) { $_SESSION['alert'] = ''; }
    if(empty($_SESSION['edit_mode'])) { $_SESSION['edit_mode'] = false; }

    include_once 'header.php';

    $action = filter_input(INPUT_POST, 'action');
    if(!$action) { $action = filter_input(INPUT_GET, 'action'); }
    if(!$action) { $action = 'view_home'; }
    
    ShowError();
    ShowMessage();

    //perform necessary action, sent by forms
    switch($action) {
        case 'set_mortgage': 
            $new_mortgage = filter_input(INPUT_POST, 'new_mortgage');
            if($new_mortgage != '') {
                $errcode = $data->UpdateCurrentMortgage($new_mortgage);
                if($errcode>0) {
                    $_SESSION['alert'] = "Updated current mortgage.";
                    $data->SetFromDB($data->GetID());
                } else {
                    $_SESSION['error_message'] = "Could not update mortgage"; ShowError($errcode);
                }
            }

            include 'home.php';
            break;
        case 'set_escrow': 
            $new_escrow = filter_input(INPUT_POST, 'new_escrow');
            if($new_escrow != '') {
                $errcode = $data->UpdateCurrentEscrow($new_escrow);
                if($errcode>0) {
                    $_SESSION['alert'] = "Updated current escrow.";
                    $data->SetFromDB($data->GetID());
                } else {
                    $_SESSION['error_message'] = "Could not update escrow"; ShowError($errcode);
                }
            }

            include 'home.php';
            break;
        case 'set_allotment': 
            $new_allotment = filter_input(INPUT_POST, 'new_allotment');
            if($new_allotment != '') {
                $values = array($new_allotment,$data->GetID());
                if($db->SafeExec("UPDATE data SET Allotment = :0 WHERE `ID` = :1",$values)) {
                    $_SESSION['alert'] = "Updated allotment.";
                    $data->SetFromDB($data->GetID());
                    
                    } else {
                        $_SESSION['error_message'] = "Could not update allotment."; ShowError();
                    }
            }

            include 'home.php';
            break;
            
        default: //do default action, load home page
            ShowAlert();
            include 'home.php';
            break;
    } //end of switch statement
    
    ShowAlert();

    include 'footer.php';