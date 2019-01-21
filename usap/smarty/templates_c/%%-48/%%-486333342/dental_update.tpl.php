<?php /* Smarty version 2.3.0, created on 2002-11-18 14:53:00
         compiled from dental_update.tpl */ ?>
<table border="1" cellpadding="2" cellspacing="0" align="center" width="90%">
  <tr>
    <td class="table_heading">Dental Update</td>
  </tr>
  <tr>
    <td>
      <h3>Click <a href="dental_update_directions.html">here</a> for directions.</h3>
      <br>
      <span class="example">Cut and paste the Dental information from the web page into the following text area. Do not worry about formatting</span>
      <form method="POST" action="<?php echo $this->_tpl_vars['url']['action']; ?>
">
        Unit: <?php echo $this->_tpl_vars['unit_select']; ?>
<br>
        <textarea name="data" rows="10" cols="50"></textarea>
        <br>
        <input type="submit" name="submit" value="Enter Data">
      </form>
    </td>
  </tr>
  <?php if (isset($this->_sections["r"])) unset($this->_sections["r"]);
$this->_sections["r"]['name'] = "r";
$this->_sections["r"]['loop'] = is_array("1") ? count("1") : max(0, (int)"1");
$this->_sections["r"]['show'] = (bool)$this->_tpl_vars['result'];
$this->_sections["r"]['max'] = $this->_sections["r"]['loop'];
$this->_sections["r"]['step'] = 1;
$this->_sections["r"]['start'] = $this->_sections["r"]['step'] > 0 ? 0 : $this->_sections["r"]['loop']-1;
if ($this->_sections["r"]['show']) {
    $this->_sections["r"]['total'] = $this->_sections["r"]['loop'];
    if ($this->_sections["r"]['total'] == 0)
        $this->_sections["r"]['show'] = false;
} else
    $this->_sections["r"]['total'] = 0;
if ($this->_sections["r"]['show']):

            for ($this->_sections["r"]['index'] = $this->_sections["r"]['start'], $this->_sections["r"]['iteration'] = 1;
                 $this->_sections["r"]['iteration'] <= $this->_sections["r"]['total'];
                 $this->_sections["r"]['index'] += $this->_sections["r"]['step'], $this->_sections["r"]['iteration']++):
$this->_sections["r"]['rownum'] = $this->_sections["r"]['iteration'];
$this->_sections["r"]['index_prev'] = $this->_sections["r"]['index'] - $this->_sections["r"]['step'];
$this->_sections["r"]['index_next'] = $this->_sections["r"]['index'] + $this->_sections["r"]['step'];
$this->_sections["r"]['first']      = ($this->_sections["r"]['iteration'] == 1);
$this->_sections["r"]['last']       = ($this->_sections["r"]['iteration'] == $this->_sections["r"]['total']);
?>
  <tr>
    <td class="table_heading">Results</td>
  </tr>
  <tr>
    <td>
      <table border="1" cellpadding="2" cellspacing="0" width="100%">
        <tr>
          <th>Successful Updates</th>
          <?php if (isset($this->_sections["bad_names1"])) unset($this->_sections["bad_names1"]);
$this->_sections["bad_names1"]['name'] = "bad_names1";
$this->_sections["bad_names1"]['loop'] = is_array("1") ? count("1") : max(0, (int)"1");
$this->_sections["bad_names1"]['show'] = (bool)$this->_tpl_vars['result']['bad'];
$this->_sections["bad_names1"]['max'] = $this->_sections["bad_names1"]['loop'];
$this->_sections["bad_names1"]['step'] = 1;
$this->_sections["bad_names1"]['start'] = $this->_sections["bad_names1"]['step'] > 0 ? 0 : $this->_sections["bad_names1"]['loop']-1;
if ($this->_sections["bad_names1"]['show']) {
    $this->_sections["bad_names1"]['total'] = $this->_sections["bad_names1"]['loop'];
    if ($this->_sections["bad_names1"]['total'] == 0)
        $this->_sections["bad_names1"]['show'] = false;
} else
    $this->_sections["bad_names1"]['total'] = 0;
if ($this->_sections["bad_names1"]['show']):

            for ($this->_sections["bad_names1"]['index'] = $this->_sections["bad_names1"]['start'], $this->_sections["bad_names1"]['iteration'] = 1;
                 $this->_sections["bad_names1"]['iteration'] <= $this->_sections["bad_names1"]['total'];
                 $this->_sections["bad_names1"]['index'] += $this->_sections["bad_names1"]['step'], $this->_sections["bad_names1"]['iteration']++):
