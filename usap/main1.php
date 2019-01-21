<?
//configuration

include("lib-common.php");

$header = com_siteheader("usap - main");
echo $header;

$notifications = array('remarks','next_apft','due_apft','due_dental','appointments','cua');

?>
<p>&nbsp;</p>
<table width='80%' border='1' cellspacing='0' cellpadding='1' align='center'>
<?

if(isset($notifications) && count($notifications)>0)
{
    echo "<tr><td class='heading'>Notifications:</td></tr>";
    foreach($notifications as $n)
    {
        if($t = com_notification($n))
        { echo "<tr><td>$t</td></tr>\n"; }
    }
    echo "</table>";
}

?>
<p>&nbsp;</p>
<table width="80%" border="1" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td class="heading">USAP Status Messages:</td>
  </tr>
  <tr>
    <td>
      <p>August 4th, 2004</p>
      <blockquote>
        <strong>Change to All Reports:</strong>I've added a feature that allows you to sort any report by any
        of the columns shown. Reports are normally sorted by the last name, but this will allow you to sort
        by rank or unit, according to your needs. Just click on the &darr; to sort ascending (a to z, 0 to 9)
        and the &uarr; to sort descending (z to a, 9 to 0) in the header of each report. If you need to sort by
        multiple columns, export the report to Excel and do the sorting there.
      </blockquote>
      <blockquote>
        <strong>Bug Fix:</strong>Two bugs were recently reported and fixed. Thanks to SSG LePage for reporting a bug
        with the Airborne contract-type information not being saved correctly. This bug is fixed and the information
        is no longer reset back to Contract all the time. Thanks also to SFC D'Antonio for reporting a bug with assigning
        permissions to new users. This bug has also been fixed and permissions can be assigned as normal.
      </blockquote>
      <blockquote>
        <strong>Status Update:</strong>Added 'FTX' as a Daily Status option for students and permanent party. 
      </blockquote>
      <p>May 25th, 2004</p>
      <blockquote>
        <strong>USAP Update:</strong>
        I will be conducting a complete rewrite and update of USAP from June - August, adding more features and
        cleaning up certain areas. If you have any suggestions, please forward them to me at
        <a href="mailto:holmesj@gordon.army.mil">holmesj@gordon.army.mil</a>.
      </blockquote>
    </td>
  </tr>
</table>

<p>&nbsp;</p>
<table width="80%" border="1" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td class="heading">Current News from <a target="_blank" href="http://ebird.afis.osd.mil/">Early Bird</a></td>
  </tr>
  <tr>
    <td>
    <div>(<em>Click on a link below to show the news items.</em>)</div>
    <br />
    <?php early_bird(); ?>
    </td>
  </tr>
</table>
<?

$footer = com_sitefooter();
echo $footer;

?>
