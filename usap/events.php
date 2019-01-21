<?

function displayArrayR($arr, $indent='') {
     if ($arr) {
         foreach ($arr as $value) {
             if (is_array($value)) {
                 //
                 displayArrayR($value, $indent . '--');
             } else {
                 //  Output
                 echo "$indent $value <br/>\n";
             }
         }
     }
 } 


session_start();

include('config.php');
include('lib-common.php');
$id = $_GET['id'];

//Display header
echo com_siteheader('Schedule Events');

$query = "SELECT *
          FROM events_table
		  WHERE id = $id";
		  
$result = mysql_query($query) or die("ERROR: " . mysql_error());

while ($row = mysql_fetch_assoc($result)) {
  foreach($row as $key => $val) {
    echo "$key => $val<br/>\n";
  }
}
echo "<br/>\n";

foreach ($_CONF['event'] as $key => $val) {
  if (is_array($val)) {
    echo "$key => <br/>\n";
	foreach($val as $key_1=>$val_1) {
	  echo "&nbsp;&nbsp;&nbsp;&nbsp;$val_1<br/>\n"; }
  }
}  
?>