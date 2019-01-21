<?
include("../lib-common.php");

include("../classes/validate.class.php");
include("../classes/roster.class.php");

$val = new validate;
$valid_platoons = array();

//validate unit
if($unit = $val->unit($_GET['unit'],14))
{
    //default sql where values
    $report["where"] = " 1 ";

    $battalion = $unit[0];
    $company = $unit[1];

    if(isset($_GET['license_type']) && $_GET['license_type'] != 'All')
    {
        if($input['license_type'] = $val->conf($_GET['license_type'],'license_type'))
        { $report['where'] .= " AND d.license_type = '{$input['license_type']}' "; }        
    }
    
    if(isset($_GET['permit_exp']))
    { $report['where'] .= " and d.permit_exp > 0 and d.permit_exp < current_date "; }
    
    if(isset($_GET['license_exp']))
    { $report['where'] .= " and d.license_exp > 0 and d.license_exp < current_date "; }
    
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
    $header =  "<strong>UNDER CONSTRUCTION: " . $report["unit"] . "  --- $date</strong>";

    //create page header
    echo com_siteheader("UNDER CONSTRUCTION: " . $report["unit"] . "  --- $date");

    if(!isset($_REQUEST["export2"]))
    { echo "export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>word</a>\n"; }

    $query = "select m.id, m.Last_Name, m.First_Name, m.middle_initial as MI, concat(m.rank,m.promotable) as Rank, "
            ."right(m.ssn,4) as SSN, m.Gender, "
            ."d.License_Type, upper(date_format(d.test_date,'%d%b%y')) as Test_Date, upper(date_format(d.permit_exp,'%d%b%y')) as Permit_Exp, "
            ."upper(date_format(d.license_exp,'%d%b%y')) as License_Exp, upper(date_format(d.received,'%d%b%y')) as Received, "
            ."upper(date_format(d.complete_ddc,'%d%b%y')) as Complete_DDC, upper(date_format(d.complete_348_8001,'%d%b%y')) as Complete_348_8001, "
            ."d.remark, concat(co.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as Unit "
            ."from main m, drivers d, battalion b, company co "
            ."where m.pcs = 0 and m.battalion = b.battalion_id and m.company = co.company_id "
            ."and m.id = d.id and " . $report['where']
            ." order by m.last_name, m.first_name, m.middle_initial, m.ssn";

//echo $query;

    $roster = new roster($query);
    $roster->setheader($header);
    $roster->link_page("data_sheet.php");
    $roster->link_column(0);
    $roster->sethidecolumn(0);
    $roster->setReportName('driverroster');
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
