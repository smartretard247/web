<?
include("../lib-common.php");

include("../classes/validate.class.php");
include("../classes/roster.class.php");

$val = new validate;
$pt = array();

if(!isset($_GET['days']))
{ $days = 1; }
else
{ $days = (int)($_GET['days']); }

$report['where'] = '';
if(isset($_REQUEST['subject']) && $_REQUEST['subject'] != 'All')
{ 
    $s = (int)$_REQUEST['subject'];
    $report['where'] = " AND rs.remarks_subjects_id = $s "; 
}

$date = strtoupper(date("dMY"));

$header =  "<strong>New Remarks --- $date</strong>";

echo com_siteheader("New Remarks --- $date");

if(!isset($_REQUEST["export2"]))
{ echo "Export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>Excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>Word</a>\n"; }

$day_select = "<select name='days'>
               <option value='1'>1</option>
               <option value='2'>2</option>
               <option value='3'>3</option>
               <option value='4'>4</option>
               <option value='5'>5</option>
               <option value='6'>6</option>
               <option value='7'>7</option>
               <option value='30'>30</option>
               <option value='60'>60</option>
               <option value='90'>90</option>
               <option value='120'>120</option>
               <option value='150'>150</option>
               <option value='180'>180</option>
               </select>";

$subject_select = subject_select();
$subject_select = add_option($subject_select,'All');

echo "<form method=\"GET\" action=\"{$_CONF['html']}/reports/new_remarks_report.php\">Show all remarks from the past $day_select 
      days with subject $subject_select <input type=\"submit\" value=\"Go\" class=\"button\"></form>";
                     
$query = "select m.id,if(r.restricted=1,'%%%','') as R,
    concat(m.rank, ' ',m.last_name, ', ',m.first_name,' ',m.middle_initial) as Name, rs.Subject, REPLACE(r.Remark,'\n','<br />') as Remark,
    upper(date_format(r.time,'%d%b%y %T')) as Time, concat(c.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as Unit,
    concat(left(m2.first_name,1), left(m2.middle_initial,1), left(m2.last_name,1)) as 'By'
    from main m, remarks r, remarks_subjects rs, main m2, user_permissions up, company c, battalion b
    where m.battalion = up.battalion_id and m.company = up.company_id and up.user_id = {$_SESSION['user_id']}
    and ((up.permission_id = 19 and r.restricted=0) or (up.permission_id = 32 and r.restricted=1))
    and m.id = r.id and r.subject = rs.remarks_subjects_id and r.entered_by = m2.id
    and to_days(now()) - to_days(r.time) between 0 and $days and m.battalion = b.battalion_id and
    m.company = c.company_id {$report['where']} order by r.time desc";

    $roster = new roster($query);
    $roster->setheader($header);
    $roster->link_page("data_sheet.php");
    $roster->link_column(0);
    $roster->sethidecolumn(0);
    $roster->setReportName('newremarksreport');
	$roster->allowUserOrderBy(TRUE);
    $data = $roster->drawroster();
    
    $formatted_data = str_replace("<td>%%%</td>","<td bgcolor=\"red\">X</td>",$data);
    
    echo $formatted_data;

echo com_sitefooter();

?>
