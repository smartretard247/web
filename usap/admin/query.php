<?
include("../lib-common.php");
include("lib-admin.php");
include("../classes/roster.class.php");

//ensure user has permission to view
//this page. if not, show error and exit.
if(!check_permission(26))
{
    echo com_siteheader("unauthorized access");
    echo "unauthorized access: you do not have permission to access this area";
    echo com_sitefooter();
    exit();
}

//display header
echo com_siteheader("custom query");
//display menu
echo admin_menu();

if(isset($_POST['query']))
{
    if(strlen($_POST['query']) == 0 || strlen($_POST['name']) == 0)
    { echo "please provide a name and query."; }
    else
    {
        $code = md5(uniqid(rand(),1));

        $result = mysql_query("insert into queries (code,name,query) values ('$code','" . $_POST['name'] . "','" . $_POST['query'] . "')");

        if($e = mysql_error())
        { echo "error with query: " . $e . "<br>if duplicate key, please submit query again."; }
        else
        {
            echo "insert successful!";
            unset($_POST);
        }
    }
}

if(!isset($_POST['name']))
{ $_POST['name'] = ""; }
if(!isset($_POST['query']))
{ $_POST['query'] = ""; }

echo "<form method='post' action='" . $_SERVER['SCRIPT_NAME'] . "'>\n"
    ."<br><table border='1' align='center' width='50%' cellpadding='1' cellspacing='1'>\n"
    ."<tr class='heading'><td>New Custom Query</td></tr>\n"
    ."<tr><td>name: <input type='text' name='name' size='30' value='" . $_POST['name'] . "'></td></tr>\n"
    ."<tr><td>query: <textarea cols='50' rows='5' name='query'>" . $_POST['query'] . "</textarea></td></tr>\n"
    ."<tr><td align='center'><input type='submit' class='button' name='submit' value='enter'></td></tr></table></form>\n";

$roster = new roster("select code,name,query from queries");
$roster->setheader("available custom queries");
$roster->link_page("query.php");
$roster->link_column(0);
echo $roster->drawroster();

echo com_sitefooter();

?>
