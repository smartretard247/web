<table border="1" cellspacing="1" cellpadding="1" align="center" width="90%">
<?
if(check_permission(14))
{
    ?>
    <tr class="heading">
      <td>View CUA Report</td>
    </tr>
    <form method="get" action="<?=$_CONF['html']?>/reports/cua_report.php">
    <tr>
      <td>
        Unit: <?=unit_select(14)?>
        &nbsp;
        <input type="submit" class="button" name="submit" value="Go">
      </td
    </tr>
    <?
}
?>
<tr><? include("cua2.inc.php"); ?></tr>
</table>