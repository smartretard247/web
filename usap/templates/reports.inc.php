<?php
$unit_select = unit_select(14);
$unit_select2 = unit_select(array(32,19));

$class_select = class_select(13,'',1);
$class_select_multi = str_replace("size='1'","multiple size='5'",$class_select);
$class_select_multi = str_replace("name='class_id'","name='class_id[]'",$class_select_multi);

$m = 0;
$month_select = "<select name='month'>\n";
foreach($_CONF['months'] as $month)
{
    $month_select .= "<option value='" . ($m+1) . "' ";
    if(date("m") == $m+1) { $month_select .= "selected"; }
    $month_select .= ">" . $_CONF['months'][$m++] . "</option>\n";
}
$month_select .= "</select>\n";

$subject_select = subject_select();
$subject_select = add_option($subject_select,'All');

$daily_report_pp_checked = '';
$daily_report_student_checked = ' checked';

if(isset($_COOKIE['daily_report_pers_type']))
{
    if($_COOKIE['daily_report_pers_type'] == 'pp')
    {
        $daily_report_pp_checked = ' checked';
        $daily_report_student_checked = '';
    }
}
?>
<br>

<table border='1' width='95%' align='center' cellpadding='5' cellspacing='2'>
  <col width='20%' align='left' style="font-weight:bold"></col>
  <col width='25%' align='center'></col>
  <col width='50%' align='center'></col>
  <col width='5%' align='center'></col>
  <tr class="table_cheading">
    <th>report</th>
    <th>&nbsp;</th>
    <th>options</th>
    <th>&nbsp;</th>
  </tr>
  <tr>
    <form method='get' action='reports/daily_report.php'>
      <td>Daily Report</td>
      <td>Unit:
        <?php=$unit_select?>
      </td>
      <td>
        <input type='radio' name='pers_type' value='pp'<?php=$daily_report_pp_checked?>>Permanent Party
        <input type='radio' name='pers_type' value='student'<?php=$daily_report_student_checked?>>Student
      </td>
      <td><input type="submit" class="button" name='submit' value='Go'></td>
    </form>
  </tr>
  <tr>
    <form method='get' action='reports/alpha_roster.php'>
      <td>Alpha Roster</td>
      <td>Unit:
        <?php=$unit_select?>
      </td>
      <td>
        <?php foreach($_CONF['pers_type'] as $type)
           { echo "<input type='checkbox' name='pers_type[]' value='$type'>$type&nbsp;&nbsp;"; }
        ?>
        <br>
          <input type="checkbox" name="component[]" value="Regular Army">Regular Army&nbsp;&nbsp;
          <input type="checkbox" name="component[]" value="National Guard">National Guard&nbsp;&nbsp;
          <input type="checkbox" name="component[]" value="Army Reserves">Army Reserves&nbsp;&nbsp;
          <input type="checkbox" name="all_comp" value="1">All Components
        <br><input type="checkbox" name="show_full_ssn" value="1">Full SSN
        (<input type="checkbox" name="basd" value="1">Include BASD/Marital Status)
      </td>
      <td><input type="submit" class="button" value='Go' name='submit'></td>
    </form>
  </tr>
  <tr>
    <form method="get" action="reports/att_det_roster.php">
      <td>Attached/Detached Roster</td>
      <td>Unit: <?php=$unit_select?></td>
      <td>
        <input type="checkbox" name="location[]" value="Attached">Attached
        &nbsp;&nbsp;
        <input type="checkbox" name="location[]" value="Detached">Detached
      </td>
      <td><input type="submit" class="button" value="Go" name="submit"></td>
    </form>
  </tr>
  <?php
  if(!empty($class_select))
  { ?>
  <tr>
    <form method='get' action='reports/class_roster.php'>
      <td>Class Roster</td>
      <td>Class:
        <?php=$class_select?>
      </td>
      <td><span class="example">(Empty classes are not listed to the left.)</span></td>
      <td><input type="submit" class="button" name='submit' value='Go'></td>
    </form>
  </tr>
  <?php } ?>
  <tr>
    <form method='get' action='reports/platoon_roster.php'>
      <td>Student Platoon Roster</td>
      <td>Unit:
        <?php=$unit_select?>
      </td>
      <td>
        <b><center>Platoon:</b></p><?php foreach($_CONF['platoon'] as $type)
                   { echo "<input type='checkbox' name='platoon[]' value='$type'>$type&nbsp;"; } ?>
        <br>
        <center><b>Shift:</b></p><?php foreach($_CONF['shift'] as $type)
                  { echo "<input type='checkbox' name='shift[]' value='$type'>$type&nbsp;"; } ?>
        <br>
        <p>Gender: <?php foreach($_CONF['gender'] as $type)
                   { echo "<input type='checkbox' name='gender[]' value='$type'>$type&nbsp;"; } ?>
        <br>
        Phase: <?php foreach($_CONF['phase'] as $type)
                  { echo "<input type='checkbox' name='phase[]' value='$type'>$type&nbsp;"; } ?>
      </td>
      <td><input type="submit" class="button" name='submit' value='Go'></td>
    </form>
  </tr>
  <tr>
    <form method='get' action='reports/inactive_report.php'>
      <td>Inactive Report</td>
      <td>Unit:
        <?php=$unit_select?>
      </td>
      <td>
        <input type='radio' name='pers_type' value='pp'>Permanent Party
        <input type='radio' name='pers_type' value='student' checked>Student
      </td>
      <td><input type="submit" class="button" name='submit' value='Go'></td>
    </form>
  </tr>
  <tr>
    <form method='get' action='reports/hrap_report.php'>
      <td>HRAP Report (by class)</td>
      <td valign='top'>Class:
        <?php=$class_select_multi?>
      </td>
      <td><span class="example">Use control-click to select multiple classes. Only HRAP address
      will show on report, all others will be empty. Empty classes are not shown in the list to the left.</span></td>
      <td><input type="submit" class="button" name='submit' value='Go'></td>
    </form>
  </tr>
  <tr>
    <form method='get' action='reports/hrap_report2.php'>
      <td>HRAP Status Report (by Unit)</td>
      <td>Unit:
        <?php=$unit_select?>
      </td>
      <td>
        <input type="radio" name="type" value="active" checked>Active Only&nbsp;&nbsp;
        <input type="radio" name="type" value="pcs">PCS Only&nbsp;&nbsp;
        <input type="radio" name="type" value="activepcs">Active and PCS
        <br>
        <span class="example">If PCS was chosen, start and end PCS dates can be given below. Leaving both dates blank
        will result in all PCS soldiers, providing a start date only will result in all soldiers after that date and an end
        date only will result in all soldiers before that date. Student records are kept for one year.</span>
        <br>
        Start: <input type="text" name="start_date" size="10" maxlength="9">
        End: <input type="text" name="end_date" size="10" maxlength="9">
      </td>
      <td><input type="submit" class="button" name='submit' value='Go'></td>
    </form>
  </tr>
  <tr>
    <form method='get' action='reports/profile_report.php'>
      <td>Profile Report</td>
      <td>Unit:
        <?php=$unit_select?>
      </td>
      <td>
        Platoon:
        <?php foreach($_CONF['platoon'] as $type)
                 { echo "<input type='checkbox' name='platoon[]' value='$type'>$type&nbsp;&nbsp;"; }
                ?>
        <br>
        Shift: <?php foreach($_CONF['shift'] as $type)
                  { echo "<input type='checkbox' name='shift[]' value='$type'>$type&nbsp;"; } ?>
        <br>
        <input type="checkbox" name="profile_only" value="1"> On current profile only
        <br>
        <input type="checkbox" name="recovery_only" value="1"> On recovery only
      </td>
      <td><input type="submit" class="button" name='submit' value='Go'></td>
    </form>
  </tr>
  <tr>
  <form method='get' action='<?php=$_CONF['html']?>/reports/eoc_rollup.php'>
    <td>EOC Rollup</td>
    <td>Unit: <?php=$unit_select?></td>
    <td>
      Start Date: <input type="text" name="start_date" size="10" maxlength="9">
      &nbsp;&nbsp;
      End Date: <input type="text" name="end_date" size="10" maxlength="9">
      &nbsp;&nbsp;
      <input type="checkbox" name="stats_only" value="1"> Statistics Only
      <br><span class="example">Start and End date can match to see statistics for a single day. Dates are inclusive.</span>
    </td>
    <td><input type="submit" class="button" name="submit" value="Go"></td>
  </form>
  </tr>
  <tr>
    <form method='get' action='reports/telephone_roster.php'>
      <td>Telephone / Local Address Roster</td>
      <td valign='top'>Unit: <?php=$unit_select?></td>
      <td>
        <font size='-1'>
        <input type='radio' name='pers_type' value='pp' checked> permanent party
        <input type='radio' name='pers_type' value='student'>student
        <br>
        address and phone information are from the local / off-post address</font>
      </td>
      <td><input type="submit" class="button" name='submit' value='Go'></td>
    </form>
  </tr>
  <tr>
    <form method='get' action='reports/gain_loss_roster.php'>
      <td>Gain/Loss Roster</td>
      <td valign='top'>Unit: <?php=$unit_select?></td>
      <td>
        <input type='radio' name='type' value='gain'>Gain
        <input type='radio' name='type' value='loss' checked>Loss
        <input type='radio' name='type' value='pcs'>PCS
        &nbsp;&nbsp;&nbsp;&nbsp;
        Days:
        <select name='days'>
            <option value='1'>1</option>
            <option value='2'>2</option>
            <option value='3'>3</option>
            <option value='4'>4</option>
            <option value='5'>5</option>
            <option value='6'>6</option>
            <option value='7'>7</option>
            <option value='30'>30</option>
            <option value='60'>60</option>
            <option value='90'>90</option>
            <option value='120'>120</option>
            <option value='150'>150</option>
            <option value='180'>180</option>
        </select><br>
        <?php foreach($_CONF['pers_type'] as $type)
           { echo "<input type='checkbox' name='pers_type[]' value='$type'>$type&nbsp;&nbsp;"; }
        ?>
        <br><font size='-1'>Loss roster requires a PCS or ETS date to be given.</font>
      </td>
      <td><input type="submit" class="button" name='submit' value='Go'></td>
    </form>
  </tr>
  <tr>
    <form method='get' action='reports/marriage_report.php'>
      <td>Marriage Report</td>
      <td>Unit:
        <?php=$unit_select?>
      </td>
      <td><?php foreach($_CONF['pers_type'] as $type)
                 { echo "<input type='checkbox' name='pers_type[]' value='$type'>$type&nbsp;&nbsp;"; }
          ?><br><?php=conf_select("marital_status")?>
      </td>
      <td><input type="submit" class="button" name='submit' value='Go'></td>
    </form>
  </tr>
  <tr>
    <form method='get' action='reports/graduation_roster.php'>
      <td>Graduation Roster</td>
      <td>Unit:
        <?php=$unit_select?>
      </td>
      <td>
        <input type='checkbox' name='pers_type[]' value='IET'> IET
        <input type='checkbox' name='pers_type[]' value='Non-IET'> Non-IET
        <br />
        Start Date: <input type="text" name="start_date" value="" size="10" maxlength="9">
        End Date: <input type="text" name="end_date" value="" size="10" maxlength="9">
        <br />
        <font size='-1'>(Check 'Not graduating w/class' on Edit Soldier to exclude from this report.)</font>
      </td>
      <td><input type="submit" class="button" value='Go' name='submit'></td>
    </form>
  </tr>
  <tr>
    <form method='get' action='reports/sign_in_roster.php'>
      <td>Sign-In Roster</td>
      <td>Unit:
        <?php=$unit_select?>
      </td>
      <td>Subject: <input type='text' name='subject' value='briefing' size='20' maxlength='50'><br>
        <?php foreach($_CONF['pers_type'] as $type)
           { echo "<input type='checkbox' name='pers_type[]' value='$type'>$type&nbsp;&nbsp;"; }
        ?><br>
        Platoon:<?php foreach($_CONF['platoon'] as $type)
                   { echo "<input type='checkbox' name='platoon[]' value='$type'>$type&nbsp;"; } ?>
        <br>
        Shift: <?php foreach($_CONF['shift'] as $type)
                  { echo "<input type='checkbox' name='shift[]' value='$type'>$type&nbsp;"; } ?>
        <br>
        Gender: <?php foreach($_CONF['gender'] as $type)
                   { echo "<input type='checkbox' name='gender[]' value='$type'>$type&nbsp;"; } ?>

      </td>
      <td><input type="submit" class="button" value='Go' name='submit'></td>
    </form>
  </tr>
  <tr>
    <form method='get' action='reports/airborne_msr_report.php'>
      <td>Airborne Report (msr)</td>
      <td>Unit:
        <?php=$unit_select?>
      </td>
      <td class="example">(Gives report in format needed for MSR, IET Only)</td>
      <td><input type="submit" class="button" value='Go' name='submit'></td>
    </form>
  </tr>
  <tr>
    <form method='get' action='reports/apft_report.php'>
      <td>APFT Report</td>
      <td>Unit:
        <?php=$unit_select?>
      </td>
      <td>Platoon:
        <?php foreach($_CONF['platoon'] as $type)
                 { echo "<input type='checkbox' name='platoon[]' value='$type'>$type&nbsp;&nbsp;"; }
                ?>
        <br>
        <input type="checkbox" name="ex_bct" checked>Exclude BCT
        <input type="checkbox" name="fail_only">Failures Only
      </td>
      <td><input type="submit" class="button" name='submit' value='Go'></td>
    </form>
  </tr>
  <tr>
    <form method='get' action='reports/ako_roster.php'>
      <td>AKO Roster</td>
      <td>Unit:
        <?php=$unit_select?>
      </td>
      <td>
        <?php foreach($_CONF['pers_type'] as $type)
           { echo "<input type='checkbox' name='pers_type[]' value='$type'>$type&nbsp;&nbsp;"; }
        ?>
        <br>Report Soldiers: <input type="radio" name="email_status" value="with_email" checked>with AKO <input type="radio" name="email_status" value="without_email">without AKO
      </td>
      <td><input type="submit" class="button" value='Go' name='submit'></td>
    </form>
  </tr>
  <?php

  //ensure time is between start and end of exodus
  if(strtolower($_CONF['exodus']) == 'on')
  {
    ?>
  <tr>
    <form method='get' action='reports/exodus_rollup.php'>
      <td>HBL Rollup</td>
      <td>Unit:
        <?php=$unit_select?>
      </td>
      <td class="example">(Summary of all Exodus travel.)</td>
      <td><input type="submit" class="button" value='Go' name='submit'></td>
    </form>
  </tr>
  <tr>
    <form method='get' action='reports/exodus_rollup_by_hour.php'>
      <td>HBL AIR Rollup (By Hour)</td>
      <td>Unit:
        <?php=$unit_select?>
      </td>
      <td>
        <input type="radio" name="mode" value="Dep" checked> Departure
        &nbsp;&nbsp;&nbsp;
        <input type="radio" name="mode" value="Ret"> Return
        <br />
        <span class="example">(Summary of AIR travel grouped into two hour increments)</span>
      </td>
      <td><input type="submit" class="button" value='Go' name='submit'></td>
    </form>
  </tr>
  <tr>
    <form method='get' action='reports/exodus_manifest.php'>
      <td>HBL Manifest</td>
      <td>Unit:
        <select size='1' name='unit' class='text_box'>
          <option value='0-0'>BDE - ALL</option>
        </select>
      </td>
      <td class="example">(This is not an official Manifest. Only the Manifest published by Brigade S3 is official. This can be used to verify
      that all of your soldiers are appearing on the Manifest like they should, though. This is the same form that Brigade
      will run to generate the offical Manifest. If a soldier is not appearing on the manifest, check the flight times to make
      sure it is not before 0500 for Atlanta or Augusta.)</td>
      <td><input type="submit" class="button" value='Go' name='submit'></td>
    </form>
  </tr>
  <?php
  }
  ?>
  <tr>
    <form method='get' action='reports/security_report.php'>
      <td>Security (S2) Report</td>
      <td>Unit:
        <?php=$unit_select?>
      </td>
      <td>
        <?php
        $select = conf_select('clearance_status');
        $select = add_option($select,'All');
        ?>
        Clearance Status:&nbsp;
          <input type="checkbox" name="all_resubmit" value="all_resubmit">All Resubmit&nbsp;
          or <?php=$select?>
        <br>
        <?php foreach($_CONF['pers_type'] as $type)
           { echo "<input type='checkbox' name='pers_type[]' value='$type'>$type&nbsp;&nbsp;"; }
        ?>
        <br>
        Platoon:<?php foreach($_CONF['platoon'] as $type)
                   { echo "<input type='checkbox' name='platoon[]' value='$type'>$type&nbsp;"; } ?>
        <br>
        Shift: <?php foreach($_CONF['shift'] as $type)
                  { echo "<input type='checkbox' name='shift[]' value='$type'>$type&nbsp;"; } ?>
        <br>
        Limit Arrival Date to:
        <select name="month">
          <option value="0">Any</option>
          <option value="1">January</option>
          <option value="2">February</option>
          <option value="3">March</option>
          <option value="4">April</option>
          <option value="5">May</option>
          <option value="6">June</option>
          <option value="7">July</option>
          <option value="8">August</option>
          <option value="9">September</option>
          <option value="10">October</option>
          <option value="11">November</option>
          <option value="12">December</option>
        </select>
        &nbsp;&nbsp;
        <select name="year">
          <option value="0">Any</option>
          <option value="2002">2002</option>
          <option value="2003">2003</option>
        </select>
      </td>
      <td><input type="submit" class="button" name='submit' value='Go'></td>
    </form>
  </tr>
  <tr>
    <form method="get" action="reports/driver_roster.php">
      <td>Military Driver's License Roster</td>
      <td>Unit: <?php=$unit_select?></td>
      <td>Type:
        <?php $s = conf_select("license_type");
           $s = add_option($s,'All');
           echo $s; ?><br>
        <input type="checkbox" name="permit_exp" value="1">Expired Permits&nbsp;
        <input type="checkbox" name="license_exp" value="1">Expired Licenses
      </td>
      <td><input type="submit" class="button" name="submit" value="Go"></td>
    </form>
  </tr>
  <tr>
    <form method='get' action='reports/dental_roster.php'>
      <td>Dental Roster</td>
      <td>Unit:
        <?php=$unit_select?>
      </td>
      <td>Category:
        <?php foreach($_CONF['dental_category'] as $cat)
           { echo "<input type='checkbox' name='dental_category[]' value='$cat'>$cat&nbsp;"; }
           echo "<br>\n";
           foreach($_CONF['pers_type'] as $type)
           { echo "<input type='checkbox' name='pers_type[]' value='$type'>$type&nbsp;&nbsp;"; }
        ?><br>
        Platoon:<?php foreach($_CONF['platoon'] as $type)
                   { echo "<input type='checkbox' name='platoon[]' value='$type'>$type&nbsp;"; } ?>
        <br>
        Shift: <?php foreach($_CONF['shift'] as $type)
                  { echo "<input type='checkbox' name='shift[]' value='$type'>$type&nbsp;"; } ?>
      </td>
      <td><input type="submit" class="button" value='Go' name='submit'></td>
    </form>
  </tr>
  <tr valign="top">
    <form method="GET" action="reports/mos_report.php">
      <td>MOS Report</td>
      <td>Unit: <?php= $unit_select?> </td>
      <td>
          <?php foreach($_CONF['pers_type'] as $type)
             { echo "<input type='checkbox' name='pers_type[]' value='$type'>$type&nbsp;&nbsp;"; }
          ?>
          <span class="example">(Hold down the 'control' key while clicking to select multiple MOSs or Clearance Statuses)</span>
          <br>
          MOS: <?php=conf_select('mos','',1,5)?>
          &nbsp;&nbsp;
          Clearance:
          <?php
            $cs = conf_select('clearance_status','',1,5);
            $cs = add_option($cs,'Any Clearance');
            echo $cs;
          ?>
      </td>
      <td><input type="submit" class="button" value="Go" name="submit"></td>
    </form>
  </tr>
  <tr>
    <form method="GET" action="reports/permission_report2.php">
      <td>Permission Report</td>
      <td>Unit: <?php=$unit_select?> </td>
      <td class="example">
        <?php=permission_select();?>
      </td>
      <td><input type="submit" class="button" value="Go" name="submit"></td>
    </form>
  </tr>
  <tr>
    <form method='get' action='reports/religion_roster.php'>
      <td>Religion Roster</td>
      <td>Unit:
        <?php=$unit_select?>
      </td>
      <td>
        <?php foreach($_CONF['pers_type'] as $type)
           { echo "<input type='checkbox' name='pers_type[]' value='$type'>$type&nbsp;&nbsp;"; }
           echo "<br>\n";
           $r = conf_select("religion");
           $r = add_option($r,'All Religions');
           echo "Limit to: " . $r;
        ?>
      </td>
      <td><input type="submit" class="button" value='Go' name='submit'></td>
    </form>
  </tr>
  <tr>
    <form method='get' action='reports/pov_report.php'>
      <td>POV Report</td>
      <td>Unit:
        <?php=$unit_select?>
      </td>
      <td>
        <?php foreach($_CONF['pers_type'] as $type)
           { echo "<input type='checkbox' name='pers_type[]' value='$type'>$type&nbsp;&nbsp;"; }
        ?>
      </td>
      <td><input type="submit" class="button" value='Go' name='submit'></td>
    </form>
  </tr>
  <tr>
    <form method='get' action='reports/race_report.php'>
      <td>Race Report</td>
      <td>Unit:
        <?php=$unit_select?>
      </td>
      <td>
        <?php foreach($_CONF['pers_type'] as $type)
           { echo "<input type='checkbox' name='pers_type[]' value='$type'>$type&nbsp;&nbsp;"; }
        ?>
      </td>
      <td><input type="submit" class="button" value='Go' name='submit'></td>
    </form>
  </tr>
  <tr>
    <form method='get' action='reports/appointment_report.php'>
      <td>Appointment Report</td>
      <td>Unit:
        <?php=$unit_select?>
      </td>
      <td>
        Platoon:
        <?php foreach($_CONF['platoon'] as $type)
                 { echo "<input type='checkbox' name='platoon[]' value='$type'>$type&nbsp;&nbsp;"; }
                ?>
        <br>
        Shift: <?php foreach($_CONF['shift'] as $type)
                  { echo "<input type='checkbox' name='shift[]' value='$type'>$type&nbsp;"; } ?>
        <br />
        Start Date: <input type="text" name="start_date" size="10" maxlength="9">
        &nbsp;&nbsp;
        End Date: <input type="text" name="end_date" size="10" maxlength="9">
        <br />
        <span class="example">(Start and/or End date can be left blank.)</span>
      </td>
      <td><input type="submit" class="button" name='submit' value='Go'></td>
    </form>
  </tr>
  <tr>
    <form method='get' action='reports/dob_report.php'>
      <td>Date of Birth and Age Report</td>
      <td>Unit:
        <?php=$unit_select?>
      </td>
      <td>
        <?php foreach($_CONF['pers_type'] as $type)
           { echo "<input type='checkbox' name='pers_type[]' value='$type'>$type&nbsp;&nbsp;"; }
        ?>
        <br>
        Min Age: <input type="text" name="min_age" size="3"> Max Age: <input type="text" name="max_age" size="3">
        <br>
        <span class="example">(Leave From/To blank for all ages)</span>
        <br>
        <input type="checkbox" name="sortbyage" value="1"> Sort by Age
      </td>
      <td><input type="submit" class="button" value='Go' name='submit'></td>
    </form>
  </tr>
  <tr>
    <form method='get' action='reports/phase_roster.php'>
      <td>Student Phase Roster</td>
      <td>Unit:
        <?php=$unit_select?>
      </td>
      <td>
        Platoon:<?php foreach($_CONF['platoon'] as $type)
                   { echo "<input type='checkbox' name='platoon[]' value='$type'>$type&nbsp;"; } ?>
        <br>
        Shift: <?php foreach($_CONF['shift'] as $type)
                  { echo "<input type='checkbox' name='shift[]' value='$type'>$type&nbsp;"; } ?>
        <br>
        Gender: <?php foreach($_CONF['gender'] as $type)
                   { echo "<input type='checkbox' name='gender[]' value='$type'>$type&nbsp;"; } ?>
        <br>
        Status: <?php=conf_select('phase_roster','all',0,0,'',1)?>
      </td>
      <td><input type="submit" class="button" name='submit' value='Go'></td>
    </form>
  </tr>
  <tr>
    <form method='get' action='reports/remarks_report.php'>
      <td>Remarks Report</td>
      <td>Unit:
        <?php=$unit_select2?>
      </td>
      <td>
        <?php foreach($_CONF['pers_type'] as $type)
           { echo "<input type='checkbox' name='pers_type[]' value='$type'>$type&nbsp;&nbsp;"; }
        ?>
        <br />
        New remarks in the past
        <select name='days'>
            <option value='1'>1</option>
            <option value='2'>2</option>
            <option value='3'>3</option>
            <option value='4'>4</option>
            <option value='5'>5</option>
            <option value='6'>6</option>
            <option value='7'>7</option>
            <option value='30'>30</option>
            <option value='60'>60</option>
            <option value='90'>90</option>
            <option value='120'>120</option>
            <option value='150'>150</option>
            <option value='180'>180</option>
        </select> days<br>
      Remark Subject: <?php=$subject_select?>
      </td>
      <td><input type="submit" class="button" value='Go' name='submit'></td>
    </form>
  </tr>
  <tr>
    <form method='get' action='reports/cac_report.php'>
      <td>CAC Report</td>
      <td>Unit:
        <?php=$unit_select?>
      </td>
      <td>
        <?php foreach($_CONF['pers_type'] as $type)
           { echo "<input type='checkbox' name='pers_type[]' value='$type'>$type&nbsp;&nbsp;"; }
        ?><br>
        Platoon:<?php foreach($_CONF['platoon'] as $type)
                   { echo "<input type='checkbox' name='platoon[]' value='$type'>$type&nbsp;"; } ?>
        <br />
        <input type="checkbox" name="with_cac" value="1">With CAC
        <input type="checkbox" name="without_cac" value="1">Without CAC
      </td>
      <td><input type="submit" class="button" value='Go' name='submit'></td>
    </form>
  </tr>
  <tr>
    <form method='get' action='reports/address_report.php'>
      <td>Address Report</td>
      <td>Unit:
        <?php=$unit_select?>
      </td>
      <td>
        <?php foreach($_CONF['pers_type'] as $type)
           { echo "<input type='checkbox' name='pers_type[]' value='$type'>$type&nbsp;&nbsp;"; }
        ?><br>
        Platoon:<?php foreach($_CONF['platoon'] as $type)
                   { echo "<input type='checkbox' name='platoon[]' value='$type'>$type&nbsp;"; } ?>
        <br />
        Address Type: <?php=add_option(conf_select('address_type'),'Any');?>
      </td>
      <td><input type="submit" class="button" value='Go' name='submit'></td>
    </form>
  </tr>
 <form method='get' action='reports/roomRoster.php'>
      <td>Building Roster</td>
      <td>Unit:
        <?php=$unit_select?>
      </td>
      <td>
        <?php              echo "<br>\n";
          ?>
		Building Number:<input type='text' name='building_number' value='' size='20' maxlength='50'>
	<?php
        ?>
      </td>
      <td><input type="submit" class="button" value='Go' name='submit'></td>
    </form>
</tr>  
 <form method='get' action='reports/roomRoster.php'>
      <td>Room Roster</td>
      <td>Unit:
        <?php=$unit_select?>
      </td>
      <td>
        <?php              echo "<br>\n";
          ?>
		Building Number:<input type='text' name='building_number' value='' size='20' maxlength='50'></p>
		Room Number:<input type='text' name='room' value='' size='20' maxlength='50'>
	<?php
        ?>
      </td>
      <td><input type="submit" class="button" value='Go' name='submit'></td>
    </form>
</table>



<br>
<table border='1' align='center' width='95%' cellspacing='1' cellpadding='1'>
<form method='get' action='<?php=$_CONF['html']?>/query.php'>
  <tr class="table_heading">
    <td>Custom Report / Query</td>
  </tr>
  <tr>
    <td align='center'>code: <input type='text' size='33' maxlength='32' name='code'>&nbsp;<input type="submit" class="button" name='submit' value='Go'></td>
  </tr>
</form>
</table>
