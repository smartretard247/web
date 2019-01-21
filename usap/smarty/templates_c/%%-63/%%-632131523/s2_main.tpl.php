<?php /* Smarty version 2.3.0, created on 2004-01-14 09:55:52
         compiled from s2_main.tpl */ ?>
<table border="1" cellpadding="2" cellspacing="0" width="95%" align="center">
  <tr>
    <td class="table_cheading">S2 - Security</td>
  </tr>

  
  <?php if (isset($this->_sections["error"])) unset($this->_sections["error"]);
$this->_sections["error"]['name'] = "error";
$this->_sections["error"]['loop'] = is_array("1") ? count("1") : max(0, (int)"1");
$this->_sections["error"]['show'] = (bool)$this->_tpl_vars['error'];
$this->_sections["error"]['max'] = $this->_sections["error"]['loop'];
$this->_sections["error"]['step'] = 1;
$this->_sections["error"]['start'] = $this->_sections["error"]['step'] > 0 ? 0 : $this->_sections["error"]['loop']-1;
if ($this->_sections["error"]['show']) {
    $this->_sections["error"]['total'] = $this->_sections["error"]['loop'];
    if ($this->_sections["error"]['total'] == 0)
        $this->_sections["error"]['show'] = false;
} else
    $this->_sections["error"]['total'] = 0;
if ($this->_sections["error"]['show']):

            for ($this->_sections["error"]['index'] = $this->_sections["error"]['start'], $this->_sections["error"]['iteration'] = 1;
                 $this->_sections["error"]['iteration'] <= $this->_sections["error"]['total'];
                 $this->_sections["error"]['index'] += $this->_sections["error"]['step'], $this->_sections["error"]['iteration']++):
$this->_sections["error"]['rownum'] = $this->_sections["error"]['iteration'];
$this->_sections["error"]['index_prev'] = $this->_sections["error"]['index'] - $this->_sections["error"]['step'];
$this->_sections["error"]['index_next'] = $this->_sections["error"]['index'] + $this->_sections["error"]['step'];
$this->_sections["error"]['first']      = ($this->_sections["error"]['iteration'] == 1);
$this->_sections["error"]['last']       = ($this->_sections["error"]['iteration'] == $this->_sections["error"]['total']);
?>
  <tr>
    <td class="error"><?php echo $this->_tpl_vars['error']; ?>
</td>
  </tr>
  <?php endfor; endif; ?>

  
  <?php if (isset($this->_sections["message"])) unset($this->_sections["message"]);
$this->_sections["message"]['name'] = "message";
$this->_sections["message"]['loop'] = is_array("1") ? count("1") : max(0, (int)"1");
$this->_sections["message"]['show'] = (bool)$this->_tpl_vars['message'];
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
  <tr>
    <td class="notice"><?php echo $this->_tpl_vars['message']; ?>
</td>
  </tr>
  <?php endfor; endif; ?>

  
  <?php if (isset($this->_sections["search"])) unset($this->_sections["search"]);
$this->_sections["search"]['name'] = "search";
$this->_sections["search"]['loop'] = is_array("1") ? count("1") : max(0, (int)"1");
$this->_sections["search"]['show'] = (bool)$this->_tpl_vars['show']['search'];
$this->_sections["search"]['max'] = $this->_sections["search"]['loop'];
$this->_sections["search"]['step'] = 1;
$this->_sections["search"]['start'] = $this->_sections["search"]['step'] > 0 ? 0 : $this->_sections["search"]['loop']-1;
if ($this->_sections["search"]['show']) {
    $this->_sections["search"]['total'] = $this->_sections["search"]['loop'];
    if ($this->_sections["search"]['total'] == 0)
        $this->_sections["search"]['show'] = false;
} else
    $this->_sections["search"]['total'] = 0;
if ($this->_sections["search"]['show']):

            for ($this->_sections["search"]['index'] = $this->_sections["search"]['start'], $this->_sections["search"]['iteration'] = 1;
                 $this->_sections["search"]['iteration'] <= $this->_sections["search"]['total'];
                 $this->_sections["search"]['index'] += $this->_sections["search"]['step'], $this->_sections["search"]['iteration']++):
$this->_sections["search"]['rownum'] = $this->_sections["search"]['iteration'];
$this->_sections["search"]['index_prev'] = $this->_sections["search"]['index'] - $this->_sections["search"]['step'];
$this->_sections["search"]['index_next'] = $this->_sections["search"]['index'] + $this->_sections["search"]['step'];
$this->_sections["search"]['first']      = ($this->_sections["search"]['iteration'] == 1);
$this->_sections["search"]['last']       = ($this->_sections["search"]['iteration'] == $this->_sections["search"]['total']);
?>
  <tr>
    <form method="GET" action="<?php echo $this->_tpl_vars['url']; ?>
