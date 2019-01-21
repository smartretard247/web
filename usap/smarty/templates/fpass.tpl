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
            {section name=msg loop=1 show=$msg}
              <div class="notice" align="center">{$msg}</div>
              <br>
            {/section}

            {section name="username_form" loop=1 show=$show.username_form}
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
            {/section}

            {section name="change_pw_form" loop=1 show=$show.change_pw_form}
              <blockquote>Fill in the following form to change your password. Passwords must be at least 8 characters long,
              and have at least one uppercase letter, lowercase letter, and number in them.
              <form method="POST" action="fpass.php">
                <input type="hidden" name="hcode" value="{$hcode}">
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
            {/section}
          </td>
        </tr>
        <tr class="table_cheading">
          <td>
            <a href="{$url}">{$text}</a>
          </td>
        </tr>
      </table>
      <br>
    </td>
  </tr>
</table>
</body>
</html>
