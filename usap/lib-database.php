<?

$link_id = @mysql_connect($_CONF["db_host"],$_CONF["db_user"],$_CONF["db_password"]) or die(header("location: " . $_CONF["html"] . "/error.php?error=0"));

$db = @mysql_select_db($_CONF["db_name"]) or die(header("location: " . $_CONF["html"] . "/error.php?error=1"));

?>