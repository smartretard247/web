<?
include("../lib-common.php");

include("../classes/validate.class.php");
include("../classes/roster.class.php");

$val = new validate;

if($report['id'] = $val->id($_REQUEST['id'],11))
{
    $query = "select concat(m.rank,m.promotable, ' ', m.last_name, ', ',m.first_name, ' ', m.middle_initial)
              as info from main m where m.id = {$report['id']}";
    $result = mysql_query($query) or die("Soldier info select error: " . mysql_error());

    $info = mysql_result($result,0);

    $date = strtoupper(date("dMY"));

    $header =  "<strong>S2 History Report</strong><br>$info";

    echo com_siteheader("S2 History Report");

    if(!isset($_REQUEST["export2"]))
    { echo "Export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>Excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>Word</a>\n"; }

    echo "<p>NOTICE: This report was created on 14JAN04, so status changes before that date will not be shown.</p>";

    $comp = implode(",",$_CONF['component']);
    $comp_abbr = "'" . implode("','",$_CONF['component_abbrev']) . "'";

    $query = "select upper(date_format(s2h.status_date,'%d%b%y %T')) as Date, s2h.Clearance_Status,
              s2h.Derog_Issue, s2h.Issue_Detail from s2_history s2h
              where s2h.id = {$report['id']} order by s2h.status_date desc";

    $roster = new roster($query);
    $roster->setheader($header);
    $roster->setReportName('s2historyreport');
	$roster->allowUserOrderBy(TRUE);
    echo $roster->drawroster();

}
else
{
    echo com_siteheader("Invalid permissions - S2 History Report");
    echo "Invalid permissions.";
}

echo com_sitefooter();

?>