<?
include('../lib-common.php');
include($_CONF['path'] . 'classes/validate.class.php');
include($_CONF['path'] . 'classes/roster.class.php');
include($_CONF['path'] . 'smarty/Smarty.class.php');

//Default variables
$error_flag = 0;
$show['mass_input'] = TRUE;
$show['mass_results'] = FALSE;
$show['issue_detail_report'] = FALSE;
$show['search'] = TRUE;

$val = new Validate;
$smarty = new Smarty;
$smarty->template_dir = $_CONF['path'] . 'smarty/templates';
$smarty->compile_dir = $_CONF['path'] . 'smarty/templates_c';

//Site Header
echo com_siteheader("S2 - Security");

/*****************
* PROCESS SEARCH *
*****************/
//determine if search has been submitted
if(isset($_GET["locate_text"]) && strlen($_GET["locate_text"]) > 0)
{
    //see if search text is all numbers (ssn)
    if(ereg("^[0-9]+$",$_GET["locate_text"]))
    {
        //if length is four, create sql to match last four of ssn
        if(strlen($_GET["locate_text"]) == 4)
        { $criteria[] = "right(m.ssn,4) = " . $_GET["locate_text"]; }
        //else if length is 9, create sql to match entire ssn
        elseif(strlen($_GET["locate_text"]) == 9)
        { $criteria[] = "m.ssn = " . $_GET["locate_text"]; }
        //if length is not 4 or 9, number is not valid. create error
        else
        { $val->error[] = "invalid social security number entered"; }
    }
    //see if search text matches name
    elseif(eregi("^([a-z]{0,1}(\\\')?[a-z]+)[,]?[ ]?([a-z]+)?$",$_GET["locate_text"],$match))
    {
        $criteria[] = "m.last_name like '" . $match[1] . "%'";

        //if first name was given, create sql to match first part
        if(strlen($match[3]) > 0)
        { $criteria[] = "m.first_name like '" . $match[3] . "%'"; }
    }
    //if search text does not match ssn or name, set error
    else
    { $val->error[] = "bad search text, please enter again"; }

    //Display errors or list of results matching criteria
    if($val->iserrors())
    { echo $val->geterrors(); }
    else
    {
        $crit = implode(" AND ",$criteria);
        $query = "SELECT m.id, m.Last_Name, m.First_Name, m.Middle_Initial AS MI, m.SSN,
                m.Rank, s2.clearance_status, m.Gender, concat(c.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as Unit
                FROM main m LEFT JOIN s2 on m.id = s2.id, company c, battalion b, user_permissions up WHERE m.battalion = b.battalion_id and m.company = c.company_id
                and up.permission_id = 30 and up.battalion_id = m.battalion and up.company_id = m.company
                and up.user_id = {$_SESSION['user_id']} and $crit";
        $roster = new roster($query);
        $roster->setheader('Locate Results');
        $roster->link_page("s2/index.php");
        $roster->link_column(0);
        $roster->sethidecolumn(0);
        $locate_results = $roster->drawroster();
        //If only one row matches criteria, show
        //data sheet for that soldier, otherwise
        //show a list of matches
        if($roster->query_rows == 1)
        {
            $show['info'] = TRUE;
            $_REQUEST['id'] = $roster->query_id;
            unset($locate_results);
        }
        else
        { $show['info'] = FALSE; }
    }
}

/***************************
* PROCESS NEW SOLDIER INFO *
***************************/
//Process data sheet of soldier
elseif(isset($_POST['submit']))
{
    //Validate user data
    $input['id'] = $val->id($_POST['id'],30);
    $input['clearance_status'] = $val->conf($_POST['clearance_status'],'clearance_status','Clearance Status');
    $input['derog_issue'] = $val->conf($_POST['derog_issue'],'derog_issue','Derog Issue');

    $allow_empty_status_date = ($input['clearance_status'] == 'Resubmit' || $input['clearance_status'] == 'Prenom') ? 0 : 1;
    $input['status_date'] = $val->check('date',$_POST['status_date'],'Status Date',$allow_empty_status_date);

    $input['remark'] = htmlentities($val->check('string',$_POST['remark'],'Remark',1));
    $input['issue_detail'] = htmlentities($val->check('string',$_POST['issue_detail'],'Issue Detail',1));

    if(isset($_POST['meps']))
    { $input['meps'] = $val->conf($_POST['meps'],'meps','MEPS'); }

    //Display errors or process changes
    if($val->iserrors())
    {
        $error = $val->geterrors();
        $error_flag = 1;
    }
    else
    {
        //Determine if an INSERT or UPDATE is required
        $result = mysql_query("select clearance_status, status_date+0 from s2 where id = {$input['id']}");
        if($prev_row = mysql_fetch_assoc($result))
        {
            $query = "update s2 set clearance_status='{$input['clearance_status']}', derog_issue = '{$input['derog_issue']}',
                    status_date = '{$input['status_date']}', remark = '{$input['remark']}', issue_detail = '{$input['issue_detail']}'
                    where id = {$input['id']}";
        }
        else
        {
            $query = "insert into s2 (id, clearance_status, derog_issue, status_date, remark, issue_detail) values
                ({$input['id']},'{$input['clearance_status']}','{$input['derog_issue']}',
                '{$input['status_date']}','{$input['remark']}','{$input['issue_detail']}')";
        }

        $result = mysql_query($query);
        if($e = mysql_error())
        {
            $error = "Error inserting/updating S2 data: " . $e;
            $error_flag = 1;
        }

        //Insert record of new data into s2_history table
        //if different from last statuses
        $query = "SELECT clearance_status, derog_issue, issue_detail FROM s2_history WHERE id = {$input['id']}
                  ORDER BY status_date DESC LIMIT 1";
        $result = mysql_query($query) or die('Unable to get last S2 status: ' . mysql_error());
        $row = mysql_fetch_assoc($result);

        if(empty($row) || $row['clearance_status'] != $input['clearance_status'] || $row['derog_issue'] != $input['derog_issue'] || $row['issue_detail'] != $input['issue_detail'])
        {
            $query = "INSERT INTO s2_history (id, clearance_status, derog_issue, issue_detail) VALUES
                      ({$input['id']},'{$input['clearance_status']}','{$input['derog_issue']}','{$input['issue_detail']}')";
            $result = mysql_query($query);
        }

        if(isset($input['meps']))
        {
            $query = "update student set meps = '{$input['meps']}' where id = {$input['id']}";
            $result = mysql_query($query);
        }

        //If Resubmit or Prenom was chosen, take Status Date and make/update appointment for soldier
        if($input['clearance_status'] == 'Resubmit' || $input['clearance_status'] == 'Prenom')
        {
            $query = '';
            $start = $input['status_date'] . '081500';
            $end = $input['status_date'] . '160000';
            $query = "select apt_id, start+0 as s from appointments where id = {$input['id']} and
                      description = '{$input['clearance_status']}' and location='BDE Security (Darling Hall Rm. 307)'";
            $result = mysql_query($query) or die("Error checking for appointment: " . mysql_error());
            if($row = mysql_fetch_assoc($result))
            {
                if($row['s'] != $start)
                { $query = "UPDATE appointments SET start = '$start', end = '$end', description = '{$input['clearance_status']}' WHERE apt_id = {$row['apt_id']}"; }
            }
            else
            { $query = "INSERT INTO appointments (id, description, location, start, end) VALUES ({$input['id']},'{$input['clearance_status']}',
                        'BDE Security (Darling Hall Rm. 307)','$start','$end')";
            }
            if($query)
            {
                $result = mysql_query($query) or die("Error setting appointment: " . mysql_error());
                $message = "{$input['clearance_status']} appointment made for soldier.";
            }
        }
        //If status was changed _from_ Resubmit or Prenom, then delete old appointment date
        elseif($prev_row['clearance_status'] == 'Resubmit' || $prev_row['clearance_status'] == 'Prenom')
        {
            $query = "DELETE FROM appointments WHERE id = {$input['id']} and description = '{$prev_row['clearance_status']}'
                      and location = 'BDE Security (Darling Hall Rm. 307)'";
            $result = mysql_query($query) or die("Error deleting old appointment: " . mysql_error());
            if(mysql_affected_rows())
            { $message = "Old {$prev_row['clearance_status']} appointment deleted for soldier."; }
        }

    }
}

