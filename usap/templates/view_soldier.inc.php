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

// if SM PCS (PCS flag is set to 1 in the main table) then show PCS information
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
	// if operator has permission to delete the Soldier, show restore button
    if($val->id($_REQUEST['id'],3))
    {
        echo "<form method='get' action='" . $_CONF['html'] . "/data_sheet.php'><tr><td>"
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
  <table width="90%" border="1" align="center">
  <?php

if($main_row["pcs"] == 0) { // do not show top option if Soldier PCS 

  $img_excel       = "<img src='images/icons/excel.png'         width='40' border='0' align='middle' title='Export to Excel File'    onClick=\"document.location.href='{$_SERVER['SCRIPT_NAME']}?{$_SERVER['QUERY_STRING']}&export2=excel'\">";
  $img_word        = "<img src='images/icons/new-world.png'     width='32' border='0' align='middle' title='Export to Word Document' onClick=\"document.location.href='{$_SERVER['SCRIPT_NAME']}?{$_SERVER['QUERY_STRING']}&export2=word'\">";
  $img_remark_view = "<img src='images/icons/comment.png'       width='24' border='0' align='middle' title='View Remarks'            onClick=\"document.location.href='{$_CONF['html']}/remarks.php?id={$_REQUEST['id']}'\">";
  $img_remark_add  = "<img src='images/icons/comment_add.png'   width='24' border='0' align='middle' title='Add Remarks'             onClick=\"document.location.href='{$_CONF['html']}/add_remark.php?id={$_REQUEST['id']}'\">";
  $img_user_edit   = "<img src='images/icons/user-edit.png'     width='24' border='0' align='middle' title='Edit User'               onClick=\"document.location.href='{$_CONF['html']}/edit_soldier.php?id={$_REQUEST['id']}'\">";
  $img_user_delete = "<img src='images/icons/user-remove.png'   width='24' border='0' align='middle' title='Delete User'             onClick=\"document.location.href='{$_CONF['html']}/delete.php?id={$_REQUEST['id']}&method=delete'\">";
  $img_appt        = "<img src='images/icons/calendar.png'      width='24' border='0' align='middle' title='Schedule Appointment'    onClick=\"document.location.href='{$_CONF['html']}/appointment.php?id={$_REQUEST['id']}'\">";
  $img_print_form  = "<img src='images/icons/print.png'         width='24' border='0' align='middle' title='Print Form'              onClick=\"gm('forms.php?id=$id',3);\">";
  $img_apft_add    = "<img src='images/icons/apft_add.png'      width='24' border='0' align='middle' title='Add APFT'                onClick=\"document.location.href='{$_CONF['html']}/add_apft.php?id={$_REQUEST['id']}'\">";
  $img_apft_view   = "<img src='images/icons/apft.png'          width='24' border='0' align='middle' title='View APFT'               onClick=\"document.location.href='{$_CONF['html']}/apft.php?id={$_REQUEST['id']}'\">";

   if(!isset($_REQUEST["export2"]))
    {
        //display links to export to excel or word and link to other pages
        echo "<tr><td align=\"center\">\n
              [$img_excel | $img_word]
              &nbsp;&nbsp;
			  [$img_remark_view | $img_remark_add]
              &nbsp;&nbsp;
			  [$img_apft_view | $img_apft_add]";

        if($val->id($_REQUEST["id"],2) || $_REQUEST['id'] == $_SESSION['user_id'] || $val->id($_REQUEST['id'],32))
        { echo "&nbsp;&nbsp;[ $img_appt ]"; }

        if($val->id($_REQUEST["id"],2) || $_REQUEST['id'] == $_SESSION['user_id'])
        { echo "&nbsp;&nbsp;[$img_user_edit]"; }

        if($val->id($_REQUEST["id"],3) && $main_row["pcs"] == 0)
        { echo "&nbsp;&nbsp;[$img_user_delete]";}
		
    echo "&nbsp;&nbsp;[$img_print_form]";
	
	echo "</td></tr>\n";
} // if PCS top options ends

	echo "<tr><td align='center'>";
      	echo "&nbsp;&nbsp;&nbsp;&nbsp;";
 	echo "[<a href='?'>(+)Advance Phase</a>]";
	echo "&nbsp;&nbsp;";
	echo "[<a href='?'>(-)Phase-Back</a>]";

        echo "</td></tr>\n";
    }
  ?>
    <tr>
      <td class="table_heading">Soldier Information</td>
    </tr>
  <tr>
    <td>
      <table width="100%" border="0" cellspacing="2" cellpadding="2">
        <tr class="column_name">
          <td width="30%">Last Name</td>
          <td width="30%">First Name</td>
          <td width="20%">Middle Initial</td>
          <td width="20%">&nbsp;</td>
        </tr>
        <tr class="data">
          <td width="30%"> <?php=htmlentities($main_row["last_name"])?> </td>
          <td width="30%"> <?php=htmlentities($main_row["first_name"])?> </td>
          <td width="20%"> <?php=htmlentities($main_row["middle_initial"])?> </td>
          <td width="20%">&nbsp;</td>
        </tr>
        <tr class="column_name">
          <td width="30%">Social Security Number</td>
          <td width="30%">Gender</td>
          <td width="20%">Rank</td>
          <td width="20%">Date of Rank</td>
        </tr>
        <tr class="data">
          <td width="30%"> <?php=htmlentities($main_row["ssn"])?> </td>
          <td width="30%"> <?php=htmlentities($main_row["gender"])?> </td>
          <td width="20%"> <?php=htmlentities($main_row["rank"])?><?php=htmlentities($main_row["promotable"])?> </td>
          <td width="20%"> <?php=$main_row['dor'];?> </td>
        </tr>
        <tr class="column_name">
          <td width="30%">Personnel Type</td>
          <td width="30%">ETS</td>
          <td width="20%">MOS</td>
          <td width="20%">Component</td>
        </tr>
        <tr class="data">
          <td width="30%"> <?php=htmlentities($main_row["pers_type"])?> </td>
          <td width="30%"> <?php=htmlentities($main_row["ets"])?> </td>
          <td width="20%"> <?php=htmlentities($main_row["mos"])?> </td>
          <td width="20%"> <?php=htmlentities($main_row["component"])?> </td>
        </tr>
        <tr class="column_name">
          <td width="30%">Date Entered Service (days)</td>
          <td width="30%">PCS Date / Location</td>
          <td width="20%">U.S. Citizen</td>
          <td width="20%">Issued CAC</td>
        </tr>
        <tr class="data">
          <td width="30%"> <?php echo htmlentities($main_row["date_entered_service"]); echo ($main_row['pers_type']=='IET')?" ({$main_row['days_since_enter']})":''?> </td>
          <td width="30%"> <?php=htmlentities($main_row["pcs_date"])?> / <?php=htmlentities($main_row['pcs_location'])?></td>
          <td width="20%"> <?php=htmlentities($main_row["us_citizen"])?> </td>
          <td width="20%"> <?php=htmlentities($main_row["cac"])?> </td>
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
        <tr class="column_name">
          <td width="25%">Unit</td>
          <td width="25%">Platoon</td>
	  <td width="25%">Room Number</td>
          <td width="25%">&nbsp;</td>
        </tr>
        <tr class="data">
          <td width="25%"><?php echo htmlentities($main_row["battalion"]) . "-" . htmlentities($main_row["company"]); ?></td>
          <td width="25%"> <?php=htmlentities($main_row["platoon"])?> </td>
          <td width="25%"><a href="reports\roomRoster.php?room=<?php=htmlentities($main_row["room_number"])?>&building_number=<?php=htmlentities($main_row["building_number"])?>"><?php=htmlentities($main_row["room_number"])?></a></td>
          <td width="25%">&nbsp;</td>
        </tr>
        <tr class="column_name">
          <td width="25%">Building Number</td>
          <td width="25%">Flagged: <span class="data"><?php=$main_row["flagged"]?></span></td>
          <td width="50%">Arrival Date (days)</td>
        </tr>
        <tr class="data">
          <td width="25%"><a href="reports\roomRoster.php?building_number=<?php=htmlentities($main_row["building_number"])?>"><?php=htmlentities($main_row["building_number"])?></a> </td>
          <td width="25%"> <span class="column_name">date: </span><?php=$main_row["flag_date"]?></td>
          <td width="50%"> <?php echo htmlentities($main_row["arrival_date"]); echo ($main_row['pers_type']=='IET')?" ({$main_row['days_since_arrival']})":''?></td>
        </tr>
        <tr class="data">
          <td colspan="2"><span class="column_name">Daily Status:</span> <?php=$main_row['status']?></td>
          <td width="50%" class="column_name">Status Remark</td>
        </tr>
        <tr class="data">
          <td colspan="2"><span class="column_name">Inactive Status:</span> <?php=$main_row['inact_status']?></td>
          <td width="50%"> <?php=htmlentities($main_row["status_remark"])?> </td>
        </tr>
        <tr>
          <td colspan="4">
            <a href='<?php=$_CONF['html']?>/reports/status_history_report.php?id=<?php=$_REQUEST['id']?>'>Complete Daily and Inactive Status History</a>
          </td>
      </table>
    </td>
  </tr>
  <?php
    if(strtolower($main_row['location']) != 'organic' && $main_row['location'] != '')
    {
      ?>
        <tr>
          <td class="table_heading">Attached / Detached Information</td>
        </tr>
        <tr align="right">
          <td colspan="4" class="example">
            <table border='0' width="100%" cellpadding="2" cellspacing="2">
              <tr>
                <td>
                  <table width="100%" cellspacing="2" cellpadding="2">
                    <tr class="column_name">
                      <td colspan="3">Location</td>
                    </tr>
                    <tr class="data">
                      <td><?php=$main_row['location']?></td>
                    </tr>
                    <tr class="column_name">
                      <td width="33%">Detached From</td>
                      <td width="33%">Attached To</td>
                      <td width="34%">Date</td>
                    </tr>
                    <tr class="data">
                      <td width="33%"><?php=$location_row['detached_co']?> - <?php=$location_row['detached_bn']?></td>
                      <td width="33%"><?php=$location_row['attached_co']?> - <?php=$location_row['attached_bn']?></td>
                      <td width="34%"> <?php=htmlentities($location_row["effective"])?> </td>
                    </tr>
                    <tr class="column_name">
                      <td width="33%">Position</td>
                      <td colspan="2">Reason</td>
                    </tr>
                    <tr class="data">
                      <td width="33%"> <?php=htmlentities($location_row["position"])?> </td>
                      <td colspan="2"> <?php=htmlentities($location_row["reason"])?> </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      <?php
    }

if($allow_full_view)
{
    if(in_array($main_row['pers_type'],$_CONF['perm_party']))
    {
        ?>
        <tr>
          <td class="table_heading">TDA Information</td>
        </tr>
        <tr>
          <td>
            <table border="0" width="100%" cellspacing="2" cellpadding="2">
              <tr>
                <td colspan="7" class="column_name">Assigned TDA Position Information</td>
              </tr>
              <tr class="column_name">
                <td>PARA</td>
                <td>LN</td>
                <td>Position</td>
                <td>Grade</td>
                <td>MDEP</td>
                <td>REQ</td>
                <td>AUTH</td>
              </tr>
              <tr class="data">
                <td><?php=@$tda_row['para']?></td>
                <td><?php=@$tda_row['ln']?></td>
                <td><?php=@$tda_row['position']?></td>
                <td><?php=@$tda_row['gr']?></td>
                <td><?php=@$tda_row['mdep']?></td>
                <td><?php=@$tda_row['req']?></td>
                <td><?php=@$tda_row['auth']?></td>
              </tr>
              <tr>
                <td colspan="7" class="column_name">Working TDA Position Information</td>
              </tr>
              <tr class="column_name">
                <td>PARA</td>
                <td>LN</td>
                <td>Position</td>
                <td>Grade</td>
                <td>MDEP</td>
                <td>REQ</td>
                <td>AUTH</td>
              </tr>
              <tr class="data">
                <td><?php=@$tda_row['para2']?></td>
                <td><?php=@$tda_row['ln2']?></td>
                <td><?php=@$tda_row['position2']?></td>
                <td><?php=@$tda_row['gr2']?></td>
                <td><?php=@$tda_row['mdep2']?></td>
                <td><?php=@$tda_row['req2']?></td>
                <td><?php=@$tda_row['auth2']?></td>
              </tr>
              <tr>
                <td colspan="7">
                  <span class="column_name">Comment: </span>
                  <span class="data"><?php=@$tda_row['comment']?></span>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <?php
    }
    ?>
  <tr>
    <td class="table_heading">Security Information</td>
  </tr>
  <tr>
    <td>
      <table width="100%" border="0" cellspacing="2" cellpadding="2">
        <tr class="column_name">
          <td>Clearance Status</td>
          <td>Derog Issue</td>
          <td>Status Date</td>
        </tr>
        <tr>
          <td><?php=htmlentities($main_row["clearance_status"])?></td>
          <td><?php=htmlentities($main_row["derog_issue"])?></td>
          <td><?php=htmlentities($main_row["status_date"])?></td>
        </tr>
        <tr>
          <td colspan="3"><a href="<?php=$_CONF['html']?>/reports/s2_history_report.php?id=<?php=$_REQUEST['id']?>">Complete S2 History Report</a></td>
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
        <tr class="column_name">
          <td>Blood Type</td>
          <td>Dental Category</td>
          <td>Next Dental Exam</td>
          <td>HIV Test Date</td>
        </tr>
        <tr class="data">
          <td> <?php=htmlentities($main_row["blood_type"])?> </td>
          <td> <?php=htmlentities($main_row["dental_category"])?> </td>
          <td> <?php=htmlentities($main_row['dental_date'])?> </td>
          <td> <?php=htmlentities($main_row["hiv_date"])?> </td>
        </tr>
        <tr class="column_name">
          <td>Height</td>
          <td>Weight</td>
          <td>Hair Color</td>
          <td>Eye Color</td>
        </tr>
        <tr class="data">
          <td> <?php=htmlentities($main_row["height"])?> </td>
          <td> <?php=htmlentities($main_row["weight"])?> </td>
          <td> <?php=htmlentities($main_row["hair_color"])?> </td>
          <td> <?php=htmlentities($main_row["eye_color"])?> </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td class="table_heading">Profile Information</td>
  </tr>
  <tr>
    <td>
      <?php if(isset($profile_row) && count($profile_row) > 0) { ?>
      <table width="100%" border="0" cellspacing="2" cellpadding="2">
        <tr class="column_name">
          <td>Profile</td>
          <td>Start</td>
          <td>End</td>
          <td>Recovery End</td>
          <td>Reason</td>
          <td>Limitations</td>
        </tr>
        <?php
            foreach($profile_row as $p)
            {
                ?>
                <tr>
                  <td><?php=$p['profile']?></td>
                  <td><?php=$p['start']?></td>
                  <td><?php=$p['profile_end']?></td>
                  <td><?php=$p['recovery_end']?></td>
                  <td><?php=$p['profile_reason']?></td>
                  <td><?php=$p['profile_limitations']?>&nbsp;</td>
                </tr>
                <?php
            }
                ?>
      </table>
      <?php } else { echo "There are no current profiles."; } ?>
      Click <a href="<?php=$_CONF['html']?>/reports/profile_history_report.php?id=<?php=$_REQUEST['id']?>">here</a> for a complete history of all profiles for this soldier. <img src="images/icons/file-add.png" title="Add Profile" onClick="gm('<?php echo "add_profile.php?id=$id"; ?>',2)">
    </td>
  </tr>
  <tr>
    <td class="table_heading">Personal Information</td>
  </tr>
  <tr>
    <td>
      <table width="100%" border="0" cellspacing="2" cellpadding="2">
        <tr class="column_name">
          <td width="33%">Marital Status</td>
          <td width="33%">Number of Dependents</td>
          <td width="25%">DOB (Age)</td>
        </tr>
        <tr class="data">
          <td width="33%"> <?php=htmlentities($main_row["marital_status"])?> </td>
          <td width="33%"> <?php=htmlentities($main_row["num_dependents"])?> </td>
          <td width="25%"> <?php=htmlentities($main_row["dob"]); echo " (". intval($main_row["age"]/365) .")"; ?> </td>
        </tr>
        <tr class="column_name">
          <td width="33%">Religion</td>
          <td width="33%">Education</td>
          <td width="34%">Colleges Attended</td>
        </tr>
        <tr class="data">
          <td width="33%"> <?php=htmlentities($main_row["religion"])?> </td>
          <td width="33%"> <?php=htmlentities($main_row["education"])?> </td>
          <td width="34%"> <?php=htmlentities($main_row["colleges"])?> </td>
        </tr>
        <tr class="column_name">
          <td colspan="2">Special skills</td>
          <td width="34%">Race</td>
        </tr>
        <tr class="data">
          <td colspan="2"> <?php=htmlentities($main_row["special_skills"])?> </td>
          <td width="34%"> <?php=htmlentities($main_row["race"])?> </td>
        </tr>
        <tr class="column_name">
          <td colspan="2">Sports</td>
          <td width="34%">AKO Email</td>
        </tr>
        <tr class="data">
          <td colspan="2"> <?php=htmlentities($main_row["sports"])?> </td>
          <td width="34%"> <?php=htmlentities($main_row["email"])?> </td>
        </tr>
      </table>
    </td>
  </tr>
<?php
//only show this part of table if
//there is pov data on this soldier
if($main_row["pov_tag"] != "")
{
    ?>
  <tr>
    <td class="table_heading">POV Information</td>
  </tr>
  <tr>
    <td>
      <table width="100%" border="0" cellspacing="2" cellpadding="2">
        <col width="33%"></col>
        <col width="33%"></col>
        <col width="34%"></col>
        <tr class="column_name">
          <td>Make</td>
          <td>Model</td>
          <td>&nbsp;</td>
        </tr>
        <tr class="data">
          <td><?php=htmlentities($main_row["pov_make"])?></td>
          <td><?php=htmlentities($main_row["pov_model"])?></td>
          <td>&nbsp;</td>
        </tr>
        <tr class="column_name">
          <td>Year</td>
          <td>State</td>
          <td>Tag Number</td>
        </tr>
        <tr class="data">
          <td><?php=htmlentities($main_row["pov_year"])?></td>
          <td><?php=htmlentities($main_row["pov_state"])?></td>
          <td><?php=htmlentities($main_row["pov_tag"])?></td>
        </tr>
        <tr class="column_name">
          <td>Post Decal Number</td>
          <td>Center Access Decal Number</td>
          <td>Housing Access Decal</td>
        </tr>
        <tr class="data">
          <td><?php=htmlentities($main_row['post_decal'])?></td>
          <td><?php=htmlentities($main_row['center_access_decal'])?></td>
          <td><?php=htmlentities($main_row['housing_decal'])?></td>
        </tr>
      </table>
    </td>
  </tr>
<?php
} //end if for displaying pov data

} //end if for $allow_full permission

//only display this part of table is
//soldier is a student
if(in_array($main_row['pers_type'],$_CONF['students']))
{
    ?>
  <tr>
    <td class="table_heading">School Information</td>
  </tr>
    <tr>
    <td>
      <table width="100%" border="0" cellspacing="2" cellpadding="2">
        <tr class="column_name">
          <td width="33%">Class</td>
          <td width="33%">Shift</td>
          <td width="34%">HRAP</td>
        </tr>
        <tr class="data">
          <td width="33%"><a href='<?php=$_CONF['html']?>/class.php?class_id=<?php=$student_row['class_id']?>'><?php echo htmlentities($student_row["mos"]) . " -- " . htmlentities($student_row["class_number"]); ?></a></td>
          <td width="33%"> <?php=htmlentities($student_row["shift"])?> </td>
          <td width="34%"> <?php=htmlentities($student_row["hrap"])?></td>
        </tr>
        <tr class="column_name">
          <td width="33%">Birth City</td>
          <td width="33%">Birth State</td>
          <td width="34%">Birth Country</td>
        </tr>
        <tr class="data">
          <td width="33%"> <?php=htmlentities($student_row["birth_city"])?> </td>
          <td width="33%"> <?php=htmlentities($student_row["birth_state"])?> </td>
          <td width="34%"> <?php=htmlentities($student_row["birth_country"])?> </td>
        </tr>
        <tr class="column_name">
          <td width="33%">Civilian Occupation</td>
          <td width="33%">Basic Training Post</td>
          <td width="34%">AOT Type</td>
        </tr>
        <tr class="data">
          <td width="33%"> <?php=htmlentities($student_row["civilian_occupation"])?> </td>
          <td width="33%"> <?php=htmlentities($student_row["basic_training_post"])?> </td>
          <td width="34%"> <?php=htmlentities($student_row["aot_type"])?> </td>
        </tr>
        <tr class="column_name">
          <td width="33%">Swim Qualified: <span class="data"><?php=htmlentities($student_row["swim"])?></span></td>
          <td width="33%">Heat Injury: <span class="data"><?php=htmlentities($student_row["heat"])?></span></td>
          <td width="34%">Cold Injury: <span class="data"><?php=htmlentities($student_row["cold"])?></span></td>
        </tr>
        <tr class="column_name">
          <td width="33%">Date: <span class="data"><?php=htmlentities($student_row["swim_date"])?></span></td>
          <td width="33%">Date: <span class="data"><?php=htmlentities($student_row["heat_date"])?></span></td>
          <td width="34%">Date: <span class="data"><?php=htmlentities($student_row["cold_date"])?></span></td>
        </tr>
        <tr class="column_name">
          <td width="33%">CTT: <span class="data"><?php=htmlentities($student_row["ctt"])?></span></td>
          <td width="33%">Assignment</td>
          <td width="34%">MEPS Station</td>
        </tr>
        <tr class="column_name">
          <td width="33%">Date: <span class="data"><?php=htmlentities($student_row["ctt_date"])?></span></td>
          <td width="33%" class="data"><?php=htmlentities($student_row['assignment'])?></td>
          <td width="34%" class="data"><?php=htmlentities($student_row['meps'])?></td>
        </tr>
        <tr class="column_name">
          <td>Academic Average</td>
          <td>Test Failures</td>
          <td>&nbsp;</td>
        </tr>
        <tr class="data">
          <td><?php=$student_row['academic_avg']?></td>
          <td><?php=$student_row['test_failures']?></td>
          <td><span class="column_name">Airborne: </span><?php=htmlentities($student_row["airborne"])?></td>
        </tr>
        <tr>
          <td colspan="3"><hr width="50%"></td>
        </tr>
        <tr>
          <td colspan="3">
            <table border="0" width="100%" align="left">

              <tr class="column_name">
                <td width="17%">Phase</td>
                <td width="16%">Date Phase IV</td>
                <td width="16%">Date Phase V</td>
                <td width="16%">Proj. Phase V
                <td width="16%">Date Phase V+</td>
                <td width="16%">Proj. Phase V+</td>
              </tr>

              <tr class="data">
                <td><?php=$student_row['phase']?></td>
                <td><?php=$student_row['date_phaseiv']?></td>
                <td><?php=$student_row['date_phasev']?></td>
                <td><?php=$student_row['proj_date_phasev']?></td>
                <td><?php=$student_row['date_phaseva']?></td>
                <td><?php=$student_row['proj_date_phaseva']?></td>
		</p>
		</tr>
		<tr>
		<a href="??">[(+)Advance Phase]</a>
		<a href="??">[(-)Phase-Back]</a>
		</tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
<?php
//end if for displaying student data
}

//if exodus is on, display exodus block
if($val->exodus($main_row['pers_type']))
{
    echo "<tr><td>\n";
    include($_CONF['path'] . "templates/view_exodus.inc.php");
    echo "</td></tr>\n";
}


if($allow_full_view)
{
    if(isset($student_row) && $student_row['airborne'] == "Y")
    {
        ?>
        <tr>
          <td class="table_heading">Airborne information</td>
        </tr>
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="2" cellpadding="2">
              <col width="33%"></col>
              <col width="33%"></col>
              <col width="34%"></col>
              <tr class="column_name">
                <td>Type</td>
                <td>Packet Initiated</td>
                <td>Vol. Statement Date</td>
              </tr>
              <tr class="data">
                <td><?php=htmlentities($ab_row['type'])?></td>
                <td><?php=htmlentities($ab_row['packet_init'])?></td>
                <td><?php=htmlentities($ab_row['vol_date'])?></td>
              </tr>
              <tr class="column_name">
                <td>Physical Part 1 date</td>
                <td>Physical Part 2 date</td>
                <td>4187 Submission date</td>
              </tr>
              <tr class="data">
                <td><?php=htmlentities($ab_row['physical1'])?></td>
                <td><?php=htmlentities($ab_row['physical2'])?></td>
                <td><?php=htmlentities($ab_row['submit_4187'])?></td>
              </tr>
              <tr class="column_name">
                <td>Packet Turned in Date</td>
                <td colspan="2">Remark</td>
              </tr>
              <tr class="data">
                <td><?php=htmlentities($ab_row['packet_ti'])?></td>
                <td colspan="2"><?php=htmlentities($ab_row['remark'])?></td>
            </table>
          </td>
        </tr>
        <?php
    } //end airborne block

//only display addresses if
//there are some in the database
if($soldier_has_address == 1)
{
?>
  <tr>
    <td class="table_heading">Contact Information</td>
  </tr>
  <?php
    while($address_row = mysql_fetch_array($address_result))
    {
	// create code to locate address in google maps
	$street = str_replace(" ","+",$address_row['street1']);
	$street = str_replace("APT","",strtoupper($street)); // remove information that google maps don't handle
	$city   = str_replace(" ","+",$address_row['city']);
	$state  = strtoupper($address_row['state']);
	$gm = "http://maps.google.com/maps/api/staticmap?center=$street,$city,$state&zoom=14&size=520x520&markers=size:mid|color:blue|label:A|$street,$city,$state%20%20&sensor=false";
	// Google Maps API static
        ?>
  <tr>
    <td>
      <table border='0' width='100%' cellspacing='2' cellpadding='2'>
        <tr class="column_name">
          <td width="33%">Address Type </td>
          <td width="33%">Name</td>
          <td width="33%">Relationship</td>
        </tr>
        <tr class="data">
          <td width="33%"> <?php=htmlentities($address_row["type"])?></td>
          <td width="33%"> <?php=htmlentities($address_row["name"])?> </td>
          <td width="33%"> <?php=htmlentities($address_row["relationship"])?> </td>
        </tr>
        <tr class="column_name">
          <td width="33%">Address</td>
          <td width="33%">Phone Numbers</td>
          <td width="33%">&nbsp;</td>
        </tr>
        <tr class="data">
          <td width="33%"> <?php=htmlentities($address_row["street1"])?><br>
            <?php if(strlen($address_row["street2"]) > 0) { echo htmlentities($address_row["street2"]) . "<br>"; } ?>
            <?php=htmlentities($address_row["city"])?>, <?php=htmlentities(strtoupper($address_row["state"]))?> <?php=htmlentities($address_row["zip"])?>&nbsp;<img src="images/icons/maps-icon.png" width="16" onClick="gm('<?php echo $gm ?>',1);" title="Google Maps Address Location"><br/>
            <?php=htmlentities(strtoupper($address_row["country"]))?> </td>
          <td width="33%" valign="top">
            <?php=htmlentities($address_row["phone1"])?><br>
            <?php=htmlentities($address_row["phone2"])?>
          </td>
          <td width="33%">&nbsp; </td>
        </tr>
      </table>
    </td>
    </tr>
    <?php
    //end while loop for displaying adresses
    }
//end if for displaying addresses
}

} //end if for $allow_full permission
?>
</table>
</table>
