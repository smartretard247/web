<?php /* Smarty version 2.3.0, created on 2003-01-07 23:33:00
         compiled from fpass.tpl */ ?>
<html>
<head>
<title>USAP Password Reset</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="css.php">
</head>

<body bgcolor="#ffffff">
  <table width="70%" border="1" cellspacing="2" cellpadding="2" align="center">
  <tr>
    <td>
      <p align="center"><font size="7"><b><i>USAP</i></b></font></p>
      <p align="center"><font size="5"><strong>Unit Soldier Administration Program</strong></p>
      <table width="90%" border="1" cellspacing="2" cellpadding="2" align="center">
        <tr>
          <td class="table_cheading">
            Reset USAP Password
          </td>
        </tr>
        <tr>
          <td>
            <?php if (isset($this->_sections["msg"])) unset($this->_sections["msg"]);
$this->_sections["msg"]['name'] = "msg";
$this->_sections["msg"]['loop'] = is_array("1") ? count("1") : max(0, (int)"1");
$this->_sections["msg"]['show'] = (bool)$this->_tpl_vars['msg'];
$this->_sections["msg"]['max'] = $this->_sections["msg"]['loop'];
$this->_sections["msg"]['step'] = 1;
$this->_sections["msg"]['start'] = $this->_sections["msg"]['step'] > 0 ? 0 : $this->_sections["msg"]['loop']-1;
if ($this->_sections["msg"]['show']) {
    $this->_sections["msg"]['total'] = $this->_sections["msg"]['loop'];
    if ($this->_sections["msg"]['total'] == 0)
        $this->_sections["msg"]['show'] = false;
} else
    $this->_sections["msg"]['total'] = 0;
if ($this->_sections["msg"]['show']):

            for ($this->_sections["msg"]['index'] = $this->_sections["msg"]['start'], $this->_sections["msg"]['iteration'] = 1;
                 $this->_sections["msg"]['iteration'] <= $this->_sections["msg"]['total'];
                 $this->_sections["msg"]['index'] += $this->_sections["msg"]['step'], $this->_sections["msg"]['iteration']++):
$this->_sections["msg"]['rownum'] = $this->_sections["msg"]['iteration'];
$this->_sections["msg"]['index_prev'] = $this->_sections["msg"]['index'] - $this->_sections["msg"]['step'];
$this->_sections["msg"]['index_next'] = $this->_sections["msg"]['index'] + $this->_sections["msg"]['step'];
$this->_sections["msg"]['first']      = ($this->_sections["msg"]['iteration'] == 1);
$this->_sections["msg"]['last']       = ($this->_sections["msg"]['iteration'] == $this->_sections["msg"]['total']);
?>
              <div class="notice" align="center"><?php echo $this->_tpl_vars['msg']; ?>
</div>
              <br>
            <?php endfor; endif; ?>

            <?php if (isset($this->_sections["username_form"])) unset($this->_sections["username_form"]);
$this->_sections["username_form"]['name'] = "username_form";
$this->_sections["username_form"]['loop'] = is_array("1") ? count("1") : max(0, (int)"1");
$this->_sections["username_form"]['show'] = (bool)$this->_tpl_vars['show']['username_form'];
$this->_sections["username_form"]['max'] = $this->_sections["username_form"]['loop'];
$this->_sections["username_form"]['step'] = 1;
$this->_sections["username_form"]['start'] = $this->_sections["username_form"]['step'] > 0 ? 0 : $this->_sections["username_form"]['loop']-1;
if ($this->_sections["username_form"]['show']) {
    $this->_sections["username_form"]['total'] = $this->_sections["username_form"]['loop'];
    if ($this->_sections["username_form"]['total'] == 0)
        $this->_sections["username_form"]['show'] = false;
} else
    $this->_sections["username_form"]['total'] = 0;
if ($this->_sections["username_form"]['show']):

            for ($this->_sections["username_form"]['index'] = $this->_sections["username_form"]['start'], $this->_sections["username_form"]['iteration'] = 1;
                 $this->_sections["username_form"]['iteration'] <= $this->_sections["username_form"]['total'];
                 $this->_sections["username_form"]['index'] += $this->_sections["username_form"]['step'], $this->_sections["username_form"]['iteration']++):
