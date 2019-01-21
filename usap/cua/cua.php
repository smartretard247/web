<?
//configuration file
include("../lib-common.php");

//header
echo com_siteheader("15th RSB Computer User Agreement");

$query = "select 1 from cua where id = " . $_SESSION['user_id'] . " and time between now() - interval 1 year and now()";
$result = mysql_query($query);
if(mysql_num_rows($result))
{ include("cua_done.inc.php"); }
else
{
    if(isset($_POST['cua_submit']))
    {
        if(isset($_POST['statement']) && count($_POST['statement']) == 20)
        {
            //see if user already exists in cua table
            $result = mysql_query("select 1 from cua where id = " . $_SESSION['user_id']) or die("select error:" . mysql_error());
            if(mysql_num_rows($result))
            { $query = "update cua set time = now() where id = " . $_SESSION['user_id']; }
            else
            { $query = "insert into cua (id) values (" . $_SESSION['user_id'] . ")"; }

            $result = mysql_query($query) or die("error inserting/updating cua record: " . mysql_error());

            include("cua_done.inc.php");
        }
        else
        {
            echo "<br><br><strong><center><font color='red'>Error: You did not check all of the boxes</font></center></strong>";
            include("cua.inc.php");
        }
    }
    else
    { include("cua.inc.php"); }
}
echo com_sitefooter();
?>