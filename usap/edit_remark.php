<?
//configuration file
require("lib-common.php");
//validation routines
require($_CONF["path"] . "classes/validate.class.php");

//see if edit was chosen in com_choosesoldier
//and redirect to edit page with chosen id
if(isset($_GET["com_cs_action"]) && $_GET["com_cs_action"] == "view")
{
    header("location: " . $_CONF["html"] . "/remarks.php?id=" . $_GET["id"]);
    exit();
}

//validation object
$val = new validate;

//display header
$display = com_siteheader("edit remark");
//display choose soldier form
$display .= com_choosesoldier("remarks");
echo $display;

//ensure user has edit own remark or edit unit remark permission
if(isset($_REQUEST["id"]) && (!$val->id($_REQUEST["id"],17) && !$val->id($_REQUEST["id"],23)))
{
    echo "<center>you do not have the correct permissions</center>";
    echo com_sitefooter();
    exit();
}

if(isset($_POST['delete']))
{
    if($input['remarks_id'] = $val->fk_constraint($_POST['remark_id'],'remarks','remarks_id'))
    {
        if($val->id($_REQUEST['id'],24))
        {
            $query = "delete from remarks where remarks_id = " . $input['remarks_id'];
            $result = mysql_query($query) or die("delete remarks error: " . mysql_error());
        }
        elseif($val->id($_REQUEST['id'],18))
        {
            $query = "delete from remarks where remarks_id = " . $input['remarks_id'] . " and "
                    ."entered_by = " . $_SESSION['user_id'];
            $result = mysql_query($query) or die("delete remarks error: " . mysql_error());
        }
        if(mysql_affected_rows())
        { echo "remark deleted"; }
        else
        { echo "remark not deleted: $query"; }
    }
    else
    { echo "invalid remark selection"; }
}

//see if form was submitted
if(isset($_POST["remark_submit"]))
{
    //validate form values
    $val->fk_constraint($_POST["remark_id"],"remarks","remarks_id");
    $val->fk_constraint($_POST["subject"],"remarks_subjects","remarks_subjects_id");
    $val->check("string",$_POST["remark"],"remark");
    $insert["new_date"] = $val->check("date",$_POST["new_date"],"new date",1);

    if(isset($_POST['restricted']))
    { $insert['restricted'] = 1; }
    else
    { $insert['restricted'] = 0; }

    //check for errors, if there are some, display them
    if($val->iserrors())
    { echo $val->geterrors(); }
    else
    {
        //if new date was empty, create string to keep date at current value, otherwise
        //create string to update it to value supplied by user
        if($insert["new_date"] == "") { $time = "time"; } else { $time = "'" . $insert["new_date"] . "'"; }
        $update_query = "update remarks set subject = " . $_POST["subject"] . ",remark = '" . $_POST["remark"] . 
                        "', time = " . $time . ", restricted = {$insert['restricted']} where remarks_id = '" . $_POST["remark_id"] . "'";
                        
        $update_result = mysql_query($update_query) or die("update error [$update_query]: " . mysql_error());

        echo "<center><font size='5'>update successful</font></center>";

        //remove form values
        unset($_POST);
    }
}

if(isset($_REQUEST["id"]))
{
    //default values
    $options = "";
    $javascript = "";
    if(!isset($_POST["subject"])) { $_POST["subject"] = " none"; }
    if(!isset($_POST["remark"])) { $_POST["remark"] = ""; }
    if(!isset($_POST["new_date"])) { $_POST["new_date"] = ""; }

    if($val->id($_REQUEST['id'],17) && !$val->id($_REQUEST['id'],23))
    { $input['where'] = " and r.entered_by = " . $_SESSION['user_id'] . " "; }
    else
    { $input['where'] = ""; }

    if(!$val->id($_REQUEST['id'],32))
    { $input['where'] .= " and r.restricted = 0 "; }

    $remark_query = "select m.last_name, m.first_name, m.middle_initial, r.remarks_id, r.subject, "
                ."r.remark, upper(date_format(r.time,'%d%b%y')) as time, r.restricted from main m, remarks r "
                ."where r.id = m.id and m.id = '" . $_REQUEST["id"] . "' " . $input['where']
                ." order by r.time desc";

    $remark_result = mysql_query($remark_query) or die("remark select error [" . $remark_query . "]: " . mysql_error());
    if($remark_row = mysql_fetch_array($remark_result))
    {
        $last_name = $remark_row["last_name"];
        $first_name = $remark_row["first_name"];
        $middle_initial = $remark_row["middle_initial"];

        do
        {
            $options .= "<option value='" . $remark_row["remarks_id"] . "'";
            if(isset($_POST["remarks_id"]) && $_POST["remarks_id"] == $remark_row["remarks_id"])
            { $options .= " selected "; }
            $options .= ">" . $remark_row["time"] . "</option>\n";

            $db_remark = $remark_row["remark"];
            $db_remark = addslashes($db_remark);
            $db_remark = str_replace("\n","\\n",$db_remark);
            $db_remark = str_replace("\r","\\r",$db_remark);

            $javascript .="db_date[" . $remark_row["remarks_id"] . "] = '" . $remark_row["time"] . "';\n";
            $javascript .="db_subject[" . $remark_row["remarks_id"] . "] = '" . $remark_row["subject"] . "';\n";
            $javascript .="db_remark[" . $remark_row["remarks_id"] . "] = '" . $db_remark . "';\n";
            $javascript .="db_restricted[" . $remark_row['remarks_id'] . "] = " . (($remark_row['restricted']==1)?'true':'false') . ";\n";

        }while($remark_row = mysql_fetch_array($remark_result));

        include($_CONF["path"] . "templates/edit_remarks.inc.php");
    }
    else
    { echo "<center>no remarks that you have permission to edit for this soldier</center>\n"; }
}

echo com_sitefooter();
?>
