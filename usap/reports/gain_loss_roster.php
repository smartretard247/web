<?
include("../lib-common.php");

include("../classes/validate.class.php");
include("../classes/roster.class.php");

$val = new validate;
$pt = array();

//validate unit
if($unit = $val->unit($_GET['unit'],14))
{
    $battalion = $unit[0];
    $company = $unit[1];

    //default sql where values
    $report["where"] = " 1 ";

    if(isset($_GET['pers_type']) && count($_GET['pers_type']) > 0)
    {
        //validate each of the personal types selected
        foreach($_GET['pers_type'] as $pers_type)
        {
            //$pt[] will contain all only valid personnel types
            if($val->conf($pers_type,"pers_type"))
            { $pt[] = $pers_type; }
        }
        //create string of the chosen pers_types to use in query later
        if(isset($pt) && count($pt) > 0)
        {
            $pt_string = "'" . implode("','",$pt) . "'";
            $report['where'] .= " and m.pers_type IN ($pt_string) ";
        }
    }

    $input['days'] = (int)$_GET['days'];

    switch($_GET['type'])
    {
        case "gain":
            $input['type'] = "Gain";
            $report['where'] .= " and m.pcs = 0 and m.arrival_date between now() - interval " . $input['days'] . " day and now() ";
            $report['column'] = " upper(date_format(m.arrival_date,'%d%b%y')) as Arrival_Date, to_days(now()) - to_days(m.arrival_date) as Days_Remaining ";
            $report['sort'] = " asc ";
            break;
        case "pcs":
            $input['type'] = "PCS";
            $report['where'] .= " and m.pcs = 1 and m.pcs_date between now() - interval " . $input['days'] . " day and now() ";
            $report['column'] = " upper(date_format(m.pcs_date,'%d%b%y')) as PCS_Date, to_days(now()) - to_days(m.pcs_date) as Days_Remaining ";
            $report['sort'] = " asc ";
            break;
        default:
            $input['type'] = "Loss";
            $report['where'] .= " and m.pcs = 0 and (m.pcs_date between now() and now() + interval " . $input['days'] . " day OR m.ets between now() and now() + interval {$input['days']} day) ";
            $report['column'] = " upper(date_format(m.pcs_date,'%d%b%y')) as PCS_Date, upper(date_format(m.ets,'%d%b%y')) as ETS_Date, if(m.pcs_date<m.ets and m.pcs_date>0,to_days(m.pcs_date),to_days(ets)) - to_days(now()) as Days_Remaining";
            $report['sort'] = " desc ";
    }

    //get text for battalion and company
    //and set where clause to limit to certain
    //battalion or company, if applicable.
    if($battalion == 0 && $company == 0)
    { $report['unit'] = '15 SIG BDE'; }
    else
    {
        $result = mysql_query("select battalion from battalion where battalion_id = $battalion") or die(mysql_error());
        $report["battalion"] = mysql_result($result,0);
        $report["where"] .= " and m.battalion = " . $battalion . " ";

        if($company == 0)
        { $report['company'] = ''; }
        else
        {
            $result = mysql_query("select company from company where company_id = " . $company) or die(mysql_error());
            $report["company"] = mysql_result($result,0);
            $report["where"] .= " and m.company = " . $company . " ";
        }

        $report["unit"] = $report["company"] . " " . $report["battalion"];
    }

    $date = strtoupper(date("dMY"));

    $header =  "<strong>" . $input['type'] . " Roster: " . $report["unit"] . " --- $date </strong>";

    $comp = implode(",",$_CONF['component']);
    $comp_abbr = "'" . implode("','",$_CONF['component_abbrev']) . "'";

    echo com_siteheader($input['type'] . " Roster" . $report['unit'] . " --- $date");

    if(!isset($_REQUEST["export2"]))
    { echo "export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>word</a>\n"; }

    $query = "select m.id, m.Last_Name, m.First_Name, m.middle_initial as MI, "
            ."concat(m.rank,m.promotable) as Rank, right(m.ssn,4) as SSN, m.gender as Gen, "
            ."m.platoon as Plt, m.MOS, m.Pers_Type, "
            ."elt(find_in_set(m.component,'" . $comp . "')," . $comp_abbr . ") as Comp, "
            .$report['column'] . ", concat(co.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as Unit from main m, battalion b, company co "
            ."where m.battalion = b.battalion_id and m.company = co.company_id "
            ."and " . $report["where"]
            . " order by days_remaining " . $report['sort'] . ",m.last_name, m.first_name, m.middle_initial, m.ssn";

    $roster = new roster($query);
    $roster->setheader($header);
    $roster->link_page("data_sheet.php");
    $roster->link_column(0);
    $roster->sethidecolumn(0);
    $roster->setReportName('gainlossroster');
	$roster->allowUserOrderBy(TRUE);
    echo $roster->drawroster();

}
else
{
    echo com_siteheader("Invalid Permissions - Gain/Loss Roster");
    echo "Invalid Permissions.";
}

echo com_sitefooter();

?>