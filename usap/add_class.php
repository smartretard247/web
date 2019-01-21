<?
include("lib-common.php");
include($_CONF["path"] . "classes/validate.class.php");

$val = new validate;

echo com_siteheader("Add Class");

if(isset($_POST["add_class_submit"]))
{
    if($s = $val->unit($_POST["unit"],4))
    {
        $input["battalion_id"] = $s[0];
        $input["company_id"] = $s[1];
    }

    $input["start_date"]   = $val->check("date", $_POST["start_date"],"Start Date");
    $input["eoc_date"]     = $val->check("date", $_POST["eoc_date"],"EOC Date",1);
    $input["ctt_date"]     = $val->check("date", $_POST["ctt_date"],"CTT Date",1);
    $input["trans_date"]   = $val->check("date", $_POST["trans_date"],"Transition Date",1);
    $input["stx_start"]    = $val->check("date", $_POST["stx_start"],"STX Start Date",1);
    $input["stx_end"]      = $val->check("date", $_POST["stx_end"],"STX End Date",1);
    $input["grad_date"]    = $val->check("date", $_POST["grad_date"],"Graduation Date");
    $input["pcs_date"]     = $val->check("date", $_POST["pcs_date"],"PCS Date");
    $input["mos"]          = $val->check("mos",  $_POST["mos"],"MOS");
    $input["class_number"] = $val->check("sword",$_POST["class_number"],"Class Number");
    $input['aot_type']     = $val->conf($_POST['aot_type'],'aot_type');
    $input['phase']        = $val->conf($_POST['phase'],'phase');
    
    $input['extras'] = (strlen(implode('',$_POST['value'])) > 0) ? 1 : 0;
    
    if($val->iserrors())
    { echo $val->geterrors(); }
    else
    {
        $query = "insert into class (start_date, eoc_date, ctt_date, trans_date, stx_start, stx_end,
                  grad_date, pcs_date, mos, class_number, battalion_id, company_id, extras, aot_type, phase) values
                  ('{$input['start_date']}','{$input['eoc_date']}','{$input['ctt_date']}',
                  '{$input['trans_date']}','{$input['stx_start']}','{$input['stx_end']}',
                  '{$input['grad_date']}','{$input['pcs_date']}','{$input['mos']}',
                  '{$input['class_number']}',{$input['battalion_id']},{$input['company_id']},
                  {$input['extras']},'{$input['aot_type']}','{$input['phase']}')";

        $result = mysql_query($query) or die("error in query: [$query] :: " . mysql_error());

        $class_id = mysql_insert_id();
        
        //Loop through extra fields
        $insert = '';
        for($x=0;$x<5;$x++)
        {
            //Only process fields if 'field' and 'value' are not empty
            if(!empty($_POST['field'][$x]) && !empty($_POST['value'][$x]))
            { 
                //if 'value' validates to a date, use that, otherwise treat as text
                $value = $val->check('date',$_POST['value'][$x],'');
                if(!$value)
                { $value = htmlentities($_POST['value'][$x]); }
                
                $field = htmlentities(ucwords($_POST['field'][$x]));
                
                //Create insert string to use in query later
                $insert .= "($class_id,'$field','$value'),";
            }
        }
        if($insert)
        {
            //Remove final comma from insert string
            $insert = substr($insert,0,-1);
            $query = "INSERT INTO class_extras (class_id, field, value) VALUES $insert";
            $rs = mysql_query($query) or die("Error inserting class extras: " . mysql_error());
        }
        
        echo "<span class=\"notice\">Class {$input['class_number']} for MOS {$input['mos']} added.</span>";
        unset($_POST);
    }
}

//turn down error reporting to elimnate
//notices from null values returned from
//database
error_reporting(E_ERROR | E_WARNING | E_PARSE);

include($_CONF["path"] . "templates/add_class.inc.php");

echo com_sitefooter();
?>
