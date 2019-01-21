<?

##############################################
#
# this file contains common functions that can
# be used on any page
#
# copyright 2002: U.S. Army
#
##############################################

//configuration data
require("config.php");

//database connection\
require($_CONF["path"] . "lib-database.php");

//security functions
require($_CONF["path"] . "lib-security.php");

//general functions
require($_CONF["path"] . "lib-custom.php");


set_time_limit(120);

//start session
session_start();
set_access_permission(10);

if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == 1)
{ error_reporting(E_ALL); }
else
{ error_reporting(0); }

//update time in database for user and delete any old times
$result1 = mysql_query("update users set last_access = now() where user_id = {$_SESSION['user_id']}");
$result2 = mysql_query("update users set last_access = 0 where last_access < now() - interval 5 minute");

if($_SESSION['battalion_id'] == 1)
{
    $image = 'ordnance.gif';
    $_CONF['up']['main_color'] = '#DC143C';
    $_CONF['up']['row_highlight_color'] = '#F9BFCB';
}
else
{ $image = 'signalflags.gif'; }


##############################################
#
# displays top of page and menu.
# sets default title if none passed
#
##############################################
function com_siteheader($title = "USAP - Unit Soldier Administration Program")
{
    global $_CONF;
    global $image;

    $retval = "";

    //see if an export to flag was set for this page:
    if(isset($_REQUEST["export2"]))
    {
        //determine which format the page is requested in and
        //send appropriate headers. return from the function here
        //and do not send any html headers.
        switch($_REQUEST["export2"])
        {
            case "excel":
                echo com_excelheader("USAP_Export_Report");
                return;
                break;
            case "word":
                echo com_wordheader("USAP_Export_Report");
                return;
                break;
            case "web":
            	echo "<html><head><title>Test</title></head>";
				return;
            	break;

        }
    }

    //used to duplicate query strings when debug picture is clicked
    $query_string = "";
    if(isset($_SERVER["QUERY_STRING"]))
    {
        if(substr($_SERVER["QUERY_STRING"],0,13) == "debug_mode=1&")
        { $query_string = substr($_SERVER["QUERY_STRING"],13); }
        else
        { $query_string = $_SERVER["QUERY_STRING"]; }
    }


    $qs = (isset($_SERVER['QUERY_STRING'])) ? '?' . $_SERVER['QUERY_STRING'] : '';
    $_SESSION["redirect_to"] = $_CONF['web'] . $_SERVER['SCRIPT_NAME'] . $qs;

    //html head
    $retval .="
<html>
<head><title>$title</title>
<meta http-equiv=\"content-type\" content=\"text/html; charset=iso-8859-1\">
<link rel=\"stylesheet\" href=\"{$_CONF['html']}/css.php\">
<script type=\"text/javascript\" src=\"include/gm.js\"></script>" . com_alert() . "
</head>
<body>
      <!-- html body -->
      <table border='0' cellspacing='1' width='100%'>
      <col width='10%'></col>
      <col width='90%'></col>
      <tr><td align='center'>
      <a href='{$_CONF['html']}/debug.php'><img width=118 height=50 src='{$_CONF['html']}/images/usapLogo.gif' align='absmiddle' border='0' alt='Click to enable Debugging Mode'></a>
      </td><td style=\"font-size: large; font-weight: bolder; text-align: center\">
      Welcome to the Unit Soldier Administration Program<br>
      Logged-in user: {$_SESSION['rank']} {$_SESSION['last_name']}, {$_SESSION['first_name']}
      </td></tr>
      <tr><td valign='top'>" . com_menu() . "</td><td valign='top'>\n<br>" . com_debug();

    return($retval);
}

##############################################
#
# creates headers to send ms excel file
#
##############################################
function com_excelheader($filename = "download")
{
    //ms excel headers
    header("content-type: application/vnd.ms-excel; name='excel'");
    header("content-disposition: attachment; filename=" . $filename . ".xls");
}

##############################################
#
# creates headers to send ms word file
#
##############################################
function com_wordheader($filename = "download")
{
    //ms word headers
    header("content-type: application/vnd.ms-word; name='word'");
    header("content-disposition: attachment; filename=" . $filename . ".doc");
}


