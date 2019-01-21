<?php /* Smarty version 2.3.0, created on 2003-06-23 17:14:02
         compiled from edit_group.tpl */ ?>
<p>&nbsp;</p>
<table width="95%" border="1" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td class="heading">Group Edit</td>
  </tr>
  <?php if (isset($this->_sections["error"])) unset($this->_sections["error"]);
$this->_sections["error"]['name'] = "error";
$this->_sections["error"]['show'] = (bool)$this->_tpl_vars['display']['error'];
$this->_sections["error"]['loop'] = 1;
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
    <td class="error"><?php echo $this->_tpl_vars['display']['error']; ?>
</td>
  </tr>
  <?php endfor; endif; ?>
  <?php if (isset($this->_sections["notice"])) unset($this->_sections["notice"]);
$this->_sections["notice"]['name'] = "notice";
$this->_sections["notice"]['show'] = (bool)$this->_tpl_vars['display']['notice'];
$this->_sections["notice"]['loop'] = 1;
$this->_sections["notice"]['max'] = $this->_sections["notice"]['loop'];
$this->_sections["notice"]['step'] = 1;
$this->_sections["notice"]['start'] = $this->_sections["notice"]['step'] > 0 ? 0 : $this->_sections["notice"]['loop']-1;
if ($this->_sections["notice"]['show']) {
    $this->_sections["notice"]['total'] = $this->_sections["notice"]['loop'];
    if ($this->_sections["notice"]['total'] == 0)
        $this->_sections["notice"]['show'] = false;
} else
    $this->_sections["notice"]['total'] = 0;
if ($this->_sections["notice"]['show']):

            for ($this->_sections["notice"]['index'] = $this->_sections["notice"]['start'], $this->_sections["notice"]['iteration'] = 1;
                 $this->_sections["notice"]['iteration'] <= $this->_sections["notice"]['total'];
                 $this->_sections["notice"]['index'] += $this->_sections["notice"]['step'], $this->_sections["notice"]['iteration']++):
$this->_sections["notice"]['rownum'] = $this->_sections["notice"]['iteration'];
$this->_sections["notice"]['index_prev'] = $this->_sections["notice"]['index'] - $this->_sections["notice"]['step'];
$this->_sections["notice"]['index_next'] = $this->_sections["notice"]['index'] + $this->_sections["notice"]['step'];
$this->_sections["notice"]['first']      = ($this->_sections["notice"]['iteration'] == 1);
$this->_sections["notice"]['last']       = ($this->_sections["notice"]['iteration'] == $this->_sections["notice"]['total']);
?>
  <tr>
    <td class="notice"><?php echo $this->_tpl_vars['display']['notice']; ?>
</td>
  </tr>
  <?php endfor; endif; ?>
  <tr>
    <td align="center">
      <br />
      <form method="GET" action="<?php echo $this->_tpl_vars['conf']['html']; ?>
/edit_group.php">
      <table border="0" cellspacing="0" cellpadding="2" align="center">
        <col class="column_name" />
        <col class="data" />
        <tr>
          <td>Unit: </td>
          <td><?php echo $this->_tpl_vars['display']['unit_select']; ?>
</td>
        </tr>
        <tr>
          <td>Field: </td>
          <td><?php echo $this->_tpl_vars['display']['field_select']; ?>
</td>
        </tr>
        <tr>
          <td>Personnel Type: </td>
          <td>
            <?php if (isset($this->_sections["pt"])) unset($this->_sections["pt"]);
$this->_sections["pt"]['name'] = "pt";
$this->_sections["pt"]['loop'] = is_array($this->_tpl_vars['conf']['pers_type']) ? count($this->_tpl_vars['conf']['pers_type']) : max(0, (int)$this->_tpl_vars['conf']['pers_type']);
$this->_sections["pt"]['show'] = true;
$this->_sections["pt"]['max'] = $this->_sections["pt"]['loop'];
$this->_sections["pt"]['step'] = 1;
$this->_sections["pt"]['start'] = $this->_sections["pt"]['step'] > 0 ? 0 : $this->_sections["pt"]['loop']-1;
if ($this->_sections["pt"]['show']) {
    $this->_sections["pt"]['total'] = $this->_sections["pt"]['loop'];
    if ($this->_sections["pt"]['total'] == 0)
        $this->_sections["pt"]['show'] = false;
} else
    $this->_sections["pt"]['total'] = 0;
