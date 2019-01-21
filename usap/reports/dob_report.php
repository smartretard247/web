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
    
    $report['having'] = ' 1 ';
    if($min = (int)$_REQUEST['min_age'])
    { $report['having'] .= " AND Age >= $min "; }
    if($max = (int)$_REQUEST['max_age'])
    { $report['having'] .= " AND Age <= $max "; }
    
    if(isset($_REQUEST['sortbyage']))
    { $orderby = ' Age, m.DOB, m.Last_name, m.first_name '; }
    else
    { $orderby = ' m.Last_name, m.first_name '; }
    

    $date = strtoupper(date("dMY"));

    $header =  "<strong>Date of Birth and Age Report: " . $report["unit"] . " --- $date </strong>";

    echo com_siteheader("Date of Birth and Age Report" . $report['unit'] . " --- $date");

    if(!isset($_REQUEST["export2"]))
    { echo "Export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>Excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>Word</a>\n"; }


    $query = "select m.id, m.Last_Name, m.First_Name, m.middle_initial as MI, concat(m.rank,m.promotable) as Rank,
              right(m.ssn,$ssn_length) as SSN, year(current_date) - year(m.dob) - (if(dayofyear(m.dob)>dayofyear(current_date),1,0)) as Age,
              upper(date_format(m.DOB,'%d%b%y')) as DOB, m.Gender,
              m.platoon as PLT, m.Pers_Type,
              concat(co.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as Unit from main m, battalion b, company co
              where m.pcs = 0 and m.battalion = b.battalion_id and m.company = co.company_id
              and {$report['where']} HAVING {$report['having']}
              order by $orderby";
   
    $roster = new roster($query);
    $roster->setheader($header);
    $roster->link_page("data_sheet.php");
    $roster->link_column(0);
    $roster->sethidecolumn(0);
    $roster->setReportName('dobreport');
	$roster->allowUserOrderBy(TRUE);
    echo $roster->drawroster();

}
else
{
    echo com_siteheader("Invalid permissions - Date of Birth and Age Report");
    echo "Invalid permissions.";
}

echo com_sitefooter();

?>
