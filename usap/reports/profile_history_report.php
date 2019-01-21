<?
include("../lib-common.php");

include("../classes/validate.class.php");
include("../classes/roster.class.php");

$val = new validate;

if($input['id'] = $val->id($_REQUEST['id'],11))
{
    $query = "select concat(m.rank,m.promotable, ' ', m.last_name, ', ',m.first_name, ' ', m.middle_initial) as info from 
              main m where m.id = {$input['id']}";
    $result = mysql_query($query) or die("Error selecting soldier information: " . mysql_error());
    
    $info = mysql_result($result,0);

    $date = strtoupper(date("dMY"));

    $header =  "<strong>Profile History Report</strong><br>$info";

    echo com_siteheader("Profile History Report");

    if(!isset($_REQUEST["export2"]))
    { echo "export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>Excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>Word</a>\n"; }

    $comp = implode(",",$_CONF['component']);
    $comp_abbr = "'" . implode("','",$_CONF['component_abbrev']) . "'";

    $query = "select p.id,p.Profile, upper(date_format(p.profile_start,'%d%b%y')) as Start, 
              if(left(p.profile,1)='P','Perm',upper(date_format(p.profile_start + INTERVAL profile_length DAY,'%d%b%y'))) as End,
              if(left(p.profile,1)='P','Perm',upper(date_format(p.profile_start + INTERVAL LEAST(profile_length+90,profile_length*3) DAY,'%d%b%y'))) as Recovery_End, 
              p.profile_reason as Reason, p.profile_limitations as Limitations,
              upper(date_format(p.date,'%d%b%y')) as Date_Entered 
              from profile p 
              where p.id = {$input['id']} order by p.date desc";

    $roster = new roster($query);
    $roster->setheader($header);
    $roster->sethidecolumn(0);
	$roster->setReportName('profilehistoryreport');
	$roster->allowUserOrderBy(TRUE);    
    echo $roster->drawroster();

}
else
{
    echo com_siteheader("Invalid Permissions - Profile History Report");
    echo "Invalid Permissions.";
}

echo com_sitefooter();

?>