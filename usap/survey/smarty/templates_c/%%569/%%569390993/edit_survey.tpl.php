<?php /* Smarty version 2.3.0, created on 2004-08-02 12:07:25
         compiled from Default/edit_survey.tpl */ ?>
<table width="70%" align="center" cellpadding="0" cellspacing="0">
  <tr class="grayboxheader">
    <td width="14"><img src="<?php echo $this->_tpl_vars['conf']['images_html']; ?>
/box_left.gif" border="0" width="14"></td>
    <td background="<?php echo $this->_tpl_vars['conf']['images_html']; ?>
/box_bg.gif">Edit Survey</td>
    <td width="14"><img src="<?php echo $this->_tpl_vars['conf']['images_html']; ?>
/box_right.gif" border="0" width="14"></td>
  </tr>
</table>
<table width="70%" align="center" class="bordered_table">

<?php echo $this->_tpl_vars['show']['links']; ?>



  <?php if (isset($this->_sections["error"])) unset($this->_sections["error"]);
$this->_sections["error"]['name'] = "error";
$this->_sections["error"]['loop'] = is_array("1") ? count("1") : max(0, (int)"1");
$this->_sections["error"]['show'] = (bool)$this->_tpl_vars['show']['error'];
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
    <td class="error">Error: <?php echo $this->_tpl_vars['show']['error']; ?>
</td>
  </tr>
  <?php endfor; endif; ?>



  <?php if (isset($this->_sections["notice"])) unset($this->_sections["notice"]);
$this->_sections["notice"]['name'] = "notice";
$this->_sections["notice"]['loop'] = is_array("1") ? count("1") : max(0, (int)"1");
$this->_sections["notice"]['show'] = (bool)$this->_tpl_vars['show']['notice'];
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
    <td class="message"><?php echo $this->_tpl_vars['show']['notice']; ?>
</td>
  </tr>
  <?php endfor; endif; ?>


  <tr>
    <td>
      <?php echo $this->_tpl_vars['show']['content']; ?>

    </td>
  </tr>

  <tr>
    <td align="center">
      <br />
      [ <a href="<?php echo $this->_tpl_vars['conf']['html']; ?>
/index.php">Return to Main</a>
      &nbsp;|&nbsp;
      <a href="<?php echo $this->_tpl_vars['conf']['html']; ?>
/admin.php">Admin</a> ]
    </td>
  </tr>
</table>