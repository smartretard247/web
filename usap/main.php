<?
session_start(); 

//configuration

include("lib-common.php");

$header = com_siteheader("USAP - [Main Menu]");
echo $header;

if(!isset($_GET['notificationsOff'])){
$notifications = array('remarks','next_apft','due_apft','due_dental','appointments','cua');
}

if(isset($_GET['notificationsOff'])){
?>
<table width='80%' border='1' cellspacing='0' cellpadding='1' align='center'>
<tr><td class='heading'>Notifications:</td></tr>
<tr><td><i>Notifications have been turned off!</i> Click <a href='main.php'>here to Display Notifications</a></td></tr>
<?

}

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
    <td class="heading">USAP System-Wide Messages:</td>
  </tr>
  <tr>
    <td>
      <p>07 APR 2007</p>
	<font color="#ff0000"><i>I am looking into the issues with USAP crashing every night. Until permanent resolution; I will implement a failover server to increase application availability. --CPL M
      <blockquote></i></font>
        <strong>Bug Fixed:</strong> I have repaired a strange error recieved when
	running the appointments report from the notifications screen. Please let me know
	if you incur additional errors. --M
      </blockquote>
      <blockquote>
      <strong><font color="#00ff0">**ALERT**:</strong></font> FTR Management (FTR, AWOL, DFR) Tracking is now operational for 
      BETA sites. If you are a BETA site, please reference the e-mail sent out this evening with usage instructions. It is important
      that I get feedback from Beta sites to insure all bugs are worked out for master implementation on Tuesday. As such, 
      I will activate this functionality for the rest of the production environment USAP (regular users) on Tuesday evening. 
      Questions, please contact <a href="mailto:tommy.matthewsjr@us.army.mil?subject=Outage">SPC Matthews</a>
      </blockquote>
      <blockquote>
      <b>BedCheck Roster Report: </b> This report is currently implemented as the Building Roster and is accessible from the 
		reports menu.</p>
      </blockquote>
      <blockquote>
              <strong>Speed Enhancements:  </strong>USAP performance has been significantly improved.
      </blockquote>
      	<blockquote>
	<strong>CAC Login Added:  </strong> Users now have the ability to register CAC cards
	with USAP for use in place of usernames and passwords. It is important that you register
	your CAC now, because USAP will be CAC ONLY by the end of the year.
	Click <a href="/cac/portal/cacRegPortal.php" target=_blank>here</a> to register your CAC.
	</blockquote>
	<blockquote>
	<strong>Problems?:  </strong>If you belive you know of a bug, please report it <a href="mailto:tommy.m@gordon.army.mil?subject=USAP BUG REPORT">here</a>.</p>
	</blockquote>
	<blockquote>
	<strong>****Upcoming Features****</strong>
	<hr>
	<b>Formation Management: </b>Ability to define formations by any combination of time, phase, and shift, and status.
	Will allow you to generate the Formation Roster based on the formations you defined. For example, 'PT Formation'
	for 1st Shift All Phases.
	</p>
	<b>FTR Tracking: </b> Ability to record and report on individual FTR's for each soldier. FTRs will be input by
	defined formation. Cadre / Units will have the ability to report on FTR statistics by company; and will immediately
        and automatically identify soldiers eligible for AWOL status. Will assist unit cadre in identifying trends in soldier
	attendance of formations automatically.
	</p>
	

    </td>
  </tr>
</table>

<p>&nbsp;</p>
<table width="80%" border="1" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td class="heading">UNIT Wide Announcements:</a></td>
  </tr>
  <tr>
    <td>
    <div>(<em>This box contains status messages specific to your UNIT only.</em>)</div>
    <br />
    <?php //early_bird(); ?>
    </td>
  </tr>
</table>
<?

// search for events

$oper = $_SESSION['user_id'];
$bn = $_SESSION['battalion_id'];
$co = $_SESSION['company_id'];

$events_query="SELECT CONCAT(main.Rank,\" \",main.Last_Name,\", \",main.First_Name) AS full_name, events_table.*
               FROM events_table
               LEFT JOIN main ON main.id=events_table.id
               WHERE (main.Battalion = '$bn' AND main.Company = '$co')
               AND events_table.event_done=0
               AND events_table.event_start=0
               AND events_table.start_date <= DATE(NOW())
               AND events_table.stop_date >= DATE(NOW())";
			   
$events_result = mysql_query($events_query) or die ("Error:" . mysql_error());
if (mysql_num_rows($events_result) > 0) {
  echo "\n<script language=\"javascript\">\n";
  echo "window.open ('execute_events.php','',\"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, copyhistory=no, resizable=no ,width=700, height=400,\")";
  echo "</script>\n\n";
  }

// events checks finished

$footer = com_sitefooter();
echo $footer;

?>
