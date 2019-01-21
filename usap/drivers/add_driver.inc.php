<br>
<form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
  <table width="95%" border="1" cellpadding="2" cellspacing="0">
    <tr class="table_cheading"> 
      <td colspan="3">Add New License</td>
    </tr>
    <tr class="column_name"> 
      <td colspan="3">Choose Soldier:</td>
    </tr>
    <tr> 
      <td colspan="3"> 
        <?=soldier_select($_POST['id'],29)?>
      </td>
    </tr>
	<tr><td>
	<table border="0" width="100%">
    <tr class="column_name"> 
      <td>License Type</td>
      <td>DDC Complete</td>
      <td>348/8001 Complete</td>
    </tr>
    <tr> 
      <td> 
        <?=conf_select("license_type",$_POST['license_type'])?>
      </td>
      <td><input name="complete_ddc" type="text" class="text_box" value="<?=$_POST['complete_ddc']?>" size="10" maxlength="9"></td>
      <td><input name="complete_348_8001" type="text" class="text_box" id="complete_348_8001" value="<?=$_POST['complete_348_8001']?>" size="10" maxlength="9"></td>
    </tr>
    <tr class="column_name"> 
      <td>Test Date</td>
      <td>Permit Expiration</td>
      <td>License Expiration</td>
    </tr>
    <tr> 
      <td><input name="test_date" type="text" class="text_box" id="test_date" value="<?=$_POST['test_date']?>" size="10" maxlength="9"></td>
      <td><input name="permit_exp" type="text" class="text_box" id="permit_exp" value="<?=$_POST['permit_exp']?>" size="10" maxlength="9"></td>
      <td><input name="license_exp" type="text" class="text_box" id="license_exp" value="<?=$_POST['license_exp']?>" size="10" maxlength="9"></td>
    </tr>
    <tr class="column_name"> 
      <td>Received</td>
      <td colspan="2">Remark</td>
    </tr>
    <tr> 
      <td><input name="received" type="text" class="text_box" id="received" value="<?=$_POST['received']?>" size="10" maxlength="9"></td>
      <td colspan="2"><input name="remark" type="text" class="text_box" id="remark" value="<?=$_POST['remark']?>" size="45"></td>
    </tr>
    <tr> 
      <td colspan="3"><div align="center"> 
          <input name="clear" type="reset" id="clear" value="Clear Form">
          &nbsp; 
          <input name="submit" type="submit" id="Submit" value="Add License">
        </div></td>
    </tr>
	</table>
	</td></tr>
    <tr>
      <td colspan="3"><div align="center"><a href="<?=$_CONF['html']?>/drivers/index.php">Return to Master Driver Menu</a></div></td>
    </tr>
  </table>
</form>