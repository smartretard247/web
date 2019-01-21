<?
//configuration file
include("../lib-common.php");
include("../classes/validate.class.php");
include("../classes/roster.class.php");

//header
echo com_siteheader("Master Driver Records");

if(!isset($_REQUEST['id']))
{ $_REQUEST['id'] = 0; }
?>
<br>
<table border="1" width="95%" cellpadding="2" cellspacing="0" align="center">
  <tr class="table_cheading">
    <td>Master Driver Records</td>
  </tr>
  <tr>
    <td align="center">
      <form method="GET" action="<?=$_SERVER['PHP_SELF']?>">
      Choose soldier to View/Edit: <?=driver_select($_REQUEST['id'])?>
      <input type="submit" name="submit" value="Go">
      </form>
    </td>
  </tr>
  <tr>
    <td align="center"><a href="<?=$_CONF['html']?>/drivers/add_driver.php">Add New Record</a></td>
  </tr>
</table>
<br>
<?
if(isset($_REQUEST['id']) && $_REQUEST['id'] > 0)
{ 
    $val = new Validate();
    
    if($val->id($_REQUEST['id'],29))
    {
        $result = mysql_query("SELECT CONCAT(m.last_name,', ',m.first_name,' ',m.middle_initial,' ',m.rank,' - ',right(m.ssn,4)) AS name FROM main m WHERE m.id = {$_REQUEST['id']}") or die(mysql_error());
        $name = mysql_result($result,0);
        $header = "Driver Info for $name";
        
        $query = "SELECT driver_id,License_Type, UPPER(DATE_FORMAT(test_date,'%d%b%y')) AS Test_Date, "
                        ."UPPER(DATE_FORMAT(permit_exp,'%d%b%y')) as Permit_Expiration, "
                        ."UPPER(DATE_FORMAT(license_exp,'%d%b%y')) as License_Expiration, "
                        ."UPPER(DATE_FORMAT(received,'%d%b%y')) as Received, "
                        ."UPPER(DATE_FORMAT(complete_ddc,'%d%b%y')) as DDC_Complete, "
                        ."UPPER(DATE_FORMAT(complete_348_8001,'%d%b%y')) as '348/8001_Complete', "
                        ."Remark, CONCAT('%edit',driver_id,'%') AS Edit, CONCAT('%delete',driver_id,'%') "
                        ."AS 'Delete' FROM drivers WHERE id = {$_REQUEST['id']}";

        $roster = new roster($query);
        $roster->setheader($header);
        $roster->sethidecolumn(0);
        $data = $roster->drawroster();
        
        $data = preg_replace("/%edit([0-9]+)%/","<a href='{$_CONF['html']}/drivers/edit_driver.php?driver_id=$1'>Edit</a>",$data);
        $data = preg_replace("/%delete([0-9]+)%/","<a href='{$_CONF['html']}/drivers/delete_driver.php?driver_id=$1'>Delete</a>",$data);        
        
        echo $data;
    }
    else
    { echo "Invalid Permissions"; }
}
echo com_sitefooter();
?>