/s2/index.php">
    <td>
      Locate: <input type="text" name="locate_text" size="40">&nbsp;
      <input type="submit" value="Go" class="button">
      &nbsp;&nbsp;
      <input type="submit" value="Issue Detail Report" name="issue_detail_submit" class="button">
    </td>
    </form>
  </tr>
  <?php endfor; endif; ?>

  
  <?php if (isset($this->_sections["info"])) unset($this->_sections["info"]);
$this->_sections["info"]['name'] = "info";
$this->_sections["info"]['loop'] = is_array("1") ? count("1") : max(0, (int)"1");
$this->_sections["info"]['show'] = (bool)$this->_tpl_vars['locate_results'];
$this->_sections["info"]['max'] = $this->_sections["info"]['loop'];
$this->_sections["info"]['step'] = 1;
$this->_sections["info"]['start'] = $this->_sections["info"]['step'] > 0 ? 0 : $this->_sections["info"]['loop']-1;
if ($this->_sections["info"]['show']) {
    $this->_sections["info"]['total'] = $this->_sections["info"]['loop'];
    if ($this->_sections["info"]['total'] == 0)
        $this->_sections["info"]['show'] = false;
} else
    $this->_sections["info"]['total'] = 0;
if ($this->_sections["info"]['show']):

            for ($this->_sections["info"]['index'] = $this->_sections["info"]['start'], $this->_sections["info"]['iteration'] = 1;
                 $this->_sections["info"]['iteration'] <= $this->_sections["info"]['total'];
                 $this->_sections["info"]['index'] += $this->_sections["info"]['step'], $this->_sections["info"]['iteration']++):
$this->_sections["info"]['rownum'] = $this->_sections["info"]['iteration'];
$this->_sections["info"]['index_prev'] = $this->_sections["info"]['index'] - $this->_sections["info"]['step'];
$this->_sections["info"]['index_next'] = $this->_sections["info"]['index'] + $this->_sections["info"]['step'];
$this->_sections["info"]['first']      = ($this->_sections["info"]['iteration'] == 1);
$this->_sections["info"]['last']       = ($this->_sections["info"]['iteration'] == $this->_sections["info"]['total']);
?>
  <tr>
    <td><?php echo $this->_tpl_vars['locate_results']; ?>
</td>
  </tr>
  <?php endfor; endif; ?>

  
  <?php if (isset($this->_sections["info"])) unset($this->_sections["info"]);
$this->_sections["info"]['name'] = "info";
$this->_sections["info"]['loop'] = is_array("1") ? count("1") : max(0, (int)"1");
$this->_sections["info"]['show'] = (bool)$this->_tpl_vars['show']['info'];
$this->_sections["info"]['max'] = $this->_sections["info"]['loop'];
$this->_sections["info"]['step'] = 1;
$this->_sections["info"]['start'] = $this->_sections["info"]['step'] > 0 ? 0 : $this->_sections["info"]['loop']-1;
if ($this->_sections["info"]['show']) {
    $this->_sections["info"]['total'] = $this->_sections["info"]['loop'];
    if ($this->_sections["info"]['total'] == 0)
        $this->_sections["info"]['show'] = false;
} else
    $this->_sections["info"]['total'] = 0;
if ($this->_sections["info"]['show']):

            for ($this->_sections["info"]['index'] = $this->_sections["info"]['start'], $this->_sections["info"]['iteration'] = 1;
                 $this->_sections["info"]['iteration'] <= $this->_sections["info"]['total'];
                 $this->_sections["info"]['index'] += $this->_sections["info"]['step'], $this->_sections["info"]['iteration']++):
$this->_sections["info"]['rownum'] = $this->_sections["info"]['iteration'];
$this->_sections["info"]['index_prev'] = $this->_sections["info"]['index'] - $this->_sections["info"]['step'];
$this->_sections["info"]['index_next'] = $this->_sections["info"]['index'] + $this->_sections["info"]['step'];
$this->_sections["info"]['first']      = ($this->_sections["info"]['iteration'] == 1);
$this->_sections["info"]['last']       = ($this->_sections["info"]['iteration'] == $this->_sections["info"]['total']);
?>
  <tr>
    <td class="table_heading">Soldier Information</td>
  </tr>
  <tr>
    <td>
      <form method="POST" action="<?php echo $this->_tpl_vars['url']; ?>
/s2/index.php">
      <table border="0" cellpadding="2" cellspacing="0" width="100%">
        <col width="33%"></col>
        <col width="33%"></col>
        <col width="34%"></col>
        <?php if (isset($this->_sections["pcs"])) unset($this->_sections["pcs"]);
