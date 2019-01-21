<table width="95%" border="1" cellpadding="2">
  <tr class="table_heading"> 
    <td colspan="3">License info for 
      <?=$name?>
    </td>
<?
while($row = mysql_fetch_array($result))
{
	?>
  </tr>
  <tr class="column_name"> 
    <td>License Type</td>
    <td>DDC Complete</td>
    <td>348/8001 Complete</td>
  </tr>
  <tr> 
    <td><?=$row['license_type']?></td>
    <td><?=$row['complete_ddc']?></td>
    <td><?=$row['complete_348_8001']?></td>
  </tr>
  <tr class="column_name"> 
    <td>Test Date</td>
    <td>Permit Expiration</td>
    <td>License Expiration</td>
  </tr>
  <tr> 
    <td><?=$row['test_date']?></td>
    <td><?=$row['permit_exp']?></td>
    <td><?=$row['license_exp']?></td>
  </tr>
  <tr class="column_name"> 
    <td>Received</td>
    <td>Remark</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td><?=$row['received']?></td>
    <td colspan="2"><?=htmlentities($row['remark'])?></td>
  </tr>
  <tr> 
    <td colspan="3"><hr></td>
  </tr>
<? } ?>  
</table>
