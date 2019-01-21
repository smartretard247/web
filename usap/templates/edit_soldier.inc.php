<script language="javascript">
function confirm_move()
{
    move_string = "Click ok to move this soldier to a new unit.\n"
    + "You will lose all control over this soldier \n"
    + "if you do not have permissions for the new unit."
    + "All permissions will be removed for this soldier and "
    + "the gaining unit will need to reset them.";
    return confirm(move_string)
}

function phase_change()
{
    today = '<?php=strtoupper(date('dMy'))?>';

    if(document.edit_soldier.phase.value=='IV')
    { document.edit_soldier.date_phaseiv.value = today; }
    if(document.edit_soldier.phase.value=='V')
    { document.edit_soldier.date_phasev.value = today; }
    if(document.edit_soldier.phase.value=='V+')
    { document.edit_soldier.date_phaseva.value = today; }
}

</script>
<?php

$phase_select = conf_select("phase",$student_row["phase"]);
$phase_select = add_attribute($phase_select,'onchange="phase_change();"');

//attempt to ensure that global variables
//are set to view this file. this can be bypassed,
//but viewing this page on it's own will just result
//in errors. no data will be shown.
if(!isset($_CONF["path"]))
{
    echo "access denied";
    exit();
}
if($main_row["pcs"] == 1)
{
?>
<table width="90%" border="1" align="center" class="447sig">
    <tr class="table_heading">
        <td>NOTICE: This soldier is no longer active in the unit.</td>
    </tr>
    <tr>
        <td><strong>Departure Type:</strong> <?php=$main_row["pcs_type"]?> <?php if(strlen($main_row["ets_chapter_type"]) > 0) { echo " (" . $main_row["ets_chapter_type"] . ")"; } ?></td>
    </tr>
    <tr>
        <td><strong>Departure Date:</strong> <?php=$main_row["pcs_date"]?></td>
    </tr>
    <tr>
        <td><strong>Gaining Unit:</strong> <?php=$main_row["gaining_unit"]?></td>
    </tr>
    <tr>
        <td><strong>Remarks:</strong> <?php=$main_row["pcs_remark"]?></td>
    </tr>
    <?php
    if($val->id($_REQUEST['id'],3))
    {
        echo "<form method='get' action='" . $_CONF['html'] . "/edit_soldier.php'><tr><td>"
            ."<input type='hidden' name='id' value='" . (int)$_REQUEST['id'] . "'>"
            ."<input type='submit' class='button' name='restore' value='Restore Soldier to Unit'>"
            ."</td></tr></form>";
    }
    ?>
</table>
<?php
}
//end pcs if
?>
<p>
<table width="80%" border="1" align="center">
  <tr>
    <td class="notice">
      This form will change information on a soldier in the database. All dates
        are military format, i.e. 17NOV75 or 17NOV1975.
    </td>
  </tr>
</table>
<?php
if(isset($_REQUEST['nochoose']))
{ echo "<input type='hidden' name='nochoose' value='1'>\n"; }
?>
  <table width="90%" border="1" align="center">
    <tr>
      <td align="right">
        <?php
          //always display view link. full view or partial will be determined within data_sheet.php
          echo " <a href='" . $_CONF["html"] . "/data_sheet.php?id=" . $_REQUEST["id"] . "'>View</a> ";
          //only display delete link if user has permission to delete this soldier
          if($val->id($_REQUEST["id"],3))
          { echo " <a href='" . $_CONF["html"] . "/delete.php?id=" . $_REQUEST["id"] . "&what=soldier'>Delete</a> ";}
        ?>
      </td>
    </tr>
    <?php
    //show row to move soldier if user has permission
    if($val->id($_REQUEST['id'],27))
    {
        ?>
        <tr>
          <form method="post" action="edit_soldier.php" name="move_soldier" onsubmit="return confirm_move();">
          <td>
            <input type="hidden" name="id" value="<?php=$_REQUEST["id"]?>">
            <strong>Move soldier to new unit:</strong>
            Battalion: <?php=battalion_select('battalion_id',$main_row['battalion'])?>
            Company: <?php=company_select('company_id',$main_row['company'])?>
            <input type="submit" name="move" value="Move">
          </td>
          </form>
        </tr>
        <?php
    }
    ?>
    <form method='post' name="edit_soldier" action="edit_soldier.php">
    <input type="hidden" name="id" value="<?php=$_REQUEST["id"]?>">
    <tr>
      <td class="table_heading">Soldier Information</td>
    </tr>
    <tr>
      <td>
        <table width="100%" border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td width="30%">Last Name</td>
            <td width="30%">First Name</td>
            <td width="20%">Middle Initial</td>
            <td width="20%">&nbsp;</td>
          </tr>
          <tr>
            <td width="30%" class="select">
              <input type="text" class="text_box" name="last_name" maxlength="50" size="20" value='<?php=str_replace("'","&#39;",$main_row["last_name"])?>'>
            </td>
            <td width="30%">
              <input type="text" class="text_box" name="first_name" size="20" maxlength="50" value='<?php=$main_row["first_name"]?>'>
            </td>
            <td width="20%">
              <input type="text" class="text_box" name="middle_initial" size="2" maxlength="1" value='<?php=$main_row["middle_initial"]?>'>
            </td>
            <td width="20%">&nbsp;</td>
          </tr>
          <tr>
            <td width="30%">Social Security Number</td>
            <td width="30%">Gender</td>
            <td width="20%">Rank</td>
            <td width="20%">Date of Rank</td>
          </tr>
          <tr>
            <td width="30%">
