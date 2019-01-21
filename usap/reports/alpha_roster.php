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

    if(isset($_GET['basd']))
    { $columns = " upper(date_format(m.date_entered_service,'%d%b%y')) as BASD, m.Marital_Status, "; }
    else
    { $columns = ''; }
    
    if(isset($_GET['gender']))
    { 
        if($_GET['gender'] == 'm')
        { $report['where'] .= " and m.gender = 'M' "; }
        else
        { $report['where'] .= " and m.gender = 'F' "; }
    }
    
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

    if(isset($_GET['location']))
    {
        switch($_GET['location'])
        {
            case 'Organic':
                $report['where'] .= " and m.location = 'Organic' ";
            break;
            case 'Attached':
                $report['where'] .= " and m.location = 'Attached' ";
            break;
            case 'Detached':
                $report['where'] .= " and m.location = 'Detached' ";
            break;
            case 'Not_Detached':
                $report['where'] .= ' and m.location != "Detached" ';
            break;
        }
    }
    
    if(!isset($_GET['all_comp']))
    {
        if(isset($_GET['component']) && count($_GET['component']) > 0)
        {
            //validate each of the personal types selected
            foreach($_GET['component'] as $component)
            {
                //$comp[] will contain all only valid personnel types
                if($val->conf($component,'component'))
                { $comp[] = $component; }
            }
            //create string of the chosen components to use in query later
            if(isset($comp) && count($comp) > 0)
            {
                $comp_string = "'" . implode("','",$comp) . "'";
                $report['where'] .= " and m.component IN ($comp_string) ";
            }
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

    $header =  "<strong>Alpha Roster: " . $report["unit"] . " --- $date </strong>";

    $comp = implode(",",$_CONF['component']);
    $comp_abbr = "'" . implode("','",$_CONF['component_abbrev']) . "'";

    echo com_siteheader("Alpha Roster " . $report['unit'] . " --- $date");

    if(!isset($_REQUEST["export2"]))
    { echo "Export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>Excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>Word</a>\n"; }

    $query = "select m.id, m.Last_Name, m.First_Name, m.middle_initial AS MI, CONCAT(m.rank,m.promotable) as Rank, "
        ."right(m.ssn," . $ssn_length . ") as SSN, m.Gender as Gen, $columns"
        ."elt(find_in_set(m.component,'" . $comp . "')," . $comp_abbr . ") as Comp, "
        ."m.platoon as PLT, m.MOS, upper(date_format(m.arrival_date,'%d%b%y')) as Arrival_Date, c.class_number as Class, s.Shift, s.Phase as PH, "
        ."concat(co.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as Unit from main m LEFT JOIN student s ON m.id = s.id left join class c "
        ."on s.class_id = c.class_id, battalion b, company co "
        ."where m.pcs = 0 and m.battalion = b.battalion_id and m.company = co.company_id "
        ."and " . $report["where"]
        . " order by m.last_name, m.first_name, m.middle_initial, m.ssn";

    $roster = new roster($query);
    $roster->setheader($header);
    $roster->link_page("data_sheet.php");
    $roster->link_column(0);
    $roster->sethidecolumn(0);
    $roster->setReportName('alpharoster');
	$roster->allowUserOrderBy(TRUE);
    echo $roster->drawroster();

}
else
{
    echo com_siteheader("Invalid permissions - Alpha Roster");
    echo "Invalid Permissions.";
}

echo com_sitefooter();

?>