$this->_sections["pcs"]['name'] = "pcs";
$this->_sections["pcs"]['loop'] = is_array("1") ? count("1") : max(0, (int)"1");
$this->_sections["pcs"]['show'] = (bool)$this->_tpl_vars['info']['pcs'];
$this->_sections["pcs"]['max'] = $this->_sections["pcs"]['loop'];
$this->_sections["pcs"]['step'] = 1;
$this->_sections["pcs"]['start'] = $this->_sections["pcs"]['step'] > 0 ? 0 : $this->_sections["pcs"]['loop']-1;
if ($this->_sections["pcs"]['show']) {
    $this->_sections["pcs"]['total'] = $this->_sections["pcs"]['loop'];
    if ($this->_sections["pcs"]['total'] == 0)
        $this->_sections["pcs"]['show'] = false;
} else
    $this->_sections["pcs"]['total'] = 0;
if ($this->_sections["pcs"]['show']):

            for ($this->_sections["pcs"]['index'] = $this->_sections["pcs"]['start'], $this->_sections["pcs"]['iteration'] = 1;
                 $this->_sections["pcs"]['iteration'] <= $this->_sections["pcs"]['total'];
                 $this->_sections["pcs"]['index'] += $this->_sections["pcs"]['step'], $this->_sections["pcs"]['iteration']++):
$this->_sections["pcs"]['rownum'] = $this->_sections["pcs"]['iteration'];
$this->_sections["pcs"]['index_prev'] = $this->_sections["pcs"]['index'] - $this->_sections["pcs"]['step'];
$this->_sections["pcs"]['index_next'] = $this->_sections["pcs"]['index'] + $this->_sections["pcs"]['step'];
$this->_sections["pcs"]['first']      = ($this->_sections["pcs"]['iteration'] == 1);
$this->_sections["pcs"]['last']       = ($this->_sections["pcs"]['iteration'] == $this->_sections["pcs"]['total']);
?>
        <tr>
          <td colspan="3">NOTICE: This soldier has a PCS/ETS/Deleted status</td>
        </tr>
        <?php endfor; endif; ?>
        <tr>
          <td colspan="3">
            <a href="<?php echo $this->_tpl_vars['url']; ?>
/data_sheet.php?id=<?php echo $this->_tpl_vars['info']['id']; ?>
">Data Sheet</a>
            &nbsp;|&nbsp;
            <a href="<?php echo $this->_tpl_vars['url']; ?>
/add_remark.php?id=<?php echo $this->_tpl_vars['info']['id']; ?>
">New Global Remark</a>
            &nbsp;|&nbsp;
            <a href="<?php echo $this->_tpl_vars['url']; ?>
/reports/s2_history_report.php?id=<?php echo $this->_tpl_vars['info']['id']; ?>
">Security History</a>
          </td>
        </tr>
        <tr>
          <td class="column_name">Name</td>
          <td class="column_name">Rank</td>
          <td class="column_name">SSN</td>
        </tr>
        <tr>
          <td><?php echo $this->_tpl_vars['info']['last_name']; ?>
, <?php echo $this->_tpl_vars['info']['first_name']; ?>
 <?php echo $this->_tpl_vars['info']['mi']; ?>
</td>
          <td><?php echo $this->_tpl_vars['info']['rank']; ?>
</td>
          <td><?php echo $this->_tpl_vars['info']['ssn']; ?>
</td>
        </tr>
        <tr>
          <td class="column_name">Unit</td>
          <td class="column_name">MOS</td>
          <td class="column_name">Component</td>
        </tr>
        <tr>
          <td><?php echo $this->_tpl_vars['info']['unit']; ?>
</td>
          <td><?php echo $this->_tpl_vars['info']['mos']; ?>
</td>
          <td><?php echo $this->_tpl_vars['info']['component']; ?>
</td>
        </tr>
        <tr>
          <td class="column_name">Arrival Date (days)</td>
          <td class="column_name">Inactive Status</td>
          <td class="column_name"><?php echo $this->_tpl_vars['meps_header']; ?>
</td>
        </tr>
        <tr>
          <td><?php echo $this->_tpl_vars['info']['arrival_date']; ?>
 (<?php echo $this->_tpl_vars['info']['days']; ?>
)</td>
          <td><?php echo $this->_tpl_vars['info']['inactive_status']; ?>
</td>
          <td><?php echo $this->_tpl_vars['meps_select']; ?>
</td>
        </tr>
        <tr>
          <td class="column_name">Clearance Status</td>
          <td class="column_name">Derog Issue</td>
          <td class="column_name">Status Date</td>
        </tr>
        <tr>
          <td><?php echo $this->_tpl_vars['clearance_status_select']; ?>
</td>
          <td><?php echo $this->_tpl_vars['derog_issue_select']; ?>
</td>
          <td><input type="text" name="status_date" size="9" maxlength="10" value="<?php echo $this->_tpl_vars['info']['status_date']; ?>
"></td>
        </tr>
        <tr>
          <td class="column_name" colspan="3">Issue Detail</td>
        </tr>
        <tr>
          <td colspan="3"><input type="text" size="50" name="issue_detail" value="<?php echo $this->_tpl_vars['info']['issue_detail']; ?>
