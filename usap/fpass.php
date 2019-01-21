<?

include("config.php");
include("lib-database.php");
include("smarty/smarty.class.php");

session_start();

$smarty = new Smarty;
$smarty->template_dir = $_CONF['path'] . 'smarty/templates';
$smarty->compile_dir = $_CONF['path'] . 'smarty/templates_c';
$show = array();

$query = "DELETE FROM fpass WHERE code_time < NOW() - INTERVAL 30 MINUTES";
$result = mysql_query($query);

//ensure password meets rule requirements
function check_password($pass)
{
    global $_CONF;

    //only check password if option is enabled.
    if($_CONF["check_password"] == "on")
    {
        //must have uppercase letter
        if(!ereg("[a-z]",$pass)) { return 0; }
        //must have lowercase letter
        if(!ereg("[a-z]",$pass)) { return 0; }
        //must have number
        if(!ereg("[0-9]",$pass)) { return 0; }
        //must be 8 characters long
        if(strlen($pass) < 8) { return 0; }
    }
    return 1;
}

if(isset($_POST['username']))
{   
    $show['username_form'] = TRUE;
    
    $query = "SELECT m.email, m.id FROM main m, users u WHERE m.id = u.user_id AND u.login = '{$_POST['username']}'";
    $result = mysql_query($query) or die(mysql_error());
    if($row = mysql_fetch_row($result))
    {
        if(empty($row[0]))
        { $msg = "Username <strong>" . htmlentities($_POST['username']) . "</strong> does not have an AKO Email address to send the message to."; }
        else
        {
            $code = md5(uniqid(rand(),1));
              
            $query = "INSERT INTO fpass (user_id, code) VALUES ({$row[1]},'$code')";
            $result = mysql_query($query);
            
            if($result)
            {
                $mail = "Your request to reset your USAP password has been received.\n\n"
                       ."Click on the following link to reset your password: \n"
                       ."{$_CONF['html']}/fpass.php?code=$code\n\n"
                       ."If you do not click on the link, your password will remain unchanged.\n"
                       ."This request will expire in 30 minutes.";            

                $from = "15 RSB Help Desk <15rsbhelpdesk@gordon.army.mil>";
                $to = $row[0];
                $subject = "USAP Password Request";

                if(mail($to,$subject,$mail,"From: $from\r\nReply-To:$from"))
                { 
                    $msg = "Mail sent successfully. Please click on the link in the email to reset your password."; 
                    $show['username_form'] = FALSE;
                }
                else
                { $msg = "Unable to send mail"; }
            }
            else
            { $msg = "Unable to insert code into database: " . mysql_error(); }
        }
    }
    else
    { $msg = "Username <strong>" . htmlentities($_POST['username']) . "</strong> does not exist."; }
}
elseif(isset($_GET['code']))
{
    $query = "SELECT RIGHT(m.ssn,4) as ssn, m.last_name, m.first_name, m.rank FROM main m, fpass f WHERE m.id = f.user_id and f.code = '{$_GET['code']}'";
    $result = mysql_query($query);
    
    if($row = mysql_fetch_assoc($result))
    {
        $name = $row['rank'] . ' ' . $row['last_name'] . ', ' . $row['first_name'] . '(' . $row['ssn'] . ')';
        $smarty->assign('name',$name);
        $smarty->assign('hcode',$_GET['code']);
        $show['change_pw_form'] = TRUE;
    }
    else
    { 
        $msg = "Invalid Code";
        if($e = mysql_error())
        { $msg .= "<br>Error: $e"; }
        $show['username_form'] = TRUE;
    }
}
elseif(isset($_POST['password1']))
{
    $smarty->assign("hcode",$_POST['hcode']);
    
    if(strcmp($_POST['password1'], $_POST['password2']) == 0)
    {
        if(check_password($_POST['password1']))
        {
            $query = "SELECT user_id FROM fpass WHERE code = '{$_POST['hcode']}'";
            $result = mysql_query($query);
            
            if($row = mysql_fetch_row($result))
            {
                $query = "UPDATE users SET password = PASSWORD('{$_POST['password1']}') WHERE user_id = {$row[0]}";
                $result = mysql_query($query);
                
                if($result)
                { 
                    $query = "DELETE FROM fpass WHERE code = '{$_POST['hcode']}'";
                    $result = mysql_query($query);
                    
                    $msg = "Password successfully changed. Please use the link below to return to the Login / Main Page."; 
                }
                else
                { $msg = "Unable to update password. Error: " . mysql_error(); }
            }
            else
            {
                $msg = "Invalid Code";
                if($e = mysql_error())
                { $msg .= "<br>Error: $e"; }
                $show['username_form'] = TRUE; 
            }
        }
        else
        {
            $msg = "Password does not conform to rules. Please enter a new password";
            $show['change_pw_form'] = TRUE;
        }
    }
    else
    { 
        $msg = "Passwords do not match. Please try again";
        $show['change_pw_form'] = TRUE;
    }
}
else
{ $show['username_form'] = TRUE; }

if(isset($msg))
{ $smarty->assign('msg',$msg); }

$smarty->assign('show',$show); 

if(isset($_SESSION['user_id']))
{ 
    $url = $_CONF['html'] . '/main.php'; 
    $text = "Main Page";
}
else
{ 
    $url = $_CONF['html'] . '/login.php'; 
    $text = "Login Page";
}

$smarty->assign('url',$url);
$smarty->assign('text',$text);

echo $smarty->fetch("fpass.tpl");

?>