##############################################
#
# closes table and html for page
#
##############################################
function com_sitefooter()
{
    global $_CONF;
    global $image;

    $retval = "";

    if(!isset($_REQUEST["export2"]))
    {
        $retval .="</td>
        <tr><td colspan='2'>&nbsp;</td></tr>
        <tr><td align='center'>
        <img src='{$_CONF['html']}/images/$image' align='absmiddle' border='0' onclick=\"alert(document.cookie);\">
        </td><td align='left'>
        <hr width='100%'>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&copy; 2001 U.S. Army, All Rights Reserved
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;>> <a href='{$_CONF['html']}/priv.php'>Privacy and Security</a> <<
        </td></tr>
        </table></body></html>";
    }

    mysql_close();

    return($retval);
}

##############################################
#
# displays select boxes to choose soldier
# for data, apft, and remarks.
#
# this function will create html forms that
# submit to the same page that is calling this
# function. a $_GET["id"] variable
# will contain the id of the selected soldier and a
# $_GET["com_cs_action"] will contain either
# "view" or "edit", depending
# on the option chosen by the viewer.
#
##############################################
function com_choosesoldier($action = "data")
{
    global $_CONF;

    //do not show anything if export was clicked
    if(isset($_REQUEST["export2"])) { return; }

    //define variables
    $last = "last_name, first_name, ssn";
    $ssn = "ssn, last_name, first_name";
    $allow_view = false;
    $allow_edit = false;
    $allow_add = false;
    $allow_full_ssn = 0;
    $retval = "";

    //if go was selected in choosesoldier
    //id will be set to "select". if that is detected
    //unset the passed variables;
    if(isset($_GET["id"]) && $_GET["id"] == "select")
    { unset($_GET["id"]); }

    //set the sorting parameters for the select boxes
    //if one was chosen, save it in cookie for use
    //the next time the page is accessed, otherwise
    //set a default in the cookie if there's not a
    //value there already.
    if(isset($_REQUEST["com_cs_view_sort"]))
    { $_COOKIE["view_sort"] = $_REQUEST["com_cs_view_sort"]; }
    elseif(!isset($_COOKIE["view_sort"]))
    { $_COOKIE["view_sort"] = $last; }
    setcookie("view_sort",$_COOKIE["view_sort"],time()+604800);

    //set sorting parameters for edit select box
    if(isset($_REQUEST["com_cs_edit_sort"]))
    { $_COOKIE["edit_sort"] = $_REQUEST["com_cs_edit_sort"]; }
    elseif(!isset($_COOKIE["edit_sort"]))
    { $_COOKIE["edit_sort"] = $last; }
    setcookie("edit_sort",$_COOKIE["edit_sort"],time()+604800);

    //if(check_permission(12))
    //{ $allow_full_ssn = 1; }

    //set variables for possible actions of page
    switch($action)
    {
        case "data":
            //data sheet information
            $add_permission = 1;
            $view_permission = 11;
            $edit_permission = 2;
            $label = "DATA SHEET";
            $new_page = "add_soldier.php";
        break;

        case "apft":
            //apft page information
            $add_permission = 7;
            $view_permission = 15;
            $edit_permission = 8;
            $label = "APFT INFO";
            $new_page = "add_apft.php";
        break;

        case "remarks":
            //remarks page information
            $add_permission = 16;
            $view_permission = 19;
            $edit_permission = 17;
            $label = "REMARKS";
            $new_page = "add_remark.php";
        break;

        default:
            //if action passed does not match
            //any value, return out of function
            //nothing will be displayed
            $retval = "incorrect action for com_choosesoldier";
            return($retval);
        break;
    }

    //if id is sent to this page, tack it onto end of $new_page, also
    if(isset($_REQUEST['id']))
    { $new_page .= "?id=" . (int)$_REQUEST['id']; }

    //see if user has permission to add
    if(check_permission($add_permission)) { $allow_add = true; }
    if(check_permission($view_permission)) { $allow_view = true; }
    if(check_permission($edit_permission)) { $allow_edit = true; }

    //see if user has permission to view
    //if rows are returned, user has permission
    $view_query = "select m.id, m.last_name, m.first_name, right(m.ssn,4) as ssn from main m, user_permissions up where m.pcs = 0 and m.battalion = up.battalion_id and m.company = up.company_id and up.user_id = " . $_SESSION["user_id"] . " and up.permission_id = " . $view_permission . " order by " . $_COOKIE["view_sort"];
    $view_result = mysql_query($view_query) or die("view select error [$view_query]: " . mysql_error());
    //if(mysql_num_rows($view_result) > 0) { $allow_view = true; }

    //see if user has permission to edit
    //if rows are returned, user has permissions
    $edit_query = "select m.id, m.last_name, m.first_name, right(m.ssn,4) as ssn from main m, user_permissions up where m.pcs = 0 and m.battalion = up.battalion_id and m.company = up.company_id and up.user_id = " . $_SESSION["user_id"] . " and up.permission_id = " . $edit_permission . " order by " . $_COOKIE["edit_sort"];
    $edit_result = mysql_query($edit_query) or die("edit select error [$edit_query]: " . mysql_error());
    //if(mysql_num_rows($edit_result) > 0) { $allow_edit = true; }

    //ensure use has one of these permissions, at least.
    if($allow_view || $allow_edit || $allow_add)
    {
        $retval .='
        <table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
         <tr>
              <td width="40%">
               <div align="center"><b>View</b></div>
              </td>
              <td width="20%" class="table_cheading">
               ' . $label . '
              </td>
              <td width="40%">
               <div align="center"><b>Edit</b></div>
              </td>
         </tr>
         <tr>
              <td width="40%" align="center">';

        if($allow_view)
        {
            $retval .="<form method='get' action='" . $_SERVER["SCRIPT_NAME"] . "'>\n";
            $retval .="<input type='hidden' name='com_cs_action' value='view'>\n";
            $retval .="<select name='id' onchange='submit();' class='text_box'>\n";
            $retval .="<option value='select' selected>Select a name</option>\n";
            while($view_row = mysql_fetch_array($view_result))
            {
                if(isset($_REQUEST['id']) && $_REQUEST['id'] == $view_row['id'])
                { $selected = " selected"; }
                else
                { $selected = ""; }

                if($_COOKIE["view_sort"] == $ssn)
                { $retval .="<option value='" . $view_row["id"] . "'$selected>" . $view_row["ssn"] . " - " . $view_row["last_name"] . ", " . $view_row["first_name"] . "</option>\n"; }
                else
                { $retval .="<option value='" . $view_row["id"] . "'$selected>" . $view_row["last_name"] . ", " . $view_row["first_name"] . " - " . $view_row["ssn"] . "</option>\n"; }

                //$selected = "";
            }
            $retval .="</select>\n";
                $retval .="<input type='submit' class='button' value='Go' class='button'>\n";
                $retval .="</form>\n";
        }
        else
        {
            $retval .="You are not authorized to view.\n";
        }
        $retval .='
          </td>
              <td width="20%">&nbsp;</td>
              <td width="40%" align="center">';

        //create select box filled with soldiers
        //that user has access to edit
        if($allow_edit)
        {
            $retval .="<form method='get' action='" . $_SERVER["SCRIPT_NAME"] . "'>\n";
            $retval .="<input type='hidden' name='com_cs_action' value='edit'>\n";
            $retval .="<select name='id' onchange='submit();' class='text_box'>\n";
                $retval .="<option value='select' selected>Select a name</option>\n";
            while($edit_row = mysql_fetch_array($edit_result))
            {
                if(isset($_REQUEST['id']) && $_REQUEST['id'] == $edit_row['id'])
                { $selected = " selected"; }
                else
                { $selected = ''; }

                if($_COOKIE["edit_sort"] == $ssn)
                { $retval .="<option value='" . $edit_row["id"] . "'$selected>" . $edit_row["ssn"] . " - " . $edit_row["last_name"] . ", " . $edit_row["first_name"] . "</option>\n"; }
                else
                { $retval .="<option value='" . $edit_row["id"] . "'$selected>" . $edit_row["last_name"] . ", " . $edit_row["first_name"] . " - " . $edit_row["ssn"] . "</option>\n"; }

                $selected = "";
            }
            $retval .="</select>\n";
            $retval .="<input type='submit' class='button' value='Go' class='button'>\n";
            $retval .="</form>\n";
        }
        else
        { $retval .="You are not authorized to edit.\n";}

        $retval .='
          </td>
         </tr>
             <tr>
              <td width="40%">';

        $retval .="<form method='get' action='" . $_SERVER["SCRIPT_NAME"] . "'>\n";

        if($allow_view)
        {
            $retval .='<div align="center"><font size="2">Sort by ';
            $retval .='<input type="radio" name="com_cs_view_sort" value="' . $last . '"';
                if($_COOKIE["view_sort"] == $last) { $retval .=" checked "; }
                $retval .='> Last Name ';
                $retval .='<input type="radio" name="com_cs_view_sort" value="' . $ssn . '"';
                if($_COOKIE["view_sort"] == $ssn) { $retval .=" checked "; }
                $retval .='> SSN ';
            $retval .='<input type="submit" value="Sort" class="button">';
            $retval .='</font></div>';
        }
        else
        {
            $retval .="&nbsp;";
        }
        $retval .='
          </td>
          <td width="20%"> ';
        if($allow_add)
        {
		    $img_user_edit   = "<img src='images/icons/user-add.png' width='24' border='0' align='middle' title=' Add User ' onClick=\"document.location.href='{$_CONF["html"]}/$new_page'\">";
            $retval .="<div align='center'>$img_user_edit</div>";

            //$retval .='<div align="center"><a href="' . $_CONF["html"] . '/' . $new_page . '">Add New</a></div>';
        }
        else
        {
            $retval .="&nbsp;";
        }

        $retval .='</td>';

        if($allow_edit)
        {
            $retval .='<td width="40%"> ';
            $retval .='<div align="center"><font size="2">Sort by ';
            $retval .='<input type="radio" name="com_cs_edit_sort" value="' . $last . '"';
            if($_COOKIE["edit_sort"] == $last) { $retval .=" checked "; }
                $retval .='>Last Name ';
                $retval .='<input type="radio" name="com_cs_edit_sort" value="' . $ssn . '"';
                if($_COOKIE["edit_sort"] == $ssn) { $retval .=" checked "; }
                $retval .='>SSN ';
                $retval .='<input type="submit" value="Sort" class="button">';
                $retval .='</font></div>';
        }
        else
        {
            $retval .="&nbsp;";
        }

        $retval .='
        </td>
            </tr>
            </form>
        </table>
        <hr width = "80%">';
    }

    return($retval);
}