$this->_sections["bad_names1"]['rownum'] = $this->_sections["bad_names1"]['iteration'];
$this->_sections["bad_names1"]['index_prev'] = $this->_sections["bad_names1"]['index'] - $this->_sections["bad_names1"]['step'];
$this->_sections["bad_names1"]['index_next'] = $this->_sections["bad_names1"]['index'] + $this->_sections["bad_names1"]['step'];
$this->_sections["bad_names1"]['first']      = ($this->_sections["bad_names1"]['iteration'] == 1);
$this->_sections["bad_names1"]['last']       = ($this->_sections["bad_names1"]['iteration'] == $this->_sections["bad_names1"]['total']);
?>
            <th>Failed Updates</th>
          <?php endfor; endif; ?>
          <th>On Report, Not in USAP</th>
          <th>In USAP, Not on Report</th>
        </tr>
        <tr>
          <td valign="top">
            <?php if (isset($this->_sections["g"])) unset($this->_sections["g"]);
$this->_sections["g"]['name'] = "g";
$this->_sections["g"]['loop'] = is_array($this->_tpl_vars['result']['good']) ? count($this->_tpl_vars['result']['good']) : max(0, (int)$this->_tpl_vars['result']['good']);
$this->_sections["g"]['show'] = true;
$this->_sections["g"]['max'] = $this->_sections["g"]['loop'];
$this->_sections["g"]['step'] = 1;
$this->_sections["g"]['start'] = $this->_sections["g"]['step'] > 0 ? 0 : $this->_sections["g"]['loop']-1;
if ($this->_sections["g"]['show']) {
    $this->_sections["g"]['total'] = $this->_sections["g"]['loop'];
    if ($this->_sections["g"]['total'] == 0)
        $this->_sections["g"]['show'] = false;
} else
    $this->_sections["g"]['total'] = 0;
if ($this->_sections["g"]['show']):

            for ($this->_sections["g"]['index'] = $this->_sections["g"]['start'], $this->_sections["g"]['iteration'] = 1;
                 $this->_sections["g"]['iteration'] <= $this->_sections["g"]['total'];
                 $this->_sections["g"]['index'] += $this->_sections["g"]['step'], $this->_sections["g"]['iteration']++):
$this->_sections["g"]['rownum'] = $this->_sections["g"]['iteration'];
$this->_sections["g"]['index_prev'] = $this->_sections["g"]['index'] - $this->_sections["g"]['step'];
$this->_sections["g"]['index_next'] = $this->_sections["g"]['index'] + $this->_sections["g"]['step'];
$this->_sections["g"]['first']      = ($this->_sections["g"]['iteration'] == 1);
$this->_sections["g"]['last']       = ($this->_sections["g"]['iteration'] == $this->_sections["g"]['total']);
?>
              <?php echo $this->_tpl_vars['result']['good'][$this->_sections['g']['index']]; ?>
<br>
            <?php endfor; endif; ?>
            &nbsp;
          </td>
          <?php if (isset($this->_sections["bad_names2"])) unset($this->_sections["bad_names2"]);
$this->_sections["bad_names2"]['name'] = "bad_names2";
$this->_sections["bad_names2"]['loop'] = is_array("1") ? count("1") : max(0, (int)"1");
$this->_sections["bad_names2"]['show'] = (bool)$this->_tpl_vars['result']['bad'];
$this->_sections["bad_names2"]['max'] = $this->_sections["bad_names2"]['loop'];
$this->_sections["bad_names2"]['step'] = 1;
$this->_sections["bad_names2"]['start'] = $this->_sections["bad_names2"]['step'] > 0 ? 0 : $this->_sections["bad_names2"]['loop']-1;
if ($this->_sections["bad_names2"]['show']) {
    $this->_sections["bad_names2"]['total'] = $this->_sections["bad_names2"]['loop'];
    if ($this->_sections["bad_names2"]['total'] == 0)
        $this->_sections["bad_names2"]['show'] = false;
} else
    $this->_sections["bad_names2"]['total'] = 0;
if ($this->_sections["bad_names2"]['show']):

            for ($this->_sections["bad_names2"]['index'] = $this->_sections["bad_names2"]['start'], $this->_sections["bad_names2"]['iteration'] = 1;
                 $this->_sections["bad_names2"]['iteration'] <= $this->_sections["bad_names2"]['total'];
                 $this->_sections["bad_names2"]['index'] += $this->_sections["bad_names2"]['step'], $this->_sections["bad_names2"]['iteration']++):
$this->_sections["bad_names2"]['rownum'] = $this->_sections["bad_names2"]['iteration'];
$this->_sections["bad_names2"]['index_prev'] = $this->_sections["bad_names2"]['index'] - $this->_sections["bad_names2"]['step'];
$this->_sections["bad_names2"]['index_next'] = $this->_sections["bad_names2"]['index'] + $this->_sections["bad_names2"]['step'];
$this->_sections["bad_names2"]['first']      = ($this->_sections["bad_names2"]['iteration'] == 1);
$this->_sections["bad_names2"]['last']       = ($this->_sections["bad_names2"]['iteration'] == $this->_sections["bad_names2"]['total']);
?>
          <td valign="top">
            <?php if (isset($this->_sections["b"])) unset($this->_sections["b"]);
