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
    $report["where"] = " s.status_id NOT IN (97,98) ";

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

    $comp = implode(",",$_CONF['component']);
    $comp_abbr = "'" . implode("','",$_CONF['component_abbrev']) . "'";

    if(isset($_GET['status']) && $_GET['status'] != "all")
    {
        if($input['status'] = $val->fk_constraint($_GET['status'],"status","status_id"))
        { $report['where'] .= " and s.status_id = " . $input['status'] . " "; }
    }
    if(isset($_GET['pers_type']) && $_GET['pers_type'] == "pp")
    {
        $report['where'] .= " and s.applies_to = 'permanent party' ";
        $input['pers_type'] = "permanent party";

        $query = "select m.id, m.last_name, m.first_name, m.middle_initial, right(m.ssn,4) as ssn, "
            ."concat(m.rank,m.promotable) as rank, m.MOS, m.gender, "
            ."s.status, m.status_remark as remark, "
            ."elt(find_in_set(m.component,'" . $comp . "')," . $comp_abbr . ") as comp, "
            ."concat(co.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as unit "
            ."from main m, status s, company co, battalion b "
            ."where m.pcs=0 and m.inact_status = s.status_id "
            ."and m.company = co.company_id and m.battalion=b.battalion_id and " . $report['where']
            ."order by m.last_name, m.first_name";
    }
    else
    {
        $report['where'] = $report['where'] . " and s.applies_to = 'student' ";
        $input['pers_type'] = "student";

        $query = "select m.id, m.last_name as last, m.first_name as first, m.middle_initial as mi, right(m.ssn,4) as ssn, "
            ."concat(m.rank,m.promotable) as rank, m.MOS, m.gender as gen, "
            ."left(s.status,3) as ic, s2.status as daily_status, m.status_remark as remark, if(cl.grad_date>=now() or cl.grad_date is null,'n','y') as grad, "
            ."elt(find_in_set(m.component,'" . $comp . "')," . $comp_abbr . ") as comp, "
            ."concat(co.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as unit "
            ."from main m left join student st on m.id = st.id left join class cl on st.class_id = cl.class_id, "
            ."status s, company co, battalion b, status s2 "
            ."where m.pcs=0 and m.inact_status = s.status_id and m.status = s2.status_id "
            ."and m.company = co.company_id and m.battalion=b.battalion_id and " . $report['where']
            ."order by m.last_name, m.first_name";

    }

    //generate code to create list box
    //to limit inactive roster to certain code
    $limit_form = "<form method='get' action='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "'>\n"
            ."<input type='hidden' name='unit' value='$battalion-$company'>\n"
            ."<input type='hidden' name='pers_type' value='" . $input['pers_type'] . "'>\n"
            ."limit to: <select name='status' size='1'>\n"
            ."<option value='all'>all</option>\n";
    $status_result = mysql_query("select s.status, s.status_id from status s where s.type='inactive' and s.applies_to = '" . $input['pers_type'] . "'");
    while($row = mysql_fetch_assoc($status_result))
    {
        $limit_form .= "<option value='" . $row['status_id'] . "' ";
        if(isset($_GET['status']) && $row['status_id'] == $_GET['status'])
        { $limit_form .= "selected"; }
        $limit_form .= ">" . $row['status'] . "</option>\n";
    }
    $limit_form .= "</select><input type='submit' class='button' name='submit' value='go'></form>";

    //get date string
    $date = strtoupper(date("dMY"));

    //report header
    $header =  "<strong>inactive report: " . $report["unit"] . ",  --- $date</strong>";

    $comp = implode(",",$_CONF['component']);
    $comp_abbr = "'" . implode("','",$_CONF['component_abbrev']) . "'";

    //display page header
    echo com_siteheader("inactive report: " . $report["unit"] . ",  --- $date");

    //display export to links
    if(!isset($_REQUEST["export2"]))
    { echo "<table width='100%'><tr><td width='50%'>export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>word</a></td><td width='50%' align='right'>$limit_form</td></tr></table>"; }

    //display roster/report
    $roster = new roster($query);
    $roster->setheader($header);
    $roster->link_page("data_sheet.php");
    $roster->link_column(0);
    //$roster->link_query_string("subject=14&submit_subject_limit=go");
    $roster->sethidecolumn(0);
    $roster->setReportName('inactivereport');
	$roster->allowUserOrderBy(TRUE);
    echo $roster->drawroster();

}
else
{
    echo com_siteheader("invalid permissions - inactive report");
    echo "invalid permissions.";
}

echo com_sitefooter();

?>
