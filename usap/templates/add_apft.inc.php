<script language="javascript">
<!--

var db_height = new Array();
var db_weight = new Array();

<?php=$javascript?>

function set_height_weight()
{
    if(document.add_apft_form.id.value == '')
    {
        document.add_apft_form.height.value = '';
        document.add_apft_form.weight.value = '';
    }
    else
    {
        document.add_apft_form.height.value = db_height[document.add_apft_form.id.value];
        document.add_apft_form.weight.value = db_weight[document.add_apft_form.id.value];
    }
}
//-->
</script>
<form method='post' name='add_apft_form' action="add_apft.php">
  <table width="80%" border="1" cellspacing="2" cellpadding="2" align="center">
    <tr>
      <td class="table_cheading">add apft</td>
    </tr>
    <tr><td>
        <table border='0' cellspacing='2' cellpadding='2' width='100%' align='center'>
          <col width="25%"></col>
          <col width="25%"></col>
          <col width="25%"></col>
          <col width="25%"></col>
          <tr>
            <td>Choose soldier:</td>
            <td align="center">Age <span class="example">(leave blank for current age)</span></td>
            <td align="center">Height</td>
            <td align="center">Weight</td>
          </tr>
          <tr align="center">
            <td>
<?php
    echo "<select class='text_box' name='id' onchange='set_height_weight();' onload='set_height_weight();'>\n";
    echo "<option value='' selected >choose soldier...</option>\n";
    echo $options;
    echo "</select>\n";
?>
    </td>
            <td align="center"><input type="text" class="text_box" name="age" size="3" maxlength="2" value="<?php=$_POST['age']?>"></td>
            <td>
                <input type="text" class="text_box" name="height" size="3" maxlength="2" value='<?php=$_POST["height"]?>'>
            </td>
            <td>
                <input type="text" class="text_box" name="weight" size="4" maxlength="3" value='<?php=$_POST["weight"]?>'>
            </td>
          </tr>
          <tr align="center">
            <td>Raw Push-ups</td>
            <td>Raw Sit-ups</td>
            <td>Raw Run/Alt Event Time</td>
            <td>Type</td>
          </tr>
          <tr align="center">
            <td>
                <input type="text" class="text_box" name="raw_pu" size="4" maxlength="3" value='<?php=$_POST["raw_pu"]?>'>
                <input type="checkbox" class="text_box" name="exempt_pu" value="1" <?php if(isset($_POST['exempt_pu'])) { echo "checked"; }?>>Exempt
            </td>
            <td>
                <input type="text" class="text_box" name="raw_su" size="4" maxlength="3" value='<?php=$_POST["raw_su"]?>'>
                <input type="checkbox" class="text_box" name="exempt_su" value="1" <?php if(isset($_POST['exempt_su'])) { echo "checked"; }?>>Exempt                
            </td>
            <td>
                <input type="text" class="text_box" name="raw_run" size="6" maxlength="5" value='<?php=$_POST["raw_run"]?>'>
                <input type="checkbox" name="dnf" value="1" <?phpif(isset($_POST['dnf'])) { echo "checked"; }?>>DNF
            </td>
            <td>
                <?php=conf_select("apft_type",$_COOKIE["apft_type"])?>
            </td>
          </tr>
          <tr align="center">
            <td><input type="submit" class="button" name="view" value="View Current APFT"></td>
            <td>Date</td>
            <td>Alternate Event</td>
            <td>&nbsp;</td>
          </tr>
          <tr align="center">      
            <td><input type="submit" class="button" name="data_sheet" value="Go To Data Sheet"></td>
            <td>
                <input type="text" class="text_box" name="date" size="10" maxlength="9" value='<?php if(isset($_POST['date'])) { echo $_POST['date']; } else { echo strtoupper(date("dMY")); } ?>'>
            </td>
            <td>
                <?php=conf_select("alt_event")?>
            </td>
            <td>
                <input type="submit" name="apft_submit" value="enter" class="button">
            </td>
          </tr>
        </table>
    </td></tr>
  </table>
</form>
<script>set_height_weight();</script>
