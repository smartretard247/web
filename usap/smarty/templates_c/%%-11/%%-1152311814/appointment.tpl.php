<?php /* Smarty version 2.3.0, created on 2011-03-13 00:47:00
         compiled from appointment.tpl */ ?>
<?php $this->_load_plugins(array(
array('function', 'html_options', 'appointment.tpl', 79, false),)); ?><?php if (isset($this->_sections["message"])) unset($this->_sections["message"]);
$this->_sections["message"]['name'] = "message";
$this->_sections["message"]['loop'] = is_array("1") ? count("1") : max(0, (int)"1");
$this->_sections["message"]['show'] = (bool)$this->_tpl_vars['values']['message'];
$this->_sections["message"]['max'] = $this->_sections["message"]['loop'];
$this->_sections["message"]['step'] = 1;
$this->_sections["message"]['start'] = $this->_sections["message"]['step'] > 0 ? 0 : $this->_sections["message"]['loop']-1;
if ($this->_sections["message"]['show']) {
    $this->_sections["message"]['total'] = $this->_sections["message"]['loop'];
    if ($this->_sections["message"]['total'] == 0)
        $this->_sections["message"]['show'] = false;
} else
    $this->_sections["message"]['total'] = 0;
if ($this->_sections["message"]['show']):

            for ($this->_sections["message"]['index'] = $this->_sections["message"]['start'], $this->_sections["message"]['iteration'] = 1;
                 $this->_sections["message"]['iteration'] <= $this->_sections["message"]['total'];
                 $this->_sections["message"]['index'] += $this->_sections["message"]['step'], $this->_sections["message"]['iteration']++):
$this->_sections["message"]['rownum'] = $this->_sections["message"]['iteration'];
$this->_sections["message"]['index_prev'] = $this->_sections["message"]['index'] - $this->_sections["message"]['step'];
$this->_sections["message"]['index_next'] = $this->_sections["message"]['index'] + $this->_sections["message"]['step'];
$this->_sections["message"]['first']      = ($this->_sections["message"]['iteration'] == 1);
$this->_sections["message"]['last']       = ($this->_sections["message"]['iteration'] == $this->_sections["message"]['total']);
?>
<p style="text-align:center;" class="<?php echo $this->_tpl_vars['values']['message_class']; ?>
"><?php echo $this->_tpl_vars['values']['message']; ?>
</p>
<?php endfor; endif; ?>

<table border="0" width="100%"><tr><td>

<?php if (isset($this->_sections["view_appointments"])) unset($this->_sections["view_appointments"]);
$this->_sections["view_appointments"]['name'] = "view_appointments";
$this->_sections["view_appointments"]['loop'] = is_array("1") ? count("1") : max(0, (int)"1");
$this->_sections["view_appointments"]['show'] = (bool)$this->_tpl_vars['values']['show']['current_appointments'];
$this->_sections["view_appointments"]['max'] = $this->_sections["view_appointments"]['loop'];
$this->_sections["view_appointments"]['step'] = 1;
$this->_sections["view_appointments"]['start'] = $this->_sections["view_appointments"]['step'] > 0 ? 0 : $this->_sections["view_appointments"]['loop']-1;
if ($this->_sections["view_appointments"]['show']) {
    $this->_sections["view_appointments"]['total'] = $this->_sections["view_appointments"]['loop'];
    if ($this->_sections["view_appointments"]['total'] == 0)
        $this->_sections["view_appointments"]['show'] = false;
} else
    $this->_sections["view_appointments"]['total'] = 0;
if ($this->_sections["view_appointments"]['show']):

            for ($this->_sections["view_appointments"]['index'] = $this->_sections["view_appointments"]['start'], $this->_sections["view_appointments"]['iteration'] = 1;
                 $this->_sections["view_appointments"]['iteration'] <= $this->_sections["view_appointments"]['total'];
                 $this->_sections["view_appointments"]['index'] += $this->_sections["view_appointments"]['step'], $this->_sections["view_appointments"]['iteration']++):
$this->_sections["view_appointments"]['rownum'] = $this->_sections["view_appointments"]['iteration'];
$this->_sections["view_appointments"]['index_prev'] = $this->_sections["view_appointments"]['index'] - $this->_sections["view_appointments"]['step'];
$this->_sections["view_appointments"]['index_next'] = $this->_sections["view_appointments"]['index'] + $this->_sections["view_appointments"]['step'];
$this->_sections["view_appointments"]['first']      = ($this->_sections["view_appointments"]['iteration'] == 1);
$this->_sections["view_appointments"]['last']       = ($this->_sections["view_appointments"]['iteration'] == $this->_sections["view_appointments"]['total']);
?>
<table border="1" width="100%" align="center" cellpadding="2" cellspacing="0">
  <tr>
    <td class="table_cheading">Appointments for <?php echo $this->_tpl_vars['values']['name']; ?>
