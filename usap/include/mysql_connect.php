<?

$link_id = @mysql_connect($_CONF["db_host"],$_CONF["db_user"],$_CONF["db_password"]) or die("error connecting to database");
$db = @mysql_select_db($_CONF["db_name"]) or die("error selecting database");

?>