<?php
    if($allow_full_ssn == 1)
    { echo "<input type='text' class='text_box' name='ssn' size='12' maxlength='11' value='" . $main_row["ssn"] . "'>"; }
    else
    { echo substr($main_row["ssn"],-4); }
?>
            </td>
            <td width="30%">
                <?php=conf_select("gender",$main_row['gender'])?>
            </td>
            <td width="20%"><?php echo conf_select("rank",$main_row["rank"]); ?><input type='checkbox' name='promotable' value='p' <?phpif($main_row['promotable'] == "(P)") { echo "checked"; }?>>promotable</td>
            <td width="20%"><input type="text" name="dor" size="10" maxlength="9" value="<?php=$main_row['dor']?>"></td>
          </tr>
          <tr>
            <td width="30%">Personnel Type <input type="hidden" name="old_pers_type" value="<?php=$main_row['pers_type']?>"></td>
            <td width="30%">ETS <span class="example">(leave blank for indef)</span></td>
            <td width="20%">MOS</td>
            <td width="20%">Component</td>
          </tr>
          <tr>
            <td width="30%"> <?php echo conf_select("pers_type",$main_row["pers_type"]); ?> </td>
            <td width="30%">
              <input type="text" class="text_box" name="ets" size="10" maxlength="9" value='<?php=$main_row["ets"]?>'>
            </td>
            <td width="20%">
              <?php=conf_select("mos",$main_row["mos"])?>
            </td>
            <td width="20%"> <?php echo conf_select("component",$main_row["component"]); ?> </td>
          </tr>
          <tr>
            <td width="30%">Date Entered Service</td>
            <td width="30%">PCS Date / Location</td>
            <td width="20%">U.S. Citizen</td>
            <td width="20%">Issued CAC</td>
          </tr>
          <tr>
            <td width="30%">
              <input type="text" class="text_box" name="date_entered_service" size="10" maxlength="9" value='<?php=$main_row["date_entered_service"]?>'>
            </td>
            <td width="30%">
              <input type="text" class="text_box" name="pcs_date" size="10" maxlength="9" value="<?php=$main_row['pcs_date']?>">
               /
              <?php=conf_select('pcs_location',$main_row['pcs_location'])?>
            </td>
            <td width="20%">
              <input type="radio" name="us_citizen" value="Y" <?php if($main_row["us_citizen"] == "Y") { echo " checked "; } ?>>
              Yes
              <input type="radio" name="us_citizen" value="N" <?php if($main_row["us_citizen"] == "N") { echo " checked "; } ?>>
              No
            </td>
            <td width="20%"><?php echo conf_select("yn",$main_row["cac"],0,0,"cac"); ?> </td>
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
            <td width="25%">Unit</td>
            <td width="25%">Platoon</td>
	    <td width="25%">Room Number</td>
            <td width="50%">&nbsp;</td>
          </tr>
          <tr>
            <td width="25%"><?php echo unit_select(2,$main_row["battalion"],$main_row["company"]); ?></td>
            <td width="25%">
            <?php=conf_select("platoon",$main_row['platoon'])?>
            </td>
	    <td width="25%"><input type="text" class="text_box" name="room_number" size="10" value='<?php=$main_row["room_number"];?>'></td>
	    <td width="25%">&nbsp;</td>
          </tr>
          <tr>
            <td width="25%">Building Number</td>
            <td width="25%"><input type="checkbox" name="flagged" value="Y" <?phpif($main_row['flagged'] == "Y") { echo "checked"; } ?>>Flagged</td>
            <td width="50%">Arrival Date</td>
          </tr>

          <tr>
            <td width="25%">
              <input type="text" class="text_box" name="building_number" size="10" value='<?php=$main_row["building_number"];?>'>
              <input type="checkbox" name="off_post" value="off post" <?php if($main_row["building_number"] == "OFF_POST") { echo " checked "; } ?>>
              off post </td>
            <td width="25%">date: <input type="text" class="text_box" name="flag_date" size="10" maxlength="9" value="<?php=$main_row["flag_date"]?>"></td>
            <td width="50%">
              <input type="text" class="text_box" name="arrival_date" size="10" maxlength="9" value='<?php=$main_row["arrival_date"]?>'>
            </td>
          </tr>
          <tr>
            <td colspan="2">Daily Status <?php=status_select2($main_row['status'],$applies_to,'active')?></td>
            <td width="50%">Daily Status Comment <font size='-1'><i>(Do not use for remarks, i.e., Inactive, Security, UCMJ, etc. Use remarks section or last section of this page for remarks of that nature.)</i></font></td>
          </tr>
          <tr>
            <td colspan="2">Inactive Status <?php=status_select2($main_row["inact_status"],$applies_to,'inactive',1)?> </td>
            <td width="50%">
              <input type="text" class="text_box" name="status_remark" size="20" maxlength="25" value='<?php=str_replace("'","&#39;",$main_row["status_remark"])?>'>
            </td>
          </tr>
        </table>
      </td>
    </tr>