"></td>
        </tr>
        <tr>
          <td class="column_name" colspan="3" width="100%">Remarks</td>
        </tr>
        <tr>
          <td colspan="3" width="100%">
            <textarea rows="5" cols="70" wrap="physical" name="remark"><?php echo $this->_tpl_vars['info']['remark']; ?>
</textarea>
          </td>
        </tr>
        <tr>
          <td colspan="3" align="right" width="100%">
            <input type="reset" value="Cancel" class="button">
            &nbsp;
            <input type="hidden" name="id" value="<?php echo $this->_tpl_vars['info']['id']; ?>
">
            <input type="submit" name="submit" value="Save Changes" class="button">
          </td>
        </tr>
      </table>
      </form>
    </td>
  </tr>
  <?php endfor; endif; ?>

  
  <?php if (isset($this->_sections["mass_input"])) unset($this->_sections["mass_input"]);
$this->_sections["mass_input"]['name'] = "mass_input";
$this->_sections["mass_input"]['loop'] = is_array("1") ? count("1") : max(0, (int)"1");
$this->_sections["mass_input"]['show'] = (bool)$this->_tpl_vars['show']['mass_input'];
$this->_sections["mass_input"]['max'] = $this->_sections["mass_input"]['loop'];
$this->_sections["mass_input"]['step'] = 1;
$this->_sections["mass_input"]['start'] = $this->_sections["mass_input"]['step'] > 0 ? 0 : $this->_sections["mass_input"]['loop']-1;
if ($this->_sections["mass_input"]['show']) {
    $this->_sections["mass_input"]['total'] = $this->_sections["mass_input"]['loop'];
    if ($this->_sections["mass_input"]['total'] == 0)
        $this->_sections["mass_input"]['show'] = false;
} else
    $this->_sections["mass_input"]['total'] = 0;
if ($this->_sections["mass_input"]['show']):

            for ($this->_sections["mass_input"]['index'] = $this->_sections["mass_input"]['start'], $this->_sections["mass_input"]['iteration'] = 1;
                 $this->_sections["mass_input"]['iteration'] <= $this->_sections["mass_input"]['total'];
                 $this->_sections["mass_input"]['index'] += $this->_sections["mass_input"]['step'], $this->_sections["mass_input"]['iteration']++):
$this->_sections["mass_input"]['rownum'] = $this->_sections["mass_input"]['iteration'];
$this->_sections["mass_input"]['index_prev'] = $this->_sections["mass_input"]['index'] - $this->_sections["mass_input"]['step'];
$this->_sections["mass_input"]['index_next'] = $this->_sections["mass_input"]['index'] + $this->_sections["mass_input"]['step'];
$this->_sections["mass_input"]['first']      = ($this->_sections["mass_input"]['iteration'] == 1);
$this->_sections["mass_input"]['last']       = ($this->_sections["mass_input"]['iteration'] == $this->_sections["mass_input"]['total']);
?>
  <tr>
    <td class="table_heading">Mass Input</td>
  </tr>
  <tr>
    <td>
      Copy and Paste the Name, SSN, and Clearance Status columns into this text area. Do not worry about formatting,
      just copy and paste directly from Excel.
      <form method="POST" action="<?php echo $this->_tpl_vars['url']; ?>
/s2/index.php">
        <textarea name="data" cols="70" rows="10" wrap="off"></textarea>
        <br>
        <input type="submit" value="Process Data" class="button">
      </form>
    </td>
  </tr>
  <?php endfor; endif; ?>

  
  <?php if (isset($this->_sections["mass_results"])) unset($this->_sections["mass_results"]);
$this->_sections["mass_results"]['name'] = "mass_results";
$this->_sections["mass_results"]['loop'] = is_array("1") ? count("1") : max(0, (int)"1");
$this->_sections["mass_results"]['show'] = (bool)$this->_tpl_vars['show']['mass_results'];
$this->_sections["mass_results"]['max'] = $this->_sections["mass_results"]['loop'];
$this->_sections["mass_results"]['step'] = 1;
$this->_sections["mass_results"]['start'] = $this->_sections["mass_results"]['step'] > 0 ? 0 : $this->_sections["mass_results"]['loop']-1;
if ($this->_sections["mass_results"]['show']) {
    $this->_sections["mass_results"]['total'] = $this->_sections["mass_results"]['loop'];
    if ($this->_sections["mass_results"]['total'] == 0)
        $this->_sections["mass_results"]['show'] = false;
} else
    $this->_sections["mass_results"]['total'] = 0;
if ($this->_sections["mass_results"]['show']):

            for ($this->_sections["mass_results"]['index'] = $this->_sections["mass_results"]['start'], $this->_sections["mass_results"]['iteration'] = 1;
                 $this->_sections["mass_results"]['iteration'] <= $this->_sections["mass_results"]['total'];
                 $this->_sections["mass_results"]['index'] += $this->_sections["mass_results"]['step'], $this->_sections["mass_results"]['iteration']++):
