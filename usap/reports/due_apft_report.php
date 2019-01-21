<?
include("../lib-common.php");

include("../classes/validate.class.php");
include("../classes/roster.class.php");

$val = new validate;
$platoon = array();
$report['where'] = '';

$date = strtoupper(date("dMY"));

$header =  "<strong>Due APFT Report --- $date</strong>";

echo com_siteheader("Due APFT Report --- $date");

if(!isset($_REQUEST["export2"]))
{ echo "export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>word</a>\n"; }

$report['where'] = " and m.pers_type in ('" . implode("','",$_CONF['military']) . "') ";
$report['where'] .= " and m.pcs = 0 and up.user_id = {$_SESSION['user_id']} and m.battalion = up.battalion_id and 
                     m.company = up.company_id and up.permission_id = 15 ";
                     
$query = "create temporary table max_date (primary key(id), index(date)) select id, max(date) as date from apft group by id";
$result = mysql_query($query) or die(mysql_error());

$query = "create temporary table due_apft (primary key(id), index(date)) IGNORE select m.id, a.date, a.pass_fail from main m, apft a, max_date md
          where m.id = a.id and a.id = md.id and a.date = md.date and (a.date < now() - interval 6 month or a.pass_fail = 'fail')";
          
$result = mysql_query($query) or die(mysql_error());

$query = "insert into due_apft (id) select m.id from main m left join apft a on m.id = a.id where a.id is null";
$result = mysql_query($query) or die(mysql_error());

switch($_GET['mode'])
{
    case "pp":
        $pp = " and m.pers_type in ('" . implode("','",$_CONF['perm_party']) . "') ";
        $report['where'] .= $pp;
    break;
    
    case "s":
        $s = " and m.pers_type in ('" . implode("','",$_CONF['students']) . "') ";    
        $report['where'] .= $s;
    break;
    
    case "n":
        $report['where'] .= ' and da.date is null ';
    break;
}

$p_query = "create temporary table t (primary key(id)) select p.id, p.profile from profile p where (curdate() < profile_start + interval 
            LEAST(profile_length+90,profile_length*3) DAY) OR (left(profile,1)='p') group by id";
$result = mysql_query($p_query) or die('Error creating temp profile table: ' . mysql_error());

    //$roster = new roster("select * from t limit 5");
    //$roster->setheader($header);
    //$roster->link_page("data_sheet.php");
    //$roster->link_column(0);
    //$roster->sethidecolumn(0);
    //echo $roster->drawroster();

$query = "select m.id, m.Last_Name, m.First_Name, m.middle_initial as MI, m.Rank, right(m.ssn,4) as SSN, m.Gender,
          m.platoon as PL, upper(date_format(m.arrival_date,'%d%b%y')) as Ar_Date,
          upper(date_format(da.date,'%d%b%y')) as Last_APFT, upper(da.pass_fail) as 'P/F',
          if(t.id is null,'No',CONCAT('Yes - ',t.profile)) as Profile,
          concat(co.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as Unit
          from user_permissions up, main m left join t on m.id = t.id, due_apft da, company co, battalion b where m.id = da.id and 
          m.company = co.company_id and m.battalion = b.battalion_id {$report['where']}
          order by m.last_name, m.first_name, mi";

$roster = new roster($query);
$roster->setheader($header);
$roster->link_page("data_sheet.php");
$roster->link_column(0);
$roster->sethidecolumn(0);
$roster->setReportName('dueapftreport');
$roster->allowUserOrderBy(TRUE);
echo $roster->drawroster();

echo com_sitefooter();

?>
