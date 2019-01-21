<?
include("../lib-common.php");
include("../classes/validate.class.php");

//validation object
$val = new validate();

echo com_siteheader("Add New License");

if(isset($_POST['submit']))
{
    $input['id'] =              $val->id($_POST['id'],29);
    $input['license_type'] =    $val->conf($_POST['license_type'],"license_type","License Type");
    $input['test_date'] =       $val->check("date",$_POST['test_date'],"Test Date",1);
    $input['permit_exp'] =      $val->check("date",$_POST['permit_exp'],'Permit Expiration',1);
    $input['license_exp'] =     $val->check("date",$_POST['license_exp'],'License Expiration',1);
    $input['received'] =        $val->check("date",$_POST['received'],'Received',1);
    $input['remark'] =          $val->check("string",$_POST['remark'],"Remark",1);
    $input['complete_ddc'] =    $val->check('date',$_POST['complete_ddc'],'Complete DDC',1);
    $input['complete_348_8001']=$val->check('date',$_POST['complete_348_8001'],'Complete 348/8001',1);
    
    if($val->iserrors())
    { echo $val->geterrors(); }
    else
    {
        $query = "insert into drivers (id,license_type,test_date,permit_exp,license_exp,received,"
                ."remark,complete_ddc,complete_348_8001) values ({$input['id']},'{$input['license_type']}',"
                ."'{$input['test_date']}','{$input['permit_exp']}','{$input['license_exp']}','{$input['received']}',"
                ."'{$input['remark']}','{$input['complete_ddc']}','{$input['complete_348_8001']}')";
        $result = mysql_query($query);
        if(mysql_affected_rows() == 1)
        { 
            $msg = "License data added successfully"; 
            unset($_POST);
        }
        else
        {
            if($e = mysql_error())
            {
                if(stristr($e,"duplicate"))
                { $msg = "License type {$input['license_type']} already exists for this user. Please edit or delete the existing entry."; }
                else
                { $msg = "Database Error: " . mysql_error(); }
            }
            else
            { $msg = "No change to data."; }
        }
        echo "<br><span class='notice'>$msg</span>\n";
    }
}

if(check_permission(29))
{ 
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
    include($_CONF['path'] . "/drivers/add_driver.inc.php"); 
}
else
{ echo "Invalid Permissions"; }

echo com_sitefooter();
?>