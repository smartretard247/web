<?

$errormsg[0] = "Error #0: A USAP required component has malfunctioned. Corrective action will automatically be executed!";
$errormsg[1] = "error #1: this program could not select a database after connecting to mysql";
$errormsg[2] = "error #2: this program could not process a log on query.";

if ($_GET["error"] == 0) {
	$msg = "Error: MySql service stopped.\n\n --Automated message, do not respond.";
mail("tommy.m@gordon.army.mil,tommy.matthewsjr@us.army.mil","[USAP][**ERROR**] mySql Stopped",$msg,"From: USAP Server Health Alerts");
}

?>
<html>
<head>
<title>[USAP] Error Handler</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="css.php">
</head>

<body>
<table width="75%" border="1" cellspacing="2" cellpadding="2" align="center">
  <tr>
    <td class="table_cheading">
      <font size="7">ERROR</font>
    </td>
  </tr>
  <tr>
    <td class="notice"><? echo $errormsg[$_GET["error"]]; ?></td>
  </tr>
  <tr>
    <td>
      <div align="center">An e-mail has been sent to SPC Matthews to report the error.</div>
    </td>
  </tr>
</table>
</body>
</html>
