<?

//configuration
include("lib-common.php");
//validation routines
include($_CONF["path"] . "classes/validate.class.php");

if(!check_permission(16))
{
    echo com_siteheader();
    echo "you do not have the correct permissions";
    echo com_sitefooter();
    exit();
}

//defaults
$val = new validate;
$sort_last = "m.last_name, m.first_name, m.ssn";
$sort_ssn = "m.ssn, m.last_name, m.first_name";

if(isset($_POST["sort_order"]))
{
    setcookie("remark_nsort",$_POST["sort_order"],time()+408600);
    $_COOKIE["remark_nsort"] = $_POST["sort_order"];
}
else
{
    if(!isset($_COOKIE["remark_nsort"]))
    { $_COOKIE["remark_nsort"] = $sort_last; }
}

if(isset($_POST['data_sheet']))
{
    $id = (int)$_POST['id'];
    header("Location: {$_CONF['html']}/data_sheet.php?id=$id");
    exit();
}

//display site header
echo com_siteheader("add remark");

if(isset($_POST["remark_submit"]))
{
    $insert["id"] = $val->id($_POST["id"],16);
    $insert["remark"] = $val->check("string",$_POST["remark"],"remark");
    $insert["subject"] = $val->fk_constraint($_POST["subject"],"remarks_subjects","remarks_subjects_id");
    
    if(isset($_POST['restricted']))
    { $insert['restricted'] = 1; }
    else
    { $insert['restricted'] = 0; }

    if($val->iserrors())
    { echo $val->geterrors(); }
    else
    {
        $insert_query = "insert into remarks (id, subject, remark,entered_by, restricted)
                         values ({$insert['id']},{$insert['subject']},'{$insert['remark']}',
                         {$_SESSION['user_id']},{$insert['restricted']})";

        $insert_result = mysql_query($insert_query) or die("insert error [$insert_query]: " . mysql_error());

        unset($_POST);

        echo "<br><center><font size='5'>insert successful</font></center>\n";
    }
}

//assign default values to strings if they
//are not already set.
if(!isset($_POST["subject"])) { $_POST["subject"] = ""; }
if(!isset($_POST["remark"])) { $_POST["remark"] = ""; }

include($_CONF["path"] . "templates/add_remark.inc.php");

echo com_sitefooter();
?>
