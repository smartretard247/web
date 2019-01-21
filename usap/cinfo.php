<?

session_start();

include('lib-common.php');

//Display header
echo com_siteheader('Company Info');

$bn = $_SESSION['battalion_id'];
$co = $_SESSION['company_id'];

$query = "SELECT * 
          FROM company_info
	      WHERE bn='$bn' and co='$co'";

$result = mysql_query($query) or die ("Error:" . mysql_error());

While ($row = mysql_fetch_assoc($result)) {
  foreach ($row as $key=> $val) {
    echo "$key => $val<br/>\n";
  }
}

?>