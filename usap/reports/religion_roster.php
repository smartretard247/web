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

    $report['where'] = " m.pcs = 0 ";
    
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

    if(isset($_GET['religion']) && $_GET['religion'] != 'Any Religion')
    {
        if($religion = $val->conf($_GET['religion'],'religion'))
        { $report['where'] .= " and m.religion = '$religion' "; }
    }

    $date = strtoupper(date("dMY"));

    $header =  "<strong>Religion Roster: " . $report["unit"] . " --- $date </strong>";

    echo com_siteheader("Religion Roster" . $report['unit'] . " --- $date");

    if(!isset($_REQUEST["export2"]))
    { echo "Export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>Excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>Word</a>\n"; }

    $query = "select m.id, m.Last_Name, m.First_Name, m.middle_initial as MI, "
            ."concat(m.rank,m.promotable) as Rank, right(m.ssn,4) as SSN, m.Gender as Gen, m.Religion, "
            ."concat(co.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as Unit from "
            ."main m, battalion b, company co "
            ."where m.battalion = b.battalion_id and m.company = co.company_id "
            ."and " . $report["where"]
            ." order by m.last_name, m.first_name, m.middle_initial, m.ssn";
            
	$query2 = "SELECT m.Religion, SUM(IF(m.Gender='M',1,0)) AS Male, SUM(IF(m.Gender='F',1,0)) AS Female, 
			   COUNT(m.religion) as Total FROM main m, battalion b, company co
			   where m.battalion = b.battalion_id and m.company = co.company_id and {$report['where']}
			   group by m.religion order by m.religion ASC";
			   
	$roster1 = new roster($query2);
	$roster1->setheader('Religion Rollup');
	$roster1->setReportName('religionrollup');
	$roster1->allowUserOrderBy(TRUE);
	

    $roster = new roster($query);
    $roster->setheader($header);
    $roster->link_page("data_sheet.php");
    $roster->link_column(0);
    $roster->sethidecolumn(0);
    $roster->setReportName('religion_roster');
    $roster->allowUserOrderBy(TRUE);
    
    echo '<table border="0" width="100%"><tr><td>';
    echo $roster1->drawroster();
    echo '</td></tr><tr><td>';
    echo $roster->drawroster();
    echo '</td></tr></table>';

}
else
{ echo com_siteheader("invalid permissions - Religion Roster"); }

echo com_sitefooter();

?>