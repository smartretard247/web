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


//if post values are present, they will take priority
//over cookie values
if(isset($_POST["pers_type"])) { $_COOKIE["pers_type"] = $_POST["pers_type"]; }
if(isset($_POST["mos"])) { $_COOKIE["mos"] = $_POST["mos"]; }
if(isset($_POST["building_number"])) { $_COOKIE["building_number"] = $_POST["building_number"]; }
if(isset($_POST["unit"]))
{
    $s = explode("-",$_POST["unit"]);
    $_COOKIE["battalion"] = $s[0];
    $_COOKIE["company"] = $s[1];
}

?>
<table width="80%" border="1" align="center">
  <tr>
    <td>
      <div align="center"><b><font size="4">this form will add a new soldier to
        the database. all dates are military format, i.e. 17nov75 or 17nov1975.</font></b></div>
    </td>
  </tr>
</table>
<p class="447sig"></p>
<form method='post' name="add_soldier" action="<?php=$_SERVER["SCRIPT_NAME"]?>">
  <table width="90%" border="1" align="center" class="447sig">
    <tr>
      <td class="table_heading">Soldier Information</td>
    </tr>
    <tr>
      <td>
        <table width="100%" border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td width="30%" height="21">last name</td>
            <td width="30%" height="21">first name</td>
            <td width="20%" height="21">middle initial</td>
            <td width="20%">&nbsp;</td>
          </tr>
          <tr>
            <td width="30%" class="select">
              <input type="text" name="last_name" maxlength="50" size="20" value="<?php=stripslashes($_POST["last_name"])?>">
            </td>
            <td width="30%">
              <input type="text" name="first_name" size="20" maxlength="50" value="<?php=$_POST["first_name"]?>">
            </td>
            <td width="20%">
              <input type="text" name="middle_initial" size="2" maxlength="1" value="<?php=$_POST["middle_initial"]?>">
            </td>
            <td width="20%">&nbsp;</td>
          </tr>
          <tr>
            <td width="30%">social security number</td>
            <td width="30%">gender</td>
            <td width="20%">rank</td>
            <td width="20%">Date of Rank</td>
          </tr>
          <tr>
            <td width="30%">
              <input type="text" name="ssn" size="12" maxlength="11" value="<?php=$_POST["ssn"]?>">
            </td>
            <td width="30%">
        <?php echo conf_select("gender",$_POST["gender"]); ?>
            </td>
            <td width="20%"><?php echo conf_select("rank",$_POST["rank"]); ?><input type='checkbox' name='promotable' value='(p)' <?php if($_POST['promotable'] == "(p)") { echo "checked"; }?>>promotable</td>
            <td width="20%"><input type="text" name="dor" size="10" maxlength="9" value="<?php=$_POST['dor']?>"></td>
          </tr>
          <tr>
            <td width="30%">personnel type</td>
            <td width="30%">ets <span class="example">(leave blank for indef)</span></td>
            <td width="20%">mos</td>
            <td width="20%">component</td>
          </tr>
          <tr>
            <td width="30%"> <?php echo conf_select("pers_type",$_COOKIE['addsoldier_pers_type']); ?>
            </td>
            <td width="30%">
              <input type="text" name="ets" size="10" maxlength="9" value="<?php=$_POST["ets"]?>">
            </td>
            <td width="20%">
              <?php=conf_select("mos",$_POST["mos"]);?>
            </td>
            <td width="20%"> <?php echo conf_select("component",$_POST["component"]); ?> </td>
          </tr>
          <tr>
            <td width="30%">date entered service</td>
            <td width="30%">u.s. citizen</td>
            <td width="20%">Issued CAC</td>
            <td width="20%">&nbsp;</td>
          </tr>
          <tr>
            <td width="30%">
              <input type="text" name="date_entered_service" size="10" maxlength="9" value="<?php=$_POST["date_entered_service"]?>">
            </td>
            <td width="30%">
              <input type="radio" name="us_citizen" value="Y" <?phpif($_POST["us_citizen"] == "Y" || !isset($_POST["us_citizen"])) { echo " checked "; } ?>>
              yes
              <input type="radio" name="us_citizen" value="N" <?phpif($_POST["us_citizen"] == "N") { echo " checked "; } ?>>
              no </td>
            <?php $_POST["cac"] = (isset($_POST["cac"])) ? $_POST["cac"] : "N"; ?>
            <td width="20%"><?php echo conf_select("yn",$_POST["cac"],0,0,"cac"); ?> </td>
            <td width="20%">&nbsp;</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td class="table_heading">Unit Information</td>
    </tr>
    <tr>
      <td>
        <table width="100%" border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td width="25%">unit</td>
            <td width="25%">platoon</td>
            <td width="50%"><!--security--></td>
          </tr>
          <tr>
            <td width="25%"><?php echo unit_select(1,$_COOKIE['battalion'],$_COOKIE['company']); ?></td>
            <td width="25%">
        <?php=conf_select("platoon",$_POST["platoon"])?>
            </td>
            <td width="50%"> <?php /*echo conf_select("security",$_POST["security"]);*/ ?> </td>
          </tr>
          <tr>
            <td colspan="2">building number <span class="example">(leave blank
              if off post)</span></td>
            <td width="50%">arrival date</td>
          </tr>
          <tr>
            <td colspan="2">
              <input type="text" name="building_number" size="10" value="<?php=$_COOKIE["building_number"]?>">
              <input type="checkbox" name="off_POST" value="off post" <?php if($_POST["off_POST"] == "off post") { echo " checked "; } ?>>
              off post </td>
            <td width="50%">
              <input type="text" name="arrival_date" size="10" maxlength="9" value='<?php if(isset($_POST["arrival_date"])) { echo $_POST["arrival_date"]; } else { echo military_date(time()); } ?>'>
            </td>
          </tr>
          <tr>
            <td colspan="2">status</td>
            <td width="50%">daily status comment <font size='-1'><i>(do not use for remarks, i.e., inactive, security, ucmj, etc. use remarks section or last section of this page for remarks of that nature.)</i></font></td>
          </tr>
          <tr>
            <td colspan="2"> <?php echo status_select($_POST["status"]); ?> </td>
            <td width="50%">
              <input type="text" name="status_remark" size="20" maxlength="25" value='<?php=stripslashes(str_replace("'","&#39;",$_POST["status_remark"]))?>'>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td class="table_heading">Attached / Detached Info</td>
    </tr>
    <tr>
      <td>
        <table width="100%" cellspacing="2" cellpadding="2">
          <tr>
            <td>
              Location
            </td>
            <td rowspan="2" class="example">
              If Location is not 'Organic' the following information must be provided,
              otherwise it will be ignored.
              <br>
              If Location is listed as 'Detached' then control of this soldier will be
              transfered to the unit listed under 'Attached To' and the
              soldiers unit will be changed to match the unit listed under 'Attached To'.
            </td>
          </tr>
          <tr>
            <td>
              <?php=conf_select("location",$_POST['location']);?>
            </td>
          </tr>
        </table>
        <table width="100%" cellspacing="2" cellpadding="2">
          <tr>
            <td width="33%">Detached From</td>
            <td width="33%">Attached To</td>
            <td width="34%">Date</td>
          </tr>
          <tr>
            <td width="33%">
              Bn: <?php=battalion_select("detached_bn",$_POST['detached_bn'])?> Co: <?php=company_select("detached_co",$_POST['detached_co'])?>
            </td>
            <td width="33%">
              Bn: <?php=battalion_select("attached_bn",$_POST['attached_bn'])?> Co: <?php=company_select("attached_co",$_POST['attached_co'])?>
            </td>
            <td width="34%">
              <input type="text" class="text_box" name="assigned_date" size="10" maxlength="9" value='<?php=$_POST["assigned_date"]?>'>
            </td>
          </tr>
          <tr>
            <td width="33%">Position</td>
            <td colspan="2">Reason</td>
          </tr>
          <tr>
            <td width="33%">
              <input type="text" class="text_box" name="assigned_position" size="15" value='<?php=htmlentities($_POST["assigned_position"])?>'>
            </td>
            <td colspan="2">
              <input type="text" class="text_box" name="assigned_reason" size="20" value='<?php=htmlentities($_POST["assigned_reason"])?>'>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td class="table_heading">Medical Data</td>
    </tr>
    <tr>
      <td>
        <table width="100%" border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td>blood type</td>
            <td>dental category</td>
            <td>hiv test date</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>
        <?php=conf_select("blood_type",$_POST["blood_type"])?>
            </td>
            <td>
        <?php=conf_select("dental_category",$_POST["dental_category"])?>
            </td>
            <td>
              <input type="text" name="hiv_date" size="10" maxlength="9" value='<?php=$_POST["hiv_date"]?>'>
            </td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>height</td>
            <td>weight</td>
            <td>hair color</td>
            <td>eye color</td>
          </tr>
          <tr>
            <td>
              <input type="text" name="height" size="3" maxlength="2" value='<?php=$_POST["height"]?>'>
            </td>
            <td>
              <input type="text" name="weight" size="4" maxlength="3" value='<?php=$_POST["weight"]?>'>
            </td>
            <td>
              <input type="text" name="hair_color" size="11" maxlength="10" value='<?php=$_POST["hair_color"]?>'>
            </td>
            <td>
              <input type="text" name="eye_color" size="11" maxlength="10" value='<?php=$_POST["eye_color"]?>'>
            </td>
          </tr>
          <tr>
            <td>profile</td>
            <td>profile start date</td>
            <td>profile end date</td>
            <td>recovery end date</td>
          </tr>
          <tr>
            <td>Profile Type</td>
            <td>Profile Start Date</td>
            <td>Profile Length (Days)</td>
            <td>Reason</td>
          </tr>
          <tr>
            <td>
              <?php=conf_select("profile",$_POST["profile"]);?>
            </td>
            <td>
              <input type="text" class="text_box" name="profile_start" size="10" maxlength="9" value="<?php=$_POST["profile_start"]?>">
            </td>
            <td>
              <input type="text" class="text_box" name="profile_length" size="4" maxlength="3" value="<?php=$_POST["profile_length"]?>">
              <span class="example">(Leave blank for permanent)</span>
            </td>
            <td>
              <input type="text" class="text_box" name="profile_reason" size="20" maxlength="255" value="<?php=$_POST["profile_reason"]?>">
            </td>
          </tr>
          <tr>
            <td colspan="4">Limitations</td>
          </tr>
          <tr>
            <td colspan="4">
              <input type="text" class="text_box" name="profile_limitations" size="50" maxlength="255" value="<?php=$_POST["profile_limitations"]?>">
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td class="table_heading">Personal Info</td>
    </tr>
    <tr>
      <td>
        <table width="100%" border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td width="33%">marital status</td>
            <td width="33%">number of dependents</td>
            <td width="25%">dob</td>
          </tr>
          <tr>
            <td width="33%"> <?php echo conf_select("marital_status",$_POST["marital_status"]); ?> </td>
            <td width="33%">
              <input type="text" name="num_dependents" size="3" maxlength="2" value="<?php if(isset($_POST["num_dependents"])) { echo $_POST["num_dependents"]; } else { echo "0"; } ?>">
            </td>
            <td width="25%">
              <input type="text" name="dob" size="10" maxlength="9" value='<?php=$_POST["dob"]?>'>
            </td>
          </tr>
          <tr>
            <td width="33%">religion</td>
            <td width="33%">education</td>
            <td width="34%">colleges attended</td>
          </tr>
          <tr>
            <td width="33%">
              <?php= conf_select("religion",$_POST['religion']); ?>
            </td>
            <td width="33%"> <?php echo conf_select("education",$_POST["education"]); ?> </td>
            <td width="34%">
              <input type="text" name="colleges" size="20" maxlength="50" value='<?php=stripslashes(str_replace("'","&#39;",$_POST["colleges"]))?>'>
            </td>
          </tr>
          <tr>
            <td colspan="2">special skills</td>
            <td width="34%">race</td>
          </tr>
          <tr>
            <td colspan="2">
              <input type="text" name="special_skills" size="40" maxlength="50" value='<?php=stripslashes(str_replace("'","&#39;",$_POST["special_skills"]))?>'>
            </td>
            <td width="34%">
              <?php echo conf_select('race',$_POST["race"]); ?>
            </td>
          </tr>
          <tr>
            <td colspan="2">sports</td>
            <td width="34%">ako email (@us.army.mil)</td>
          </tr>
          <tr>
            <td colspan="2">
              <input type="text" name="sports" size="40" maxlength="50" value='<?php=stripslashes(str_replace("'","&#39;",$_POST["sports"]))?>'>
            </td>
            <td width="34%"><input type='text' name='email' size='30' value='<?php=stripslashes(str_replace("'","&#39;",$_POST["email"]))?>'></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td class="table_heading">POV Information</td>
    </tr>
    <tr>
      <td>
        <table width="100%" border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td width="33%">make</td>
            <td width="33%">model</td>
            <td width="34%">&nbsp;</td>
          </tr>
          <tr>
            <td width="33%">
              <input type="text" name="pov_make" size="26" maxlength="25" value='<?php=stripslashes(str_replace("'","&#39;",$_POST["pov_make"]))?>'>
            </td>
            <td width="33%">
              <input type="text" name="pov_model" size="26" maxlength="25" value='<?php=stripslashes(str_replace("'","&#39;",$_POST["pov_model"]))?>'>
            </td>
            <td width="34%">&nbsp;</td>
          </tr>
          <tr>
            <td width="33%">year</td>
            <td width="33%">state</td>
            <td width="34%">tag number</td>
          </tr>
          <tr>
            <td width="33%">
              <input type="text" name="pov_year" size="5" maxlength="4" value='<?php=$_POST["pov_year"]?>'>
            </td>
            <td width="33%">
              <input type="text" name="pov_state" size="3" maxlength="2" value='<?php=$_POST["pov_state"]?>'>
            </td>
            <td width="34%">
              <input type="text" name="pov_tag" size="10" value='<?php=stripslashes(str_replace("'","&#39;",$_POST["pov_tag"]))?>'>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td class="table_heading">
        Contact Information <span class="example">(complete
          as many as necessary, at least one required)</span>
      </td>
    </tr>
    <tr>
      <td>
        <div align="left">
          <table width="100%" border="0" cellspacing="2" cellpadding="2">
            <tr>
              <td width="33%">address &#035;1 type</td>
              <td width="33%">name <span class="example">(person at this address)</span></td>
              <td width="33%">relationship</td>
            </tr>
            <tr>
              <td width="33%">
                <?php echo conf_select("address_type",$_POST["address_type"][0],1); ?>
              </td>
              <td width="33%">
                <input type="text" name="name[]" size="20" value='<?php=stripslashes(str_replace("'","&#39;",$_POST["name"][0]))?>'>
              </td>
              <td width="33%">
                <input type="text" name="relationship[]" size="20" value='<?php=stripslashes(str_replace("'","&#39;",$_POST["relationship"][0]))?>'>
              </td>
            </tr>
            <tr>
              <td width="33%">street 1</td>
              <td width="33%">street 2</td>
              <td width="33%">&nbsp;</td>
            </tr>
            <tr>
              <td width="33%">
                <input type="text" name="street1[]" size="20" value='<?php=stripslashes(str_replace("'","&#39;",$_POST["street1"][0]))?>'>
              </td>
              <td width="33%">
                <input type="text" name="street2[]" size="20" value='<?php=stripslashes(str_replace("'","&#39;",$_POST["street2"][0]))?>'>
              </td>
              <td width="33%">&nbsp;</td>
            </tr>
            <tr>
              <td width="33%">city</td>
              <td width="33%">state</td>
              <td width="33%">zip</td>
            </tr>
            <tr>
              <td width="33%">
                <input type="text" name="city[]" size="20" value='<?php=stripslashes(str_replace("'","&#39;",$_POST["city"][0]))?>'>
              </td>
              <td width="33%">
                <input type="text" name="state[]" size="3" maxlength="2" value='<?php=$_POST["state"][0]?>'>
              </td>
              <td width="33%">
                <input type="text" name="zip[]" size="6" value='<?php=$_POST["zip"][0]?>'>
              </td>
            </tr>
            <tr>
              <td width="33%">phone 1</td>
              <td width="33%">phone 2</td>
              <td width="33%">country</td>
            </tr>
            <tr>
              <td width="33%">
                <input type="text" name="phone1[]" size="20" value='<?php=stripslashes(str_replace("'","&#39;",$_POST["phone1"][0]))?>'>
              </td>
              <td width="33%">
                <input type="text" name="phone2[]" size="20" value='<?php=stripslashes(str_replace("'","&#39;",$_POST["phone2"][0]))?>'>
              </td>
              <td width="33%">
                <input type="text" name="country[]" size="20" value="<?php if(isset($_POST["country"][0])) { echo stripslashes(str_replace("'","&#39;",$_POST["country"][0])); } else { echo "usa"; } ?>">
              </td>
            </tr>
            <tr>
              <td colspan="3">
                <hr width="50%">
              </td>
            </tr>
            <tr>
              <td width="33%">address &#035;2 type</td>
              <td width="33%">name <span class="example">(person at this address)</span></td>
              <td width="33%">relationship</td>
            </tr>
            <tr>
              <td width="33%">
                <?php echo conf_select("address_type",$_POST["address_type"][1],1); ?>
              </td>
              <td width="33%">
                <input type="text" name="name[]" size="20" value='<?php=stripslashes(str_replace("'","&#39;",$_POST["name"][1]))?>'>
              </td>
              <td width="33%">
                <input type="text" name="relationship[]" size="20" value='<?php=stripslashes(str_replace("'","&#39;",$_POST["relationship"][1]))?>'>
              </td>
            </tr>
            <tr>
              <td width="33%">street 1</td>
              <td width="33%">street 2</td>
              <td width="33%">&nbsp;</td>
            </tr>
            <tr>
              <td width="33%">
                <input type="text" name="street1[]" size="20" value='<?php=stripslashes(str_replace("'","&#39;",$_POST["street1"][1]))?>'>
              </td>
              <td width="33%">
                <input type="text" name="street2[]" size="20" value='<?php=stripslashes(str_replace("'","&#39;",$_POST["street2"][1]))?>'>
              </td>
              <td width="33%">&nbsp;</td>
            </tr>
            <tr>
              <td width="33%">city</td>
              <td width="33%">state</td>
              <td width="33%">zip</td>
            </tr>
            <tr>
              <td width="33%">
                <input type="text" name="city[]" size="20" value='<?php=stripslashes(str_replace("'","&#39;",$_POST["city"][1]))?>'>
              </td>
              <td width="33%">
                <input type="text" name="state[]" size="3" maxlength="2" value='<?php=$_POST["state"][1]?>'>
              </td>
              <td width="33%">
                <input type="text" name="zip[]" size="6" value='<?php=$_POST["zip"][1]?>'>
              </td>
            </tr>
            <tr>
              <td width="33%">phone 1</td>
              <td width="33%">phone 2</td>
              <td width="33%">country</td>
            </tr>
            <tr>
              <td width="33%">
                <input type="text" name="phone1[]" size="20" value='<?php=stripslashes(str_replace("'","&#39;",$_POST["phone1"][1]))?>'>
              </td>
              <td width="33%">
                <input type="text" name="phone2[]" size="20" value='<?php=stripslashes(str_replace("'","&#39;",$_POST["phone2"][1]))?>'>
              </td>
              <td width="33%">
                <input type="text" name="country[]" size="20" value="<?php if(isset($_POST["country"][1])) { echo stripslashes(str_replace("'","&#39;",$_POST["country"][1])); } else { echo "usa"; } ?>">
              </td>
            </tr>
            <tr>
              <td colspan="3">
                <hr width="50%">
              </td>
            </tr>
            <tr>
              <td width="33%">address &#035;3 type</td>
              <td width="33%">name <span class="example">(person at this address)</span></td>
              <td width="33%">relationship</td>
            </tr>
            <tr>
              <td width="33%">
                <?php echo conf_select("address_type",$_POST["address_type"][2],1); ?>
              </td>
              <td width="33%">
                <input type="text" name="name[]" size="20" value='<?php=stripslashes(str_replace("'","&#39;",$_POST["name"][2]))?>'>
              </td>
              <td width="33%">
                <input type="text" name="relationship[]" size="20" value='<?php=stripslashes(str_replace("'","&#39;",$_POST["relationship"][2]))?>'>
              </td>
            </tr>
            <tr>
              <td width="33%">street 1</td>
              <td width="33%">street 2</td>
              <td width="33%">&nbsp;</td>
            </tr>
            <tr>
              <td width="33%">
                <input type="text" name="street1[]" size="20" value='<?php=stripslashes(str_replace("'","&#39;",$_POST["street1"][2]))?>'>
              </td>
              <td width="33%">
                <input type="text" name="street2[]" size="20" value='<?php=stripslashes(str_replace("'","&#39;",$_POST["street2"][2]))?>'>
              </td>
              <td width="33%">&nbsp;</td>
            </tr>
            <tr>
              <td width="33%">city</td>
              <td width="33%">state</td>
              <td width="33%">zip</td>
            </tr>
            <tr>
              <td width="33%">
                <input type="text" name="city[]" size="20" value='<?php=stripslashes(str_replace("'","&#39;",$_POST["city"][2]))?>'>
              </td>
              <td width="33%">
                <input type="text" name="state[]" size="3" maxlength="2" value='<?php=$_POST["state"][2]?>'>
              </td>
              <td width="33%">
                <input type="text" name="zip[]" size="6" value='<?php=$_POST["zip"][2]?>'>
              </td>
            </tr>
            <tr>
              <td width="33%">phone 1</td>
              <td width="33%">phone 2</td>
              <td width="33%">country</td>
            </tr>
            <tr>
              <td width="33%">
                <input type="text" name="phone1[]" size="20" value='<?php=stripslashes(str_replace("'","&#39;",$_POST["phone1"][2]))?>'>
              </td>
              <td width="33%">
                <input type="text" name="phone2[]" size="20" value='<?php=stripslashes(str_replace("'","&#39;",$_POST["phone2"][2]))?>'>
              </td>
              <td width="33%">
                <input type="text" name="country[]" size="20" value="<?php if(isset($_POST["country"][2])) { echo stripslashes(str_replace("'","&#39;",$_POST["country"][2])); } else { echo "usa"; } ?>">
              </td>
            </tr>
            <tr>
              <td colspan="3">
                <hr width="50%">
              </td>
            </tr>
          </table>
        </div>
      </td>
    </tr>
    <tr>
      <td class="table_heading">
        School Information <span class="example">(leave
          blank for permanent party)</span>
      </td>
    </tr>
    <tr>
      <td>
        <table width="100%" border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td width="33%">birth city</td>
            <td width="33%">birth state</td>
            <td width="34%">birth country</td>
          </tr>
          <tr>
            <td width="33%">
              <input type="text" name="birth_city" size="20" value='<?php=$_POST["birth_city"]?>'>
            </td>
            <td width="33%">
              <input type="text" name="birth_state" size="3" maxlength="2" value='<?php=$_POST["birth_state"]?>'>
            </td>
            <td width="34%">
              <input type="text" name="birth_country" size="20"  value="<?php if(isset($_POST["birth_country"])) { echo $_POST["birth_country"]; } else { echo "usa"; } ?>">
            </td>
          </tr>
          <tr>
            <td width="33%">civilian occupation</td>
            <td width="33%">basic training post</td>
            <td width="34%">aot type</td>
          </tr>
          <tr>
            <td width="33%">
              <input type="text" name="civilian_occupation" size="20"  value="<?php if(isset($_POST["civilian_occupation"])) { echo $_POST["civilian_occupation"]; } else { echo "none (n/a)"; } ?>">
            </td>
            <td width="33%">
              <?php=conf_select("bct_location",$_POST["bct_location"])?>
            </td>
            <td width="34%">
              <?php=conf_select("aot_type",$_POST["aot_type"])?>
            </td>
          </tr>
          <tr>
            <td width="33%">Phase</td>
            <td width="34%">MEPS Station</td>
            <td width="34%">&nbsp;</td>
          </tr>
          <tr>
            <td width="33%"><?php=conf_select("phase",$_POST["phase"])?></td>
            <td width="33%"><?php=conf_select("meps",$_POST['meps'])?></td>
            <td width="34%"><input type="checkbox" name="airborne" value="Y" <?phpif($_POST["airborne"] == "Y") { echo " checked "; } ?> > Airborne</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td class="table_heading">Remarks</td>
    </tr>
    <tr>
      <td>
        <table width="100%" border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td>subject</td>
          </tr>
          <tr>
            <td>
              <?php echo subject_select($_POST["subject"]); ?>
            </td>
          </tr>
          <tr>
            <td>remark</td>
          </tr>
          <tr>
            <td>
              <textarea name="remark" cols="75" rows="4" wrap="physical"><?php=$_POST["remark"]?></textarea>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td class="table_cheading">
        <input type="submit" name="submit" value="<?php if(isset($_POST[ssn])) { echo "Re-"; } ?>Enter Information" class="button">
      </td>
    </tr>
  </table>
</form>
<p>&nbsp; </p>
