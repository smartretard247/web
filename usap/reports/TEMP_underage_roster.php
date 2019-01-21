<?
include("../lib-common.php");

include("../classes/validate.class.php");
include("../classes/roster.class.php");

$val = new validate;


$date = strtoupper(date("dMY"));

$header =  "<strong>Incorrect Date of Birth Roster:  --- $date </strong>";

$comp = implode(",",$_CONF['component']);
$comp_abbr = "'" . implode("','",$_CONF['component_abbrev']) . "'";

echo com_siteheader("Incorrect Date of Birth Roster:  --- $date ");

if(!isset($_REQUEST["export2"]))
{ echo "Export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . @$_SERVER["QUERY_STRING"] . "&export2=excel'>Excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . @$_SERVER["QUERY_STRING"] . "&export2=word'>Word</a>\n"; }

$query = "select m.id, m.Last_Name, m.First_Name, m.Middle_Initial as Mi, concat(m.rank,m.promotable) as Rank, 
    right(m.ssn,4) as SSN, upper(date_format(m.dob,'%d%b%y')) as DOB, m.Gender, m.platoon as PLT, m.MOS, m.Pers_Type, elt(find_in_set(m.component,'$comp'),$comp_abbr) as Comp, 
    concat(c.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as Unit from main m, battalion b, company c, 
    user_permissions up where m.pcs = 0 and m.battalion = b.battalion_id and m.company = c.company_id and 
    m.battalion = up.battalion_id and m.company = up.company_id and up.user_id = {$_SESSION['user_id']} and up.permission_id = 2
    and year(current_date) - year(m.dob) - (if(dayofyear(m.dob)>dayofyear(current_date),1,0)) < {$_CONF['min_age']} and m.pers_type != 'Civilian'
    order by m.last_name, m.first_name, m.middle_initial, m.ssn";

$roster = new roster($query);
$roster->setheader($header);
$roster->link_page("data_sheet.php");
$roster->link_column(0);
$roster->sethidecolumn(0);
$roster->setReportName('tmpunderageroster');
$roster->allowUserOrderBy(TRUE);
echo $roster->drawroster();

echo com_sitefooter();

?>