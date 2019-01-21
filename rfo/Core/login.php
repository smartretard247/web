<?php $root = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT'); //get root folder for relative paths
    session_save_path($root . '/sessions'); session_start();
    
    include_once $_SESSION['rootDir'] . '../../database.php'; $db = new Database('ssa');
    include_once $_SESSION['rootDir'] . 'account.php'; $cAccount = new Account;
    
    $cAccount->SetUsername(filter_input(INPUT_POST, 'Username'));
    $cAccount->SetPassword(filter_input(INPUT_POST, 'ThePassword'));
    
    $username = $cAccount->GetUsername();
    $password = $cAccount->GetPassword();

    if($username != '' && $password != '') {
        $accountType = $cAccount->HasValidCombo();
        if($accountType) {
            $cAccount->SetFromDB($username);
            
            $_SESSION['valid_user'] = $cAccount->GetUsername();
            $_SESSION['FirstName'] = $cAccount->GetFirstName();
            
            switch ($accountType) {
                case '64':
                    $_SESSION['admin_enabled'] = true;
                    $_SESSION['debug'] = true;
                    break;
                case '32':
                    $_SESSION['admin_enabled'] = true;
                    $_SESSION['debug'] = false;
                default:
                    break;
            }
        } else { $_SESSION['error_message'] = '<script type="text/javascript">alert("Invalid username and/or password.  Please try again.")</script>';
        }
    } else { $_SESSION['error_message'] = '<script type="text/javascript">alert("You must enter a username and password to login.  Please try again.")</script>'; }
    
    header("location:../");
    exit();
?>
