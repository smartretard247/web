<?
include("f:/usap/config.php");
//include database connection
require("f:/usap/lib-database.php");



//cac registration wizard

if($_SERVER['CERT_SERIALNUMBER'] == "") {
?>
alert("You must insert your CAC to continue");
javascript:window.close();

<?
exit();
}

if ($_POST['cacReg'] == "Register") {
$query = "select u.change_password, u.user_id, up.permission_id from user_permissions up, users u where u.user_id = up.user_id and u.login = '" . $_POST["login"] . "' and u.password = password('" . $_POST["password"] . "') group by up.permission_id";
//echo($query);
$result = mysql_query($query);

//if error in query, send to error page
if(mysql_error())
{
    echo "error: " . mysql_error();
    exit();
}

//determine how many rows were returned
$num_rows = mysql_num_rows($result);

//if no rows were returned, send to error page
if($num_rows < 1)
{
 
    echo "<STRONG><CENTER>ERROR</CENTER></STRONG></p></p></p>";
    echo "<B> You provided invalid username and / or password. You are required to have current USAP credentials to register.</B></p>";
    echo "<center> Please try using the CAC Portal Again...</p>";
    echo "<p><a href='javascript: self.close()'>Close Window</a>";
    //header("location: cacRegPortal.php");
    exit();
}

$row = mysql_fetch_array($result);

$regQuery = "update users set cacSerialNumber='" . $_SERVER['CERT_SERIALNUMBER'] . "' where user_id=" . $row['user_id'];
$updateResult = mysql_query($regQuery);
if(mysql_error())
{
    echo "error: " . mysql_error();
    exit();
}
?>
<HTML>
<HEAD>
<title>Success in your CAC Registration!</title>
</head>
<Body>
<center>
<img border="0" src="signalflags.gif" width="98" height="77"></p>
	<strong>Please print this screen for your records! </strong></p>
	</p>
	Registered CAC:</p>
		<i>
		<? echo $_SERVER['CERT_SERIALNUMBER'] . "--" . $_SERVER['CERT_SUBJECT']; ?>
		</i></p>
	For User:
		<i>
		<? echo $row['user_id']; ?>
		</i></p>
<p>Click here to <a href='javascript: self.close()'>Return to USAP Login Page...</a>
		
</center>
</body>
</html>
	


<?
exit();
}
?>

<html>
<head>
<title>USAP CAC Registration & Management Portal V1</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="css/main.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="780" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td>
		<table width="780" border="0" cellspacing="0" cellpadding="0">
        	<tr>
        	  
          <td bgcolor="#000099"><h2>&nbsp;&nbsp;&nbsp;<FONT face=Arial 
            color=#ffffff size=7>USAP CAC Registration Portal</FONT></h2></td>
        	</tr>
			<tr>
        	  <td bgcolor="#6666ff">
            <P align=right>&nbsp;(<EM>C) 2007 SPC MATTHEWS; 15 SIG 
          BDE</EM></P></td>
        	</tr>
      	</table>
		<table width="780" border="0" cellspacing="0" cellpadding="0">
        	<tr>
        	  <td width="100" valign="top">
			  <br>
				<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center">
			  		<tr>
						<td>	
							<table width="111" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#efefef" class="bevel" style="WIDTH: 111px; HEIGHT: 99px" 
                 >
 							 <tr>
 							   <td bgcolor="#6699ff"><div align="center" class="title">main 
                        menu</div></td>
 							 </tr>
 							 <tr>
 							   <td>&nbsp;</td>
 							 </tr>
 							 <tr>
 							   <td>
                        <UL>
                          <LI>Help</LI>
							<LI>Admin</LI>
                          <LI 
