<?
include("lib-common.php");
echo com_siteheader("USAP - Phasing Operation");
echo com_sitefooter();

?>
<form action="phaseOperation.php" method="post">

<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=unicode">
<meta content="MSHTML 6.00.3790.0" name="GENERATOR"></head>
<body><font face="Arial" color="#000000" size="2"><font face="Arial" color="#000000" size="2">
<DIV>
<table height="339" cellspacing="2" cellpadding="2" width="432" bgcolor="#ffffff" border="1">
  
  <tr valign="top" height="43">
    <td width="420">
      <p align="left"><br><b>Solider Last Name, First Name&nbsp; RANK</b></p></td></tr>
  <tr valign="top">
    <td borderColor=#ff8000 width="420">
      <P align=left><br>New Phase:<select size="1" name="newPhase" tabindex="1">
		<option value="IV" selected>IV</option>
		<option value="V">V</option>
		<option value="V+">V+</option>
		<option value="V-DIAMOND">V-DIAMOND</option>
		</select><BR>&nbsp;</P>
		<P align=left>Effective Date:<input type="text" name="effectiveDate" size="18" tabindex="2"></P>
		<P align=left><BR>Justification for Phase UP/DOWN:<br>
		<textarea rows="10" name="remark" cols="50" tabindex="3"></textarea><BR><BR>
		Generate DA4856? <input type="checkbox" name="generateCounsel" value="true" tabindex="4"></P></td></tr>
  <tr valign="top">
    <td borderColor=#ff8000 width="420">
      <p align="center"><input type="submit" value="Save" name="operation"></td></tr></table></DIV></font></font>

</format>
</body></html>
<?
ech "test";
?>