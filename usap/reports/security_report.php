<?
include("../lib-common.php");

include("../classes/validate.class.php");
include("../classes/roster.class.php");

$val = new validate;
$valid_platoons = array();
$ssn_length = 4;

//validate unit
if($unit = $val->unit($_GET['unit'],14))
{
    //default sql where values
    $report["where"] = " 1 ";

    if($val->unit($_GET['unit'],12))
    { $ssn_length = 9; }
    
    if(count($_GET['pers_type']) > 0)
    {
        $pt = array();
        //validate each of the personal types selected
        foreach($_GET['pers_type'] as $pers_type)
        {
            //$pt[] will contain all only valid personnel types
            if($val->conf($pers_type,"pers_type"))
            { $pt[] = $pers_type; }
        }

        if(count($pt) > 0)
        {
            //create string of the chosen pers_types to use in query later
            $pt_string = "'" . implode("','",$pt) . "'";
            $report['where'] .= "and m.pers_type in ($pt_string) ";
        }
    }

    //if All_Resubmits was checked, add
    //clause that looks for all possible resubmit values
    if(isset($_GET['all_resubmit']))
    { $report['where'] .= " AND s2.clearance_status IN ('resubmit','incomplete','prenom','ssbi required','upscope') "; }
    
    //check if clearance status was set and it's not "all"
    //and assign value to where clause
    if(isset($_GET['clearance_status']) && $_GET['clearance_status'] != "All")
    {
        if($input['clearance_status'] = $val->conf($_GET['clearance_status'],'clearance_status'))
        { $report['where'] .= " AND s2.clearance_status = '{$input['clearance_status']}' "; }
    }
    
    //loop through and verify each platoon passed if it's in an array
    if(is_array($_GET['platoon']))
    {
        foreach($_GET['platoon'] as $platoon)
        {
            if($valid_platoon = $val->conf($platoon,"platoon"))
            { $valid_platoons[] = $valid_platoon; }
        }
    }

    //if any valid platoons were passed, add then to string to be
    //incorporated into sql query.
    if(count($valid_platoons) > 0)
    {
      $platoon_string = "'" . implode("','",$valid_platoons) . "'";
      $report['where'] .= "and m.platoon in ($platoon_string) ";
    }

    //loop through and verify each shift passed if it's in an array
    if(is_array($_GET['shift']))
    {
        foreach($_GET['shift'] as $shift)
        {
            if($valid_shift = $val->conf($shift,"shift"))
            { $valid_shifts[] = $valid_shift; }
        }
    }

    //if any valid shifts were passed, add then to string to be
    //incorporated into sql query.
    if(count($valid_shifts) > 0)
    {
        $shift_string = "'" . implode("','",$valid_shifts) . "'";
        $report['where'] .= "and s.shift in ($shift_string) ";
    }

    if(isset($_GET['month']) && $_GET['month'] != 0)
    {
        $month = (int)$_GET['month'];
        $report['where'] .= " and month(m.arrival_date) = $month ";
    }
    
    if(isset($_GET['year']) && $_GET['year'] != 0)
    {
        $year = (int)$_GET['year'];
        $report['where'] .= " and year(m.arrival_date) = $year ";
    }
    
    $battalion = $unit[0];
    $company = $unit[1];

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

    //title of report
    $header =  "<strong>Security (S2) Report: " . $report["unit"] . "  --- $date</strong>";

    //set variables that list possible component
    //values and the abbreviations of them
    $comp = implode(",",$_CONF['component']);
    $comp_abbr = "'" . implode("','",$_CONF['component_abbrev']) . "'";

    //create page header
    echo com_siteheader("Security Report: " . $report["unit"] . "  --- $date");

    if(!isset($_REQUEST["export2"]))
    { echo "export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>word</a>\n"; }

    $query = "select m.id, m.Last_Name, m.First_Name, m.middle_initial as MI, concat(m.rank,m.promotable) as Rank, "
            ."right(m.ssn,$ssn_length) as SSN, m.Gender, "
            ."elt(find_in_set(m.component,'" . $comp . "')," . $comp_abbr . ") as Comp, "
            ."m.platoon as PLT, m.MOS, upper(date_format(m.arrival_date,'%d%b%y')) as Ar_Date, "
            ."s2.Clearance_Status, UPPER(DATE_FORMAT(s2.status_date,'%d%b%y')) AS Status_Date, s2.Derog_Issue, s.MEPS, "
            ."concat(co.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as Unit "
            ."from main m left join s2 on m.id = s2.id, student s left join class c "
            ."on s.class_id = c.class_id, battalion b, company co "
            ."where m.pcs = 0 and m.id = s.id  and m.battalion = b.battalion_id and m.company = co.company_id "
            ."and " . $report['where']
            ." order by m.last_name, m.first_name, m.middle_initial, m.ssn";

//echo $query;

    $roster = new roster($query);
    $roster->setheader($header);
    $roster->link_page("data_sheet.php");
    $roster->link_column(0);
    $roster->sethidecolumn(0);
    $roster->setReportName('securityreport');
	$roster->allowUserOrderBy(TRUE);
    echo $roster->drawroster();

}
else
{
    echo com_siteheader("Invalid Permissions - Security Report");
    echo "Invalid Permissions.";
}

echo com_sitefooter();

?>
