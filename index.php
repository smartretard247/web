<?php
    $lifetime = 60 * 60 * 24; //24 hours
    ini_set('session.use_only_cookies', true);
    ini_set('session.gc_probability', 1);
    ini_set('session.gc_divisor', 100);
    session_set_cookie_params($lifetime, '/'); //all paths, must be called before session_start()
    session_save_path(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/sessions'); session_start();
    date_default_timezone_set('Japan');
    //date_default_timezone_set('America/New_York');
    
    #$_SESSION['rootDir'] = "/";
    $_SESSION['rootDir'] = "";
    include_once $_SESSION['rootDir'] . 'core/include.php';

    if(empty($_SESSION['valid_user'])) { $_SESSION['valid_user'] = false; }
    if(empty($_SESSION['admin_enabled'])) { $_SESSION['admin_enabled'] = false; }
    if(empty($_SESSION['debug'])) { $_SESSION['debug'] = false; }
    if(empty($_SESSION['error_message'])) { $_SESSION['error_message'] = ''; }
    if(empty($_SESSION['edit_mode'])) { $_SESSION['edit_mode'] = false; }
    
    if($_SESSION['valid_user']) {
      include_once $_SESSION['rootDir'] . 'core/thumb.php';
    }
    
    $action = filter_input(INPUT_POST, 'action');
    if(!$action) { $action = filter_input(INPUT_GET, 'action'); }
    if(!$action) { 
        switch ($_SESSION['valid_user']) {
            case 'smartretard247': $action = 'jesse_index';
                break;
            case 'Bliss': $action = 'anne_index';
                break;
            default: $action = 'view_home';
                break;
        }
    }
    $select_all = filter_input(INPUT_POST, 'select_all');
    if(!$select_all) { $select_all = filter_input(INPUT_GET, 'select_all'); }

    $gotoPage = 'view/rightbar.php';

    //perform necessary action, sent by forms
    switch($action) {
        case 'newseason': if($_SESSION['valid_user']) {
                $idOfShow = filter_input(INPUT_POST, 'showid');
                $endsOn = filter_input(INPUT_POST, 'endson');
                $startsOn = filter_input(INPUT_POST, 'startson');
                $quality = filter_input(INPUT_POST, 'quality');
                $episode = filter_input(INPUT_POST, 'nextEpisode');
                $airs = filter_input(INPUT_POST, 'airsOn');
                $dbServer->SafeExec("UPDATE shows SET SeasonStart = :0, SeasonEnd = :1, Quality = :2, CurrentEpisode = :3, Airs = :4 WHERE ID = :5", array($startsOn, $endsOn, $quality, $episode, $airs, $idOfShow));
                $gotoPage = 'index_jesse.php';
            }
            break;
        case 'eventcomplete': if($_SESSION['valid_user']) {
                $idOfEvent = filter_input(INPUT_GET, 'event');
                $dbServer->SafeExec("UPDATE calendar SET Complete = 1 WHERE ID = :0", array($idOfEvent));
                $gotoPage = 'index_jesse.php';
            }
            break;
        case 'eventsnooze': if($_SESSION['valid_user']) {
                $idOfEvent = filter_input(INPUT_GET, 'event');
                $row = $db->Query("SELECT TheDate FROM calendar WHERE ID = '$idOfEvent'")->fetch();
                if($row) {
                    $event = $row['TheDate'];
                    $day = (int)substr($event, 8, 2) + 1;
                    if($day < 10) { $day = "0$day"; }
                    $newDay = substr($event, 0, 8);
                    $newDay .= $day;
                    $dbServer->SafeExec("UPDATE calendar SET TheDate = :0 WHERE ID = :1", array($newDay,$idOfEvent));
                }
                $gotoPage = 'index_jesse.php';
            }
            break;
        case 'delete': if($_SESSION['valid_user']) {
                $idOfEvent = filter_input(INPUT_GET, 'event');
                $dbServer->SafeExec("DELETE FROM calendar WHERE ID = :0", array($idOfEvent));
                $gotoPage = 'index_jesse.php';
            }
            break;
        case 'view_home': if($_SESSION['valid_user']) {
                $gotoPage = 'index_jesse.php';
            }
            break;
        case 'create_account':
            $gotoPage = 'view/create_account.php';
            break;
        case 'view_accounts': $gotoPage = 'view/list.php';
            break;
        case 'anne_index': if($_SESSION['valid_user']) {
                $gotoPage = 'index_anne.php';
            }
            break;
        case 'jesse_index': if($_SESSION['valid_user']) {
                $idOfEvent = filter_input(INPUT_GET, 'event');
                $idOfShow = filter_input(INPUT_GET, 'showid');
                if($idOfEvent) {
                    $gotoPage = 'view/event_edit.php';
                } else if($idOfShow) {
                    $gotoPage = 'view/show_edit.php';
                } else {
                    $gotoPage = 'index_jesse.php';
                }
            }
            break;
        default: $gotoPage = 'view/rightbar.php';
            break;
    } //end of switch statement
                
    include_once 'view/header.php'; ?>
    
    <body>
        <div id="page">
            <div id="main">
                <?php ShowError(); ShowMessage(); include $gotoPage; ?>
            </div><!-- end main -->

            <?php include 'view/footer.php';