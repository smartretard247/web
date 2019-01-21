<br>
<form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
<input type="hidden" name="driver_id" value="<?=$_REQUEST['driver_id']?>">
<input type="hidden" name="id" value="<?=$row['id']?>">
  <table width="95%" border="1" cellpadding="2" cellspacing="0">
    <tr class="table_cheading"> 
      <td>Edit License</td>
    </tr>
    <tr> 
      <td><span class="column_name">Soldier:</span> </span>
        <?=$row['name']?>
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
        <?=conf_select("license_type",$row['license_type'])?>
      </td>
      <td><input name="complete_ddc" type="text" class="text_box" value="<?=$row['complete_ddc']?>" size="10" maxlength="9"></td>
      <td><input name="complete_348_8001" type="text" class="text_box" id="complete_348_8001" value="<?=$row['complete_348_8001']?>" size="10" maxlength="9"></td>
    </tr>
    <tr class="column_name"> 
      <td>Test Date</td>
      <td>Permit Expiration</td>
      <td>License Expiration</td>
    </tr>
    <tr> 
      <td><input name="test_date" type="text" class="text_box" id="test_date" value="<?=$row['test_date']?>" size="10" maxlength="9"></td>
      <td><input name="permit_exp" type="text" class="text_box" id="permit_exp" value="<?=$row['permit_exp']?>" size="10" maxlength="9"></td>
      <td><input name="license_exp" type="text" class="text_box" id="license_exp" value="<?=$row['license_exp']?>" size="10" maxlength="9"></td>
    </tr>
    <tr class="column_name"> 
      <td>Received</td>
      <td colspan="2">Remark</td>
    </tr>
    <tr> 
      <td><input name="received" type="text" class="text_box" id="received" value="<?=$row['received']?>" size="10" maxlength="9"></td>
      <td colspan="2"><input name="remark" type="text" class="text_box" id="remark" value="<?=$row['remark']?>" size="45"></td>
    </tr>
    <tr> 
      <td colspan="3"><div align="center"> 
          <input name="clear" type="reset" id="clear" value="Clear Form">
          &nbsp; 
          <input name="submit" type="submit" id="Submit" value="Update License">
        </div></td>
    </tr>
	</table>
	</td></tr>
    <tr>
      <td><div align="center"><a href="<?=$_CONF['html']?>/drivers/index.php?id=<?=$row['id']?>">Return to Master Driver Menu</a></div></td>
    </tr>
  </table>
</form>