$this->_sections["mass_results"]['rownum'] = $this->_sections["mass_results"]['iteration'];
$this->_sections["mass_results"]['index_prev'] = $this->_sections["mass_results"]['index'] - $this->_sections["mass_results"]['step'];
$this->_sections["mass_results"]['index_next'] = $this->_sections["mass_results"]['index'] + $this->_sections["mass_results"]['step'];
$this->_sections["mass_results"]['first']      = ($this->_sections["mass_results"]['iteration'] == 1);
$this->_sections["mass_results"]['last']       = ($this->_sections["mass_results"]['iteration'] == $this->_sections["mass_results"]['total']);
?>
  <tr>
    <td class="table_heading">Results</td>
  </tr>
  <tr>
    <td>
      <table border="1" width="100%">
        <tr valign="top">
        <?php if (isset($this->_sections["s"])) unset($this->_sections["s"]);
$this->_sections["s"]['name'] = "s";
$this->_sections["s"]['loop'] = is_array("1") ? count("1") : max(0, (int)"1");
$this->_sections["s"]['show'] = (bool)$this->_tpl_vars['report']['successful'];
$this->_sections["s"]['max'] = $this->_sections["s"]['loop'];
$this->_sections["s"]['step'] = 1;
$this->_sections["s"]['start'] = $this->_sections["s"]['step'] > 0 ? 0 : $this->_sections["s"]['loop']-1;
if ($this->_sections["s"]['show']) {
    $this->_sections["s"]['total'] = $this->_sections["s"]['loop'];
    if ($this->_sections["s"]['total'] == 0)
        $this->_sections["s"]['show'] = false;
} else
    $this->_sections["s"]['total'] = 0;
if ($this->_sections["s"]['show']):

            for ($this->_sections["s"]['index'] = $this->_sections["s"]['start'], $this->_sections["s"]['iteration'] = 1;
                 $this->_sections["s"]['iteration'] <= $this->_sections["s"]['total'];
                 $this->_sections["s"]['index'] += $this->_sections["s"]['step'], $this->_sections["s"]['iteration']++):
$this->_sections["s"]['rownum'] = $this->_sections["s"]['iteration'];
$this->_sections["s"]['index_prev'] = $this->_sections["s"]['index'] - $this->_sections["s"]['step'];
$this->_sections["s"]['index_next'] = $this->_sections["s"]['index'] + $this->_sections["s"]['step'];
$this->_sections["s"]['first']      = ($this->_sections["s"]['iteration'] == 1);
$this->_sections["s"]['last']       = ($this->_sections["s"]['iteration'] == $this->_sections["s"]['total']);
?>
          <td><strong>Successful:</strong><br>
            <?php if (isset($this->_sections["suc"])) unset($this->_sections["suc"]);
$this->_sections["suc"]['name'] = "suc";
$this->_sections["suc"]['loop'] = is_array($this->_tpl_vars['report']['successful']) ? count($this->_tpl_vars['report']['successful']) : max(0, (int)$this->_tpl_vars['report']['successful']);
$this->_sections["suc"]['show'] = true;
$this->_sections["suc"]['max'] = $this->_sections["suc"]['loop'];
$this->_sections["suc"]['step'] = 1;
$this->_sections["suc"]['start'] = $this->_sections["suc"]['step'] > 0 ? 0 : $this->_sections["suc"]['loop']-1;
if ($this->_sections["suc"]['show']) {
    $this->_sections["suc"]['total'] = $this->_sections["suc"]['loop'];
    if ($this->_sections["suc"]['total'] == 0)
        $this->_sections["suc"]['show'] = false;
} else
    $this->_sections["suc"]['total'] = 0;
if ($this->_sections["suc"]['show']):

            for ($this->_sections["suc"]['index'] = $this->_sections["suc"]['start'], $this->_sections["suc"]['iteration'] = 1;
                 $this->_sections["suc"]['iteration'] <= $this->_sections["suc"]['total'];
                 $this->_sections["suc"]['index'] += $this->_sections["suc"]['step'], $this->_sections["suc"]['iteration']++):
$this->_sections["suc"]['rownum'] = $this->_sections["suc"]['iteration'];
$this->_sections["suc"]['index_prev'] = $this->_sections["suc"]['index'] - $this->_sections["suc"]['step'];
$this->_sections["suc"]['index_next'] = $this->_sections["suc"]['index'] + $this->_sections["suc"]['step'];
$this->_sections["suc"]['first']      = ($this->_sections["suc"]['iteration'] == 1);
$this->_sections["suc"]['last']       = ($this->_sections["suc"]['iteration'] == $this->_sections["suc"]['total']);
?>
              <?php echo $this->_tpl_vars['report']['successful'][$this->_sections['suc']['index']]; ?>
<br>
            <?php endfor; endif; ?>
          </td>
        <?php endfor; endif; ?>
        <?php if (isset($this->_sections["e"])) unset($this->_sections["e"]);