##############################################
#
# displays a table containing all get, post,
# and cookie variables for debugging purposes
#
##############################################
function com_debug()
{
    global $_CONF;
    $retval = '';

    if($_CONF["debug_mode"] == "on" || isset($_SESSION['debug_mode']))
    {
        //this function uses output buffering to
        //capture output, because print_r() prints
        //directly to the screen.
        ob_start();
        echo "<table width='80%' border='2' align='center'>\n";
        echo "<tr><td>\n";
        echo "<b>Debugging Information:</b><br>\n";
        echo "get:";
        print_r($_GET);
        echo "\n<br>----<br>\n";
        echo "post:";
        print_r($_POST);
        echo "\n<br>----<br>\n";
        echo "cookie:";
        print_r($_COOKIE);
        echo "\n<br>----<br>\n";
        echo "request:";
        print_r($_REQUEST);
        echo "\n<br>----<br>\n";
        echo "session:";
        print_r($_SESSION);
        echo "</td></tr>\n";
        echo "</table>\n";

        //get contents of buffer into variable
        $retval = ob_get_contents();
        //clear buffer
        ob_end_clean();

    }

    return($retval);
}

##############################################
#
# displays menu based on users permissions
#
##############################################
function com_menu()
{
    global $_CONF;

    $retval = "";

    $retval .='
    <br>
    <table width="100%" border="1" cellspacing="0" cellpadding="1">
      <tr class="table_cheading">
        <td>Menu</td>
      </tr>
      <tr>
        <td>
          <font size="-1">
          <strong>&loz;&nbsp;<a href="' . $_CONF['html'] . '/main.php?notificationsOff=1">Main</a><br>
          <strong>&loz;&nbsp;<a href="' . $_CONF['html'] . '/help.php">Help</a><br>
          <strong>&loz;&nbsp;<a href="' . $_CONF['html'] . '/fpass.php">Change Password</a><br>
          <strong>&loz;&nbsp;<a href="' . $_CONF['html'] . '/cinfo.php">Company Info</a><br>		  
          &loz;&nbsp;Soldier Info</strong><br>';

    if(check_permission(1,2,3,11))
    { $retval .='</strong>&nbsp;&ang;&nbsp;<a href="' . $_CONF["html"] . '/data_sheet.php">Data Sheet</a><br>'; }

    if(check_permission(7,8,9,15))
    { $retval .='&nbsp;&ang;&nbsp;<a href="' . $_CONF["html"] . '/apft.php">APFT</a><br>'; }

    if(check_permission(16,17,18,19))
    { $retval .='&nbsp;&ang;&nbsp;<a href="' . $_CONF["html"] . '/remarks.php">Remarks</a><br>'; }

    if(check_permission(2))
    { $retval .='&nbsp;&ang;&nbsp;<a href="' . $_CONF["html"] . '/edit_group.php">Group Edit</a><br>'; }

    if(check_permission(1))
    { $retval .='&nbsp;&ang;&nbsp;<a href="' . $_CONF["html"] . '/add_soldier.php">Add New Soldier</a><br>'; }

    if(check_permission(28))
    { $retval .= '&nbsp;&ang;&nbsp;<a href="' . $_CONF['html'] . '/add_special.php">Add Special</a><br>'; }

    if(check_permission(4,5,6,13))
    { $retval .='<strong>&loz;&nbsp;<a href="' . $_CONF["html"] . '/class.php">Class Info</a></strong><br>'; }

	if(check_permission(4,5,6,13))
    { $retval .='<strong>&loz;&nbsp;<a href="' . $_CONF["html"] . '/formations.php">Formations</a></strong><br>'; 
      $retval .='<strong>&loz;&nbsp;<a href="' . $_CONF["html"] . '/ftr_tracker.php">FTR Management</a></strong><br>'; 
	}

    if(check_permission(29))
    { $retval .='<strong>&loz;&nbsp;<a href="' . $_CONF['html'] . '/drivers/index.php">Master Driver</a></strong><br>'; }

    if(check_permission(30))
    { $retval .='<strong>&loz;&nbsp;<a href="' . $_CONF['html'] . '/s2/index.php">S2 - Security</a></strong><br>'; }

    if(check_permission(31))
    { $retval .='<strong>&loz;&nbsp;<a href="' . $_CONF['html'] . '/dental_update.php">Dental Update</a></strong><br>'; }

    if(check_permission(14))
    { $retval .='<strong>&loz;&nbsp;<a href="' . $_CONF["html"] . '/reports.php">Reports</a></strong><br>'; }

	if(check_permission(34))
    { $retval .='<strong>&loz;&nbsp;<a href="' . $_CONF["html"] . '/survey/index.php" target=_blank>Survey System</a></strong><br>'; }

    $retval .='<strong>&loz;&nbsp;<a href="' . $_CONF["html"] . '/search.php">Search</a><br>'
             .'&loz;&nbsp;<a href="' . $_CONF["html"] . '/cua/cua.php">Computer User Agreement</a><br>'
             .'&loz;&nbsp;<a href="' . $_CONF['html'] . '/priv.php">Privacy and Security</a><br>'
             .'&loz;&nbsp;<a href="' . $_CONF["html"] . '/logout.php">Logout</a><br>'
             .'</strong>';

    if(check_permission(25))
    { $retval .= "<strong>&loz;&nbsp;<a href='" . $_CONF['admin_html'] . "/index.php'>Admin Menu</a></strong><br>\n"; }

    $retval .= "</font></td></tr></table><br>\n";

    $retval .= "<form method='get' action='" . $_CONF['html'] . "/search.php'>"
              ."<table border='1' width='100%' cellpadding='0' cellspacing='0'>"
              ."<tr class='table_cheading'><td colspan='2'>Quick Search</td></tr>"
              ."<tr><td><input type='text' size='15' maxlength='25' name='search_text' class='text_box'>"
              ."</td><td><input type='submit' class='button' name='submit' value='Go'></td></tr>"
              ."</table></form>";

    $retval .= com_whos_online();

    return($retval);
}

