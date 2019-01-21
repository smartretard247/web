<?php /* Smarty version 2.3.0, created on 2004-08-10 15:05:39
         compiled from Default/admin_login.tpl */ ?>
<table width="70%" align="center" cellpadding="0" cellspacing="0">
  <tr class="grayboxheader">
    <td width="14"><img src="<?php echo $this->_tpl_vars['conf']['images_html']; ?>
/box_left.gif" border="0" width="14"></td>
    <td background="<?php echo $this->_tpl_vars['conf']['images_html']; ?>
/box_bg.gif">Administration Login</td>
    <td width="14"><img src="<?php echo $this->_tpl_vars['conf']['images_html']; ?>
/box_right.gif" border="0" width="14"></td>
  </tr>
</table>

<table width="70%" align="center" class="bordered_table">
  <?php if (isset($this->_sections["message"])) unset($this->_sections["message"]);
$this->_sections["message"]['name'] = "message";
$this->_sections["message"]['show'] = (bool)$this->_tpl_vars['message'];
$this->_sections["message"]['loop'] = 1;
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
    <tr><td class="error"><?php echo $this->_tpl_vars['message']; ?>
</td></tr>
  <?php endfor; endif; ?>
  <tr>
    <td align="center">
      <form method="POST" action="<?php echo $this->_tpl_vars['conf']['html']; ?>
/admin.php" name="login_form">
        Please enter the administrator password:
        <br>
        <input type="password" name="admin_password" size="15">
        <br>
        <input type="submit" value="Enter">
      </form>
    </td>
  </tr>
</table>
<script language="JavaScript">
document.login_form.admin_password.focus();
</script>