$this->_sections["e"]['name'] = "e";
$this->_sections["e"]['loop'] = is_array("1") ? count("1") : max(0, (int)"1");
$this->_sections["e"]['show'] = (bool)$this->_tpl_vars['report']['error'];
$this->_sections["e"]['max'] = $this->_sections["e"]['loop'];
$this->_sections["e"]['step'] = 1;
$this->_sections["e"]['start'] = $this->_sections["e"]['step'] > 0 ? 0 : $this->_sections["e"]['loop']-1;
if ($this->_sections["e"]['show']) {
    $this->_sections["e"]['total'] = $this->_sections["e"]['loop'];
    if ($this->_sections["e"]['total'] == 0)
        $this->_sections["e"]['show'] = false;
} else
    $this->_sections["e"]['total'] = 0;
if ($this->_sections["e"]['show']):

            for ($this->_sections["e"]['index'] = $this->_sections["e"]['start'], $this->_sections["e"]['iteration'] = 1;
                 $this->_sections["e"]['iteration'] <= $this->_sections["e"]['total'];
                 $this->_sections["e"]['index'] += $this->_sections["e"]['step'], $this->_sections["e"]['iteration']++):
$this->_sections["e"]['rownum'] = $this->_sections["e"]['iteration'];
$this->_sections["e"]['index_prev'] = $this->_sections["e"]['index'] - $this->_sections["e"]['step'];
$this->_sections["e"]['index_next'] = $this->_sections["e"]['index'] + $this->_sections["e"]['step'];
$this->_sections["e"]['first']      = ($this->_sections["e"]['iteration'] == 1);
$this->_sections["e"]['last']       = ($this->_sections["e"]['iteration'] == $this->_sections["e"]['total']);
?>
          <td><strong>Errors:</strong><br>
            <?php if (isset($this->_sections["err"])) unset($this->_sections["err"]);
$this->_sections["err"]['name'] = "err";
$this->_sections["err"]['loop'] = is_array($this->_tpl_vars['report']['error']) ? count($this->_tpl_vars['report']['error']) : max(0, (int)$this->_tpl_vars['report']['error']);
$this->_sections["err"]['show'] = true;
$this->_sections["err"]['max'] = $this->_sections["err"]['loop'];
$this->_sections["err"]['step'] = 1;
$this->_sections["err"]['start'] = $this->_sections["err"]['step'] > 0 ? 0 : $this->_sections["err"]['loop']-1;
if ($this->_sections["err"]['show']) {
    $this->_sections["err"]['total'] = $this->_sections["err"]['loop'];
    if ($this->_sections["err"]['total'] == 0)
        $this->_sections["err"]['show'] = false;
} else
    $this->_sections["err"]['total'] = 0;
if ($this->_sections["err"]['show']):

            for ($this->_sections["err"]['index'] = $this->_sections["err"]['start'], $this->_sections["err"]['iteration'] = 1;
                 $this->_sections["err"]['iteration'] <= $this->_sections["err"]['total'];
                 $this->_sections["err"]['index'] += $this->_sections["err"]['step'], $this->_sections["err"]['iteration']++):
$this->_sections["err"]['rownum'] = $this->_sections["err"]['iteration'];
$this->_sections["err"]['index_prev'] = $this->_sections["err"]['index'] - $this->_sections["err"]['step'];
$this->_sections["err"]['index_next'] = $this->_sections["err"]['index'] + $this->_sections["err"]['step'];
$this->_sections["err"]['first']      = ($this->_sections["err"]['iteration'] == 1);
$this->_sections["err"]['last']       = ($this->_sections["err"]['iteration'] == $this->_sections["err"]['total']);
?>
              <?php echo $this->_tpl_vars['report']['error'][$this->_sections['err']['index']]; ?>
<br>
            <?php endfor; endif; ?>
          </td>
        <?php endfor; endif; ?>
        <?php if (isset($this->_sections["n"])) unset($this->_sections["n"]);
$this->_sections["n"]['name'] = "n";
$this->_sections["n"]['loop'] = is_array("1") ? count("1") : max(0, (int)"1");
$this->_sections["n"]['show'] = (bool)$this->_tpl_vars['report']['not_in_usap'];
$this->_sections["n"]['max'] = $this->_sections["n"]['loop'];
$this->_sections["n"]['step'] = 1;
$this->_sections["n"]['start'] = $this->_sections["n"]['step'] > 0 ? 0 : $this->_sections["n"]['loop']-1;
if ($this->_sections["n"]['show']) {
    $this->_sections["n"]['total'] = $this->_sections["n"]['loop'];
    if ($this->_sections["n"]['total'] == 0)
        $this->_sections["n"]['show'] = false;
} else
    $this->_sections["n"]['total'] = 0;
if ($this->_sections["n"]['show']):

            for ($this->_sections["n"]['index'] = $this->_sections["n"]['start'], $this->_sections["n"]['iteration'] = 1;
                 $this->_sections["n"]['iteration'] <= $this->_sections["n"]['total'];
                 $this->_sections["n"]['index'] += $this->_sections["n"]['step'], $this->_sections["n"]['iteration']++):
