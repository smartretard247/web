<?

//configuration values
include("config.php");


?>
<html>
<head>
<title>USAP 2.1.5 - Please Login!</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="<?=$_CONF['html']?>/css.php">
<script type="text/javascript" src="include/encrypt.js"></script>
<script type="text/javascript" src="include/send.js"></script>
</head>

<body class="body" onload="document.login.login.focus();">
<form method="post" action="<? echo $_CONF['web'];?>/login_process.php" name="login">
<input type='hidden' name='javascript_test' value='no'>
  <table width="70%" border="1" cellspacing="2" cellpadding="2" align="center">
  <tr>
    <td>
      <p align="center"><font size="7"><b><i><img src="usaplogo.gif"></i></b></font></p>
      <p align="center"><font size="5"><strong>Unit Soldier Administration Program</strong></p>

<table width="90%" border = "0" cellspacing = "2" cellpadding = "2" align="center">
<tr><td>
<center></center>
<center>[<a href="<?=$_CONF['html']?>/cacProcess.php">CAC LOGIN</a>]
 |
[<a href="<?=$_CONF['html']?>/cac/portal/cacRegPortal.php" target=_blank>REGISTER MY CAC</a>]</center></p></p>
</tr></td>
</table>
      <table width="90%" border="1" cellspacing="2" cellpadding="2" align="center">
        <tr>
          <td>
<?
//if error message is set, display it
if(isset($_GET["error"])) { echo "<p class='error'>" . $errormsg[$_GET["error"]] . "</p>";  } ?>


              <table width="40%" border="0" cellspacing="2" cellpadding="2" align="center">

              <tr>
                <td width="50%" align="right">Login:</td>
                <td width="50%">
                    <input type="text" name="login" size="11" class="text_box" value="">
                </td>
              </tr>
              <tr>
                <td width="50%" align="right">Password:</td>
                <td width="50%">
                    <input type="password" name="password" size="11" class="text_box" value="">
                </td>
              </tr>

            </table>

            </td>

        </tr>

        <tr class="heading">
          <td>
            <div align="center">
                <input type="submit" name="login2" value="Login" class="button" onClick="send();">
            </div>
          </td>
        </tr>
        <tr>
          <td class="example" align="center">
            NOTICE: This is a Department of Defense Computer System. This computer system, including all related equipment, networks,
            and network devices (specifically including Internet access) are provided only for authorized U.S. Government use. DoD
            computer systems may be monitored for all lawful purposes, including to ensure that their use is authorized, for
            management of the system, to facilitate protection against unauthorized access, and to verify security procedures,
            survivability, and operational security. Monitoring includes active attacks by authorized DoD entities to test or verify
            the security of this system. During monitoring, information may be examined, recorded, copied and used for authorized
            purposes. All information, including personal information, placed or sent over this system may be monitored. Use of this
            DoD computer system, authorized or unauthorized, constitutes consent to monitoring of this system. Unauthorized use may
            subject you to criminal prosecution. Evidence of unauthorized use collected during monitoring may be used for
            administrative, criminal, or other adverse action. Use of this system constitutes consent to monitoring for these purposes.
          </td>
        </tr>
      </table>
      <table border="0" width="40%" align="center">
        <tr>
          <td align="center">
            <a href="<?=$_CONF['html']?>/help.php">Need Help?</a>
          </td>
          <td align="center">
            <a href="<?=$_CONF['html']?>/fpass.php">Forgot / Reset Password?</a>
          </td>
        </tr>
      </table>
      <p align="center"><a href='<?=$_CONF['html']?>/priv.php'>Privacy and Security</a></p>
</td>
  </tr>
</table>
</form>
<center><i>USAP POC: Dee Piper, 15RSB Automation (<a href="mailto:paul.d.piper@conus.army.mil">E-Mail</a>) </p></i>
</body>
</html>
