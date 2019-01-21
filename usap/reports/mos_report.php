<?
include("../lib-common.php");

include("../classes/validate.class.php");
include("../classes/roster.class.php");

$val = new validate;
$valid_css = array();
$valid_moss = array();
$valid_pts = array();

//validate unit
if($unit = $val->unit($_GET['unit'],14))
{
    $battalion = $unit[0];
    $company = $unit[1];

    $ssn_length = 4;
    if($val->unit($_GET['unit'],12))
    { $ssn_length = 9; }

    //default sql where values
    $report["where"] = " 1 ";

    //loop through and verify each MOS passed if it's in an array
    if(isset($_GET['mos']) && is_array($_GET['mos']))
    {
        foreach($_GET['mos'] as $mos)
        {
            if($valid_mos = $val->conf($mos,"mos"))
            { $valid_moss[] = $valid_mos; }
        }
    }

    //if any valid platoons were passed, add then to string to be
    //incorporated into sql query.
    if(count($valid_moss) > 0)
    {
      $mos_string = "'" . implode("','",$valid_moss) . "'";
      $report['where'] .= " and m.mos in ($mos_string) ";
    }

    //loop through and verify each clearance status passed if it's in an array
    if(isset($_GET['clearance_status']) && !in_array('Any Clearance',$_GET['clearance_status']))
    {
        if(is_array($_GET['clearance_status']))
        {
            foreach($_GET['clearance_status'] as $cs)
            {
                if($valid_cs = $val->conf($cs,"clearance_status"))
                { $valid_css[] = $valid_cs; }
            }
        }
    }

    //if any valid clearance_statuses were passed, add then to string to be
    //incorporated into sql query.
    if(count($valid_css) > 0)
    {
        $cs_string = "'" . implode("','",$valid_css) . "'";
        $report['where'] .= " and s2.clearance_status in ($cs_string) ";
    }

    if(isset($_GET['pers_type']) && is_array($_GET['pers_type']))
    {
        foreach($_GET['pers_type'] as $pt)
        {
            if($valid_pt = $val->conf($pt,'pers_type'))
            { $valid_pts[] = $valid_pt; }
        }
    }

    if(count($valid_pts) > 0)
    {
        $pt_string = "'" . implode("','",$valid_pts) . "'";
        $report['where'] .= " and m.pers_type in ($pt_string) ";
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

    $header =  "<strong>MOS Report: " . $report["unit"] . "  --- $date</strong>";

    $comp = implode(",",$_CONF['component']);
    $comp_abbr = "'" . implode("','",$_CONF['component_abbrev']) . "'";

    echo com_siteheader("MOS Report: " . $report["unit"] . "  --- $date");

    if(!isset($_REQUEST["export2"]))
    { echo "export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>word</a>\n"; }

    $query = "select m.id, m.Last_Name, m.First_Name, m.middle_initial as MI, concat(m.rank,m.promotable) as Rank,
              right(m.ssn,$ssn_length) as SSN, m.Gender AS Gen,
              elt(find_in_set(m.component,'" . $comp . "')," . $comp_abbr . ") as Comp, m.MOS, s2.clearance_status,
              concat(co.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as Unit
              from main m left join s2 on m.id = s2.id, battalion b, company co
              where m.pcs = 0 and m.battalion = b.battalion_id and m.company = co.company_id
              and {$report['where']}
              order by m.battalion, m.company, m.mos, m.last_name, m.first_name, m.middle_initial, m.ssn";

    $roster = new roster($query);
    $roster->setheader($header);
    $roster->link_page("data_sheet.php");
    $roster->link_column(0);
    $roster->sethidecolumn(0);
    $roster->setReportName('mosreport');
	$roster->allowUserOrderBy(TRUE);
    echo $roster->drawroster();

}
else
{
    echo com_siteheader("invalid permissions - platoon roster");
    echo "invalid permissions.";
}

echo com_sitefooter();

?>
