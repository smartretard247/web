<?
include("../lib-common.php");

include("../classes/validate.class.php");
include("../classes/roster.class.php");

$val = new validate;

$comp = implode(",",$_CONF['component']);
$comp_abbr = "'" . implode("','",$_CONF['component_abbrev']) . "'";

if(isset($_GET['mode']))
{
    if($_GET['mode'] == 'next_month')
    { 
        $report['where'] = " month(m.dental_date) = month(curdate() + interval 1 month) and year(m.dental_date) = year(curdate() + interval 1 month) "; 
        $date = date('F, Y',mktime(0,0,0,date('m')+1,10,date('Y')));
    }
    elseif($_GET['mode'] == 'overdue')
    {
        $report['where'] = " m.dental_date < curdate() ";
        $header = "Personnel Overdue Dental Exam";
    }
    else
    { 
        $report['where'] = " month(curdate()) = month(m.dental_date) and year(curdate()) = year(m.dental_date) "; 
        $date = date('F, Y',time());
    }
    
    if(!isset($header))
    { $header = "<strong>Personnel Due Dental Exam in $date</strong>"; }
    
    echo com_siteheader("Due Dental Exam");
    
    $query = "select m.id, m.Last_Name, m.First_Name, m.middle_initial as MI, concat(m.rank,m.promotable) as Rank,
              right(m.ssn,4) as SSN, m.Dental_category as Cat, upper(date_format(m.dental_date,'%d%b%y')) as Next_Exam_Due, 
              m.Gender, m.platoon as PLT, m.pers_type,
              elt(find_in_set(m.component,'$comp'),$comp_abbr) as Comp,
              concat(co.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as Unit 
              from main m, battalion b, company co, user_permissions up
              where m.pcs = 0 and m.battalion = b.battalion_id and m.company = co.company_id
              and m.battalion = up.battalion_id and m.company = up.company_id and up.permission_id = 11
              and up.user_id = {$_SESSION['user_id']} 
              and " . $report["where"] . " order by m.last_name, m.first_name, m.middle_initial, m.ssn";
}
//validate unit
elseif($unit = $val->unit($_GET['unit'],14))
{
    $battalion = $unit[0];
    $company = $unit[1];

    //default sql where values
    $report["where"] = " 1 ";

    //loop through and verify each dental category passed if it's an array
    if(isset($_GET['dental_category']) && is_array($_GET['dental_category']))
    {
        $valid_dc = array();
        foreach($_GET['dental_category'] as $cat)
        {
            if($val_cat = $val->conf($cat,'dental_category'))
            { $valid_dc[] = $val_cat; }
        }

        //if any valid dental categories were passed, then
        //add them to SQL where clause
        if(count($valid_dc) > 0)
        {
            $dc_string = "'" . implode("','",$valid_dc) . "'";
            $report['where'] .= " and m.dental_category IN ($dc_string) ";
        }
    }

    //loop through and verify each platoon passed if it's in an array
    if(isset($_GET['platoon']) && is_array($_GET['platoon']))
    {
        $valid_platoons = array();
        foreach($_GET['platoon'] as $platoon)
        {
            if($valid_platoon = $val->conf($platoon,"platoon"))
            { $valid_platoons[] = $valid_platoon; }
        }

        //if any valid platoons were passed, add then to string to be
        //incorporated into sql query.
        if(count($valid_platoons) > 0)
        {
          $platoon_string = "'" . implode("','",$valid_platoons) . "'";
          $report['where'] .= "and m.platoon in ($platoon_string) ";
        }
    }

    //loop through and verify each shift passed if it's in an array
    if(isset($_GET['shift']) && is_array($_GET['shift']))
    {
        $valid_shifts = array();
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

    if(count($_GET['pers_type']) > 0)
    {
        $pt = array();
        //validate each of the personal types selected
        foreach($_GET['pers_type'] as $pers_type)
        {
            //$pt[] will contain all only valid personnel types
            if($val->conf($pers_type,"pers_type"))
            { $pt[] = $pers_type; }
        }

        if(count($pt) > 0)
        {
            //create string of the chosen pers_types to use in query later
            $pt_string = "'" . implode("','",$pt) . "'";
            $report['where'] .= "and m.pers_type in ($pt_string) ";
        }
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

    $header =  "<strong>Dental Roster, " . $report['unit'] . " --- $date</strong>";
    
    echo com_siteheader("Dental Roster, " . $report['unit'] . " --- $date");

    $query = "select m.id, m.Last_Name, m.First_Name, m.middle_initial as MI, concat(m.rank,m.promotable) as Rank,
        right(m.ssn,4) as SSN, m.Dental_category as Cat, upper(date_format(m.dental_date,'%d%b%y')) as Next_Exam_Due, 
        m.Gender, m.platoon as PLT, m.pers_type,
        elt(find_in_set(m.component,'$comp'),$comp_abbr) as Comp,
        concat(co.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as Unit 
        from main m, battalion b, company co
        where m.pcs = 0 and m.battalion = b.battalion_id and m.company = co.company_id
        and " . $report["where"] . " order by m.last_name, m.first_name, m.middle_initial, m.ssn";

}
else
{ 
    echo com_siteheader("Invalid Permissions");
    echo "Invalid Permissions";
    echo com_sitefooter();
    exit();
}
    
if(!isset($_REQUEST["export2"]))
{ echo "export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>word</a>\n"; }

$roster = new roster($query);
$roster->setheader($header);
$roster->link_page("data_sheet.php");
$roster->link_column(0);
$roster->sethidecolumn(0);
$roster->setReportName('dentalroster');
$roster->allowUserOrderBy(TRUE);
echo $roster->drawroster();

echo com_sitefooter();

?>