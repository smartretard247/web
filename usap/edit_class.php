<?
//configuration file
include("lib-common.php");
//validation routines
include($_CONF["path"] . "classes/validate.class.php");

if(isset($_GET["view_class"]))
{
    header("location: " . $_CONF["html"] . "/class.php?class_id=" . $_GET["class_id"]);
}

//default variables
$val = new validate;

//display site header
echo com_siteheader("edit class");
include($_CONF["path"] . "templates/choose_class.inc.php");

if(isset($_POST['delete_class']))
{
    if($input['class_id'] = $val->cclass($_POST['class_id'],6))
    {
        $query = "select count(*) from main m, student s where s.id = m.id and s.class_id = " . $input['class_id']
                ." and m.pcs = 0";
        $result = mysql_query($query) or die("count class member error: " . mysql_error());

        if(mysql_result($result,0) == 0)
        {
            $query = "update class set inactive = 1 where class_id = {$input['class_id']}";
            $result = mysql_query($query) or die("update class inactive error: " . mysql_error());
            if(mysql_affected_rows())
            { echo "<span class=\"notice\">Deletion successful!</span>"; }
            else
            { echo "<span class=\"notice\">Could not delete class!</span>"; }
        }
        else
        { echo "<span class=\"error\">Warning: Cannot delete class with active soldiers assigned to it. All soldiers must PCS in order to delete a class.</span>"; }
    }
    else
    { echo "Invalid permissions!"; }
}
elseif(isset($_POST['restore_class']))
{
    if($input['class_id'] = $val->cclass($_POST['class_id'],6))
    {
        $query = "update class set inactive = 0 where class_id = " . $input['class_id'];
        $result = mysql_query($query) or die("update class active error: " . mysql_error());
        if(mysql_affected_rows())
        { echo "<span class=\"notice\">Class Restored</span>"; }
        else
        { echo "<span class=\"notice\">Could not restore class!</span>"; }
    }
    else
    { echo "Invalid permissions!"; }
}
elseif(isset($_POST["edit_class"]))
{
    $input["class_id"] = $val->cclass($_POST["class_id"],5);

    if($s = $val->unit($_POST["unit"],5))
    {
        $input["battalion_id"] = $s[0];
        $input["company_id"] = $s[1];
    }

    $input["start_date"]   = $val->check("date", $_POST["start_date"],"Start Date");
    $input["eoc_date"]     = $val->check("date", $_POST["eoc_date"],"EOC Date",1);
    $input["ctt_date"]     = $val->check("date", $_POST["ctt_date"],"CTT Date",1);
    $input["trans_date"]   = $val->check("date", $_POST["trans_date"],"Transition Date",1);
    $input["stx_start"]    = $val->check("date", $_POST["stx_start_date"],"STX Start Date",1);
    $input["stx_end"]      = $val->check("date", $_POST["stx_end_date"],"STX End Date",1);
    $input["grad_date"]    = $val->check("date", $_POST["grad_date"],"Graduation Date");
    $input["pcs_date"]     = $val->check("date", $_POST["pcs_date"],"PCS Date",1);
    $input["mos"]          = $val->check("mos",  $_POST["mos"],"MOS");
    $input["class_number"] = $val->check("sword",$_POST["class_number"],"Class Number");
    $input['aot_type']     = $val->conf($_POST['aot_type'],'aot_type');
    $input['phase']        = $val->conf($_POST['phase'],'phase');
    $input['shift']        = $val->conf($_POST['shift'],'shift');
    $input['status']       = $_POST['status'];
    $input['extras']       = (strlen(implode('',$_POST['value'])) > 0) ? 1 : 0;    
    
    if($val->iserrors())
    { echo $val->geterrors(); }
    else
    {
        $query = "update class set
                  start_date = '{$input['start_date']}', eoc_date = '{$input['eoc_date']}', ctt_date = '{$input['ctt_date']}', 
                  trans_date = '{$input['trans_date']}', stx_start = '{$input['stx_start']}', stx_end = '{$input['stx_end']}', 
                  grad_date = '{$input['grad_date']}', pcs_date = '{$input['pcs_date']}', mos = '{$input['mos']}', 
                  class_number = '{$input['class_number']}', battalion_id = {$input['battalion_id']}, extras = {$input['extras']},
                  company_id = {$input['company_id']}, aot_type = '{$input['aot_type']}', phase = '{$input['phase']}'
                  where class_id = {$input['class_id']}";

        $result = mysql_query($query) or die("error in query: [$query] :: " . mysql_error());

        //Reset everyone in class to chosen AOT_Type and Phase
        //if checkboxes were checked. 
        $set = '';
        if(isset($_POST['reset_aot']))
        { $set .= "aot_type = '{$input['aot_type']}',"; }
        if(isset($_POST['reset_shift']))
        { $set .= "shift = '{$input['shift']}',"; }
        if(isset($_POST['reset_status']))
        { 
        	$query_rsLimited = "SELECT ID from student where Class_ID = {$input['class_id']}"; 
			//echo $query_rsLimited . "</p>";
			$updResult = mysql_query($query_rsLimited);
		
			$num=mysql_numrows($updResult);
			$i=0;
			while ($i < $num) {
				$tempID= mysql_result($updResult,$i,"ID");
				$queery = "UPDATE main SET Status={$input['status']} WHERE id = " . $tempID;
				$rsU = mysql_query($queery) or die("Error resetting status" . mysql_error());
				$i++;
			}
		
        }
        if(isset($_POST['reset_phase']))
        { 
            $set .= "phase = '{$input['phase']}',"; 
            switch($input['phase'])
            {
                case 'IV':
                    $set .= "date_phaseiv = CURDATE(),";
                break;
                case 'V':
                    $set .= "date_phasev = CURDATE(),";
                break;
                case 'V+':
                    $set .= "date_phaseva = CURDATE(),";
                break;
            }
        }
        
        
        if($set)
        {
            $set = substr($set,0,-1);
            $query = "UPDATE student SET $set WHERE class_id = {$input['class_id']}";
            //echo $query;
            $rs = mysql_query($query) or die("Error resetting aot_type and phase: " . mysql_error());
        }
        
        if($input['extras'])
        {
            $delete_ids = '';
            
            for($x=0;$x<5;$x++)
            {
                $extra_id = (isset($_POST['extra_id'][$x])) ? $_POST['extra_id'][$x] : 0;

                if(!empty($_POST['field'][$x]) && !empty($_POST['value'][$x]))
                {
                    //if 'value' validates to a date, use that, otherwise treat as text
                    $value = $val->check('date',$_POST['value'][$x],'');
                    if(!$value)
                    { $value = htmlentities($_POST['value'][$x]); }
                
                    $field = htmlentities(ucwords($_POST['field'][$x]));
                    
                    if($extra_id)
                    { $query = "UPDATE class_extras SET field = '$field', value = '$value' WHERE extra_id = $extra_id"; }
                    else
                    { $query = "INSERT INTO class_extras (class_id, field, value) VALUES ({$input['class_id']},'$field','$value')"; }
                    
                    $rs = mysql_query($query) or die("Error updating class extras: " . mysql_error());
                }
                elseif($extra_id)
                { $delete_ids .= $extra_id . ','; }
            }
            
            if($delete_ids)
            {
                $delete_ids = substr($delete_ids,0,-1);
                $query = "DELETE FROM class_extras WHERE extra_id IN ($delete_ids)";
                $rs = mysql_query($query) or die("Error deleting class extras: " . mysql_error());
            }
        }
        echo "<span class=\"notice\">Class {$input['class_number']} for MOS {$input['mos']} updated.</span>";
        unset($_POST);
    }
}

