<?
$query="SELECT DISTINCT room_number from main where building_number='25715' order by room_number asc";
$result=mysql_query($query);
$num=mysql_numrows($result);


$i=0;
while ($i < $num) {
$first=mysql_result($result,$i,"room_number");

	$query2="select last_name, first_name from main where building_number='25715' and room_number='" . $first . "' order by last_name asc";
	$result2=mysql_query($query2);
	$ct=mysql_numrows($result2);
	$idx=0;
	while ($idx < $ct) 
		{
		echo mysql_result($result2, $idx, "last_name");
		echo mysql_result($result2, $idx, "first_name");
		++$idx;
		}

++$i;
}
?>