$this->_sections["username_form"]['rownum'] = $this->_sections["username_form"]['iteration'];
$this->_sections["username_form"]['index_prev'] = $this->_sections["username_form"]['index'] - $this->_sections["username_form"]['step'];
$this->_sections["username_form"]['index_next'] = $this->_sections["username_form"]['index'] + $this->_sections["username_form"]['step'];
$this->_sections["username_form"]['first']      = ($this->_sections["username_form"]['iteration'] == 1);
$this->_sections["username_form"]['last']       = ($this->_sections["username_form"]['iteration'] == $this->_sections["username_form"]['total']);
?>
              <blockquote>To reset your password, enter your username in the box below. You will be sent an email to
              your <a href="https://us.army.mil" target="_blank">AKO Email</a> address associated with your username. The email will contain a link that
              will allow you to reset your password. If you do not click on the link and enter a new password, your password
              will not be changed.</blockquote>
              <form method="POST" action="fpass.php">
                <div align="center">
                  Username/Login: <input type="text" name="username">&nbsp;<input type="submit" value="Send Email" name="submit">
                </div>
              </form>
              <br>
            <?php endfor; endif; ?>

            <?php if (isset($this->_sections["change_pw_form"])) unset($this->_sections["change_pw_form"]);
$this->_sections["change_pw_form"]['name'] = "change_pw_form";
$this->_sections["change_pw_form"]['loop'] = is_array("1") ? count("1") : max(0, (int)"1");
$this->_sections["change_pw_form"]['show'] = (bool)$this->_tpl_vars['show']['change_pw_form'];
$this->_sections["change_pw_form"]['max'] = $this->_sections["change_pw_form"]['loop'];
$this->_sections["change_pw_form"]['step'] = 1;
$this->_sections["change_pw_form"]['start'] = $this->_sections["change_pw_form"]['step'] > 0 ? 0 : $this->_sections["change_pw_form"]['loop']-1;
if ($this->_sections["change_pw_form"]['show']) {
    $this->_sections["change_pw_form"]['total'] = $this->_sections["change_pw_form"]['loop'];
    if ($this->_sections["change_pw_form"]['total'] == 0)
        $this->_sections["change_pw_form"]['show'] = false;
} else
    $this->_sections["change_pw_form"]['total'] = 0;
if ($this->_sections["change_pw_form"]['show']):

            for ($this->_sections["change_pw_form"]['index'] = $this->_sections["change_pw_form"]['start'], $this->_sections["change_pw_form"]['iteration'] = 1;
                 $this->_sections["change_pw_form"]['iteration'] <= $this->_sections["change_pw_form"]['total'];
                 $this->_sections["change_pw_form"]['index'] += $this->_sections["change_pw_form"]['step'], $this->_sections["change_pw_form"]['iteration']++):
$this->_sections["change_pw_form"]['rownum'] = $this->_sections["change_pw_form"]['iteration'];
$this->_sections["change_pw_form"]['index_prev'] = $this->_sections["change_pw_form"]['index'] - $this->_sections["change_pw_form"]['step'];
$this->_sections["change_pw_form"]['index_next'] = $this->_sections["change_pw_form"]['index'] + $this->_sections["change_pw_form"]['step'];
$this->_sections["change_pw_form"]['first']      = ($this->_sections["change_pw_form"]['iteration'] == 1);
$this->_sections["change_pw_form"]['last']       = ($this->_sections["change_pw_form"]['iteration'] == $this->_sections["change_pw_form"]['total']);
?>
              <blockquote>Fill in the following form to change your password. Passwords must be at least 8 characters long,
              and have at least one uppercase letter, lowercase letter, and number in them.
              <form method="POST" action="fpass.php">
                <input type="hidden" name="hcode" value="<?php echo $this->_tpl_vars['hcode']; ?>
">
                <table border="0" align="center" cellspacing="2">
                  <tr>
                    <td align="right">New Password:</td>
                    <td><input type="password" name="password1"></td>
                  </tr>
                  <tr>
                    <td align="right">Retype New Password:</td>
                    <td><input type="password" name="password2"></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td><input type="submit" name="submit" value="Reset Password"></td>
                  </tr>
                </table>
              </form>
            <?php endfor; endif; ?>
          </td>
        </tr>
        <tr class="table_cheading">
          <td>
            <a href="<?php echo $this->_tpl_vars['url']; ?>
"><?php echo $this->_tpl_vars['text']; ?>
</a>
          </td>
        </tr>
      </table>
      <br>
    </td>
  </tr>
</table>
</body>
</html>