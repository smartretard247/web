<?
include("../lib-common.php");

include("../classes/validate.class.php");
include("../classes/roster.class.php");

$val = new validate;

//if none is chosen, display message
if($_GET['class_id'] == "none")
{
    echo com_siteheader("class roster");
    echo "please choose a class";
}
//other wise validate unit
elseif($report['class_id'] = $val->cclass($_GET['class_id'],13))
{
    //get class information
    $result = mysql_query("select class_number, mos from class where class_id = " . $report['class_id']) or die("class info query error: " . mysql_error());
    list($report['class_number'], $report['class_mos']) = mysql_fetch_row($result);

    $date = strtoupper(date("dMY"));

    $header =  "<strong>class roster: " . $report['class_mos'] . "-" . $report['class_number'] . " --- $date</strong>";

    echo com_siteheader("class roster: " . $report['class_mos'] . "-" . $report['class_number'] . " --- $date");

    if(!isset($_REQUEST["export2"]))
    { echo "export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>word</a>\n"; }

    $comp = implode(",",$_CONF['component']);
    $comp_abbr = "'" . implode("','",$_CONF['component_abbrev']) . "'";

    $query = "select m.id, m.Last_Name, m.First_Name, m.middle_initial as MI, concat(m.rank,m.promotable) as Rank, right(m.ssn,4) as SSN, m.Gender AS Gen, "
            ."elt(find_in_set(m.component,'" . $comp . "')," . $comp_abbr . ") as Comp, "
            ."m.platoon as PLT, m.MOS, s.Shift, s.Phase as PH, s.Academic_Avg, s.Test_Failures, "
            ."concat(co.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as Unit from main m ,student s left join class c "
            ."on s.class_id = c.class_id, battalion b, company co "
            ."where m.pcs = 0 and m.id = s.id  and m.battalion = b.battalion_id and m.company = co.company_id "
            ."and c.class_id = " . $report['class_id']
            . " order by m.last_name, m.first_name, m.middle_initial, m.ssn";

    $roster = new roster($query);
    $roster->setheader($header);
    $roster->link_page("data_sheet.php");
    $roster->link_column(0);
    $roster->sethidecolumn(0);
    $roster->setReportName('classroster');
	$roster->allowUserOrderBy(TRUE);
    echo $roster->drawroster();

}
else
{
    echo com_siteheader("invalid permissions - class roster");
    echo "invalid permissions.";
}

echo com_sitefooter();

?>
