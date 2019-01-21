<?
include("lib-common.php");
include($_CONF['path'] . "/classes/roster.class.php");

echo com_siteheader("Custom Query");

if(!eregi("^[0-9a-f]{32}$",$_REQUEST['code']))
{
    echo "Bad Code";
    echo com_sitefooter();
    exit();
}

$result = mysql_query("select query, name from queries where code = '" . $_REQUEST['code'] . "'") or die("code select error: " . mysql_error());
if(mysql_num_rows($result) > 0)
{
    list($query,$name) = mysql_fetch_row($result);

    if(!isset($_REQUEST["export2"]))
    { echo "Export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>Excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>Word</a>\n"; }

    $roster = new roster($query);
    $roster->setheader($name);
    echo $roster->drawroster();
}
else
{ echo "Code does not match database."; }

echo com_sitefooter();
?>