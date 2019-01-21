<script language="javascript">
<!--

var db_height = new Array();
var db_weight = new Array();
var db_pu = new Array();
var db_su = new Array();
var db_run = new Array();
var db_alt_event = new Array();
var db_alt_score = new Array();
var db_date = new Array();
var db_type = new Array();
var db_use_alt = new Array();
var db_age = new Array();
var db_exempt_pu = new Array();
var db_exempt_su = new Array();

<?php=$javascript?>

function set_form()
{
    if(document.edit_apft_form.apft_id.value == '')
    {
        document.edit_apft_form.height.value = '';
        document.edit_apft_form.weight.value = '';
        document.edit_apft_form.raw_pu.value = '';
        document.edit_apft_form.raw_su.value = '';
        document.edit_apft_form.raw_run.value = '';
        document.edit_apft_form.alt_event.value = '';
        document.edit_apft_form.date.value = '';
        document.edit_apft_form.apft_type.value = 'student-eoc';
        document.edit_apft_form.dnf.checked = false;
        document.edit_apft_form.age.value = '';
        document.edit_apft_form.exempt_pu.checked = false;
        document.edit_apft_form.exempt_su.checked = false;
    }
    else
    {
        document.edit_apft_form.height.value = db_height[document.edit_apft_form.apft_id.value];
        document.edit_apft_form.weight.value = db_weight[document.edit_apft_form.apft_id.value];
        document.edit_apft_form.raw_pu.value = db_pu[document.edit_apft_form.apft_id.value];
        document.edit_apft_form.raw_su.value = db_su[document.edit_apft_form.apft_id.value];
        document.edit_apft_form.alt_event.value = db_alt_event[document.edit_apft_form.apft_id.value];
        document.edit_apft_form.date.value = db_date[document.edit_apft_form.apft_id.value];
        document.edit_apft_form.apft_type.value = db_type[document.edit_apft_form.apft_id.value];
        document.edit_apft_form.age.value = db_age[document.edit_apft_form.apft_id.value];
        if(db_run[document.edit_apft_form.apft_id.value] == '9999')
        {
          document.edit_apft_form.dnf.checked = true;
          document.edit_apft_form.raw_run.value = '';
        }
        else
        {
          document.edit_apft_form.raw_run.value = db_run[document.edit_apft_form.apft_id.value];
          document.edit_apft_form.dnf.checked = false;
        }
        if(db_exempt_pu[document.edit_apft_form.apft_id.value] == 1)
        { document.edit_apft_form.exempt_pu.checked = true; }
        else
        { document.edit_apft_form.exempt_pu.checked = false; }
        if(db_exempt_su[document.edit_apft_form.apft_id.value] == 1)
        { document.edit_apft_form.exempt_su.checked = true; }
        else
        { document.edit_apft_form.exempt_su.checked = false; }
    }
}
//-->
</script>
<form method='post' name='edit_apft_form' >
<input type=hidden name='id' value='<?php=$_REQUEST["id"]?>'>
  <table width="80%" border="1" cellspacing="2" cellpadding="2" align="center">
    <col width="25%"/>
    <col width="25%"/>
    <col width="25%"/>
    <col width="25%"/>
    <tr>
      <td align="right">
        <?php
          if($val->id($_REQUEST["id"],15))
          { echo " <a href='" . $_CONF["html"] . "/apft.php?id=" . $_REQUEST["id"] . "'>View</a>&nbsp;&nbsp;"; }
        ?>
        <a href="<?php=$_CONF['html']?>/data_sheet.php?id=<?php=$_REQUEST['id']?>">Data Sheet</a>
      </td>
    </tr>
    <tr>
      <td class="table_cheading">Edit APFT For <?php=$name?></td>
    </tr>
    <tr><td>
        <table border='0' cellspacing='2' cellpadding='2' width='100%' align='center'>
          <tr align="center">
            <td>Choose Date:</td>
            <td>Age</td>
            <td>Height</td>
            <td>Weight</td>
          </tr>
          <tr align="center">
            <td>
<?php
    echo "<select class='text_box' name='apft_id' onchange='set_form();' onload='set_form();'>\n";
    echo "<option value='' selected >Choose Date...</option>\n";
    echo $options;
    echo "</select>\n";
?>
            </td>
            <td><input type="text" class="text_box" name="age" size="3" maxlength="2" value="<?php if(isset($_POST['age'])) { echo $_POST['age']; } ?>"></td>
            <td>
                <input type="text" class="text_box" name="height" size="3" maxlength="2" value='<?php if(isset($_POST["height"])) { echo $_POST["height"]; }?>'>
            </td>
            <td>
                <input type="text" class="text_box" name="weight" size="4" maxlength="3" value='<?php if(isset($_POST["weight"])) { echo $_POST["weight"]; }?>'>
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
                <input type="text" class="text_box" name="raw_pu" size="4" maxlength="3" value='<?php if(isset($_POST["raw_pu"])) { echo $_POST["raw_pu"]; }?>'>
                <input type="checkbox" class="text_box" name="exempt_pu" value="1" <?php if(isset($_POST['exempt_pu'])) { echo "checked"; }?>>Exempt                
            </td>
            <td>
                <input type="text" class="text_box" name="raw_su" size="4" maxlength="3" value='<?php if(isset($_POST["raw_su"])) { echo $_POST["raw_su"]; }?>'>
                <input type="checkbox" class="text_box" name="exempt_su" value="1" <?php if(isset($_POST['exempt_su'])) { echo "checked"; }?>>Exempt                
            </td>
            <td>
                <input type="text" class="text_box" name="raw_run" size="6" maxlength="5" value='<?php if(isset($_POST["raw_run"])) { echo $_POST["raw_run"]; }?>'>
                <input type="checkbox" name="dnf" value="1" <?phpif(isset($_POST['dnf'])) { echo "checked"; }?>>DNF
            </td>
            <td>
        <?php=conf_select("apft_type",$_COOKIE["apft_type"])?>
            </td>
          </tr>
          <tr align="center">
            <td>&nbsp;</td>
            <td>Date</td>
            <td>Alternate Event</td>
            <td><?php=($val->id($_REQUEST['id'],9))?"Check Box to Delete":"&nbsp;"?></td>
          </tr>
          <tr align="center">
            <td>&nbsp;</td>
            <td>
                <input type="text" class="text_box" name="date" size="10" maxlength="9" value='<?php if(isset($_POST['date'])) { echo $_POST['date']; } ?>'>            
            </td>

            <td>
                <?php=conf_select("alt_event",$_POST['alt_event'])?>           
            </td>
            <td>
              <?php
                if($val->id($_REQUEST["id"],9))
                { echo "<input type='checkbox' name='delete' value='delete'>\n"; }
                else
                { echo "&nbsp;"; }
              ?>
            </td>
          </tr>
          <tr align="center">
            <td colspan="3">&nbsp;</td>
            <td>
              <input type="submit" name="apft_submit" value="enter" class="button">
            </td>
          </tr>
        </table>
    </td></tr>
  </table>
</form>
