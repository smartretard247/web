<?
include("config.php");

//check system
if ($_SERVER['CERT_SERIALNUMBER'] == "") {
header("location: " . $_CONF["html"] . "/login.php?error=7");
// exit();
}


//include database connection
require("lib-database.php");

//check if username and password match. if so
//select out permissions of user logging on
$query1 = "select user_id from users where cacSerialNumber='" . $_SERVER['CERT_SERIALNUMBER'] . "'";
//echo($query1);
$res2= mysql_query($query1);
$usersRows = mysql_fetch_array($res2);

$query = "SELECT Permission_ID, User_ID FROM user_permissions where User_ID=" . $usersRows['user_id'] . " group by Permission_ID";
//echo $usersRows['user_id'];
//echo "query: " . $query . "</p>";

$result = mysql_query($query);


//if error in query, send to error page
if(mysql_error())
{
    echo "error: " . mysql_error();
    exit();
}

//determine how many rows were returned
$num_rows = mysql_num_rows($result);
//echo "rows: " . $num_rows;
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
//echo "var_userid::>" . $usersRows['user_id'];
$_SESSION["user_id"] = $usersRows['user_id'];
$change_password = 0;
do
{
    $_SESSION["permissions"][] = $row[0];
	//echo "perm added: " . $row[0] . "</p>";
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



//send user to main page
//unless redirect was set in session
if(isset($_SESSION["redirect_to"]))
{ header("location: " . $_SESSION["redirect_to"]); }
else
{ header("location: " . $_CONF["html"] . "/main.php?notificationsOff=1"); }
?>
