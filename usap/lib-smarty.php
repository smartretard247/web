<?php

function smarty_name_select($permission_id)
{
    $permission_id = (int)$permission_id;
    $retval = array();
   
    if(isset($_REQUEST['id']))
    { $retval['selected'] = (int)$_REQUEST['id']; }
    
    $query = "SELECT m.id, CONCAT(m.Last_Name, ', ', m.First_Name, ' ', m.Middle_Initial, ' ', m.Rank, m.Promotable) as name FROM main m, user_permissions up
              WHERE up.user_id = {$_SESSION['user_id']} and up.battalion_id = m.battalion and up.company_id = m.company and 
              up.permission_id = $permission_id and m.pcs=0 ORDER BY m.last_name, m.first_name";
    $result = mysql_query($query) or die("Error getting names for select box: " . mysql_error());
    while($row = mysql_fetch_assoc($result))
    { $retval['options'][$row['id']] = $row['name']; }
    
    return $retval;
}

function smarty_time_select($selected='')
{
    $retval = array();
    $retval['selected'] = $selected;
    
    $date = mktime(0,0,0,1,1,2000);
    
    for($x=0;$x<48;$x++)
    {
        $hms = date('His',$date);
        $hm = date('H:i',$date);
        $retval['options'][$hms] = $hm;
        $date += 60 * 30;        
    }
    
    return $retval;
}

?>