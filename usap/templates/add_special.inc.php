<?php
//attempt to ensure that global variables
//are set to view this file. this can be bypassed,
//but viewing this page on it's own will just result
//in errors. no data will be shown.
if(!isset($_CONF["path"]))
{
    echo "access denied";
    exit();
}

if(isset($_POST['unit']))
{
    $t = explode("-",$_POST['unit']);
    $_POST['battalion'] = $t[0];
    $_POST['company'] = $t[1];
}

?>
<table width="80%" border="1" align="center">
  <tr>
    <td>
      <div align="center"><b><font size="4">This form will allow you to add a soldier/civilian
      to the database and it will not require all of the fields. Entering personnel this way could
      corrupt the database and cause some queries to fail. all dates should be entered in military
      format, i.e. 17NOV75 or 17NOV1975.</font></b></div>
    </td>
  </tr>
</table>
<p><?php=$msg?></p>
<form method='post' name="add_soldier" action="<?php=$_SERVER["SCRIPT_NAME"]?>">
  <table width="90%" border="1" align="center">
    <tr>
      <td class="table_heading">Basic Information</td>
    </tr>
    <tr>
      <td>
        <table width="100%" border="0" cellspacing="0" cellpadding="2">
          <col width="25%"></col>
          <col width="25%"></col>
          <col width="25%"></col>
          <col width="25%"></col>
          <tr>
            <td>Last Name</td>
            <td>First Name</td>
            <td>Middle Initial</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>
              <input type="text" name="last_name" maxlength="50" size="20" value="<?php=stripslashes(htmlentities($_POST["last_name"]))?>">
            </td>
            <td>
              <input type="text" name="first_name" size="20" maxlength="50" value="<?php=htmlentities($_POST["first_name"])?>">
            </td>
            <td>
              <input type="text" name="middle_initial" size="2" maxlength="1" value="<?php=htmlentities($_POST["middle_initial"])?>">
            </td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>Social Security Number</td>
            <td>Gender</td>
            <td>Rank</td>
            <td>Location</td>
          </tr>
          <tr>
            <td>
              <input type="text" name="ssn" size="12" maxlength="11" value="<?php=htmlentities($_POST["ssn"])?>">
            </td>
            <td>
              <?php echo conf_select("gender",$_POST["gender"]); ?>
            </td>
            <td>
              <?php echo conf_select("rank","None"); ?>
            </td>
            <td>
              <?php=conf_select("location",$_POST['location'])?>
            </td>
          </tr>
          <tr>
            <td>Personnel Type</td>
            <td>Unit</td>
            <td>DOB</td>
            <td>AKO Email (@us.army.mil)</td>
          </tr>
          <tr>
            <td>
              <?php echo conf_select("perm_party",$_POST['perm_party']); ?>
            </td>
            <td>
              <?php echo unit_select(28,$_POST['battalion'],$_POST['company']); ?>
            </td>
            <td>
              <input type="text" name="dob" size="10" maxlength="9" value='<?php=htmlentities($_POST["dob"])?>'>
            </td>
            <td>
              <input type='text' name='email' size='30' value='<?php=htmlentities($_POST["email"])?>'>
            </td>
          </tr>
          <tr>
            <td colspan="4" class="example">
              Not Required: Middle Initial, DOB, Email. If SSN is left blank, random 5 digit number will be created.
            </td>
        </table>
      </td>
    </tr>
    <tr>
      <td align="center">
        <input type="submit" name="submit" value="<?php if(isset($_POST[ssn])) { echo "re-"; } ?>enter information" class="button">
      </td>
    </tr>
  </table>
</form>
