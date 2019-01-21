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
    if(isset($_GET['show_full_ssn']) && $val->unit($_GET['unit'],12))
    { $ssn_length = 9; }

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

    $header =  "<strong>Race Report: " . $report["unit"] . " --- $date </strong>";

    $comp = implode(",",$_CONF['component']);
    $comp_abbr = "'" . implode("','",$_CONF['component_abbrev']) . "'";

    echo com_siteheader("Race Report" . $report['unit'] . " --- $date");

    if(!isset($_REQUEST["export2"]))
    { echo "export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>word</a>\n"; }


    $query = "select m.id, m.last_name, m.first_name, m.middle_initial as mi, concat(m.rank,m.promotable) as rank, "
        ."right(m.ssn," . $ssn_length . ") as ssn, m.gender, "
        ."m.platoon as plt, m.mos,m.pers_type, m.Race, "
        ."elt(find_in_set(m.component,'" . $comp . "')," . $comp_abbr . ") as comp, "
        . "concat(co.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as unit from main m, battalion b, company co "
        ."where m.pcs = 0 and m.battalion = b.battalion_id and m.company = co.company_id "
        ."and " . $report["where"]
        . " order by m.last_name, m.first_name, m.middle_initial, m.ssn";
   
    $roster = new roster($query);
    $roster->setheader($header);
    $roster->link_page("data_sheet.php");
    $roster->link_column(0);
    $roster->sethidecolumn(0);
	$roster->setReportName('racereport');
	$roster->allowUserOrderBy(TRUE);    
    echo $roster->drawroster();

}
else
{
    echo com_siteheader("invalid permissions - alpha roster");
    if(count($_GET['pers_type']) == 0)
    { echo "no personnel type chosen."; }
    else
    { echo "invalid permissions.";  }
}

echo com_sitefooter();

?>