$this->_sections["n"]['rownum'] = $this->_sections["n"]['iteration'];
$this->_sections["n"]['index_prev'] = $this->_sections["n"]['index'] - $this->_sections["n"]['step'];
$this->_sections["n"]['index_next'] = $this->_sections["n"]['index'] + $this->_sections["n"]['step'];
$this->_sections["n"]['first']      = ($this->_sections["n"]['iteration'] == 1);
$this->_sections["n"]['last']       = ($this->_sections["n"]['iteration'] == $this->_sections["n"]['total']);
?>
          <td><strong>Not In USAP:</strong><br>
            <?php if (isset($this->_sections["niu"])) unset($this->_sections["niu"]);
$this->_sections["niu"]['name'] = "niu";
$this->_sections["niu"]['loop'] = is_array($this->_tpl_vars['report']['not_in_usap']) ? count($this->_tpl_vars['report']['not_in_usap']) : max(0, (int)$this->_tpl_vars['report']['not_in_usap']);
$this->_sections["niu"]['show'] = true;
$this->_sections["niu"]['max'] = $this->_sections["niu"]['loop'];
$this->_sections["niu"]['step'] = 1;
$this->_sections["niu"]['start'] = $this->_sections["niu"]['step'] > 0 ? 0 : $this->_sections["niu"]['loop']-1;
if ($this->_sections["niu"]['show']) {
    $this->_sections["niu"]['total'] = $this->_sections["niu"]['loop'];
    if ($this->_sections["niu"]['total'] == 0)
        $this->_sections["niu"]['show'] = false;
} else
    $this->_sections["niu"]['total'] = 0;
if ($this->_sections["niu"]['show']):

            for ($this->_sections["niu"]['index'] = $this->_sections["niu"]['start'], $this->_sections["niu"]['iteration'] = 1;
                 $this->_sections["niu"]['iteration'] <= $this->_sections["niu"]['total'];
                 $this->_sections["niu"]['index'] += $this->_sections["niu"]['step'], $this->_sections["niu"]['iteration']++):
$this->_sections["niu"]['rownum'] = $this->_sections["niu"]['iteration'];
$this->_sections["niu"]['index_prev'] = $this->_sections["niu"]['index'] - $this->_sections["niu"]['step'];
$this->_sections["niu"]['index_next'] = $this->_sections["niu"]['index'] + $this->_sections["niu"]['step'];
$this->_sections["niu"]['first']      = ($this->_sections["niu"]['iteration'] == 1);
$this->_sections["niu"]['last']       = ($this->_sections["niu"]['iteration'] == $this->_sections["niu"]['total']);
?>
              <?php echo $this->_tpl_vars['report']['not_in_usap'][$this->_sections['niu']['index']]; ?>
<br>
            <?php endfor; endif; ?>
          </td>
        <?php endfor; endif; ?>
        <?php if (isset($this->_sections["b"])) unset($this->_sections["b"]);
$this->_sections["b"]['name'] = "b";
$this->_sections["b"]['loop'] = is_array("1") ? count("1") : max(0, (int)"1");
$this->_sections["b"]['show'] = (bool)$this->_tpl_vars['report']['bad_status'];
$this->_sections["b"]['max'] = $this->_sections["b"]['loop'];
$this->_sections["b"]['step'] = 1;
$this->_sections["b"]['start'] = $this->_sections["b"]['step'] > 0 ? 0 : $this->_sections["b"]['loop']-1;
if ($this->_sections["b"]['show']) {
    $this->_sections["b"]['total'] = $this->_sections["b"]['loop'];
    if ($this->_sections["b"]['total'] == 0)
        $this->_sections["b"]['show'] = false;
} else
    $this->_sections["b"]['total'] = 0;
if ($this->_sections["b"]['show']):

            for ($this->_sections["b"]['index'] = $this->_sections["b"]['start'], $this->_sections["b"]['iteration'] = 1;
                 $this->_sections["b"]['iteration'] <= $this->_sections["b"]['total'];
                 $this->_sections["b"]['index'] += $this->_sections["b"]['step'], $this->_sections["b"]['iteration']++):
$this->_sections["b"]['rownum'] = $this->_sections["b"]['iteration'];
$this->_sections["b"]['index_prev'] = $this->_sections["b"]['index'] - $this->_sections["b"]['step'];
$this->_sections["b"]['index_next'] = $this->_sections["b"]['index'] + $this->_sections["b"]['step'];
$this->_sections["b"]['first']      = ($this->_sections["b"]['iteration'] == 1);
$this->_sections["b"]['last']       = ($this->_sections["b"]['iteration'] == $this->_sections["b"]['total']);
?>
          <td><strong>Bad Status:</strong><br>
            <?php if (isset($this->_sections["bs"])) unset($this->_sections["bs"]);