#######################################
#
# logs error messages to error log with
# current date and time and name of file
# that error occurred in.
#
#######################################
function com_error($msg)
{
    global $_CONF;

    if($_CONF["log_errors"] == "on" && file_exists($_CONF["error_log"]))
    {
        $now = date("y-m-d h:i:s");
        $page = $_SERVER["SCRIPT_NAME"];
        $lg = $now . " -- " . $page . " -- " . $msg;

        error_log($msg,3,$_CONF["error_log"]);
    }
}

#######################################
#
# function to log messages to error log
#
#######################################
function com_verbose($msg)
{
    global $_CONF;

    if($_CONF["verbose"] == "on" && file_exists($_CONF["error_log"]))
    {
        error_log($msg,3,$_CONF["error_log"]);
    }
}

function com_notification($no)
{
    global $_CONF;
    $retval = FALSE;
    $display_empty_query = 1;

    $day = date("w");
    switch($day)
    {
        case 0:
            $days = 2;
            break;
        case 1:
            $days = 3;
            break;
        default:
            $days = 1;
    }

    switch($no)
    {
        case "remarks":
            $query = "select count(*) as c from main m, remarks r, user_permissions up where m.battalion = up.battalion_id
                      and m.company = up.company_id and up.user_id = {$_SESSION['user_id']}
                      and ((up.permission_id = 19 and r.restricted=0) or (up.permission_id = 32 and r.restricted=1))
                      and to_days(now()) - to_days(r.time) between 0 and $days and m.id = r.id";
            $text = "There have been <a href='" . $_CONF['html'] ."/reports/new_remarks_report.php?days=$days'>%c% new remarks</a> in the past $days days.";
        break;

        case "due_apft":

            $pp = " da.pers_type in ('" . implode("','",$_CONF['perm_party']) . "') ";
            $s = " da.pers_type in ('" . implode("','",$_CONF['students']) . "') ";
            $n = " n != 1 ";
            $m = " da.pers_type in ('" . implode("','",$_CONF['military']) . "') ";

            $temp_query = "create temporary table max_date (primary key(id), index(date)) select id, max(date) as date from apft group by id";
            $result = mysql_query($temp_query) or die("Error making max_date: " . mysql_error());

            $temp_query = "create temporary table due_apft (primary key(id)) IGNORE select m.id, m.pers_type, '1' as n from main m, apft a, max_date md
                      where m.id = a.id and a.id = md.id and a.date = md.date and (a.date < now() - interval 6 month or a.pass_fail = 'fail')
                      ";

            $result = mysql_query($temp_query) or die("Error making due_apft: " . mysql_error());

            $temp_query = "insert into due_apft (id, pers_type) select m.id,m.pers_type from main m left join apft a on m.id = a.id where a.id is null";
            $result = mysql_query($temp_query) or die("Error adding to due_apft: " . mysql_error());

            $query = "select sum(if($pp,1,0)) as pp, sum(if($s,1,0)) as s, sum(if($n,1,0)) as n from due_apft da, main m, user_permissions up
                      where m.id = da.id and m.battalion = up.battalion_id and m.company = up.company_id and up.user_id = {$_SESSION['user_id']}
                      and up.permission_id = 15 and m.pcs=0 and $m";

            $text = "There are <a href=\"{$_CONF['html']}/reports/due_apft_report.php?mode=pp\">%pp%</a> Perm. Party and
                     <a href=\"{$_CONF['html']}/reports/due_apft_report.php?mode=s\">%s%</a> Students due an APFT.
                     (<a href=\"{$_CONF['html']}/reports/due_apft_report.php?mode=n\">%n%</a> personnel do not have an APFT on record.)";
        break;

        case "next_apft":
            $temp_query = "select upper(date_format(date,'%d%b%y')) as last_apft, upper(date_format(date+interval 6 month,'%b%y')) as next_apft,
                      unix_timestamp(date+interval 6 month) as next_apft2 from apft where id = {$_SESSION['user_id']} order by date desc limit 1";
            $result = mysql_query($temp_query);
            if(mysql_num_rows($result)==1)
            {
                $row = mysql_fetch_assoc($result);
                $text = "Your last APFT was on {$row['last_apft']}. ";
                $t = mktime(0,0,0,date('m'),1,date('Y'));
                if($t > $row['next_apft2'])
                { $text .= "<span class='error'>You are overdue an APFT since {$row['next_apft']}.</span>"; }
                else
                { $text .= "You are due an APFT in {$row['next_apft']}."; }
            }
            elseif(in_array($_SESSION['pers_type'],$_CONF['military']))
            { $text = "<span class='error'>You are overdue for an APFT. There is no record of an APFT for you in this database.</span>"; }
        break;

        case "due_dental":
            $temp_query = "select count(*), month(dental_date) from main m, user_permissions up where
                      month(dental_date) in (month(curdate()),month(curdate()+interval 1 month))
                      and
                      year(dental_date) in (year(curdate()),year(curdate() + interval 1 month))
                      and m.battalion = up.battalion_id and m.company = up.company_id and up.user_id = {$_SESSION['user_id']} and
                      up.permission_id = 11 and m.pcs=0
                      group by year(dental_date), month(dental_date)
                      order by year(dental_date), month(dental_date)";

            $result = mysql_query($temp_query) or die(mysql_error());
            $cur = 0;
            $nex = 0;
            while($row = mysql_fetch_row($result))
            {
                if($row[1] == date('m'))
                { $cur = $row[0]; }
                else
                { $nex = $row[0]; }
            }

            $temp_query2 = "select count(*) from main m, user_permissions up where dental_date < curdate()
                            and m.battalion = up.battalion_id and m.company =
                            up.company_id and up.user_id = {$_SESSION['user_id']} and up.permission_id = 11 and m.pcs=0";

            $result2 = mysql_query($temp_query2);
            $overdue = (int)@mysql_result($result2,0);

            $text = "Dental Exams: <a href='{$_CONF['html']}/reports/dental_roster.php?mode=current'>$cur</a> are due this month,
                     <a href='{$_CONF['html']}/reports/dental_roster.php?mode=next_month'>$nex</a> are due next month and
                     <a href='{$_CONF['html']}/reports/dental_roster.php?mode=overdue'>$overdue</a> are overdue for an exam.";
        break;

        case 'appointments';
            $text = 'Appointments:<br /><table border="1" cellpadding="1" cellspacing="0" width="90%" align="center"><tr>';
            for($x=-1;$x<=4;$x++)
            { $text .= sprintf('<th>%s</th>',date('D, M j',strtotime("today + $x day"))); }
            $text .= '</tr><tr align="center">';
            for($x=1;$x<=6;$x++)
            {
                $date = date('dMy',mktime(12,12,12,date('m'),date('d')+($x-2),date('y')));
                $text .= "<td><a href=\"{$_CONF['html']}/reports/appointment_report.php?start_date=$date&end_date=$date\">%c$x%</a></td>\n";
            }
            $text .= '</tr></table>';

            $query1 = "SELECT
                  SUM(IF(TO_DAYS(CURDATE() + INTERVAL -1 DAY) BETWEEN TO_DAYS(a.start) AND TO_DAYS(a.end),1,0)) c1,
                  SUM(IF(TO_DAYS(CURDATE() + INTERVAL  0 DAY) BETWEEN TO_DAYS(a.start) AND TO_DAYS(a.end),1,0)) c2,
                  SUM(IF(TO_DAYS(CURDATE() + INTERVAL  1 DAY) BETWEEN TO_DAYS(a.start) AND TO_DAYS(a.end),1,0)) c3,
                  SUM(IF(TO_DAYS(CURDATE() + INTERVAL  2 DAY) BETWEEN TO_DAYS(a.start) AND TO_DAYS(a.end),1,0)) c4,
                  SUM(IF(TO_DAYS(CURDATE() + INTERVAL  3 DAY) BETWEEN TO_DAYS(a.start) AND TO_DAYS(a.end),1,0)) c5,
                  SUM(IF(TO_DAYS(CURDATE() + INTERVAL  4 DAY) BETWEEN TO_DAYS(a.start) AND TO_DAYS(a.end),1,0)) c6
                  from appointments a, main m, user_permissions up where m.id = a.id and m.pcs = 0 and
                  up.user_id = {$_SESSION['user_id']} and m.battalion = up.battalion_id and m.company = up.company_id
                  and up.permission_id = 1";
            $result = mysql_query($query1);
            $val = mysql_fetch_assoc($result);
            if(array_sum($val) == 0)
            { unset($text); unset($val); unset($query); }
        break;

        case 'cua':
            $result = mysql_query("select 1 from cua where id = {$_SESSION['user_id']} AND Time BETWEEN NOW() - INTERVAL 1 YEAR AND NOW()");
            if(mysql_num_rows($result)==0)
            { $text = "<span class=\"error\">You have not signed the <a href=\"{$_CONF['html']}/cua/cua.php\">Computer User Agreement</a> within the past year.</span>"; }
        break;
    }

    if(isset($val) && isset($text))
    { $retval = preg_replace("/%(\w+)%/Ue",'@(int)$val["$1"]',$text); }
    elseif(isset($query))
    {
        $result = mysql_query($query) or die("notifcation query error: " . mysql_error());
        $num_rows = mysql_num_rows($result);

        if(isset($text) && $val = mysql_fetch_array($result))
        $retval = preg_replace("/%(\w+)%/Ue",'@(int)$val["$1"]',$text);
    }
    elseif(isset($text))
    { $retval = $text; }

    return $retval;
}

function com_whos_online()
{
    global $_CONF;

    if(!class_exists("roster"))
    { require($_CONF['path'] . "classes/roster.class.php"); }

    $query = "select m.id, concat(m.rank,m.promotable,' ',m.last_name,' (',if(b.battalion+0,b.battalion+0,b.battalion),')<br>Online for ',
              sec_to_time(unix_timestamp(now())-unix_timestamp(login_time))) from main m, battalion b, users u where m.id = u.user_id and u.last_access > 0 and
              m.battalion = b.battalion_id order by login_time asc";

    //display roster/report
    $roster = new roster($query);
    $roster->link_page("data_sheet.php");
    $roster->link_column(0);
    $roster->sethidecolumn(0);
    $roster->show_column_headers(0);
    $roster->setheader("Who's Online");
    $roster->setdatafontsize("xx-small");
    $retval = $roster->drawroster();

    //Str replace to set header background
    //color to main color just for this use
    $retval = str_replace("id='header'","id='header' class='table_cheading'",$retval);
    //Adjust display of times from HH:MM:SS
    //to H hours, M minutes
    $retval = preg_replace("/([0-9]{2}):([0-9]{2}):([0-9]{2})/",'$1 hr $2 min',$retval);

    return $retval;
}

