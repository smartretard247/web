<?
include("../lib-common.php");

include("../classes/validate.class.php");
include("../classes/roster.class.php");

$val = new validate;
$pt = array();
$ssn_length = 4;

//validate unit
if($unit = $val->unit($_GET['unit'],14))
{
    $battalion = $unit[0];
    $company = $unit[1];

    if(count($_GET['pers_type']) > 0)
    {
        //validate each of the personal types selected
        foreach($_GET['pers_type'] as $pers_type)
        {
            //$pt[] will contain all only valid personnel types
            if($val->conf($pers_type,"pers_type"))
            { $pt[] = $pers_type; }
        }
        //create string of the chosen pers_types to use in query later
        $pt_string = "'" . implode("','",$pt) . "'";
    }
    else
    { $pt_string = "''"; }

    //default sql where values
    $report["where"] = " 1 ";

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

    if(isset($_GET['email_status']))
    {
        switch($_GET['email_status'])
        {
            case "with_email":
                $report['where'] .= " and m.email != '' ";
            break;
            case "without_email":
                $report['where'] .= " and m.email = '' ";
            break;
        }
    }

    $date = strtoupper(date("dMY"));

    $header =  "<strong>AKO Roster: " . $report["unit"] . " --- $date </strong>";

    echo com_siteheader("AKO Roster" . $report['unit'] . " --- $date");

    if(!isset($_REQUEST["export2"]))
    { echo "Export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>Excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>Word</a>\n"; }

    $query = "select m.id, m.Last_Name, m.First_Name, m.middle_initial as MI, "
            ."concat(m.rank,m.promotable) as Rank, right(m.ssn,4) as SSN, m.Email, m.Platoon, "
            ."concat(co.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as Unit from "
            ."main m, battalion b, company co "
            ."where m.pcs = 0 and m.battalion = b.battalion_id and m.company = co.company_id "
            ."and m.pers_type in (" . $pt_string . ") and " . $report["where"]
            ." order by m.last_name, m.first_name, m.middle_initial, m.ssn";

    $roster = new roster($query);
    $roster->setheader($header);
    $roster->link_page("data_sheet.php");
    $roster->link_column(0);
    $roster->sethidecolumn(0);
    $roster->setReportName('akoroster');
	$roster->allowUserOrderBy(TRUE);
    echo $roster->drawroster();

}
else
{ echo com_siteheader("Invalid permissions - AKO Roster"); }

echo com_sitefooter();

?>