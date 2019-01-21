<?
include("../lib-common.php");

include("../classes/validate.class.php");
include("../classes/roster.class.php");

$val = new validate;

if($report['id'] = $val->id($_REQUEST['id'],11))
{
    $result = mysql_query("select concat(m.rank,m.promotable, ' ', m.last_name, ', ',m.first_name, ' ', m.middle_initial) as info from main m where m.id = " . $report['id']) or die("soldier info select error: " . mysql_error());
    $info = mysql_result($result,0);

    $date = strtoupper(date("dMY"));

    $header =  "<strong>status history report</strong><br>$info";

    echo com_siteheader("status history report");

    if(!isset($_REQUEST["export2"]))
    { echo "export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>word</a>\n"; }

    $comp = implode(",",$_CONF['component']);
    $comp_abbr = "'" . implode("','",$_CONF['component_abbrev']) . "'";

    $query = "select upper(date_format(sh.date,'%d%b%y %T')) as date, s.status as daily_status, "
            ."s2.status as inactive_status, sh.status_remark from status_history sh "
            ."left join status s on s.status_id = sh.daily_status_id left join status s2 on "
            ."s2.status_id = sh.inact_status_id "
            ."where sh.id = " . $report['id'] . " order by sh.date desc";

    $roster = new roster($query);
    $roster->setheader($header);
    $roster->setReportName('statushistoryreport');
	$roster->allowUserOrderBy(TRUE);
    echo $roster->drawroster();

}
else
{
    echo com_siteheader("invalid permissions - status history report");
    echo "invalid permissions.";
}

echo com_sitefooter();

?>