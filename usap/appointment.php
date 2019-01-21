<?php

include('lib-common.php');
include_once($_CONF['path'] . '/lib-smarty.php');
include_once($_CONF['path'] . '/classes/validate.class.php');
include_once($_CONF['path'] . '/classes/roster.class.php');
include_once($_CONF['path'] . '/smarty/Smarty.class.php');

$val = new Validate;
$smarty = new Smarty;
$smarty->template_dir = $_CONF['path'] . 'smarty/templates';
$smarty->compile_dir = $_CONF['path'] . 'smarty/templates_c';

//Default display mode
$display_mode = 'new';
$report['where'] = '';
$values['roster'] = '';
$values['message_class'] = 'notice';

//Display header
echo com_siteheader('Appointments');

if($values['id'] = $val->id($_REQUEST['id'],1))
{
    //Process Deletion of Appointments
    if(isset($_REQUEST['delete']) && count($_REQUEST['delete']) > 0)
    {
        $delete = '';
        foreach($_REQUEST['delete'] as $d)
        { $delete .= (is_numeric($d)) ? "$d," : ''; }
        if($delete)
        {
            $delete = substr($delete,0,-1);
            $query = "DELETE FROM appointments where apt_id IN ($delete) AND id = {$values['id']}";
            $result = mysql_query($query) or die("Error deleting appointments: " . mysql_error());
            $values['message'] = 'Appointments Deleted';
            $values['message_class'] = 'notice';
        }
    }
    //Process New Appointment
    elseif(isset($_POST['new_appointment_submit']))
    {       
        $input['id']            = $val->id($_POST['id'],1);
        $input['description']   = $val->check('string',$_POST['description'],'Description');
        $input['location']      = $val->check('string',$_POST['location'],'Location');
        $input['start_date']    = $val->check('date',$_POST['start_date'],'Start Date');
        $input['end_date']      = $val->check('date',$_POST['end_date'],'End Date');
        $input['start_time']    = $val->check('mtime',$_POST['start_time'],'Start_Time');
        $input['end_time']      = $val->check('mtime',$_POST['end_time'],'End Time');
        $input['private']       = (isset($_POST['private'])) ? 1 : 0;
        $input['notes']         = $val->check('string',$_POST['notes'],'Notes',1);
        $input['start']         = $input['start_date'] . $input['start_time'];
        $input['end']           = $input['end_date'] . $input['end_time'];

        if($input['start'] >= $input['end'])
        { $val->error[] = 'Start Date must be before End Date'; }
        if($input['start_date'] < date('Ymd'))
        { $val->error[] = 'Start Date cannot be before today'; }

        if($val->iserrors())
        { 
            $display_mode = 'post';
            $values['message'] = $val->geterrors();
            $values['names'] = smarty_name_select(2);
            $values['description'] = htmlentities($_POST['description']);
            $values['location'] = htmlentities($_POST['location']);
            $values['start_times'] = smarty_time_select($_POST['start_time']);
            $values['end_times'] = smarty_time_select($_POST['end_time']);
            $values['start_date'] = $_POST['start_date'];
            $values['end_date'] = $_POST['end_date'];  
            $values['notes'] = htmlentities($_POST['notes']);
            $values['private'] = isset($_POST['private']) ? 'checked' : '';
        }
        else
        {            
            $query = "INSERT INTO appointments (id,description,location,start,end,notes,private) VALUES ({$input['id']},'{$input['description']}',
                      '{$input['location']}',{$input['start']},{$input['end']},'{$input['notes']}',{$input['private']})";
            $result = mysql_query($query) or die("Error inserting appointment: " . mysql_error());
            $values['message'] = 'New Appointment Saved';
            $values['message_class'] = 'notice';
        }
    }
   
    $query = "SELECT CONCAT(m.last_name, ', ', m.first_name, ' ', m.middle_initial, ' ', m.rank, m.promotable) as Name FROM main m where id = {$values['id']}";
    $result = mysql_query($query) or die("Error getting name for appointments: " . mysql_error());
    $values['name'] = mysql_result($result,0);

    if(!$val->id($_REQUEST['id'],32))
    { $report['where'] .= ' and a.private=0 '; }
    if(!isset($_REQUEST['all_apt']))
    { $report['where'] .= ' and ( a.start >= CURDATE() OR a.end >= CURDATE() ) '; }

    $query = "SELECT  CONCAT('@',apt_id) as 'Delete?', upper(date_format(a.start,'%d%b%y %H:%i')) as Start, 
                      upper(date_format(a.end,'%d%b%y %H:%i')) as End, a.Description, a.Location, 
                      if(a.Private=1,'Y','N') as Private, a.Notes 
			  FROM main m, appointments a 
			  WHERE m.id = a.id and m.id = {$values['id']} {$report['where']}
              ORDER BY a.start desc";

    $roster = new roster($query);
    $r = $roster->drawroster();
       
    if($roster->query_rows > 0)
    {       
        if(isset($_REQUEST['export2']))
        {
            $values['show']['current_appointments'] = FALSE;
            $replacement = '&nbsp;';
            $values['show']['export_heading'] = TRUE;
        }
        else
        {
            $values['show']['current_appointments'] = TRUE; 
            $replacement = '<div style="text-align:center"><input type="checkbox" name="delete[]" value="$1"></div>';
        }
        $values['roster'] = preg_replace('/@([0-9]+)/',$replacement,$r);        
    }
    else
    {
        if(isset($_REQUEST['all_apt']))
        { $values['message'] = 'No current or past appointments found.'; }
        else
        { $values['message'] = 'No current appointments found.'; }
        $values['roster'] = '';
    }

    if(!isset($_REQUEST['export2']))
    { 
        $values['show']['all_apt_link'] = TRUE;
        $values['show']['new_appointment'] = TRUE;
        if($display_mode == 'new')
        {
            $values['start_times'] = smarty_time_select('080000');
            $values['end_times'] = smarty_time_select('083000');
            $values['start_date'] = strtoupper(date('dMy'));
            $values['end_date'] = strtoupper(date('dMy'));
        }
    }
}
else
{ $values['message'] = 'Invalid Permissions: Unable to add/view appointments'; }

$values['url'] = $_CONF['html'];

$smarty->assign('values',$values);

echo $smarty->fetch("appointment.tpl");

//Display footer
echo com_sitefooter();
?>