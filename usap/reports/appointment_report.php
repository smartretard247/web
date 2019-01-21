<?
include("../lib-common.php");

include("../classes/validate.class.php");
include("../classes/roster.class.php");

$val = new validate;

$input['start_date'] = $val->check('date',@$_GET['start_date'],'Start Date',1);
$input['end_date']   = $val->check('date',@$_GET['end_date'],'End Date',1);
$report['where'] = ' 1 ';
$date = '';

//Exit script if problem with dates
if($val->iserrors())
{ echo $val->geterrors(), com_sitefooter(); exit(); }

$report["where"] = " 1 ";

if($input['start_date'] && $input['end_date'])
{
    $report['where'] .= " and (a.start BETWEEN {$input['start_date']}000000 AND {$input['end_date']}235959
                               or
                               a.end   BETWEEN {$input['start_date']}000000 AND {$input['end_date']}235959
                               or
                               (a.start < {$input['start_date']}000000 AND a.end > {$input['end_date']}235959) ) ";
}
elseif($input['start_date'])
{ $report['where'] .= " and a.end >= {$input['start_date']}000000 "; }
elseif($input['end_date'])
{ $report['where'] .= " and a.start <= {$input['end_date']}235959 "; }

//validate unit
if(!isset($_GET['unit']))
{
    $query = "select m.id, m.room_number as Room, concat(m.last_name,', ',m.first_name,' ',m.middle_initial,' ',m.rank,m.promotable) as Name,
               m.platoon as PL, s.Shift, a.Description, a.Location, upper(date_format(a.start,'%d%b%y %H:%i')) as Start,
               upper(date_format(a.end,'%d%b%y %H:%i')) as End, if(length(a.notes)>0,'Y','N') as N
               
               from main m left join student s on m.id = s.id, appointments a, company c, battalion b, user_permissions up
               where m.id = a.id and m.pcs = 0 and up.user_id = {$_SESSION['user_id']} and up.permission_id = 1 and
               m.company = up.company_id and m.battalion = up.battalion_id
               and m.company = c.company_id and m.battalion=b.battalion_id and {$report['where']}
               order by name, a.start desc";

    $report['unit'] = '';

    if(isset($_GET['start_date']))
    { $date = strtoupper($_GET['start_date']); }

}
elseif($unit = $val->unit($_GET['unit'],14))
{
    $battalion = $unit[0];
    $company = $unit[1];

    //default sql where values
    //$report["where"] = " 1 ";

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
    if(isset($_GET['shift']) && is_array($_GET['shift']))
    {
        foreach($_GET['shift'] as $shift)
        {
            if($valid_shift = $val->conf($shift,"shift"))
            { $valid_shifts[] = $valid_shift; }
        }

        //if any valid shifts were passed, add then to string to be
        //incorporated into sql query.
        if(count($valid_shifts) > 0)
        {
            $shift_string = "'" . implode("','",$valid_shifts) . "'";
            $report['where'] .= "and s.shift in ($shift_string) ";
        }
    }

    $date = strtoupper(date("dMY"));

    $query = "select m.id, m.room_number as Room, concat(m.last_name,', ',m.first_name,' ',m.middle_initial,' ',m.rank,m.promotable) as Name,
              m.platoon as PL, s.Shift, a.Description, a.Location, upper(date_format(a.start,'%d%b%y %H:%i')) as Start,
              upper(date_format(a.end,'%d%b%y %H:%i')) as End, if(length(a.notes>0),'Yes','No') as Notes,
              concat(c.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as Unit, x.htmlVal as Signature
              from main m, htmlVals x left join student s on m.id = s.id, appointments a, company c, battalion b where x.id=3 and m.id = a.id and m.pcs = 0
              and m.company = c.company_id and m.battalion=b.battalion_id and {$report['where']}
              order by name, a.start desc";
}

if(isset($query))
{
    $header =  "<strong>Appointment Report" . $report["unit"] . "  --- $date</strong>";

    $comp = implode(",",$_CONF['component']);
    $comp_abbr = "'" . implode("','",$_CONF['component_abbrev']) . "'";

    echo com_siteheader("Appointment Report" . $report["unit"] . "  --- $date");

    if(!isset($_REQUEST["export2"]))
    { echo "Export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>Excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>Word</a>\n"; }

    $roster = new roster($query);
    $roster->setheader($header);
    $roster->link_page("data_sheet.php");
    $roster->link_column(0);
    $roster->sethidecolumn(0);
    $roster->setReportName('appointmentreport');
	if (!isset($_REQUEST["export2"])) {
		$roster->allowUserOrderBy(TRUE);
	} else {
		$roster->allowUserOrderBy(FALSE);
	}
    echo $roster->drawroster();

}
else
{
    echo com_siteheader("invalid permissions - profile report");
    echo "invalid permissions.";
}

echo com_sitefooter();

?>