$this->_sections["b"]['name'] = "b";
$this->_sections["b"]['loop'] = is_array($this->_tpl_vars['result']['bad']) ? count($this->_tpl_vars['result']['bad']) : max(0, (int)$this->_tpl_vars['result']['bad']);
$this->_sections["b"]['show'] = true;
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
              <?php echo $this->_tpl_vars['result']['bad'][$this->_sections['b']['index']]; ?>
<br>
            <?php endfor; endif; ?>
            &nbsp;
          </td>
          <?php endfor; endif; ?>
          <td valign="top">
            <?php if (isset($this->_sections["nfu"])) unset($this->_sections["nfu"]);
$this->_sections["nfu"]['name'] = "nfu";
$this->_sections["nfu"]['loop'] = is_array($this->_tpl_vars['result']['nfu']) ? count($this->_tpl_vars['result']['nfu']) : max(0, (int)$this->_tpl_vars['result']['nfu']);
$this->_sections["nfu"]['show'] = true;
$this->_sections["nfu"]['max'] = $this->_sections["nfu"]['loop'];
$this->_sections["nfu"]['step'] = 1;
$this->_sections["nfu"]['start'] = $this->_sections["nfu"]['step'] > 0 ? 0 : $this->_sections["nfu"]['loop']-1;
if ($this->_sections["nfu"]['show']) {
    $this->_sections["nfu"]['total'] = $this->_sections["nfu"]['loop'];
    if ($this->_sections["nfu"]['total'] == 0)
        $this->_sections["nfu"]['show'] = false;
} else
    $this->_sections["nfu"]['total'] = 0;
if ($this->_sections["nfu"]['show']):

            for ($this->_sections["nfu"]['index'] = $this->_sections["nfu"]['start'], $this->_sections["nfu"]['iteration'] = 1;
                 $this->_sections["nfu"]['iteration'] <= $this->_sections["nfu"]['total'];
                 $this->_sections["nfu"]['index'] += $this->_sections["nfu"]['step'], $this->_sections["nfu"]['iteration']++):
$this->_sections["nfu"]['rownum'] = $this->_sections["nfu"]['iteration'];
$this->_sections["nfu"]['index_prev'] = $this->_sections["nfu"]['index'] - $this->_sections["nfu"]['step'];
$this->_sections["nfu"]['index_next'] = $this->_sections["nfu"]['index'] + $this->_sections["nfu"]['step'];
$this->_sections["nfu"]['first']      = ($this->_sections["nfu"]['iteration'] == 1);
$this->_sections["nfu"]['last']       = ($this->_sections["nfu"]['iteration'] == $this->_sections["nfu"]['total']);
?>
              <?php echo $this->_tpl_vars['result']['nfu'][$this->_sections['nfu']['index']]; ?>
<br>
            <?php endfor; endif; ?>
            &nbsp;
          </td>
          <td valign="top">
            <?php if (isset($this->_sections["nfr"])) unset($this->_sections["nfr"]);
$this->_sections["nfr"]['name'] = "nfr";
$this->_sections["nfr"]['loop'] = is_array($this->_tpl_vars['result']['nfr']) ? count($this->_tpl_vars['result']['nfr']) : max(0, (int)$this->_tpl_vars['result']['nfr']);
$this->_sections["nfr"]['show'] = true;
$this->_sections["nfr"]['max'] = $this->_sections["nfr"]['loop'];
$this->_sections["nfr"]['step'] = 1;
$this->_sections["nfr"]['start'] = $this->_sections["nfr"]['step'] > 0 ? 0 : $this->_sections["nfr"]['loop']-1;
if ($this->_sections["nfr"]['show']) {
    $this->_sections["nfr"]['total'] = $this->_sections["nfr"]['loop'];
    if ($this->_sections["nfr"]['total'] == 0)
        $this->_sections["nfr"]['show'] = false;
} else
    $this->_sections["nfr"]['total'] = 0;
if ($this->_sections["nfr"]['show']):

            for ($this->_sections["nfr"]['index'] = $this->_sections["nfr"]['start'], $this->_sections["nfr"]['iteration'] = 1;
                 $this->_sections["nfr"]['iteration'] <= $this->_sections["nfr"]['total'];
                 $this->_sections["nfr"]['index'] += $this->_sections["nfr"]['step'], $this->_sections["nfr"]['iteration']++):
$this->_sections["nfr"]['rownum'] = $this->_sections["nfr"]['iteration'];
$this->_sections["nfr"]['index_prev'] = $this->_sections["nfr"]['index'] - $this->_sections["nfr"]['step'];
$this->_sections["nfr"]['index_next'] = $this->_sections["nfr"]['index'] + $this->_sections["nfr"]['step'];
$this->_sections["nfr"]['first']      = ($this->_sections["nfr"]['iteration'] == 1);
$this->_sections["nfr"]['last']       = ($this->_sections["nfr"]['iteration'] == $this->_sections["nfr"]['total']);
?>
              <?php echo $this->_tpl_vars['result']['nfr'][$this->_sections['nfr']['index']]; ?>
<br>
            <?php endfor; endif; ?>
            &nbsp;
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <?php endfor; endif; ?>
</table>