<?php
if(in_array($main_row['pers_type'],$_CONF['perm_party']))
{
    ?>
    <tr>
      <td class="table_heading">TDA Position Information</td>
    </tr>
    <tr>
      <td>
        <table border="0" width="100%" cellspacing="2" cellpadding="2">
          <tr>
            <td class="example" colspan="2">
              Under Construction: Assigned TDA is the official paragraph and line number that personnel
              are assigned under. The last number in paranthesis represents the Authorized number of personnel
              allowed in that position. The Assigned TDA position will only allow you to assign people if
              there are authorized slots left. The Working TDA position will allow you to assign people anywhere
              within the unit without limiting to the number of authorized positions.
            </td>
          </tr>
          <tr>
            <td>Assigned TDA Position</td>
            <td>
              <?php=tda_select($main_row['battalion'], $main_row['company'], $main_row['pers_type'], $tda_row['assigned_tda_id'])?>
            </td>
          </tr>
          <tr>
            <td>Working TDA Position</td>
            <td>
              <?php=tda_select($main_row['battalion'], $main_row['company'], $main_row['pers_type'], $tda_row['working_tda_id'], 'working')?>
            </td>
          </tr>
          <tr>
            <td>Comment</td>
            <td>
              <input type="text" name="tda_comment" size="40" maxlength="255" value="<?php=$tda_row['comment']?>">
            </td>
        </table>
      </td>
    </tr>
    <?php
}
?>
    <tr>
      <td class="table_heading">Attached / Detached Information</td>
    </tr>
    <tr>
      <td>
        <table border="0" width="100%" cellspacing="2" cellpadding="2">
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
                    soldier's unit will be changed to match the unit listed under 'Attached To'.
                    <br>
                    Any unit set to 'Other' must be explained in the 'Reason' text box.
                  </td>
                </tr>
                <tr>
                  <td>
                    <?php=conf_select("location",$main_row['location']);?>
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
                    Bn: <?php=add_option(battalion_select("detached_bn",$location_row['detached_bn']),'')?> Co: <?php=add_option(company_select("detached_co",$location_row['detached_co']),'')?>
                  </td>
                  <td width="33%">
                    Bn: <?php=add_option(battalion_select("attached_bn",$location_row['attached_bn']),'')?> Co: <?php=add_option(company_select("attached_co",$location_row['attached_co']),'')?>
                  </td>
                  <td width="34%">
                    <input type="text" class="text_box" name="assigned_date" size="10" maxlength="9" value='<?php=$location_row["effective"]?>'>
                  </td>
                </tr>
                <tr>
                  <td width="33%">Position</td>
                  <td colspan="2">Reason</td>
                </tr>
                <tr>
                  <td width="33%">
                    <input type="text" class="text_box" name="assigned_position" size="15" value='<?php=str_replace("'","&#39;",$location_row["position"])?>'>
                  </td>
                  <td colspan="2">
                    <input type="text" class="text_box" name="assigned_reason" size="40" maxlength="255" value='<?php=str_replace("'","&#39;",$location_row["reason"])?>'>
                  </td>
                </tr>
              </table>
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
            <td>Blood Type</td>
            <td>Dental Category</td>
            <td>HIV Test Date</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td> <?php echo conf_select("blood_type",$main_row["blood_type"]); ?> </td>
            <td>
                <?php=conf_select("dental_category",$main_row['dental_category'])?>
            </td>
            <td>
              <input type="text" class="text_box" name="hiv_date" size="10" maxlength="9" value='<?php=$main_row["hiv_date"]?>'>
            </td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>Height</td>
            <td>Weight</td>
            <td>Hair Color</td>
            <td>Eye Color</td>
          </tr>
          <tr>
            <td>
              <input type="text" class="text_box" name="height" size="3" maxlength="2" value='<?php=$main_row["height"]?>'>
            </td>
            <td>
              <input type="text" class="text_box" name="weight" size="4" maxlength="3" value='<?php=$main_row["weight"]?>'>
            </td>
            <td>
              <input type="text" class="text_box" name="hair_color" size="11" maxlength="10" value='<?php=$main_row["hair_color"]?>'>
            </td>
            <td>
              <input type="text" class="text_box" name="eye_color" size="11" maxlength="10" value='<?php=$main_row["eye_color"]?>'>
            </td>
          </tr>
          <?php if(isset($profile_row) && count($profile_row)>0) : ?>
          <tr>
            <td colspan="4" class="column_name">Current Profiles</td>
          </tr>
          <tr>
            <td colspan="4">
              <table border="1" width="100%">
                <tr class="table_csheading">
                  <td align="center">Delete?</td>
                  <td align="center">Profile</td>
                  <td align="center">Start</td>
                  <td align="center">End</td>
                  <td align="center">Recovery End</td>
                  <td>Reason</td>
                  <td>Limitations</td>
                </tr>
                <?php
                    foreach($profile_row as $p) : ?>
                        <tr>
                          <td align="center"><input type="checkbox" name="profile_delete[]" value="<?php=$p['profile_id']?>"></td>
                          <td align="center"><?php=$p['profile']?></td>
                          <td align="center"><?php=$p['start']?></td>
                          <td align="center"><?php=$p['profile_end']?></td>
                          <td align="center"><?php=$p['recovery_end']?></td>
                          <td><?php=$p['profile_reason']?></td>
                          <td><?php=$p['profile_limitations']?>&nbsp;</td>
                        </tr>
                        <?php endforeach; ?>
              </table>
            </td>
          </tr>
          <?php endif; //end current profile section
          ?>
          <tr>
            <td colspan="4" class="column_name">Add New Profile</td>
          </tr>
          <tr>
            <td>Profile Type</td>
            <td>Profile Start Date</td>
            <td>Profile Length (Days)</td>
            <td>Reason</td>
          </tr>
          <tr>
            <td>
              <?php=conf_select("profile");?>
            </td>
            <td>
              <input type="text" class="text_box" name="profile_start" size="10" maxlength="9" value="">
            </td>
            <td>
              <input type="text" class="text_box" name="profile_length" size="4" maxlength="3" value="">
              <span class="example">(Leave blank for permanent)</span>
            </td>
            <td>
              <input type="text" class="text_box" name="profile_reason" size="20" maxlength="255" value="">
            </td>
          </tr>
          <tr>
            <td colspan="4">Limitations</td>
          </tr>
          <tr>
            <td colspan="4">
              <input type="text" class="text_box" name="profile_limitations" size="50" maxlength="255" value="">
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td class="table_heading">Personal Information</td>
    </tr>
    <tr>
      <td>
        <table width="100%" border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td width="33%">Marital Status</td>
            <td width="33%">Number of Dependents</td>
            <td width="25%">DOB</td>
          </tr>
          <tr>
            <td width="33%"> <?php echo conf_select("marital_status",$main_row["marital_status"]); ?>
            </td>
            <td width="33%">
              <input type="text" class="text_box" name="num_dependents" size="3" maxlength="2" value='<?php=$main_row["num_dependents"]?>'>
            </td>
            <td width="25%">
              <input type="text" class="text_box" name="dob" size="10" maxlength="9" value='<?php=$main_row["dob"]?>'>
            </td>
          </tr>
          <tr>
            <td width="33%">Religion</td>
            <td width="33%">Education</td>
            <td width="34%">Colleges Attended</td>
          </tr>
          <tr>
            <td width="33%">
              <?php= conf_select("religion",$main_row['religion']); ?>
            </td>
            <td width="33%"> <?php echo conf_select("education",$main_row["education"]); ?> </td>
            <td width="34%">
              <input type="text" class="text_box" name="colleges" size="20" maxlength="50" value='<?php=str_replace("'","&#39;",$main_row["colleges"])?>'>
            </td>
          </tr>
          <tr>
            <td colspan="2">Special Skills</td>
            <td width="34%">Race</td>
          </tr>
          <tr>
            <td colspan="2">
              <input type="text" class="text_box" name="special_skills" size="40" maxlength="50" value='<?php=str_replace("'","&#39;",$main_row["special_skills"])?>'>
            </td>
            <td width="34%">
              <?php echo conf_select('race',$main_row["race"]); ?>
            </td>
          </tr>
          <tr>
            <td colspan="2">Sports</td>
            <td width="34%">AKO Email (@us.army.mil)</td>
          </tr>
          <tr>
            <td colspan="2">
              <input type="text" class="text_box" name="sports" size="40" maxlength="50" value='<?php=str_replace("'","&#39;",$main_row["sports"])?>'>
            </td>
            <td width="34%">
              <input type="text" class="text_box" name="email" size="30" value='<?php=str_replace("'","&#39;",$main_row["email"])?>'>
            </td>
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
            <td width="33%">Make</td>
            <td width="33%">Model</td>
            <td width="34%">&nbsp;</td>
          </tr>
          <tr>
            <td width="33%">
              <input type="text" class="text_box" name="pov_make" size="26" maxlength="25" value='<?php=$main_row["pov_make"]?>'>
            </td>
            <td width="33%">
              <input type="text" class="text_box" name="pov_model" size="26" maxlength="25" value='<?php=$main_row["pov_model"]?>'>
            </td>
            <td width="34%">&nbsp;</td>
          </tr>
          <tr>
            <td width="33%">Year</td>
            <td width="33%">State</td>
            <td width="34%">Tag Number</td>
          </tr>
          <tr>
            <td width="33%">
              <input type="text" class="text_box" name="pov_year" size="5" maxlength="4" value='<?php=$main_row["pov_year"]?>'>
            </td>
            <td width="33%">
              <input type="text" class="text_box" name="pov_state" size="3" maxlength="2" value='<?php=$main_row["pov_state"]?>'>
            </td>
            <td width="34%">
              <input type="text" class="text_box" name="pov_tag" size="10" value='<?php=$main_row["pov_tag"]?>'>
            </td>
          </tr>
          <tr>
            <td>Post Decal</td>
            <td>Center Access Decal</td>
            <td>Housing Access Decal</td>
          </tr>
          <tr>
            <td><input type="text" class="text_box" name="post_decal" size=10 value="<?php=$main_row['post_decal']?>"></td>
            <td><input type="text" class="text_box" name="center_access_decal" size="10" value="<?php=$main_row['center_access_decal']?>"></td>
            <td><input type="text" class="text_box" name="housing_decal" size="10" value="<?php=$main_row['housing_decal']?>"></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td class="table_heading">Contact Information <span class="example">(One required, complete as many as needed.)</span></td>
    </tr>
    <tr>
      <td>
        <div align="left">
        <?php
    $address_count = count($address_row["address_type"]);
    for($x=0;$x<$address_count;$x++)
    {
        //if($address_row["address_type"][$x] != "none")
        //{
        ?>
        <table border='0' width='100%' cellspacing='2' cellpadding='2'>
            <tr>
              <td width="33%">address &#035;<?php=$x+1?> Type
                <input type="hidden" name="address_id[]" value="<?php=$address_row["address_id"][$x]?>">
                <input type="checkbox" name="address_delete[]" value="<?php=$address_row["address_id"][$x]?>">
                delete? </td>
              <td width="33%">Name <span class="example">(person at this address)</span></td>
              <td width="33%">Relationship</td>
            </tr>
            <tr>
              <td width="33%"> <?php echo conf_select("address_type",$address_row["address_type"][$x],1); ?> </td>
              <td width="33%">
                <input type="text" class="text_box" name="name[]" size="20" value='<?php=str_replace("'","&#39;",$address_row["name"][$x])?>'>
              </td>
              <td width="33%">
                <input type="text" class="text_box" name="relationship[]" size="20" value='<?php=str_replace("'","&#39;",$address_row["relationship"][$x])?>'>
              </td>
            </tr>
            <tr>
              <td width="33%">Street 1</td>
              <td width="33%">Street 2</td>
              <td width="33%">&nbsp;</td>
            </tr>
            <tr>
              <td width="33%">
                <input type="text" class="text_box" name="street1[]" size="20" value='<?php=str_replace("'","&#39;",$address_row["street1"][$x])?>'>
              </td>
              <td width="33%">
                <input type="text" class="text_box" name="street2[]" size="20" value='<?php=str_replace("'","&#39;",$address_row["street2"][$x])?>'>
              </td>
              <td width="33%">&nbsp;</td>
            </tr>
            <tr>
              <td width="33%">City</td>
              <td width="33%">State</td>
              <td width="33%">Zip</td>
            </tr>
            <tr>
              <td width="33%">
                <input type="text" class="text_box" name="city[]" size="20" value='<?php=str_replace("'","&#39;",$address_row["city"][$x])?>'>
              </td>
              <td width="33%">
                <input type="text" class="text_box" name="state[]" size="3" maxlength="2" value='<?php=$address_row["state"][$x]?>'>
              </td>
              <td width="33%">
                <input type="text" class="text_box" name="zip[]" size="6" value='<?php=$address_row["zip"][$x]?>'>
              </td>
            </tr>
            <tr>
              <td width="33%">Phone 1</td>
              <td width="33%">Phone 2</td>
              <td width="33%">Country</td>
            </tr>
            <tr>
              <td width="33%">
                <input type="text" class="text_box" name="phone1[]" size="20" value='<?php=$address_row["phone1"][$x]?>'>
              </td>
              <td width="33%">
                <input type="text" class="text_box" name="phone2[]" size="20" value='<?php=$address_row["phone2"][$x]?>'>
              </td>
              <td width="33%">
                <input type="text" class="text_box" name="country[]" size="20" value='<?php=str_replace("'","&#39;",$address_row["country"][$x])?>'>
              </td>
            </tr>
            <tr>
              <td colspan="3">
                <hr width="50%">
              </td>
            </tr>
        </table>
        <?php
        //}
    }