style="FONT-FAMILY: serif">&nbsp;<a href='javascript: self.close()'>Exit</a></LI></UL></td>
 							 </tr>
 							 <tr>
 							   <td>&nbsp;</td>
							 </tr>
							</table>
						</td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td>
						</td>
				  </tr>
				  <tr><td>&nbsp;</td></tr>
				 </table>
			  </td>
			  <td width="15"></td>
			  
          <td width="665" align="middle"> 
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
               <tr> 
                <td>&nbsp;</td>
              </tr>
			  <tr> 
                <td align="middle"><table width="95%" border="0" cellpadding="0" cellspacing="0" bgcolor="#efefef" class="bevel">
                    <tr> 
                      <td bgcolor="#6699ff" class="title">&nbsp;&nbsp;&nbsp;Information about 
                        currently presented card...</td>
                    </tr>
                    <tr> 
                      <td>
                        <P align="center"><FONT size=1></FONT>&nbsp;<img border="0" src="cac.jpg" width="304" height="137"></P>
                        <P style="FONT-FAMILY: monospace" 
                        align=right>&nbsp;Identity:&nbsp; 
						<TEXTAREA id=TEXTAREA1 style="LEFT: 0px; WIDTH: 480; TOP: 1px; HEIGHT: 60" name=certSubject rows=3 readOnly cols=52>
						<?
						echo $_SERVER["CERT_SUBJECT"];
						?>
						</TEXTAREA></P>
                        <P style="FONT-FAMILY: monospace" align=right>DoD Unique 
                        Identifier: 
						<INPUT id=text1 
                        style="WIDTH: 371px; HEIGHT: 22px" readOnly size=45 
                        name=certSerialNumber value=<? echo $_SERVER['CERT_SERIALNUMBER']; ?>></P></td>
                    </tr>
                    <tr> 
                      <td>&nbsp;</td>
                    </tr>
                    <tr> 
                      <td>&nbsp;</td>
                    </tr>
                  </table></td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td><table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#efefef" class="bevel">
                    <tr> 
                      <td bgcolor="#6699ff" class="title">&nbsp;&nbsp;&nbsp;please provide 
                        your usap login...</td>
                    </tr>
			<form name="cacRegfrm" action="cacRegPortal.php" method="post">
                    <tr> 
                      <td>
                        <P align=center>
						<img border="0" src="signalflags.gif" width="98" height="77"></P>
                        <P style="FONT-FAMILY: sans-serif" 
                        align=center>Username: 
						<INPUT id=text2 
                        style="LEFT: -1px; WIDTH: 176px; TOP: 1px; HEIGHT: 22px" 
                        size=22 name=login></P>
                        <P style="FONT-FAMILY: sans-serif" 
                        align=center>&nbsp;Password: 
						<INPUT id=password1 
                        style="LEFT: 3px; WIDTH: 175px; TOP: 1px; HEIGHT: 22px" 
                        type=password size=23 name=password></P>
                        <P align=center>
						<INPUT id=button1 type=submit value=Register name=cacReg></P></td>
                    </tr>
			</form>
                    <tr> 
                      <td>&nbsp;</td>
                    </tr>
                    <tr> 
                      <td>&nbsp;</td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
			  <tr> 
                <td><table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#efefef" class="bevel">
                    <TBODY style="FONT-FAMILY: sans-serif">
                    <tr> 
                      <td bgcolor="#6699ff" class="title">&nbsp;&nbsp;&nbsp;security 
                      warning!</td>
                    </tr>
                    <tr> 
                      <td>&nbsp;</td>
                    </tr>
                    <tr> 
                      <td>&nbsp;NOTICE: This is a 
                        Department of Defense Computer System. This computer 
                        system, including all related equipment, networks, and 
                        network devices (specifically including Internet access) 
                        are provided only for authorized U.S. Government use. 
                        DoD computer systems may be monitored for all lawful 
                        purposes, including to ensure that their use is 
                        authorized, for management of the system, to facilitate 
                        protection against unauthorized access, and to verify 
                        security procedures, survivability, and operational 
                        security. Monitoring includes active attacks by 
                        authorized DoD entities to test or verify the security 
                        of this system. During monitoring, information may be 
                        examined, recorded, copied and used for authorized 
                        purposes. All information, including personal 
                        information, placed or sent over this system may be 
                        monitored. Use of this DoD computer system, authorized 
                        or unauthorized, constitutes consent to monitoring of 
                        this system. Unauthorized use may subject you to 
                        criminal prosecution. Evidence of unauthorized use 
                        collected during monitoring may be used for 
                        administrative, criminal, or other adverse action. Use 
                        of this system constitutes consent to monitoring for 
                        these purposes.</td>
                    </tr>
                    <tr> 
                      <td>&nbsp;</td>
                    </tr></TBODY>
                  </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
            </table> </td>
        </tr>
      	</table>
	  	<table width="780" border="0" cellspacing="0" cellpadding="0">
       		<tr><!-- If you would like to use this template for free you must leave the following Hyperlink active email webmaster@webzonetemplates.com and pay $10.00 to remove it..-->		
				<td bgcolor="#000099"><div align="right">V 1.0.4, Build: 
        32</div></td>
        	</tr>
      	</table>
	  </td>
  </tr>
</table>
</body>
</html>