#######################################
#
# Generate a JavaScript alert message
# (once per day by default)
#
#######################################
function com_alert()
{
    //Give start and end datetime of message
    $display_start = strtotime('2003/07/11 08:00:00');
    $display_end   = strtotime('2003/07/14 13:00:00');

    $now = time();

    $retval = '';

    if($now > $display_start && $now < $display_end && !isset($_COOKIE['com_alert']))
    {
        //Enter message you want displayed
        $msg = "NOTICE: The USAP server is currently undergoing an upgrade that may cause long page load times. BDE Automation is currently working to correct the issue.";

        $retval = "<script>alert('$msg');</script>";

        //Set cookie to persist for one day
        setcookie('com_alert',1,time()+86400);
    }

    return $retval;
}

/***************************************************
/calculate age (input string: YYYY-MM-DD)
/add by SSG Natali, Jose - Feb 2011
/***************************************************/

  function getAge($sm_strDate, $fd_strDate='') {
    // $sm_strDate - "service member" date of birth, $fd_strDate - "from date" (event or just the age at specific date)
    list($Y,$m,$d)    = explode("-",$sm_strDate);
	if ($fd_strDate) {
	  list($Y2,$m2,$d2)    = explode("-",$fd_strDate);
	  return( $m2.$d2 < $m.$d ? $Y2-$Y-1 : $Y2-$Y ); }
	Else {
      return( date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y ); }
}
?>