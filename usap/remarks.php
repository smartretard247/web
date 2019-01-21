<?
require("lib-common.php");
require($_CONF["path"] . "classes/validate.class.php");

$view_restricted = FALSE;

//see if edit was chosen in com_choosesoldier
//and redirect to edit page with chosen id
if(isset($_GET["com_cs_action"]) && $_GET["com_cs_action"] == "edit")
{
    header("location: " . $_CONF["html"] . "/edit_remark.php?id=" . $_GET["id"]);
    exit();
}

//if user chose to sort remarks
//set cookie to remember sorting preference
if(isset($_GET["remark_vsort"]))
{
    setcookie("remark_vsort",$_GET["remark_vsort"],time()+804600);
    $_COOKIE["remark_vsort"] = $_GET["remark_vsort"];
}
//otherwise set a default of time
else
{
    if(!isset($_COOKIE["remark_vsort"]))
    { $_COOKIE["remark_vsort"] = "time"; }
}
if($_COOKIE['remark_vsort'] == "subject")
{ $remark_vsort = "rs.subject asc"; }
else
{ $remark_vsort = "r.time desc"; }

//set default for subject dropdown if one
//was not posted to page
if(!isset($_REQUEST["subject"]))
{ $_REQUEST["subject"] = "none"; }

//default validation object
$val = new validate;
$report['where'] = '';

//show site header
$display = com_siteheader("remarks");
$display .= com_choosesoldier("remarks");
echo $display;

if(isset($_REQUEST["id"]))
{
    if($view_restricted = $val->id($_REQUEST['id'],32) || $val->id($_REQUEST["id"],19) || $_REQUEST['id'] == $_SESSION['user_id'])
    { 
        $result = mysql_query("SELECT CONCAT(m.rank,m.promotable,' ',m.Last_Name,', ',m.first_name,' ',m.middle_initial) as Name FROM main m where m.id = {$_REQUEST['id']}");
        $name = mysql_result($result,0);
        
        if(!$view_restricted)
        { $report['where'] = " r.restricted = 0 and "; }
        
        $remark_query = "select rs.subject, r.remarks_id, r.remark, r.restricted,
        date_format(r.time,'%d%b%y %T') as time, m2.id as eb_id, concat(left(m2.first_name,1), left(m2.middle_initial,1), left(m2.last_name,1)) as entered_by
        from remarks r, remarks_subjects rs, main m2 where {$report['where']} r.id = {$_REQUEST['id']} and
        r.subject = rs.remarks_subjects_id and r.entered_by = m2.id ";
        
        
        if(isset($_REQUEST["submit_subject_limit"]) && $_REQUEST['subject'] != 12)
        {
            $remark_query .= " and r.subject = " . $_REQUEST["subject"];
        }
        $remark_query .= " order by " . $remark_vsort;
    
        $remark_result = mysql_query($remark_query) or die("select error: " . mysql_error() . " in query: $remark_query");
    
        $remark_row = mysql_fetch_array($remark_result);
         
        $s2_query = "SELECT remark FROM S2 WHERE ID = {$_REQUEST['id']}";
        $s2_result = mysql_query($s2_query) or die(mysql_error());
        $s2_row = mysql_fetch_assoc($s2_result);

        include($_CONF["path"] . "templates/view_remarks.inc.php"); 
    }
    else
    { echo "<center>You do not have the correct permissions.</center>"; }
}

echo com_sitefooter();
?>