/*********************
* PROCESS MASS INPUT *
*********************/
//Process MASS INSERT of data
elseif(isset($_POST['data']))
{
    function upper(&$element,$key)
    { $element = strtoupper($element); }

    $clearance_types = $_CONF['clearance_status'];
    $derog_issue = $_CONF['derog_issue'];

    //Convert "clearance types" to all UPPER case to simplify matching
    array_walk($clearance_types,'upper');
    array_walk($derog_issue,'upper');

    //Match Name, SSN, and Status from data
    $data = preg_replace('/O+([0-9])/','0$1',$_POST['data']);
    preg_match_all('/(.*)\t([0-9-]{9,11})\t([a-z \/-]+)/i',$data,$match);

    /***
    $match
    [1] = Name
    [2] = SSN
    [3] = Clearance Status
    ***/

    //Loop through matched data and process INSERTS or UPDATEs
    //Keeps track of names that are Successfull, names that are
    //not in USAP but in the data, and names that have bad
    //status text (not matching something within $clearance_types
    $cnt = count($match[1]);
    for($x=0;$x<$cnt;$x++)
    {
        $derog = TRUE;
        if(substr($match[3][$x],0,5) == 'DEROG')
        {
            //Get current derog issue, stripping any non-letter characters
            $di = preg_replace('/[^A-Z]/i','',substr($match[3][$x],5));

            //If current derog issue was blank, set issue to None
            //otherwise look for matching key in $derog_issue array
            if(empty($di))
            { $key = 0; }
            else
            { $key = array_search($di,$derog_issue); }

            if($key !== FALSE)
            {
                $match['derog_issue'] = $_CONF['derog_issue'][$key];
                $match[3][$x] = 'DEROG';
            }
            else
            { $derog = FALSE; }
        }
        else
        { $match['derog_issue'] = 'None'; }

        $key = array_search(trim($match[3][$x]),$clearance_types);
        if($key !== FALSE && $derog)
        {
            $ssn = str_replace('-','',$match[2][$x]);
            $result = mysql_query("SELECT id FROM main WHERE ssn = $ssn");
            if(mysql_num_rows($result))
            {
                $id = mysql_result($result,0);
                $result2 = mysql_query("SELECT 1 FROM s2 WHERE id = $id");
                if(mysql_num_rows($result2))
                { $query = "UPDATE s2 SET clearance_status = '{$_CONF['clearance_status'][$key]}', derog_issue = '{$match['derog_issue']}' WHERE id = $id"; }
                else
                { $query = "INSERT INTO s2 (id,clearance_status,derog_issue) VALUES ($id,'{$_CONF['clearance_status'][$key]}','{$match['derog_issue']}')"; }
                $result = mysql_query($query);
                if($e = mysql_error())
                { $report['error'][] = $e; }
                else
                { $report['successful'][] = $match[1][$x]; }
            }
            else
            { $report['not_in_usap'][] = $match[1][$x]; }
        }
        else
        { $report['bad_status'][] = $match[1][$x] . "-" . $match[3][$x]; }
    }
    @sort($report['successful']);
    @sort($report['not_in_usap']);
    @sort($report['bad_status']);
    @sort($report['error']);

    $show['mass_results'] = TRUE;
    $show['mass_input'] = FALSE;
}

