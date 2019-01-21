<?
include("../lib-common.php");

include("../classes/validate.class.php");
include("../classes/roster.class.php");

//if($_SESSION['user_id'] != 1) { exit(); }

$val = new validate;
$pt = array();
$report['where'] = ' 1 ';
$a = FALSE;
$b = FALSE;

//validate unit
if( ($a = $val->unit($_GET['unit'],32)) || ($b = $val->unit($_GET['unit'],19)) )
{ 
    if($a)
    { $unit = $a; }
    else
    {
        $report['where'] .= ' and r.restricted = 0 '; 
        $unit = $b; 
    }
    
    $battalion = $unit[0];
    $company = $unit[1];
    
    if(!isset($_GET['days']))
    { $days = 1; }
    else
    { $days = (int)($_GET['days']); }

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
    
    if(isset($_GET['subject']) && $_GET['subject'] != 'All')
    {
        $subject = (int)$_GET['subject'];
        $report['where'] .= " and rs.remarks_subjects_id = $subject "; 
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

    $header =  "<strong>Remarks --- $date</strong>";

    echo com_siteheader("Remarks --- $date");

    if(!isset($_REQUEST["export2"]))
    { echo "Export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>Excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>Word</a>\n"; }

    $query = "select m.id,if(r.restricted=1,'%%%','') as R,
        concat(m.rank, ' ',m.last_name, ', ',m.first_name,' ',m.middle_initial) as Name, rs.Subject, REPLACE(r.Remark,'\n','<br />') as Remark,
        upper(date_format(r.time,'%d%b%y %T')) as Time, concat(c.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as Unit,
        concat(left(m2.first_name,1), left(m2.middle_initial,1), left(m2.last_name,1)) as 'By'
        from main m, remarks r, remarks_subjects rs, main m2, company c, battalion b
        where m.id = r.id and r.subject = rs.remarks_subjects_id and r.entered_by = m2.id
        and to_days(now()) - to_days(r.time) between 0 and $days and m.battalion = b.battalion_id and
        m.company = c.company_id and {$report['where']} order by r.time desc";

    $roster = new roster($query);
    $roster->setheader($header);
    $roster->link_page("data_sheet.php");
    $roster->link_column(0);
    $roster->sethidecolumn(0);
    $roster->setReportName('remarksreport');
	$roster->allowUserOrderBy(TRUE);
    $data = $roster->drawroster();

    $formatted_data = str_replace("<td>%%%</td>","<td bgcolor=\"red\">X</td>",$data);

    echo $formatted_data;
}
else
{
    echo com_siteheader("Invalid Permissions - Remarks Report");
    echo "Invalid Permissions";
}

echo com_sitefooter();

?>