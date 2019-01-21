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
        $report['where'] .= " and s.shift in ($shift_string) ";
    }

    if(isset($_REQUEST['profile_only']))
    { $report['where'] .= " and ((curdate() between p.profile_start AND (p.profile_start + INTERVAL p.profile_length DAY)) OR (LEFT(p.profile,1)='P')) "; }
    
    if(isset($_REQUEST['recovery_only']))
    { $report['where'] .= " and curdate() between (p.profile_start + interval p.profile_length day) AND (p.profile_start + interval LEAST(p.profile_length+90,p.profile_length*3) day) "; }
    
    $date = strtoupper(date("dMY"));

    $header =  "<strong>Profile Report: " . $report["unit"] . ",  --- $date</strong>";

    $comp = implode(",",$_CONF['component']);
    $comp_abbr = "'" . implode("','",$_CONF['component_abbrev']) . "'";

    echo com_siteheader("Profile Report: " . $report["unit"] . ",  --- $date");

    if(!isset($_REQUEST["export2"]))
    { echo "Export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>Excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>Word</a>\n"; }

    $query = "select m.id, concat(m.last_name,', ',m.first_name,' ',m.middle_initial) as Name, 
            concat(m.rank,m.promotable) as Rank, m.platoon as PL, m.gender as Gen, s.Shift,
            p.Profile, upper(date_format(p.profile_start,'%d%b%y')) as Profile_Start, 
            if(left(p.profile,1)='P','Perm',upper(date_format(p.profile_start + INTERVAL p.profile_length DAY,'%d%b%y'))) as Profile_End, 
            if(left(p.profile,1)='P','Perm',upper(date_format(p.profile_start + INTERVAL LEAST(p.profile_length+90,p.profile_length*3) DAY,'%d%b%y'))) as Recovery_End, 
            p.profile_reason as Reason, p.profile_limitations as Limitations,
            concat(c.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as Unit
            from main m left join student s on m.id = s.id, profile p, company c, battalion b where m.id = p.id and m.pcs = 0
            and m.company = c.company_id and m.battalion=b.battalion_id and
            ((CURDATE() >= p.profile_start AND CURDATE() <= (p.profile_start + INTERVAL LEAST(p.profile_length+90,p.profile_length*3) DAY))
            OR (LEFT(p.profile,1)='P')) and {$report['where']}
            order by m.last_name, m.first_name, m.middle_initial";

    $roster = new roster($query);
    $roster->setheader($header);
    $roster->link_page("data_sheet.php");
    $roster->link_column(0);
    $roster->sethidecolumn(0);
    $roster->setReportName('profilereport');
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