if ($this->_sections["pt"]['show']):

            for ($this->_sections["pt"]['index'] = $this->_sections["pt"]['start'], $this->_sections["pt"]['iteration'] = 1;
                 $this->_sections["pt"]['iteration'] <= $this->_sections["pt"]['total'];
                 $this->_sections["pt"]['index'] += $this->_sections["pt"]['step'], $this->_sections["pt"]['iteration']++):
$this->_sections["pt"]['rownum'] = $this->_sections["pt"]['iteration'];
$this->_sections["pt"]['index_prev'] = $this->_sections["pt"]['index'] - $this->_sections["pt"]['step'];
$this->_sections["pt"]['index_next'] = $this->_sections["pt"]['index'] + $this->_sections["pt"]['step'];
$this->_sections["pt"]['first']      = ($this->_sections["pt"]['iteration'] == 1);
$this->_sections["pt"]['last']       = ($this->_sections["pt"]['iteration'] == $this->_sections["pt"]['total']);
?>
              <input type="checkbox" name="pers_type[]" value="<?php echo $this->_tpl_vars['conf']['pers_type'][$this->_sections['pt']['index']]; ?>
"><?php echo $this->_tpl_vars['conf']['pers_type'][$this->_sections['pt']['index']]; ?>
&nbsp;&nbsp;
            <?php endfor; endif; ?>
          </td>
        </tr>
        <tr>
          <td>Platoon: </td>
          <td>
            <?php if (isset($this->_sections["plt"])) unset($this->_sections["plt"]);
$this->_sections["plt"]['name'] = "plt";
$this->_sections["plt"]['loop'] = is_array($this->_tpl_vars['conf']['platoon']) ? count($this->_tpl_vars['conf']['platoon']) : max(0, (int)$this->_tpl_vars['conf']['platoon']);
$this->_sections["plt"]['show'] = true;
$this->_sections["plt"]['max'] = $this->_sections["plt"]['loop'];
$this->_sections["plt"]['step'] = 1;
$this->_sections["plt"]['start'] = $this->_sections["plt"]['step'] > 0 ? 0 : $this->_sections["plt"]['loop']-1;
if ($this->_sections["plt"]['show']) {
    $this->_sections["plt"]['total'] = $this->_sections["plt"]['loop'];
    if ($this->_sections["plt"]['total'] == 0)
        $this->_sections["plt"]['show'] = false;
} else
    $this->_sections["plt"]['total'] = 0;
if ($this->_sections["plt"]['show']):

            for ($this->_sections["plt"]['index'] = $this->_sections["plt"]['start'], $this->_sections["plt"]['iteration'] = 1;
                 $this->_sections["plt"]['iteration'] <= $this->_sections["plt"]['total'];
                 $this->_sections["plt"]['index'] += $this->_sections["plt"]['step'], $this->_sections["plt"]['iteration']++):
$this->_sections["plt"]['rownum'] = $this->_sections["plt"]['iteration'];
$this->_sections["plt"]['index_prev'] = $this->_sections["plt"]['index'] - $this->_sections["plt"]['step'];
$this->_sections["plt"]['index_next'] = $this->_sections["plt"]['index'] + $this->_sections["plt"]['step'];
$this->_sections["plt"]['first']      = ($this->_sections["plt"]['iteration'] == 1);
$this->_sections["plt"]['last']       = ($this->_sections["plt"]['iteration'] == $this->_sections["plt"]['total']);
?>
              <input type="checkbox" name="platoon[]" value="<?php echo $this->_tpl_vars['conf']['platoon'][$this->_sections['plt']['index']]; ?>
"><?php echo $this->_tpl_vars['conf']['platoon'][$this->_sections['plt']['index']]; ?>
&nbsp;&nbsp;
            <?php endfor; endif; ?>
          </td>
        </tr>
        <tr>
          <td>Shift: </td>
          <td>
            <?php if (isset($this->_sections["sh"])) unset($this->_sections["sh"]);
$this->_sections["sh"]['name'] = "sh";
$this->_sections["sh"]['loop'] = is_array($this->_tpl_vars['conf']['shift']) ? count($this->_tpl_vars['conf']['shift']) : max(0, (int)$this->_tpl_vars['conf']['shift']);
$this->_sections["sh"]['show'] = true;
$this->_sections["sh"]['max'] = $this->_sections["sh"]['loop'];
$this->_sections["sh"]['step'] = 1;
$this->_sections["sh"]['start'] = $this->_sections["sh"]['step'] > 0 ? 0 : $this->_sections["sh"]['loop']-1;
if ($this->_sections["sh"]['show']) {
    $this->_sections["sh"]['total'] = $this->_sections["sh"]['loop'];
    if ($this->_sections["sh"]['total'] == 0)
        $this->_sections["sh"]['show'] = false;
} else
    $this->_sections["sh"]['total'] = 0;