$this->_sections["bs"]['name'] = "bs";
$this->_sections["bs"]['loop'] = is_array($this->_tpl_vars['report']['bad_status']) ? count($this->_tpl_vars['report']['bad_status']) : max(0, (int)$this->_tpl_vars['report']['bad_status']);
$this->_sections["bs"]['show'] = true;
$this->_sections["bs"]['max'] = $this->_sections["bs"]['loop'];
$this->_sections["bs"]['step'] = 1;
$this->_sections["bs"]['start'] = $this->_sections["bs"]['step'] > 0 ? 0 : $this->_sections["bs"]['loop']-1;
if ($this->_sections["bs"]['show']) {
    $this->_sections["bs"]['total'] = $this->_sections["bs"]['loop'];
    if ($this->_sections["bs"]['total'] == 0)
        $this->_sections["bs"]['show'] = false;
} else
    $this->_sections["bs"]['total'] = 0;
if ($this->_sections["bs"]['show']):

            for ($this->_sections["bs"]['index'] = $this->_sections["bs"]['start'], $this->_sections["bs"]['iteration'] = 1;
                 $this->_sections["bs"]['iteration'] <= $this->_sections["bs"]['total'];
                 $this->_sections["bs"]['index'] += $this->_sections["bs"]['step'], $this->_sections["bs"]['iteration']++):
$this->_sections["bs"]['rownum'] = $this->_sections["bs"]['iteration'];
$this->_sections["bs"]['index_prev'] = $this->_sections["bs"]['index'] - $this->_sections["bs"]['step'];
$this->_sections["bs"]['index_next'] = $this->_sections["bs"]['index'] + $this->_sections["bs"]['step'];
$this->_sections["bs"]['first']      = ($this->_sections["bs"]['iteration'] == 1);
$this->_sections["bs"]['last']       = ($this->_sections["bs"]['iteration'] == $this->_sections["bs"]['total']);
?>
              <?php echo $this->_tpl_vars['report']['bad_status'][$this->_sections['bs']['index']]; ?>
<br>
            <?php endfor; endif; ?>
          </td>
        <?php endfor; endif; ?>
      </tr>
    </table>
  <?php endfor; endif; ?>

  
  <?php if (isset($this->_sections["issue_detail_report"])) unset($this->_sections["issue_detail_report"]);
$this->_sections["issue_detail_report"]['name'] = "issue_detail_report";
$this->_sections["issue_detail_report"]['loop'] = is_array("1") ? count("1") : max(0, (int)"1");
$this->_sections["issue_detail_report"]['show'] = (bool)$this->_tpl_vars['show']['issue_detail_report'];
$this->_sections["issue_detail_report"]['max'] = $this->_sections["issue_detail_report"]['loop'];
$this->_sections["issue_detail_report"]['step'] = 1;
$this->_sections["issue_detail_report"]['start'] = $this->_sections["issue_detail_report"]['step'] > 0 ? 0 : $this->_sections["issue_detail_report"]['loop']-1;
if ($this->_sections["issue_detail_report"]['show']) {
    $this->_sections["issue_detail_report"]['total'] = $this->_sections["issue_detail_report"]['loop'];
    if ($this->_sections["issue_detail_report"]['total'] == 0)
        $this->_sections["issue_detail_report"]['show'] = false;
} else
    $this->_sections["issue_detail_report"]['total'] = 0;
if ($this->_sections["issue_detail_report"]['show']):

            for ($this->_sections["issue_detail_report"]['index'] = $this->_sections["issue_detail_report"]['start'], $this->_sections["issue_detail_report"]['iteration'] = 1;
                 $this->_sections["issue_detail_report"]['iteration'] <= $this->_sections["issue_detail_report"]['total'];
                 $this->_sections["issue_detail_report"]['index'] += $this->_sections["issue_detail_report"]['step'], $this->_sections["issue_detail_report"]['iteration']++):
$this->_sections["issue_detail_report"]['rownum'] = $this->_sections["issue_detail_report"]['iteration'];
$this->_sections["issue_detail_report"]['index_prev'] = $this->_sections["issue_detail_report"]['index'] - $this->_sections["issue_detail_report"]['step'];
$this->_sections["issue_detail_report"]['index_next'] = $this->_sections["issue_detail_report"]['index'] + $this->_sections["issue_detail_report"]['step'];
$this->_sections["issue_detail_report"]['first']      = ($this->_sections["issue_detail_report"]['iteration'] == 1);
$this->_sections["issue_detail_report"]['last']       = ($this->_sections["issue_detail_report"]['iteration'] == $this->_sections["issue_detail_report"]['total']);
?>
  <tr>
    <td><?php echo $this->_tpl_vars['export_links']; ?>
</td>
  </tr>
  <tr>
    <td><?php echo $this->_tpl_vars['issue_detail_report']; ?>
</td>
  </tr>
  <?php endfor; endif; ?>
</table>