<?
include("../lib-common.php");
include("../classes/validate.class.php");

//validation object
$val = new validate();

echo com_siteheader("Add New License");

if(isset($_POST['submit']))
{
	$input['driver_id'] = 		$val->fk_constraint($_POST['driver_id'],"drivers","driver_id");
	$input['id'] = 				$val->id($_POST['id'],29);
	$input['license_type'] = 	$val->conf($_POST['license_type'],"license_type","License Type");
	$input['test_date'] = 		$val->check("date",$_POST['test_date'],"Test Date",1);
	$input['permit_exp'] = 		$val->check("date",$_POST['permit_exp'],'Permit Expiration',1);
	$input['license_exp'] = 	$val->check("date",$_POST['license_exp'],'License Expiration',1);
	$input['received'] = 		$val->check("date",$_POST['received'],'Received',1);
	$input['remark'] = 			$val->check("string",$_POST['remark'],"Remark",1);
	$input['complete_ddc'] = 	$val->check('date',$_POST['complete_ddc'],'Complete DDC',1);
	$input['complete_348_8001']=$val->check('date',$_POST['complete_348_8001'],'Complete 348/8001',1);

	if($val->iserrors())
	{ echo $val->geterrors(); }
	else
	{
		$query = "update drivers set license_type = '{$input['license_type']}', "
				."test_date = '{$input['test_date']}', permit_exp = '{$input['permit_exp']}', "
				."license_exp = '{$input['license_exp']}', received = '{$input['received']}', "
				."remark = '{$input['remark']}', complete_ddc = '{$input['complete_ddc']}', "
				."complete_348_8001 = '{$input['complete_348_8001']}' where driver_id = {$input['driver_id']}";
		$result = mysql_query($query);
		if(mysql_affected_rows() == 1)
		{ 
			$msg = "License data updated successfully"; 
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

if(isset($_REQUEST['driver_id']))
{
	if($input['driver_id'] = $val->fk_constraint($_REQUEST['driver_id'],"drivers","driver_id"))
	{
		$result = mysql_query("select m.id,concat(m.last_name,', ',m.first_name,' ',m.middle_initial,' ',m.rank,' - ',right(m.ssn,4)) as name, "
						."License_Type, UPPER(DATE_FORMAT(test_date,'%d%b%y')) AS test_date, "
						."UPPER(DATE_FORMAT(permit_exp,'%d%b%y')) as permit_exp, "
						."UPPER(DATE_FORMAT(license_exp,'%d%b%y')) as license_exp, "
						."UPPER(DATE_FORMAT(received,'%d%b%y')) as received, "
						."UPPER(DATE_FORMAT(complete_ddc,'%d%b%y')) as complete_ddc, "
						."UPPER(DATE_FORMAT(complete_348_8001,'%d%b%y')) as complete_348_8001, "
						."remark from main m, drivers d where m.id = d.id and d.driver_id = {$_REQUEST['driver_id']} ")
						or die("Query error: " . mysql_error());
		$row = mysql_fetch_assoc($result);
		if($val->id($row['id'],29))
		{ include($_CONF['path'] . "/drivers/edit_driver.inc.php"); }
		else
		{ echo "Invalid Permissions"; }
	}
	else
	{ echo "Invalid ID"; }
}

echo com_sitefooter();
?>