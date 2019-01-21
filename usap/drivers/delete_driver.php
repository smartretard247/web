<?
//configuration file
include("../lib-common.php");
include("../classes/validate.class.php");
include("../classes/roster.class.php");

$val = new validate();

if(isset($_GET['delete']))
{
	//validate driver id
	$input['driver_id'] = $val->fk_constraint($_GET['driver_id'],"drivers","driver_id");
	//validate id
	$input['id'] = $val->id($_GET['id'],29);

	if($_GET['delete'] == "OK")
	{
		if($val->iserrors())
		{ echo $val->geterrors(); }
		else
		{
			$result = mysql_query("delete from drivers where driver_id = {$input['driver_id']} and id = {$input['id']}");
			if($e = mysql_error())
			{ echo "Database Error: $e"; }
			else
			{ 
				header("Location: " . $_CONF['html'] . "/drivers/index.php?id={$input['id']}");
				exit();
			}
		}
	}
	else
	{
		header("Location: " . $_CONF['html'] . "/drivers/index.php?id={$input['id']}");
		exit();
	}	 				
}

//header
echo com_siteheader("Delete Driver");

if(isset($_REQUEST['driver_id']))
{ 
	$val = new Validate();
	
	if($val->fk_constraint($_REQUEST['driver_id'],"drivers","driver_id"))
	{
		$result = mysql_query("SELECT m.id,CONCAT(m.last_name,', ',m.first_name,' ',m.middle_initial,' ',"
						."m.rank,' - ',right(m.ssn,4)) AS name FROM main m, drivers d WHERE m.id = d.id "
						."and d.driver_id = {$_REQUEST['driver_id']}") or die(mysql_error());
		$row = mysql_fetch_assoc($result);
		if($val->id($row['id'],29))
		{
			?>
			<br>
			<form method="GET" action="<?=$_SERVER['PHP_SELF']?>">
			<input type="hidden" name="driver_id" value="<?=$_REQUEST['driver_id']?>">
			<input type="hidden" name="id" value="<?=$row['id']?>">
			<table border="1" align="center" width="75%">
			  <tr class="table_cheading">
			    <td>Confirm Delete</td>
			  </tr>
			  <tr>
			    <td align="center">
				  Press OK to delete this license for <?=$row['name']?>
				  <br><br>
				  <input type="submit" name="delete" value="Cancel">
				  &nbsp;
				  <input type="submit" name="delete" value="OK">
				</td>
			  </tr>
			</table>
			</form>
			<?
		}
	}
	else
	{ echo "Invalid Driving Record"; }
}
echo com_sitefooter();
?>