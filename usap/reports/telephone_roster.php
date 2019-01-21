<?
include("../lib-common.php");

include("../classes/validate.class.php");
include("../classes/roster.class.php");

$val = new validate;
$report['where'] = " a.type='local / off-post' and m.company = c.company_id and m.battalion = b.battalion_id ";

//validate unit
if($unit = $val->unit($_GET['unit'],14))
{
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

    if(isset($_GET['pers_type']) && $_GET['pers_type'] == "student")
    { $report['where'] .= " and m.pers_type IN ('" . implode("','",$_CONF['students']) . "') "; }
    else
    { $report['where'] .= " and m.pers_type IN ('" . implode("','",$_CONF['perm_party']) . "') "; }

    $date = strtoupper(date("dMY"));

    $header =  "<strong>Telephone / Local Address Roster: " . $report['unit'] . "</strong>";

    echo com_siteheader("Telephone / Local Address Roster: " . $report['unit']);

    if(!isset($_REQUEST["export2"]))
    { echo "export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>word</a>\n"; }

    $query = "select m.id, concat(m.last_name, ', ', m.first_name, ' ', m.middle_initial) as Name, CONCAT(m.Rank,m.Promotable) AS Rank,
              concat(a.street1,' ',a.street2) as Address, a.City, a.ZIP, a.Phone1, a.Phone2,
              concat(c.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as Unit
              from main m left join address a on m.id = a.id, company c, battalion b
              where m.pcs = 0 and m.id = a.id and {$report['where']} order by name";

    $roster = new roster($query);
    $roster->setheader($header);
    $roster->link_page("data_sheet.php");
    $roster->link_column(0);
    $roster->sethidecolumn(0);
    $roster->setReportName('telephonereport');
	$roster->allowUserOrderBy(TRUE);
    echo $roster->drawroster();

}
else
{
    echo com_siteheader("invalid permissions - telephone roster");
    echo "invalid permissions.";
}

echo com_sitefooter();

?>