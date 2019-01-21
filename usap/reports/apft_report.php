<?
include("../lib-common.php");

include("../classes/validate.class.php");
include("../classes/roster.class.php");

$val = new validate;

//validate unit
if($unit = $val->unit($_GET['unit'],14))
{
    $battalion = $unit[0];
    $company = $unit[1];

    //default sql where values
    $report["where"] = " 1 ";
    $apft['where'] = " 1 ";

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

    if(isset($_GET['platoon']) && is_array($_GET['platoon']))
    {
        foreach($_GET['platoon'] as $p)
        {
            if($valid_platoon = $val->conf($p,"platoon"))
            { $platoon[] = $valid_platoon; }
        }
        if(count($platoon) > 0)
        {
            $platoon_string = "'" . implode("','",$platoon) . "'";
            $report['where'] .= " and m.platoon in ($platoon_string) ";
        }
    }

    if(isset($_GET['ex_bct']))
    { $report['where'] .= " and left(a.type,3) != 'bct' "; }

    if(isset($_GET['fail_only']))
    { $report['where'] .= " and a.pass_fail = 'fail' "; }

    $date = strtoupper(date("dMY"));

    $header =  "<strong>APFT Report: " . $report["unit"] . "  --- $date</strong>";

    echo com_siteheader("APFT Report: " . $report["unit"] . "  --- $date");

    if(!isset($_REQUEST["export2"]))
    { echo "export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>word</a>\n"; }

    $q1 = "create temporary table apft_temp (primary key(id), index(date)) select id, max(date) as date from apft group by id";
    $result = mysql_query($q1) or die("apft temp error: ". mysql_error());

    if(isset($_GET['fail_only']))
    {
        $query = "select m.id, m.Last_Name, m.First_Name, m.middle_initial as MI, right(m.ssn,4) as SSN, a.Rank, "
            ."m.platoon as PL, a.Type, a.Raw_PU, a.PU_Score, a.Raw_SU, a.SU_Score, a.Raw_Run, a.Run_Score, a.Alt_Event, "
            ."a.Alt_Score, a.Total_Score, upper(a.pass_fail) as 'P/F', upper(date_format(a.date,'%d%b%y')) as Date, a.Age, "
            ."a.Height, a.Weight from main m, apft a, apft_temp at where m.id = a.id and a.date = at.date and m.id = at.id and m.pcs = 0 "
            ."and " . $report['where'] . " order by m.last_name, m.first_name, m.middle_initial desc";
    }
    else
    {

        $query = "select m.id, m.Last_Name, m.First_Name, m.middle_initial as MI, right(m.ssn,4) as SSN, a.Rank, "
            ."m.platoon as PL, a.Type, a.Raw_PU, a.PU_Score, a.Raw_SU, a.SU_Score, a.Raw_Run, a.Run_Score, a.Alt_Event, "
            ."a.Alt_Score, a.Total_Score, a.pass_fail, upper(date_format(a.date,'%d%b%y')) as Date, a.Age, "
            ."a.Height, a.Weight from main m left join apft a on m.id = a.id, apft_temp at where m.pcs = 0 and a.date = at.date and m.id = at.id "
            ."and " . $report['where'] . " order by m.last_name, m.first_name, m.middle_initial desc";
    }

    $roster = new roster($query);
    $roster->setheader($header);
    $roster->link_page("data_sheet.php");
    $roster->link_column(0);
    $roster->sethidecolumn(0);
    $roster->setReportName('apftreport');
	$roster->allowUserOrderBy(TRUE);
    echo $roster->drawroster();

}
else
{
    echo com_siteheader("invalid permissions - profile report");
    echo "invalid permissions.";
}

echo com_sitefooter();

?>