/*****************************
* CREATE ISSUE DETAIL REPORT *
*****************************/
//Create list of soldiers who have any entry
//in the Issue Detail column
elseif(isset($_GET['issue_detail_submit']))
{
    $show['mass_input'] = FALSE;
    $show['issue_detail_report'] = TRUE;
    if(isset($_REQUEST['export2']))
    { $show['search'] = FALSE; }

    $query = "SELECT m.id, m.Last_Name, m.First_Name, m.Middle_Initial AS MI, m.SSN,
            m.Rank, m.MOS, m.Gender, upper(date_format(m.arrival_date,'%d%b%y')) as Ar_Date, s.Basic_Training_Post as BCT_Post, s.MEPS,
            s2.clearance_status, s2.derog_issue, s2.issue_detail,
            concat(c.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as Unit
            FROM main m LEFT JOIN student s on m.id = s.id LEFT JOIN s2 on m.id = s2.id,
            company c, battalion b, user_permissions up WHERE m.battalion = b.battalion_id and m.company = c.company_id
            and up.permission_id = 30 and up.battalion_id = m.battalion and up.company_id = m.company
            and up.user_id = {$_SESSION['user_id']} and length(s2.issue_detail)>0";
    $roster = new roster($query);
    $roster->setheader('Issue Detail Report');
    $roster->link_page("s2/index.php");
    $roster->link_column(0);
    $roster->sethidecolumn(0);
    $issue_detail_report = $roster->drawroster();

    $smarty->assign('issue_detail_report',$issue_detail_report);
}

/********************
* SHOW SOLDIER DATA *
********************/
//Show data sheet of soldier if "ID" is passed to page.
if(isset($_REQUEST['id']))
{
    $id = (int)$_REQUEST['id'];
    $query = "SELECT m.id,m.last_name, m.first_name, m.middle_initial AS mi, m.ssn,
            m.mos, m.rank, m.component, upper(date_format(m.arrival_date,'%d%b%y')) as arrival_date,
            to_days(curdate()) - to_days(arrival_date) as days, st.status AS inactive_status,
            concat(c.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as unit, m.pers_type,
            s.clearance_status, s.derog_issue, upper(date_format(s.status_date,'%d%b%y')) as status_date, s.remark, s.issue_detail, m.pcs
            FROM main m left join s2 s on m.id = s.id left join status st on m.inact_status = st.status_id, battalion b, company c, user_permissions up
            WHERE m.battalion = b.battalion_id and m.company = c.company_id
            and m.id = $id and m.battalion = up.battalion_id and m.company = up.company_id
            and up.user_id = {$_SESSION['user_id']} and up.permission_id = 30";

    $result = mysql_query($query) or die("Error selecting information: " . mysql_error());
    if($row = mysql_fetch_assoc($result))
    {
        if($error_flag == 1)
        { $row = array_merge($row,$_GET); }

        //Show block to insert MEPS station if soldier is IET or Non-IET
        if($row['pers_type'] == 'IET' || $row['pers_type'] == 'Non-IET')
        {
            $meps_header = 'MEPS';

            $query = "select meps from student where id = $id";
            $result = mysql_query($query);
            if($row2 = mysql_fetch_assoc($result))
            { $meps_select = conf_select("meps",$row2['meps']); }
            else
            { $meps_select = conf_select('meps',''); }
        }
        else
        {
            $meps_select = '&nbsp;';
            $meps_header = '&nbsp;';
        }

        $smarty->assign('meps_header',$meps_header);
        $smarty->assign('meps_select',$meps_select);
        $smarty->assign('info',$row);
        $smarty->assign('clearance_status_select',conf_select('clearance_status',$row['clearance_status']));
        $smarty->assign('derog_issue_select',conf_select('derog_issue',$row['derog_issue']));
        $show['info'] = TRUE;
        $show['mass_input'] = FALSE;
    }
    else
    {
        $error = "User not found";
        $show['error'] = TRUE;
    }
}

if(!isset($_REQUEST["export2"]))
{
    $export_links = "Export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . @$_SERVER["QUERY_STRING"] . "&export2=excel'>Excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . @$_SERVER["QUERY_STRING"] . "&export2=word'>Word</a>\n";
    $smarty->assign_by_ref('export_links',$export_links);
}

if(isset($show))
{ $smarty->assign('show',$show); }

$smarty->assign('url',$_CONF['html']);

if(isset($locate_results))
{ $smarty->assign('locate_results',$locate_results); }

if(isset($error))
{ $smarty->assign_by_ref('error',$error); }
if(isset($message))
{ $smarty->assign_by_ref('message',$message); }

if(isset($report))
{ $smarty->assign_by_ref('report',$report); }

echo $smarty->fetch("s2_main.tpl");

echo com_sitefooter();

?>