?>
        <table border='0' width='100%' cellspacing='2' cellpadding='2'>
            <tr>
              <td width="33%"><b>Add New Address &#035;<?php=$x+1?>:</b></td>
              <td width="33%">&nbsp;</td>
              <td width="33%">&nbsp;</td>
            </tr>
            <tr>
              <td width="33%">Address Type</td>
              <td width="33%">Name <span class="example">(If contact or next-of-kin)</span></td>
              <td width="33%">Relationship <span class="example">(If name given)</span></td>
            </tr>
            <tr>
              <td width="33%"> <?php echo conf_select("address_type","",1); ?> </td>
              <td width="33%">
                <input type="text" class="text_box" name="name[]" size="20">
              </td>
              <td width="33%">
                <input type="text" class="text_box" name="relationship[]" size="20">
              </td>
            </tr>
            <tr>
              <td width="33%">Street 1</td>
              <td width="33%">Street 2</td>
              <td width="33%">&nbsp;</td>
            </tr>
            <tr>
              <td width="33%">
                <input type="text" class="text_box" name="street1[]" size="20">
              </td>
              <td width="33%">
                <input type="text" class="text_box" name="street2[]" size="20">
              </td>
              <td width="33%">&nbsp;</td>
            </tr>
            <tr>
              <td width="33%">City</td>
              <td width="33%">State</td>
              <td width="33%">Zip</td>
            </tr>
            <tr>
              <td width="33%">
                <input type="text" class="text_box" name="city[]" size="20">
              </td>
              <td width="33%">
                <input type="text" class="text_box" name="state[]" size="3" maxlength="2">
              </td>
              <td width="33%">
                <input type="text" class="text_box" name="zip[]" size="6">
              </td>
            </tr>
            <tr>
              <td width="33%">Phone 1</td>
              <td width="33%">Phone 2</td>
              <td width="33%">Country</td>
            </tr>
            <tr>
              <td width="33%">
                <input type="text" class="text_box" name="phone1[]" size="20">
              </td>
              <td width="33%">
                <input type="text" class="text_box" name="phone2[]" size="20">
              </td>
              <td width="33%">
                <input type="text" class="text_box" name="country[]" size="20" value="usa">
              </td>
            </tr>
          </table>
        </div>
      </td>
    </tr>
