<?
#################################################
#
# this file allows you to view apft scores for
# a given soldier identified by an $id passed
# to the page.
#
#################################################

include("lib-common.php");
include($_CONF["path"] . "classes/validate.class.php");

//see if edit was chosen in com_choosesoldier
//and redirect to edit page with chosen id
if(isset($_GET["com_cs_action"]) && $_GET["com_cs_action"] == "edit")
{
    header("location: " . $_CONF["html"] . "/edit_apft.php?id=" . $_GET["id"]);
    exit();
}

//create new validation object
$val = new validate;

//defaults
$allow_full_ssn = 0;

//display header
$display = com_siteheader();
$display .= com_choosesoldier("apft");
echo $display;

if(isset($_REQUEST["id"]))
{
    //ensure current user has permission
    //to view data for this soldier
    if(!$val->id($_REQUEST["id"],15) && $_REQUEST['id'] != $_SESSION['user_id'])
    {
        echo "you do not have the correct permissions";
        echo com_sitefooter();
        exit();
    }

    //see if current user has permission to
    //view full information on this soldier
    if($val->id($_REQUEST["id"],12))
    { $allow_full_ssn = 1; }

    $apft_query =   "select "
                ."a.type, a.raw_pu, a.pu_score, a.raw_su, a.su_score, if(a.raw_run=9999,'DNF',a.raw_run) as raw_run, a.run_score, "
                ."a.alt_event, a.total_score, a.age, date_format(a.date,'%d%b%y') as date, "
                ."a.rank, a.height, a.weight, a.pass_fail, m.last_name, m.first_name, m.middle_initial, "
                ."if(" . $allow_full_ssn . ",m.ssn,right(m.ssn,4)) as ssn, b.battalion, c.company, m.gender, a.pass_fail, "
                ."pu_exempt, su_exempt "
            ."from "
                ."apft a, main m, battalion b, company c "
            ."where "
                ."a.id = m.id and m.battalion = b.battalion_id and m.company = c.company_id "
                ."and m.id = " . $_REQUEST["id"]
            ." order by "
                ." a.date desc";

    $apft_result = mysql_query($apft_query) or die("apft select error [$apft_query]: " . mysql_error());

    if($apft_row = mysql_fetch_array($apft_result))
    { include($_CONF["path"] . "templates/view_apft.inc.php"); }
    else
    { echo "no apft for this soldier in database."; }
}

echo com_sitefooter();
?>