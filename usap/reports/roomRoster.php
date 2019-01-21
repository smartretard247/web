<?
include("../lib-common.php");

include("../classes/validate.class.php");
include("../classes/roster.class.php");
$val = new validate;

if ($_GET['room'] == "") {
	$outRm = "ALL";
} else {
	$outRm = $_GET['room'];
}

//if none is chosen, display message
if($_GET['building_number'] == "")
{
    echo com_siteheader("Room Roster");
    echo "please choose a room";
}
//other wise validate unit
elseif ($_GET['building_number'] <> "")
{
    //get class information

    $date = strtoupper(date("dMY"));

    $header =  "<strong>Room Roster - (Room:" . $outRm  . ") - (Building:" . $_GET['building_number'] . ") --- $date</strong>";

    echo com_siteheader("Room roster -  (Room:" . $outRm  . ") - (Building:" . $_GET['building_number'] . ") --- $date");

    if(!isset($_REQUEST["export2"]))
    { echo "export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>word</a>\n"; }

    $comp = implode(",",$_CONF['component']);
    $comp_abbr = "'" . implode("','",$_CONF['component_abbrev']) . "'";

if (!isset($_REQUEST["export2"]))
{
	$htmlCode=1;
} else {
	$htmlCode=2;
}

if ($_GET['room'] == ""){
    $query = "select m.id, h.htmlVal as 'X', m.room_number as ROOM, m.Last_Name, m.First_Name, m.middle_initial as MI, concat(m.rank,m.promotable) as Rank, right(m.ssn,4) as SSN, m.Gender AS Gen, "
            ."m.platoon as PLT, m.MOS, s.Shift, s.Phase as PH, x.Status "
            ." from htmlVals h, main m ,student s Inner Join status x ON m.Status = x.Status_ID left join class c "
            ."on s.class_id = c.class_id, battalion b, company co "
            ."where m.pcs = 0 and m.id = s.id  and m.battalion = b.battalion_id and m.company = co.company_id "
            . " and h.id=" . $htmlCode . " and building_number=" . $_GET["building_number"]
            . " order by room_number, m.last_name, m.first_name, m.middle_initial, m.ssn";
	} else {
     $query = "select m.id, m.Last_Name, m.First_Name, m.middle_initial as MI, concat(m.rank,m.promotable) as Rank, right(m.ssn,4) as SSN, m.Gender AS Gen, "
            ."m.platoon as PLT, m.MOS, s.Shift, s.Phase as PH "
            ." from main m ,student s left join class c "
            ."on s.class_id = c.class_id, battalion b, company co "
            ."where m.pcs = 0 and m.id = s.id  and m.battalion = b.battalion_id and m.company = co.company_id "
            ."and m.room_number= " . $_GET['room'] . " and building_number=" . $_GET["building_number"]
            . " order by m.last_name, m.first_name, m.middle_initial, m.ssn";
	}

    $roster = new roster($query);
    $roster->setheader($header);
    $roster->link_page("data_sheet.php");
    $roster->link_column(0);
    $roster->sethidecolumn(0);
    $roster->setReportName('classroster');
	if(isset($_REQUEST["export2"]))
	{
	$roster->allowUserOrderBy(FALSE);
	} else {
	$roster->allowUserOrderBy(TRUE);
	}
    echo $roster->drawroster();

}
else
{
    echo com_siteheader("invalid permissions - room roster");
    echo "invalid permissions.";
}

echo com_sitefooter();

?>
