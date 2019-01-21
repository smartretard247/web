<table width="90%" border="1" align="center">
  <tr>
    <td align="right">
      <?php
        if($val->cclass($_REQUEST["class_id"],13))
        { echo " <a href='" . $_CONF["html"] . "/class.php?class_id=" . $_REQUEST["class_id"] . "'>View</a> "; }
      ?>
    </td>
  </tr>
  <tr class="table_heading">
    <td>Edit Class</td>
  </tr>
  <tr>
    <td>
      <form method='post' action='<?php=$_SERVER["SCRIPT_NAME"]?>'>
        <input type='hidden' name='class_id' value='<?php=$_REQUEST["class_id"]?>'>
        <table width="100%" border="0" cellspacing="2" cellpadding="2">
          <col width="30%">
          <col width="70%" align="left">
<?php
if($row['inactive'] == 1)
{
    ?>
    <tr>
        <td colspan="2">
            <strong>notice:</strong> this class has been deleted. all members have pcsd.
            <?php
            if($val->cclass($_REQUEST['class_id'],6))
            { echo '<input type="submit" class="button" name="restore_class" value="Restore Class">'; }
            ?>
        </td>
    </tr>
    <?php
}
?>

          <tr>
            <td>MOS:</td>
            <td>
              <input type="text" name="mos" size="5" maxlength="4" value='<?php echo $row["mos"]; ?>'>
            </td>
          </tr>
          <tr>
            <td>Class Number:</td>
            <td>
              <input type="text" name="class_number" size="15" value='<?php echo $row["class_number"]; ?>'>
            </td>
          </tr>
          <tr>
            <td>Unit</td>
            <td><?php echo unit_select(5,$row["battalion_id"],$row["company_id"]); ?></td>
          </tr>
          <tr>
            <td>Start Date</td>
            <td>
              <input type="text" name="start_date" size="10" maxlength="9" value='<?php=$row["start_date"];?>'>
            </td>
          </tr>
          <tr>
            <td>EOC Date</td>
            <td>
              <input type="text" name="eoc_date" size="10" maxlength="9" value='<?php=$row["eoc_date"];?>'>
            </td>
          </tr>
          <tr>
            <td>CTT Date</td>
            <td>
              <input type="text" name="ctt_date" size="10" maxlength="9" value='<?php=$row["ctt_date"];?>'>
            </td>
          </tr>
          <tr>
            <td>Transition Date</td>
            <td>
              <input type="text" name="trans_date" size="10" maxlength="9" value='<?php=$row["trans_date"];?>'>
            </td>
          </tr>
          <tr>
            <td>STX Start Date</td>
            <td>
              <input type="text" name="stx_start_date" size="10" maxlength="9" value='<?php=$row["stx_start"];?>'>
            </td>
          </tr>
          <tr>
            <td>STX End Date</td>
            <td>
              <input type="text" name="stx_end_date" size="10" maxlength="9" value='<?php=$row["stx_end"];?>'>
            </td>
          </tr>
          <tr>
            <td>Graduation Date</td>
            <td>
              <input type="text" name="grad_date" size="10" maxlength="9" value='<?php=$row["grad_date"];?>'>
            </td>
          </tr>
          <tr>
            <td>PCS Date</td>
            <td>
              <input type="text" name="pcs_date" size="10" maxlength="9" value='<?php=$row["pcs_date"];?>'>
            </td>
          </tr>
          <tr>
            <td>AOT Type</td>
            <td>
              <?php=conf_select('aot_type',$row['aot_type'])?>
              &nbsp;&nbsp;
              <input type="checkbox" name="reset_aot" value="1"> Reset all members of class to this AOT Type
            </td>
          </tr>
          <tr>
            <td>Phase</td>
            <td>
              <?php=conf_select('phase',$row['phase'])?>
              &nbsp;&nbsp;
              <input type="checkbox" name="reset_phase" value="1"> Reset all members of class to this Phase
            </td>
          </tr>  
          <tr>
            <td>Shift:</b></td>
            <td>
              <input type="checkbox" name="reset_shift" value="1"> Change all members of this class to Shift: 
              <?php=conf_select('shift','None');?>
            </td>
          </tr>
          <tr></tr>
          <tr>
          <td>Daily Status:</b></td>
          <td>
          	<input type="checkbox" name="reset_status" value="1">Change all members of this class to Status:
          	<?php echo status_select($_POST["status"]); ?>
          </td>
          </tr>
          <?php
          
          $x = 0;
          
          if($extras_result)
          {
            
            while($row = mysql_fetch_assoc($extras_result))
            {
                ?>
                    <tr>
                      <td>
                        <input type="hidden" name="extra_id[<?php=$x?>]" value="<?php=$row['extra_id']?>">
                        Field:<input type="text" name="field[<?php=$x?>]" size="21" maxlength="20" value="<?php=$row['field']?>">
                      </td>
                      <td>Value:<input type="text" name="value[<?php=$x?>]" size="40" maxlength="40" value="<?php=$row['value']?>"></td>
                    </tr>
                <?php
                $x++;                
            }            
          }
          
          for($y = $x; $y < 5; $y++)
          {
            ?>
              <tr>
                <td>Field:<input type="text" name="field[<?php=$y?>]" size="21" maxlength="20" value="<?php=htmlentities($_POST['field'][$y])?>"></td>
                <td>Value:<input type="text" name="value[<?php=$y?>]" size="40" maxlength="40" value="<?php=htmlentities($_POST['value'][$y])?>"></td>
              </tr>
            <?php
          }
          ?>
          <tr>
            <td >&nbsp;</td>
            <td >
              <input type="submit" name="edit_class" value="Submit Changes" class="button">
              <input type="submit" name="delete_class" value="Delete Class" class="button">
            </td>
          </tr>
        </table>
    </form>
    </td>
  </tr>
</table>