if(isset($_REQUEST["class_id"]))
{
    $class_id = (int)$_REQUEST['class_id'];
    
    $class_query =  "select
                    upper(date_format(start_date,'%d%b%y')) as start_date,
                    upper(date_format(eoc_date,'%d%b%y')) as eoc_date,
                    upper(date_format(ctt_date,'%d%b%y')) as ctt_date,
                    upper(date_format(trans_date,'%d%b%y')) as trans_date,
                    upper(date_format(stx_start,'%d%b%y')) as stx_start,
                    upper(date_format(stx_end,'%d%b%y')) as stx_end,
                    upper(date_format(grad_date,'%d%b%y')) as grad_date,
                    upper(date_format(pcs_date,'%d%b%y')) as pcs_date,
                    mos, battalion_id, company_id, class_number, inactive, extras,
                    aot_type, phase
                    from class where class_id = $class_id";

    $class_result = mysql_query($class_query) or die("Error in class query: [$class_query]: " . mysql_error());
    if($row = mysql_fetch_array($class_result))
    {
        if($row['extras'])
        {
            $query = "SELECT extra_id, field, value FROM class_extras WHERE class_id = $class_id";
            $extras_result = mysql_query($query) or die("Error retrieving class extras: " . mysql_error());
        }
        else
        { $extras_result = FALSE; }
        
        //turn down error reporting to elimnate
        //notices from null values returned from
        //database
        error_reporting(E_ERROR | E_WARNING | E_PARSE);

        include($_CONF["path"] . "templates/edit_class.inc.php");
    }
    else
    { echo "<span class=\"notice\">Class not found</span>"; }
}

echo com_sitefooter();
?>
