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

    $pp_string = "'" . implode("','",$_CONF['perm_party']) . "'";

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

    $date = strtoupper(date("dMY"));

    $header =  "<strong>CUA Report: " . $report["unit"] . " --- $date </strong>";

    echo com_siteheader("CUA Report" . $report['unit'] . " --- $date");

    if(!isset($_REQUEST["export2"]))
    { echo "export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>word</a>\n"; }

    $query = "select m.id, m.Last_Name, m.First_Name, m.middle_initial as MI, concat(m.rank,m.promotable) as Rank, "
            ."right(m.ssn," . $ssn_length . ") as SSN, "
            ."concat(co.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as Unit, if(c.id is null OR c.time < NOW() - INTERVAL 1 YEAR,'','Yes') as 'Agreed to CUA?', "
            ."upper(date_format(c.time,'%d%b%y')) as 'Date' "
            ."from main m left join cua c on m.id = c.id, battalion b, company co "
            ."where m.pcs = 0 and m.battalion = b.battalion_id and m.company = co.company_id "
            ."and m.pers_type in (" . $pp_string . ") and " . $report["where"]
            . " order by m.last_name, m.first_name, m.middle_initial, m.ssn";

    $roster = new roster($query);
    $roster->setheader($header);
    $roster->link_page("data_sheet.php");
    $roster->link_column(0);
    $roster->sethidecolumn(0);
    $roster->setReportName('cuareport');
	$roster->allowUserOrderBy(TRUE);
    echo $roster->drawroster();

}
else
{ echo com_siteheader("invalid permissions - cua report"); }

echo com_sitefooter();

?>