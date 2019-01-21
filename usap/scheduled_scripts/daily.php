<?
set_time_limit(0);

//This file is run daily by the 
//web server at 00:01

include('../config.php');
include('../lib-database.php');

//Email LT Mann a reminder
$msg = "Check Resubmits: https://usap.gordon.army.mil/reports/security_report.php?unit=1-2&clearance_status=Resubmit&submit=go\n\n
Automated message, do not respond.";

mail("tommy.m@gordon.army.mil","Check Resubmits",$msg,"From: USAP Reminder Service");
mail("tommy.m@gordon.army.mil","Check Resubmits",$msg,"From: USAP Reminder Service");

mail("paul.d.piper@conus.army.mil","Check Resubmits",$msg,"From: USAP Reminder Service");
mail("paul.d.piper@conus.army.mil","Check Resubmits",$msg,"From: USAP Reminder Service");

//Delete students who have been gone over 6 months,  
//Permanent Party who have been gone 90 days,
//and Civilians who have been gone over 30 days
$pp = "'" . implode("','",$_CONF['perm_party']) . "'";
$st = "'" . implode("','",$_CONF['students']) . "'";

$query = "SELECT ID,date_format(pcs_date,'%d%b%y') as D, pers_type FROM main WHERE pcs = 1 AND 
          ((pers_type = 'Civilian' AND pcs_date < NOW() - INTERVAL 30 DAY) OR
          (pers_type IN ($pp) AND pcs_date < NOW() - INTERVAL 90 DAY) OR
          (pers_type IN ($st) AND pcs_date < NOW() - INTERVAL 180 DAY))
          order by pers_type, pcs_date asc";
$rs = mysql_query($query);
$ids = '';
while($row = mysql_fetch_assoc($rs))
{ $ids .= $row['ID'] . ','; }

if($ids)
{
    $ids = substr($ids,0,-1);

    $tables = array('ID' =>
                array('address','airborne','apft','appointments','cua','drivers','exodus',
                      'location','profile','profile_history','remarks',
                      's2','status_history','student'),
                    'User_ID' =>
                array('fpass','user_permissions','users'));

    $e = '';

    foreach($tables as $column=>$type)
    {
        foreach($type as $t)
        {
            $query = "DELETE FROM $t WHERE $column IN ($ids)";
            $result = mysql_query($query);
            $e .= mysql_error();
        }
    }            
            
    $query = "DELETE FROM main WHERE ID IN ($ids)";
    $result = mysql_query($query);
    $e .= mysql_error();

    if(strlen($e) > 0)
    { mail('15rsbhelpdesk@gordon.army.mil','USAP DB Error','The following errors were encounted during the daily personnel deletion: ' . $e); }
}

//Delete classes that are empty and the PCS_Date
//is more than 30 days ago
$query = "select c.class_id from class c left join student s on c.class_id = s.class_id where s.class_id is null and 
          c.pcs_date < now() - interval 30 day order by c.class_id ASC";
$rs = mysql_query($query);
$ids = '';
while($row = mysql_fetch_assoc($rs))
{ $ids .= $row['class_id'] . ','; }

if($ids)
{
    $ids = substr($ids,0,-1);
    
    $query = "DELETE FROM class WHERE class_id IN ($ids)";
    $result = mysql_query($query);
    if($e = mysql_error())
    { mail('tommy.m@gordon.army.mil','USAP DB Error',"The following error was encountered while attempting to delete old classes: $e"); }
    
    $query = "DELETE FROM class_extras WHERE class_id IN ($ids)";
    $result = mysql_query($query);
    if($e = mysql_error())
    { mail('tommy.m@gordon.army.mil','USAP DB Error',"The following error was encountered while attempting to delete old class extras: $e"); }

}

?>