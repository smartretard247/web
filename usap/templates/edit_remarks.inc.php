<script language='javascript'>
var db_subject = new Array();
var db_remark = new Array();
var db_date = new Array();
var db_restricted = new Array();

<?php=$javascript?>

function set_form()
{
    if(document.edit_remark_form.remark_id.value == '')
    {
        document.edit_remark_form.subject.value = 12;
        document.edit_remark_form.remark.value = '';
        document.edit_remark_form.new_date.value='';
        document.edit_remark_form.restricted.checked = false;
    }
    else
    {
        document.edit_remark_form.subject.value = db_subject[document.edit_remark_form.remark_id.value];
        document.edit_remark_form.remark.value = db_remark[document.edit_remark_form.remark_id.value];
        document.edit_remark_form.new_date.value = db_date[document.edit_remark_form.remark_id.value];
        document.edit_remark_form.restricted.checked = db_restricted[document.edit_remark_form.remark_id.value];
    }
}
</script>
<form method='post' action='<?php=$_SERVER["SCRIPT_NAME"]?>' name='edit_remark_form'>
<input type=hidden name=id value='<?php=$_REQUEST["id"]?>'>
  <table width="90%" border="1" cellspacing="1" cellpadding="1">
    <col width="30%"></col>
    <col width="70%"></col>
    <tr>
      <td align="right" colspan="2">
        <?php
          if($val->id($_REQUEST["id"],19) || $val->id($_REQUEST['id'],32))
          { echo " <a href='" . $_CONF["html"] . "/remarks.php?id=" . $_REQUEST["id"] . "'>View</a> "; }
          if($val->id($_REQUEST['id'],11))
          { echo " <a href='{$_CONF['html']}/data_sheet.php?id={$_REQUEST['id']}'>Data Sheet</a>"; }         
        ?>
      </td>
    </tr>
    <tr class="table_cheading">
      <td colspan="2">Edit Remark For <?php echo "$last_name, $first_name $middle_initial"; ?></td>
    </tr>
    <tr>
      <td>Choose Date:</td>
      <td>
<?php
    echo "<select name='remark_id' onload='set_form();' onchange='set_form();' >\n";
    echo "<option value=''>choose date...</option>\n";
    echo $options;
    echo "</select>\n";

    if($val->id($_REQUEST["id"],18) || $val->id($_REQUEST["id"],24))
    { echo "&nbsp;&nbsp;<input type='submit' class='button' name='delete' value='delete remark'>"; }
?>
</td>
    </tr>
    <tr>
      <td>New Date:</td>
      <td><input type="text" name="new_date" size="10" maxsize="9" value="<?php=$_POST["new_date"]?>"></td>
    </tr>
    <tr>
      <td>Choose Subject:</td>
      <td><?php echo subject_select($_POST["subject"]); ?></td>
    </tr>
    <tr>
      <td valign="top">Enter Remark:</td>
      <td>
        <textarea name="remark" wrap="physical" cols="70" rows="5"><?php=$_POST["remark"]?></textarea>
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>
        <input type="checkbox" name="restricted" value="1"> <strong>Restricted Remark</strong>
        <br>
        <span class="example">Restricted remarks are viewable by a smaller set of users, usually restricted to Commanders and 1SG/CSM only.</span>
      </td>
    <tr>
      <td>
        <div align="center">
          <input type="submit" class="button" name='remark_submit' value='Enter'>
        </div>
      </td>
      <td>&nbsp;</td>
    </tr>
  </table>
</form>