if ($this->_sections["sh"]['show']):

            for ($this->_sections["sh"]['index'] = $this->_sections["sh"]['start'], $this->_sections["sh"]['iteration'] = 1;
                 $this->_sections["sh"]['iteration'] <= $this->_sections["sh"]['total'];
                 $this->_sections["sh"]['index'] += $this->_sections["sh"]['step'], $this->_sections["sh"]['iteration']++):
$this->_sections["sh"]['rownum'] = $this->_sections["sh"]['iteration'];
$this->_sections["sh"]['index_prev'] = $this->_sections["sh"]['index'] - $this->_sections["sh"]['step'];
$this->_sections["sh"]['index_next'] = $this->_sections["sh"]['index'] + $this->_sections["sh"]['step'];
$this->_sections["sh"]['first']      = ($this->_sections["sh"]['iteration'] == 1);
$this->_sections["sh"]['last']       = ($this->_sections["sh"]['iteration'] == $this->_sections["sh"]['total']);
?>
              <input type="checkbox" name="shift[]" value="<?php echo $this->_tpl_vars['conf']['shift'][$this->_sections['sh']['index']]; ?>
"><?php echo $this->_tpl_vars['conf']['shift'][$this->_sections['sh']['index']]; ?>
&nbsp;&nbsp;
            <?php endfor; endif; ?>
          </td>
        </tr>
        <tr>
          <td>Status: </td>
          <td>
            <input type="checkbox" name="status[]" value="active">Active&nbsp;&nbsp;
            <input type="checkbox" name="status[]" value="inactive">Inactive&nbsp;&nbsp;
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>
            <input type="submit" name="initial_submit" value="Go" class="button">
          </td>
        </tr>
      </table>
      </form>
    </td>
  </tr>
  <?php if (isset($this->_sections["fields"])) unset($this->_sections["fields"]);
$this->_sections["fields"]['name'] = "fields";
$this->_sections["fields"]['show'] = (bool)$this->_tpl_vars['display']['fields'];
$this->_sections["fields"]['loop'] = 1;
$this->_sections["fields"]['max'] = $this->_sections["fields"]['loop'];
$this->_sections["fields"]['step'] = 1;
$this->_sections["fields"]['start'] = $this->_sections["fields"]['step'] > 0 ? 0 : $this->_sections["fields"]['loop']-1;
if ($this->_sections["fields"]['show']) {
    $this->_sections["fields"]['total'] = $this->_sections["fields"]['loop'];
    if ($this->_sections["fields"]['total'] == 0)
        $this->_sections["fields"]['show'] = false;
} else
    $this->_sections["fields"]['total'] = 0;
if ($this->_sections["fields"]['show']):

            for ($this->_sections["fields"]['index'] = $this->_sections["fields"]['start'], $this->_sections["fields"]['iteration'] = 1;
                 $this->_sections["fields"]['iteration'] <= $this->_sections["fields"]['total'];
                 $this->_sections["fields"]['index'] += $this->_sections["fields"]['step'], $this->_sections["fields"]['iteration']++):
$this->_sections["fields"]['rownum'] = $this->_sections["fields"]['iteration'];
$this->_sections["fields"]['index_prev'] = $this->_sections["fields"]['index'] - $this->_sections["fields"]['step'];
$this->_sections["fields"]['index_next'] = $this->_sections["fields"]['index'] + $this->_sections["fields"]['step'];
$this->_sections["fields"]['first']      = ($this->_sections["fields"]['iteration'] == 1);
$this->_sections["fields"]['last']       = ($this->_sections["fields"]['iteration'] == $this->_sections["fields"]['total']);
?>
  <tr>
    <td>
      <form method="POST" action="<?php echo $this->_tpl_vars['conf']['html']; ?>
/edit_group.php">
        <p><input type="submit" name="edit_submit" value="Submit All Changes" class="button"></p>
        <input type="hidden" name="unit" value="<?php echo $this->_tpl_vars['display']['unit']; ?>
">
        <input type="hidden" name="edit_group_field" value="<?php echo $this->_tpl_vars['display']['edit_group_field']; ?>
">
          <?php echo $this->_tpl_vars['fields_table']; ?>

        <p><input type="submit" name="edit_submit" value="Submit All Changes" class="button"></p>
      </form>
    </td>
  </tr>
  <?php endfor; endif; ?> 
</table>