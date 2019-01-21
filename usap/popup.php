<?
if(isset($_POST['cancel']))
{ $script = "<script>window.close('mywindow');</script>\n"; }

if(isset($_POST['submit']))
{
  if(eregi("[a-z0-9_.-]+@[a-z0-9_.-]+",$_POST['email']))
  {
    $result = mysql_query("INSERT INTO email_list VALUES ('{$_POST['email']}')");
  }
}
    

?>
<html>
<head>
<title>Sign up for the USAP Newsletter</title>
<? if(isset($script)) { echo $script; } ?>
</head>
<body>
<form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
<table border="0" cellpadding="2" width="100%" align="center">
  <tr>
    <th class="table_heading">Sign up for the USAP Mail List</th>
  </tr>
  <tr>
    <td><font size="2">Enter your email address to sign up for the USAP Mail List or click cancel.
    The USAP Mail List will be used to send notices of new features added to USAP, like reports
    and new fields. It will also be used to give a notice of bugs, downtime, and the current
    "focus" of the developer. Enter your complete email address in the box or click cancel to disregard.
    You will only receive this notice once.</font>
    <br>
    <div align="center">
    <input type="text" name="email" size="30">
    <br>
    <input type="submit" name="cancel" value="Cancel">&nbsp;
    <input type="submit" name="submit" value="Sign-Up">
    </div>
    </td>
  </tr>
</table>
</form>
</body>
</html>