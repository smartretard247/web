<?
session_start();

include("config.php");
include("lib-database.php");

if(isset($_SESSION['user_id']))
{ $result = mysql_query("update users set last_access = 0, ip = '0.0.0.0' where user_id = {$_SESSION['user_id']}") or die(mysql_error()); }

@session_destroy();
if(isset($_SESSION))
{ unset($_SESSION); }

header("location: " . $_CONF["html"] . "/login.php");

exit();
?>