<?php
if(in_array($main_row['pers_type'],$_CONF['students']))
{
    ?>
    <tr>
      <td class="table_heading">Student Information</td>
    </tr>
    <tr>
      <td>
        <table width="100%" border="0" cellspacing="2" cellpadding="2">
          <col width="33%"></col>
          <col width="33%"></col>
          <col width="34%"></col>
          <tr>
            <td>Class <input type='checkbox' name='not_graduating' value='1' <?phpif($main_row['not_graduating']==1) { echo "checked"; }?>>Not Graduating w/Class</td>
            <td>Shift</td>
            <td>HRAP</td>
          </tr>
          <tr>
            <td> <?php echo class_select(4,$student_row["class_id"]); ?> </td>
            <td>
                <?php=conf_select("shift",$student_row['shift'])?>
            </td>
            <td><?php=conf_select('hrap',$student_row['hrap'])?></td>
            </tr>
          <tr>
            <td>Birth City</td>
            <td>Birth State</td>
            <td>Birth Country</td>
          </tr>
          <tr>
            <td>
              <input type="text" class="text_box" name="birth_city" size="20" value='<?php=str_replace("'","&#39;",$student_row["birth_city"])?>'>
            </td>
            <td>
              <input type="text" class="text_box" name="birth_state" size="3" maxlength="2" value='<?php=$student_row["birth_state"]?>'>
            </td>
            <td>
              <input type="text" class="text_box" name="birth_country" size="20" value='<?php=str_replace("'","&#39;",$student_row["birth_country"])?>'>
            </td>
          </tr>
          <tr>
            <td>Civilian Occupation</td>
            <td>Basic Training Post</td>
            <td>AOT Type</td>
          </tr>
          <tr>
            <td>
              <input type="text" class="text_box" name="civilian_occupation" size="20" value='<?php=str_replace("'","&#39;",$student_row["civilian_occupation"])?>'>
            </td>
            <td>
              <?php=conf_select("bct_location",$student_row["basic_training_POST"])?>
            </td>
            <td>
              <?php=conf_select("aot_type",$student_row["aot_type"])?>
            </td>
          </tr>
          <tr>
            <td>
              <input type="checkbox" name="swim" value="Y" <?php if($student_row["swim"] == "Y") { echo " checked "; } ?>>
              Wwim Qualified</td>
            <td>
              <input type="checkbox" name="heat" value="Y" <?php if($student_row["heat"] == "Y") { echo " checked "; } ?>>
              Heat Injury</td>
            <td>
              <input type="checkbox" name="cold" value="Y" <?php if($student_row["cold"] == "Y") { echo " checked "; } ?>>
              Cold Injury</td>
          </tr>
          <tr>
            <td>Date:
              <input type="text" class="text_box" name="swim_date" size="10" maxlength="9" value='<?php=$student_row["swim_date"]?>'>
            </td>
            <td>Date:
              <input type="text" class="text_box" name="heat_date" size="10" maxlength="9" value='<?php=$student_row["heat_date"]?>'>
            </td>
            <td>Date:
              <input type="text" class="text_box" name="cold_date" size="10" maxlength="9" value='<?php=$student_row["cold_date"]?>'>
            </td>
          </tr>
          <tr>
            <td><input type="checkbox" name="ctt" value="Y" <?phpif($student_row["ctt"] == "Y") { echo " checked "; } ?>> CTT Complete</td>
            <td>&nbsp;</td>
            <td>MEPS Station</td>
          </tr>
          <tr>
            <td>Date: <input type="text" class="text_box" name="ctt_date" size="10" maxlength="9" value="<?php=$student_row["ctt_date"]?>"></td>
            <td>&nbsp;</td>
            <td><?php=conf_select("meps",$student_row['meps'])?></td>
          </tr>
          <tr>
            <td>Assignment</td>
            <td colspan="2">Graduation Honors</td>
          </tr>
          <tr>
            <td><input type="text" class="text_box" name="assignment" size="20" value="<?php=$student_row['assignment']?>"></td>
            <td colspan="2">
              <input type="checkbox" name="honor_grad" value="Y" <?phpif($student_row["honor_grad"] == "Y") { echo " checked "; } ?>>Honor Graduate
              <input type="checkbox" name="dist_grad" value="Y" <?phpif($student_row["dist_grad"] == "Y") { echo " checked "; } ?>>Distinguished Graduate
              <input type="checkbox" name="high_pt" value="Y" <?phpif($student_row["high_pt"] == "Y") { echo " checked "; } ?>>High PT Award
            </td>
          </tr>
          <tr>
            <td>Academic Average</td>
            <td>Test Failures</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><input type="text" name="academic_avg" size="20" value="<?php=htmlentities($student_row['academic_avg'])?>"></td>
            <td><input type="text" name="test_failures" size="5" value="<?php=$student_row['test_failures']?>"></td>
            <td>
              <input type="checkbox" name="airborne" value="Y" <?php if($student_row["airborne"] == "Y") { echo " checked "; }?>> Airborne
            </td>
          </tr>
          <tr>
            <td colspan="3"><hr width="50%"></td>
          </tr>
          <tr>
            <td colspan="3">
              <table border="0" align="left" width="100%">
                <tr>
                  <td width="17%">Phase</td>
                  <td width="16%">Date Phase IV</td>
                  <td width="16%">Date Phase V</td>
                  <td width="16%">Proj. Phase V</td>
                  <td width="16%">Date Phase V+</td>
                  <td width="16%">Proj. Phase V+</td>
                </tr>
                <tr>
                  <td>
                    <?php=$phase_select?>
                    <input type="hidden" name="old_phase" value="<?php=$student_row['phase']?>">
                  </td>
                  <td><input type="text" name="date_phaseiv" size="10" maxlength="9" value="<?php=$student_row['date_phaseiv']?>"></td>
                  <td><input type="text" name="date_phasev" size="10" maxlength="9" value="<?php=$student_row['date_phasev']?>"></td>
                  <td><?php=$student_row['proj_date_phasev']?></td>
                  <td><input type="text" name="date_phaseva" size="10" maxlength="9" value="<?php=$student_row['date_phaseva']?>"></td>
                  <td><?php=$student_row['proj_date_phaseva']?></td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td colspan="4" class="example">NOTICE: Reducing the Phase of a soldier will require a remark to be made with a subject of <strong>Phasing</strong>.</td>
          </tr>
        </table>
      </td>
    </tr>
    <?php
    if($student_row['airborne'] == "Y")
    {
        ?>
        <tr>
          <td class="table_heading">Airborne Information</td>
        </tr>
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="2" cellpadding="2">
              <col width="33%"></col>
              <col width="33%"></col>
              <col width="34%"></col>
              <tr>
                <td>Type</td>
                <td>Packet Initiated</td>
                <td>Vol. Statement Date</td>
              </tr>
              <tr>
                <td><?php=conf_select("ab_type",$ab_row['type'])?></td>
                <td><input type="text" class="text_box" name="packet_init" size="10" maxlength="9" value="<?php=$ab_row['packet_init']?>"></td>
                <td><input type="text" class="text_box" name="vol_date" size="10" maxlength="9" value="<?php=$ab_row['vol_date']?>"></td>
              </tr>
              <tr>
                <td>Physical Part 1 date</td>
                <td>Physical Part 2 date</td>
                <td>4187 Submission Date</td>
              </tr>
              <tr>
                <td><input type="text" class="text_box" name="physical1" size="10" maxlength="9" value="<?php=$ab_row['physical1']?>"></td>
                <td><input type="text" class="text_box" name="physical2" size="10" maxlength="9" value="<?php=$ab_row['physical2']?>"></td>
                <td><input type="text" class="text_box" name="submit_4187" size="10" maxlength="9" value="<?php=$ab_row['submit_4187']?>"></td>
              </tr>
              <tr>
                <td>Packet Turned in Date</td>
                <td colspan="2">Remark</td>
              </tr>
              <tr>
                <td><input type="text" class="text_box" name="packet_ti" size="10" maxlength="9" value="<?php=$ab_row['packet_ti']?>"></td>
                <td colspan="2"><input type="text" class="text_box" name="ab_remark" size="30" value="<?php=$ab_row['remark']?>"></td>
              </tr>
            </table>
          </td>
        </tr>
        <?php
    } //end airborne block

    //if current datetime is between start and end of exodus,
    //include the exodus block into the page
    if($val->exodus($main_row['pers_type']))
    {
        //see if edit is on or user is in the list that's allowed
        //to edit after editing is turned off
        if(($_CONF['exodus_edit'] == "on" && time() <= strtotime($_CONF['exodus_edit_off'])) || in_array($_SESSION['user_id'],$_CONF['exodus_edit_allowed']))
        {
            echo "<tr><td>";
            include($_CONF['path'] . "templates/add_exodus.inc.php");
            echo "</td></tr>";
        }
        else
        { echo '<tr><td class="table_heading">Exodus Information</td></tr><tr><td align="center">You can no longer edit Exodus Information. Please contact CPT Moulton at Brigade S3 (791-1003) to make any changes.</td></tr>'; }
    }
    elseif($main_row['pers_type'] == 'IET' && $main_row['pcs'] == 0 && strtolower($_CONF['exodus']) == 'on')
    {
        //include block that allows the soldier to be
        //added to the exodus roster
        ?>
        <tr>
          <td class="table_heading">Exodus</td>
        </tr>
        <tr>
          <td>
            This soldier is currently not on the Exodus Roster. To add this soldier to the Exodus Roster, mark the following
            check box: <input type="checkbox" name="add_to_exodus" value="1">
          </td>
        </tr>
        <?php
    }
} //end student block
?>
    <tr>
      <td class="table_heading">Remarks</td>
    </tr>
    <tr>
      <td>
        <table width="100%" border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td>Subject</td>
          </tr>
          <tr>
            <td> <?php echo subject_select(); ?> </td>
          </tr>
          <tr>
            <td>Remark</td>
          </tr>
          <tr>
            <td>
              <textarea class="text_box" name="remark" cols="75" rows="4" wrap="physical"><?php=htmlentities(stripslashes($_POST['remark']))?></textarea>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td class="table_cheading" colspan="2">
          <input type="submit" name="edit_submit" value="Update Information" class="button">
      </td>
    </tr>
  </table>
</form>
