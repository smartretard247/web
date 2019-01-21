<?
//configuration
require("lib-common.php");

//ensure password meets rule requirements
function check_password($pass)
{
    global $_CONF;

    //only check password if option is enabled.
    if($_CONF["check_password"] == "on")
    {
        //must have uppercase letter
        if(!ereg("[a-z]",$pass)) { return 0; }
        //must have lowercase letter
        if(!ereg("[a-z]",$pass)) { return 0; }
        //must have number
        if(!ereg("[0-9]",$pass)) { return 0; }
        //must be 8 characters long
        if(strlen($pass) < 8) { return 0; }
    }
    return 1;
}

//if form has been submitted
if(isset($_POST["change_password"]))
{
    //ensure new passwords match each other
    if($_POST["new_password1"] == $_POST["new_password2"])
    {
        //check that password matches rules
        if(check_password($_POST["new_password1"]))
        {
            $query = "select 1 from users where user_id = " . $_SESSION['user_id'] . " and password = password('" . $_POST['old_password'] . "')";
            $result = mysql_query($query) or die("check old password query error: " . mysql_error());

            if(mysql_num_rows($result) == 1)
            {
                $query = "update users set change_password = 1, password = password('" . $_POST["new_password1"] . "') where user_id = " . $_SESSION["user_id"];
                $result = mysql_query($query) or die("password update failed [$query]: " . mysql_error());

                if(mysql_affected_rows() == 1)
                {
                    //change successfull, send to main
                    header("location: " . $_CONF["html"] . "/main.php");
                }
                else
                {
                    //change failed
                    $msg = "new password is not valid. please try a new password.";
                }
            }
            else
            { $msg = "old password does not match."; }
        }
        else
        { $msg = "new password does not meet the rules. please try a new password."; }
    }
    else
    { $msg = "new passwords do not match."; }
}
?>
<html>
<head>
<title>change password</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="css.php">
<script language="javascript">
<!--
function mm_findobj(n, d) { //v3.0
  var p,i,x;  if(!d) d=document; if((p=n.indexof("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=mm_findobj(n,d.layers[i].document); return x;
}

function mm_validateform() { //v3.0
  var i,p,q,nm,test,num,min,max,errors='',args=mm_validateform.arguments;
  for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=mm_findobj(args[i]);
    if (val) { nm=val.name; if ((val=val.value)!="") {
      if (test.indexof('isemail')!=-1) { p=val.indexof('@');
        if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
      } else if (test!='r') { num = parsefloat(val);
        if (val!=''+num) errors+='- '+nm+' must contain a number.\n';
        if (test.indexof('inrange') != -1) { p=test.indexof(':');
          min=test.substring(8,p); max=test.substring(p+1);
          if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
    } } } else if (test.charat(0) == 'r') errors += '- '+nm+' is required.\n'; }
  } if (errors) alert('the following error(s) occurred:\n'+errors);
  document.mm_returnvalue = (errors == '');
}
//-->
</script>
</head>

<body onload="document.change_password_form.old_password.focus();">
<form action='' method='post' name='change_password_form' onsubmit="mm_validateform('old_password','','r','new_password1','','r','new_password2','','r');return document.mm_returnvalue">
  <table width="80%" border="1" cellspacing="2" cellpadding="2" align="center">
  <tr>
    <td class="table_cheading">Change Password</td>
  </tr>
  <tr>
    <td>
      <p>You must change your password to continue. </p>
      <p>Rules: Passwords must be at least 8 characters in length, contain at
        least one uppercase letter, one lowercase letter, and at least one number.</p>
    </td>
  </tr>
  <tr>
    <td>
<?
//if message is set, display it.
if(isset($msg)) { echo "<span class='error'>$msg</span>"; } ?>
        <table width="80%" border="0" cellspacing="2" cellpadding="2" align="center">
          <tr>
            <td width="50%">
              <div align="right">Old Password: </div>
          </td>
            <td width="50%">
              <input type="password" name="old_password" size="10" class="text_box">
            </td>
        </tr>
        <tr>
            <td width="50%">
              <div align="right">New Password: </div>
          </td>
            <td width="50%">
              <input type="password" name="new_password1" size="10" class="text_box">
            </td>
        </tr>
        <tr>
            <td width="50%">
              <div align="right">Re-type New Password: </div>
          </td>
            <td width="50%">
              <input type="password" name="new_password2" size="10" class="text_box">
            </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <div align="center">
          <input type="submit" name="change_password" value="Change Password" class="button">
        </div>
    </td>
  </tr>
</table>
</form>
</body>
</html>
