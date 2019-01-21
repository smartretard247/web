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
    
    if(!isset($_GET['location']) || count($_GET['location'])>1)
    {
        $_GET['location'] = array('Attached','Detached');
        $report['title'] = 'Attached / Detached Roster';
    }
    elseif(in_array('Detached',$_GET['location']))
    { $report['title'] = "Detached Roster"; }
    else
    { $report['title'] = "Attached Roster"; }
    
    //get text for battalion and company
    //and set where clause to limit to certain
    //battalion or company, if applicable.
    if($battalion == 0 && $company == 0)
    { 
        $report['unit'] = '15 SIG BDE'; 
        if(count($_GET['location'])==1)
        {
            if(in_array('Detached',$_GET['location']))
            { $report['where'] .= " and m.location = 'Detached' "; }
            if(in_array('Attached',$_GET['location']))
            { $report['where'] .= " and m.location = 'Attached' "; }
        }
        
    }
    else
    {
        $result = mysql_query("select battalion from battalion where battalion_id = $battalion") or die(mysql_error());
        $report["battalion"] = mysql_result($result,0);
        if(in_array('Detached',$_GET['location']))
        { $report['d_where'] = " l.detached_bn = $battalion "; }
        if(in_array('Attached',$_GET['location']))
        { $report['a_where'] = " l.attached_bn = $battalion "; }

        if($company == 0)
        { $report['company'] = ''; }
        else
        {
            $result = mysql_query("select company from company where company_id = " . $company) or die(mysql_error());
            $report["company"] = mysql_result($result,0);
            if(in_array('Detached',$_GET['location']))
            { $report['d_where'] .= " and l.detached_co = $company "; }
            if(in_array('Attached',$_GET['location']))
            { $report['a_where'] .= " and l.attached_co = $company "; }
        }

        $report["unit"] = $report["company"] . " " . $report["battalion"];
        if(isset($report['d_where']) && isset($report['a_where']))
        { $report['where'] = "(({$report['d_where']}) OR ({$report['a_where']}))"; }
        else
        { $report['where'] .= 'and ' . @$report['d_where'] . @$report['a_where']; }
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

    $date = strtoupper(date("dMY"));

    $header =  "<strong>{$report['title']}: " . $report["unit"] . "  --- $date</strong>";

    $comp = implode(",",$_CONF['component']);
    $comp_abbr = "'" . implode("','",$_CONF['component_abbrev']) . "'";

    echo com_siteheader("{$report['title']}: " . $report["unit"] . "  --- $date");

    if(!isset($_REQUEST["export2"]))
    { echo "export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>word</a>\n"; }

    $query = "select m.id, m.Last_Name, m.First_Name, m.middle_initial as MI, concat(m.rank,m.promotable) as Rank, 
              right(m.ssn,4) as SSN, m.Gender as Gen, 
              elt(find_in_set(m.component,'$comp'),$comp_abbr) as Comp, m.MOS, m.Location, 
              concat(c.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as Current_Unit,
              concat(c2.company,'-',if(0+b2.battalion=0,b2.battalion,0+b2.battalion)) as Detached_From,
              concat(c1.company,'-',if(0+b1.battalion=0,b1.battalion,0+b1.battalion)) as Attached_To, l.Position, l.Reason,
              upper(date_format(l.effective,'%d%b%y')) as Date
              from main m , location l, company c1, company c2, battalion b1, battalion b2, company c, battalion b        
              where m.pcs = 0 and m.id = l.id and l.attached_bn = b1.battalion_id and l.attached_co = c1.company_id
              and l.detached_bn = b2.battalion_id and l.detached_co = c2.company_id and m.battalion = b.battalion_id and 
              m.company = c.company_id and {$report['where']}
              order by m.last_name, m.first_name, m.middle_initial, m.ssn";

    $roster = new roster($query);
    $roster->setheader($header);
    $roster->link_page("data_sheet.php");
    $roster->link_column(0);
    $roster->sethidecolumn(0);
    $roster->setReportName('attdettroster');
	$roster->allowUserOrderBy(TRUE);
    echo $roster->drawroster();

}
else
{
    echo com_siteheader("Invalid Permissions - Attached / Detached Roster");
    echo "Invalid Permissions.";
}

echo com_sitefooter();

?>
