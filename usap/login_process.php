<?

include("config.php");


//include database connection
require("lib-database.php");

//check if username and password match. if so
//select out permissions of user logging on
//$query = "select u.change_password, u.cacRequired, u.cacSerialNumber, u.user_id, up.permission_id from user_permissions up, users u where u.user_id = up.user_id and u.login = '" . $_POST["login"] . "' group by up.permission_id";

$query = "select u.change_password, u.cacRequired, u.cacSerialNumber, u.user_id, up.permission_id from user_permissions up, users u where u.user_id = up.user_id and u.login = '" . $_POST["login"] . "' and u.password = old_password('" . base64_decode($_POST['password']) . "') group by up.permission_id";
$result = mysql_query($query);

//if error in query, send to error page
if(mysql_error())
{
    echo "error: " . mysql_error();
    exit();
}

//determine how many rows were returned
$num_rows = mysql_num_rows($result);

//if no rows were returned, send to error page
if($num_rows < 1)
{
    @sleep(2);
 
    header("location: " . $_CONF["html"] . "/login.php?error=0");
    exit();
}

//start a session for the current user
session_start();

//clear any old session values
unset($_SESSION["permissions"]);
unset($_SESSION["user_id"]);

//loop through results and load user and permissions
//into session
$row = mysql_fetch_array($result);
$_SESSION["user_id"] = $row['user_id'];
$change_password = $row['change_password'];
$requireCAC = $row['cacRequired'];
$cacSerial = $row['cacSerialNumber'];
do
{
    $_SESSION["permissions"][] = $row['permission_id'];
}while($row = mysql_fetch_array($result));

//load current unit of user into session
$result = mysql_query("select m.rank, m.last_name, m.first_name, m.battalion, m.company, m.pers_type from main m where m.id = " . $_SESSION['user_id']);
$row = mysql_fetch_assoc($result);
$_SESSION['rank'] = $row['rank'];
$_SESSION['last_name'] = $row['last_name'];
$_SESSION['first_name'] = $row['first_name'];
$_SESSION['battalion_id'] = $row['battalion'];
$_SESSION['company_id'] = $row['company'];
$_SESSION['pers_type'] = $row['pers_type'];



//Update Login_Time in User Table
mysql_query("update users set Login_Time = NOW(), ip='{$_SERVER['REMOTE_ADDR']}' where user_id = {$_SESSION['user_id']}");

//if user must change password, sent them to that page
if($change_password == 0)
{
    header("location: " . $_CONF["html"] . "/change_password.php");
    exit();
}

if ($requireCAC == "1") {
 if ($cacSerial == "0") {
	header("location: " . $_CONF["html"] . "/login.php?error=8");
	exit();
} else {
    header("location: " . $_CONF["html"] . "/cac/goCAC.asp");
    exit();
} }


//send user to main page
//unless redirect was set in session
if(isset($_SESSION["redirect_to"]))
{ header("location: " . str_replace("usap/usap/", "usap/", $_SESSION["redirect_to"])); }
else 
{ header("location: " . $_CONF["html"] . "/main.php?notificationsOff=1"); }
?>
