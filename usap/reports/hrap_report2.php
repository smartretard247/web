<?
set_time_limit(0);
include("../lib-common.php");

include("../classes/validate.class.php");
include("../classes/roster.class.php");

//default values
$val = new validate;
$ssn_length = 9;
$errors = 0;
//validate unit
if($unit = $val->unit($_GET['unit'],14))
{
    $battalion = $unit[0];
    $company = $unit[1];
    $report['where'] = '';
    
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

    if(!isset($_GET['type'])) { $_GET['type'] == 'active'; }
    
    if($_GET['type'] == 'pcs' || $_GET['type'] == 'activepcs')
    {
        $input['start_date']    = $val->check('date',$_GET['start_date'],'Start Date',1);
        $input['end_date']      = $val->check('date',$_GET['end_date'],'End Date',1);
        
        if($val->iserrors())
        { $errors = $val->geterrors(); }
        else
        {
            if($input['start_date'] && $input['end_date'])
            { $report['where'] .= " and (m.pcs_date = 0 OR m.pcs_date between {$input['start_date']} AND {$input['end_date']}) "; }
            elseif($input['start_date'])
            { $report['where'] .= " and (m.pcs_date = 0 OR m.pcs_date > {$input['start_date']}) "; }
            elseif($input['end_date'])
            { $report['where'] .= " and (m.pcs_date = 0 OR m.pcs_date < {$input['end_date']}) "; }
        }
        if($_GET['type'] == 'pcs')
        { $report['where'] .= ' and m.pcs = 1 '; }
    }
    else
    { $report['where'] .= " and m.pcs = 0 "; }
    
    $date = strtoupper(date("dMY"));

    $header =  "<strong>HRAP Report (by Unit) --- $date</strong>";

    echo com_siteheader("HRAP Report (by Unit)--- $date");

    if($errors)
    { echo $errors; }
    else
    {
        if(!isset($_REQUEST["export2"]))
        { echo "export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>word</a>\n"; }

        $comp = implode(",",$_CONF['component']);
        $comp_abbr = "'" . implode("','",$_CONF['component_abbrev']) . "'";

        $query = "select m.id, CONCAT(m.rank, m.promotable, ' ', m.last_name, ', ', m.first_name, ' ', m.middle_initial) as Name, "
            ."right(m.ssn,4) as SSN, m.Gender as Gen, "
            ."elt(find_in_set(m.component,'" . $comp . "')," . $comp_abbr . ") as Comp, "
            ."m.platoon as plt, m.mos, s.hrap as HRAP_Status, c.class_number as Class, s.Shift, s.Phase as ph, "
            ."concat(co.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as Unit from main m ,student s left join class c "
            ."on s.class_id = c.class_id, battalion b, company co "
            ."where m.id = s.id  and m.battalion = b.battalion_id and m.company = co.company_id "
            ." and m.pers_type = 'IET' " . $report["where"]
            . " order by m.last_name, m.first_name, m.middle_initial, m.ssn";

        $roster = new roster($query);
        $roster->setheader($header);
        $roster->link_page("data_sheet.php");
        $roster->link_column(0);
        $roster->sethidecolumn(0);
        $roster->setReportName('hrapreport');
		$roster->allowUserOrderBy(TRUE);
        $r = $roster->drawroster();

        $rollup = '<p><span class="column_name" style="text-decoration: underline;">Total For Report</span><br />';
        foreach($_CONF['hrap'] as $hrap)
        { $rollup .= $hrap.': ' . substr_count($r,$hrap) . '<br />'; }
        $rollup .= '<br /></p>';

        echo $rollup . $r;
    }
}
else
{
    echo com_siteheader("HRAP Report (by Unit)");
    echo "Invalid Permissions - HRAP Report (by Unit)";
}

echo com_sitefooter();

?>