</td>
  </tr>
  <tr>
    <td align="center">
      [<a href="<?php echo $this->_tpl_vars['values']['url']; ?>
/appointment.php?id=<?php echo $this->_tpl_vars['values']['id']; ?>
&export2=excel&all_apt=1">Export to Excel</a>]&nbsp;&nbsp;
      [<a href="<?php echo $this->_tpl_vars['values']['url']; ?>
/appointment.php?id=<?php echo $this->_tpl_vars['values']['id']; ?>
&export2=word&all_apt=1">Export to Word</a>]&nbsp;&nbsp;
      [<a href="<?php echo $this->_tpl_vars['values']['url']; ?>
/data_sheet.php?id=<?php echo $this->_tpl_vars['values']['id']; ?>
">Data Sheet</a>]
    </td>
  </tr>
</table>
<br />
<form method="GET" action="<?php echo $this->_tpl_vars['values']['url']; ?>
/appointment.php">
<?php endfor; endif; ?>

<?php if (isset($this->_sections["export_heading"])) unset($this->_sections["export_heading"]);
$this->_sections["export_heading"]['name'] = "export_heading";
$this->_sections["export_heading"]['loop'] = is_array("1") ? count("1") : max(0, (int)"1");
$this->_sections["export_heading"]['show'] = (bool)$this->_tpl_vars['values']['show']['export_heading'];
$this->_sections["export_heading"]['max'] = $this->_sections["export_heading"]['loop'];
$this->_sections["export_heading"]['step'] = 1;
$this->_sections["export_heading"]['start'] = $this->_sections["export_heading"]['step'] > 0 ? 0 : $this->_sections["export_heading"]['loop']-1;
if ($this->_sections["export_heading"]['show']) {
    $this->_sections["export_heading"]['total'] = $this->_sections["export_heading"]['loop'];
    if ($this->_sections["export_heading"]['total'] == 0)
        $this->_sections["export_heading"]['show'] = false;
} else
    $this->_sections["export_heading"]['total'] = 0;
if ($this->_sections["export_heading"]['show']):

            for ($this->_sections["export_heading"]['index'] = $this->_sections["export_heading"]['start'], $this->_sections["export_heading"]['iteration'] = 1;
                 $this->_sections["export_heading"]['iteration'] <= $this->_sections["export_heading"]['total'];
                 $this->_sections["export_heading"]['index'] += $this->_sections["export_heading"]['step'], $this->_sections["export_heading"]['iteration']++):
$this->_sections["export_heading"]['rownum'] = $this->_sections["export_heading"]['iteration'];
$this->_sections["export_heading"]['index_prev'] = $this->_sections["export_heading"]['index'] - $this->_sections["export_heading"]['step'];
$this->_sections["export_heading"]['index_next'] = $this->_sections["export_heading"]['index'] + $this->_sections["export_heading"]['step'];
$this->_sections["export_heading"]['first']      = ($this->_sections["export_heading"]['iteration'] == 1);
$this->_sections["export_heading"]['last']       = ($this->_sections["export_heading"]['iteration'] == $this->_sections["export_heading"]['total']);
?>
<div align="center" class="table_cheading">Appointments for <?php echo $this->_tpl_vars['values']['name']; ?>
</div>
<?php endfor; endif; ?>

<?php echo $this->_tpl_vars['values']['roster']; ?>


</td></tr><tr><td>

<?php if (isset($this->_sections["all_apt"])) unset($this->_sections["all_apt"]);
$this->_sections["all_apt"]['name'] = "all_apt";
$this->_sections["all_apt"]['loop'] = is_array("1") ? count("1") : max(0, (int)"1");
$this->_sections["all_apt"]['show'] = (bool)$this->_tpl_vars['values']['show']['all_apt_link'];
$this->_sections["all_apt"]['max'] = $this->_sections["all_apt"]['loop'];
$this->_sections["all_apt"]['step'] = 1;
$this->_sections["all_apt"]['start'] = $this->_sections["all_apt"]['step'] > 0 ? 0 : $this->_sections["all_apt"]['loop']-1;
if ($this->_sections["all_apt"]['show']) {
    $this->_sections["all_apt"]['total'] = $this->_sections["all_apt"]['loop'];
    if ($this->_sections["all_apt"]['total'] == 0)
        $this->_sections["all_apt"]['show'] = false;
} else
    $this->_sections["all_apt"]['total'] = 0;
if ($this->_sections["all_apt"]['show']):

            for ($this->_sections["all_apt"]['index'] = $this->_sections["all_apt"]['start'], $this->_sections["all_apt"]['iteration'] = 1;
                 $this->_sections["all_apt"]['iteration'] <= $this->_sections["all_apt"]['total'];
                 $this->_sections["all_apt"]['index'] += $this->_sections["all_apt"]['step'], $this->_sections["all_apt"]['iteration']++):
$this->_sections["all_apt"]['rownum'] = $this->_sections["all_apt"]['iteration'];
$this->_sections["all_apt"]['index_prev'] = $this->_sections["all_apt"]['index'] - $this->_sections["all_apt"]['step'];
$this->_sections["all_apt"]['index_next'] = $this->_sections["all_apt"]['index'] + $this->_sections["all_apt"]['step'];
$this->_sections["all_apt"]['first']      = ($this->_sections["all_apt"]['iteration'] == 1);
$this->_sections["all_apt"]['last']       = ($this->_sections["all_apt"]['iteration'] == $this->_sections["all_apt"]['total']);
?>
Click <a href="<?php echo $this->_tpl_vars['values']['url']; ?>
/events.php?id=<?php echo $this->_tpl_vars['values']['id']; ?>
">here</a> to schedule an event.<br/>
Click <a href="<?php echo $this->_tpl_vars['values']['url']; ?>
/appointment.php?id=<?php echo $this->_tpl_vars['values']['id']; ?>
&all_apt=1">here</a> to view any past appointments<br />
<br />
<?php endfor; endif; ?>

<?php if (isset($this->_sections["view_appointments2"])) unset($this->_sections["view_appointments2"]);
$this->_sections["view_appointments2"]['name'] = "view_appointments2";
$this->_sections["view_appointments2"]['loop'] = is_array("1") ? count("1") : max(0, (int)"1");
$this->_sections["view_appointments2"]['show'] = (bool)$this->_tpl_vars['values']['show']['current_appointments'];
$this->_sections["view_appointments2"]['max'] = $this->_sections["view_appointments2"]['loop'];
$this->_sections["view_appointments2"]['step'] = 1;
$this->_sections["view_appointments2"]['start'] = $this->_sections["view_appointments2"]['step'] > 0 ? 0 : $this->_sections["view_appointments2"]['loop']-1;
if ($this->_sections["view_appointments2"]['show']) {
    $this->_sections["view_appointments2"]['total'] = $this->_sections["view_appointments2"]['loop'];
    if ($this->_sections["view_appointments2"]['total'] == 0)
        $this->_sections["view_appointments2"]['show'] = false;
} else
    $this->_sections["view_appointments2"]['total'] = 0;
if ($this->_sections["view_appointments2"]['show']):

            for ($this->_sections["view_appointments2"]['index'] = $this->_sections["view_appointments2"]['start'], $this->_sections["view_appointments2"]['iteration'] = 1;
                 $this->_sections["view_appointments2"]['iteration'] <= $this->_sections["view_appointments2"]['total'];
                 $this->_sections["view_appointments2"]['index'] += $this->_sections["view_appointments2"]['step'], $this->_sections["view_appointments2"]['iteration']++):
$this->_sections["view_appointments2"]['rownum'] = $this->_sections["view_appointments2"]['iteration'];
$this->_sections["view_appointments2"]['index_prev'] = $this->_sections["view_appointments2"]['index'] - $this->_sections["view_appointments2"]['step'];
$this->_sections["view_appointments2"]['index_next'] = $this->_sections["view_appointments2"]['index'] + $this->_sections["view_appointments2"]['step'];
$this->_sections["view_appointments2"]['first']      = ($this->_sections["view_appointments2"]['iteration'] == 1);
$this->_sections["view_appointments2"]['last']       = ($this->_sections["view_appointments2"]['iteration'] == $this->_sections["view_appointments2"]['total']);
?>
<input type="submit" value="Delete Checked Appointments" class="button">
<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['values']['id']; ?>
">
</form>
<?php endfor; endif; ?>

</td></tr><tr><td><br>

<?php if (isset($this->_sections["add_appointment"])) unset($this->_sections["add_appointment"]);
$this->_sections["add_appointment"]['name'] = "add_appointment";
$this->_sections["add_appointment"]['loop'] = is_array("1") ? count("1") : max(0, (int)"1");
$this->_sections["add_appointment"]['show'] = (bool)$this->_tpl_vars['values']['show']['new_appointment'];
$this->_sections["add_appointment"]['max'] = $this->_sections["add_appointment"]['loop'];
$this->_sections["add_appointment"]['step'] = 1;
$this->_sections["add_appointment"]['start'] = $this->_sections["add_appointment"]['step'] > 0 ? 0 : $this->_sections["add_appointment"]['loop']-1;
if ($this->_sections["add_appointment"]['show']) {
    $this->_sections["add_appointment"]['total'] = $this->_sections["add_appointment"]['loop'];
    if ($this->_sections["add_appointment"]['total'] == 0)
        $this->_sections["add_appointment"]['show'] = false;
} else
    $this->_sections["add_appointment"]['total'] = 0;
if ($this->_sections["add_appointment"]['show']):

            for ($this->_sections["add_appointment"]['index'] = $this->_sections["add_appointment"]['start'], $this->_sections["add_appointment"]['iteration'] = 1;
                 $this->_sections["add_appointment"]['iteration'] <= $this->_sections["add_appointment"]['total'];
                 $this->_sections["add_appointment"]['index'] += $this->_sections["add_appointment"]['step'], $this->_sections["add_appointment"]['iteration']++):
$this->_sections["add_appointment"]['rownum'] = $this->_sections["add_appointment"]['iteration'];
$this->_sections["add_appointment"]['index_prev'] = $this->_sections["add_appointment"]['index'] - $this->_sections["add_appointment"]['step'];
$this->_sections["add_appointment"]['index_next'] = $this->_sections["add_appointment"]['index'] + $this->_sections["add_appointment"]['step'];
$this->_sections["add_appointment"]['first']      = ($this->_sections["add_appointment"]['iteration'] == 1);
$this->_sections["add_appointment"]['last']       = ($this->_sections["add_appointment"]['iteration'] == $this->_sections["add_appointment"]['total']);
?>
<form method="POST" action="<?php echo $this->_tpl_vars['values']['url']; ?>
/appointment.php" name="appointment">
<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['values']['id']; ?>
">
<table border="1" cellpadding="2" cellspacing="0" width="95%" align="center">
  <tr>
    <td colspan="2" class="table_cheading">New Appointment</td>
  </tr>
  <tr>
    <td>
      <table border="0" cellpadding="2" width="100%">
        <col class="column_name" width="15%"></col>
        <col width="85%"></col>
        <tr>
          <td>Name</td>
          <td>
            <?php echo $this->_tpl_vars['values']['name']; ?>

            &nbsp;&nbsp;
            [<a href="<?php echo $this->_tpl_vars['values']['url']; ?>
/data_sheet.php?id=<?php echo $this->_tpl_vars['values']['id']; ?>
">Data Sheet</a>]
          </td>
        </tr>
        <tr>
          <td>Description</td>
          <td><input type="text" name="description" size="40" maxlength="255" value="<?php echo $this->_tpl_vars['values']['description']; ?>
"></td>
        </tr>
        <tr>
          <td>Location</td>
          <td><input type="text" name="location" size="40" maxlength="255" value="<?php echo $this->_tpl_vars['values']['location']; ?>
"></td>
        </tr>
        <tr>
          <td>Start</td>
          <td>
            Date: <input type="text" name="start_date" size="10" maxlength="9" value="<?php echo $this->_tpl_vars['values']['start_date']; ?>
" onchange="document.appointment.end_date.value=document.appointment.start_date.value;">
            Time: <select name="start_time" onchange="document.appointment.end_time.selectedIndex=document.appointment.start_time.selectedIndex+1;">
              <?php $this->_plugins['function']['html_options'][0](array('options' => $this->_tpl_vars['values']['start_times']['options'],'selected' => $this->_tpl_vars['values']['start_times']['selected']), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>
            </select>
          </td>
        </tr>
        <tr>
          <td>End</td>
          <td>
            Date: <input type="text" name="end_date" size="10" maxlength="9" value="<?php echo $this->_tpl_vars['values']['end_date']; ?>
">
            Time: <select name="end_time">
              <?php $this->_plugins['function']['html_options'][0](array('options' => $this->_tpl_vars['values']['end_times']['options'],'selected' => $this->_tpl_vars['values']['end_times']['selected']), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>
            </select>
          </td>
        </tr>
        <tr>
          <td>Private</td>
          <td>
            <input type="checkbox" name="private" <?php echo $this->_tpl_vars['values']['private']; ?>
 value="1">
            <span class="example">Viewable only by users who have View Restricted Remarks permissions for <?php echo $this->_tpl_vars['values']['name']; ?>
.</span>
        <tr>
          <td>Notes</td>
          <td><textarea rows="5" cols="40" name="notes"><?php echo $this->_tpl_vars['values']['notes']; ?>
</textarea></td>
        </tr>
      </table>
    </td>
  <tr>
    <td align="center" colspan="2">
      <input class="button" type="reset" value="Clear">
      &nbsp;&nbsp;
      <input class="button" type="submit" value="Submit" name="new_appointment_submit">
    </td>
  </tr>
</table>
</form>
<?php endfor; endif; ?>

</td></tr></table>