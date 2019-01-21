<?
session_start();
$bn = $_SESSION['battalion_id'];
$co = $_SESSION['company_id'];

include("lib-common.php");

$events_start_query="SELECT CONCAT(main.Rank,\" \",main.Last_Name,\", \",main.First_Name,if(main.Middle_Initial<>'',CONCAT(' ',main.Middle_Initial,'.'),'')) AS full_name, events_table.*
                     FROM events_table
                     LEFT JOIN main ON main.id=events_table.id
                     WHERE (main.Battalion = '$bn' AND main.Company = '$co')
                     AND events_table.event_done=0
                     AND events_table.event_start=0
                     AND events_table.start_date <= DATE(NOW())
                     AND events_table.stop_date >= DATE(NOW())";

$events_stop_query="SELECT CONCAT(main.Rank,\" \",main.Last_Name,\", \",main.First_Name,if(main.Middle_Initial<>'',CONCAT(' ',main.Middle_Initial,'.'),'')) AS full_name, events_table.*
                    FROM events_table
                    LEFT JOIN main ON main.id=events_table.id
                    WHERE (main.Battalion='$bn' AND main.Company='$co')
                    AND events_table.event_done=0
                    AND events_table.event_start=1
                    AND events_table.stop_date >= DATE(NOW())";
					 
$events_start_result = mysql_query($events_start_query) or die ("Error:" . mysql_error());
$events_stop_result  = mysql_query($events_stop_query)  or die ("Error:" . mysql_error());

if (@$_POST['event_selection']) {
  while ($row = mysql_fetch_assoc($events_start_result)) {
    $test = "event_{$row['event_id']}";
    if ($_POST[$test]==1) { 
	  echo "Change daily status to :{$row['type_event']} - {$row['event']}";
	}
  }	
exit();
}

?>
<html>
<head><title>Confirm Events</title>
</head>
<body>
<h2 align="center">Confirm Todays's Events</h2>
<hr width="600"/>
<form name="events" action="<?=$_SERVER['PHP_SELF']; ?>" method="POST">
<input type="hidden" name="event_selection" value="1">
<table border="1" align="center" width="575">
<tr>
  <td align="center">Confirm</td><td align="center">Name</td><td align="center">Event</td><td align="center">When</td><td align="center">Action</td>
</tr>
<?
while ($row = mysql_fetch_assoc($events_start_result)) { //events that should start
  echo "<tr>\n";
  echo "  <td align=\"center\"><input type=\"radio\" name=\"event_{$row['event_id']}\" value=\"1\" checked> Yes <input type=\"radio\" name=\"event_{$row['event_id']}\" value=\"0\"> No</td>\n";
  echo "  <td>{$row['full_name']}</td>\n";
  echo "  <td align=\"center\">{$row['type_event']}-{$row['event']}</td>\n";
  echo "  <td align=\"center\">" . (($row['start_date']==date('Y-m-d')) ? "Today": date("d-M-y",strtotime($row['start_date']))) . "</td>\n";
  echo "  <td align=\"center\">Start</td>\n";
  echo "</tr>\n";
}
while ($row = mysql_fetch_assoc($events_stop_result)) { // events that should stop
  echo "<tr>\n";
  echo "  <td align=\"center\"><input type=\"radio\" name=\"event_{$row['event_id']}\" value=\"1\" checked> Yes <input type=\"radio\" name=\"event_{$row['event_id']}\" value=\"0\"> No</td>\n";
  echo "  <td>{$row['full_name']}</td>\n";
  echo "  <td align=\"center\">{$row['type_event']}-{$row['event']}</td>\n";
  echo "  <td align=\"center\">" . (($row['start_date']==date('Y-m-d')) ? "Today": date("d-M-y",strtotime($row['start_date']))) . "</td>\n";
  echo "  <td align=\"center\">Stop</td>\n";
  echo "</tr>\n";
}

?>
<tr>
  <td colspan="2" align="center"><img src = "images/icons/accept.png" title="Apply" onclick="document.events.submit()"></td><td colspan="3" align="center"><img src = "images/icons/exit.png" title="Exit" onclick="window.close()"></td>
</tr>
</table>
</form>
</body>
</html>