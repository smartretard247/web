<?
include("../lib-common.php");

include("../classes/validate.class.php");
include("../classes/roster.class.php");

set_time_limit(300);

$val = new validate;
$pt = array();
$ssn_length = 4;

//validate unit
if($unit = $val->unit($_GET['unit'],14))
{
    $batt = $unit[0];
    $comp = $unit[1];
    $report['where'] = '';
    $report['where2'] = '';

    $result = mysql_query("select battalion_id, battalion from battalion order by battalion ASC") or die(mysql_error());
    while($row = mysql_fetch_assoc($result))
    {
		$battalions[$row['battalion_id']] = $row['battalion'];
		if($row['battalion_id'] == $batt)
		{ $report['battalion'] = $row['battalion']; }
	}

    $result = mysql_query("select company_id, company from company order by company ASC") or die(mysql_error());
    while($row = mysql_fetch_assoc($result))
    {
	    $companies[$row['company_id']] = $row['company'];
	    if($row['company_id'] == $comp)
	    { $report['company'] = $row['company']; }
    }
    
    if($batt == 0 && $comp == 0)
    { $report['unit'] = '15 SIG BDE'; }
    else
    {
		if($comp == 0)
		{
			$report['unit'] = $report['battalion'];
			$report['where'] .= " AND b.battalion_id = $batt ";
		}
		else
		{
			$report['unit'] = $report['battalion'] .' - ' . $report['company'];
			$report['where'] .= " AND b.battalion_id = $batt AND c.company_id = $comp ";
		}
	}
	
	if(!empty($_REQUEST['permission_id']) && is_array($_REQUEST['permission_id']))
	{
		$pid_list = '';
		foreach($_REQUEST['permission_id'] as $pid)
		{ $pid_list .= (int)$pid . ','; }
		$report['where'] .= ' AND up.permission_id IN (' . substr($pid_list,0,-1) . ') ';
		$report['where2'] = ' WHERE permission_id IN (' . substr($pid_list,0,-1) . ') ';
	}

    $date = strtoupper(date("dMY"));

    $header =  "<strong>Permission Report 2: " . $report["unit"] . " --- $date </strong>";

    echo com_siteheader("Permission Report 2: " . $report['unit'] . " --- $date");

	$query = "SELECT permission_id, permission FROM permissions {$report['where2']} ORDER BY permission_id ASC";
	$result = mysql_query($query) or die('Error in query: ' . mysql_error());
	while($row = mysql_fetch_assoc($result))
	{ $permissions[$row['permission_id']] = $row['permission']; }
	
	$query = "SELECT m.last_name, m.first_name, m.rank, up.user_id, up.permission_id, up.battalion_id, up.company_id,
			  CONCAT(c.company,'-',IF(0+b.battalion=0,b.battalion,0+b.battalion)) AS unit
			  FROM main m JOIN user_permissions up ON m.id = up.user_id, battalion b, company c 
			  WHERE m.battalion = b.battalion_id AND m.company = c.company_id AND m.pcs = 0 {$report['where']} 
			  ORDER BY m.last_name, m.first_name";
	$result = mysql_query($query) or die('Error in query: ' . mysql_error());
	while($r = mysql_fetch_assoc($result))
	{
		$data[$r['user_id']] = array('last_name'=>$r['last_name'], 'first_name'=>$r['first_name'], 'rank'=>$r['rank'], 'unit'=>$r['unit']);
		$user_permissions[$r['user_id']][$r['permission_id']][$r['battalion_id']][$r['company_id']] = 1;
	}	  
		
	$heading_colspan = 4 + ((count($companies)+1) * count($permissions));
	$permission_colspan = count($companies) + 1;
	$info_colspan = count($battalions);

	if(!isset($_REQUEST["export2"]))
    {
      ?>
      <table border="0">
        <tr>
          <td>
            <table border="1" cellpadding="4" align="left" width="500">
              <tr>
                <td>This report is best viewed in Excel. To freeze a column or row in Excel, so that when
                  you scroll the column or row remains visible, choose the row below or column to the right
                  of the ones you want to freeze. Click <strong>Window</strong> from the menu and choose
                  <strong>Freeze Panes</strong>. To unfreeze, choose <strong>Window->Unfreeze Panes</strong>.
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td>
            <br><br>
      <?
      echo "Export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>Excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>Word</a>\n"; 
    }
	
	
	echo '<table border="1" cellspacing="0" cellpadding="1" width="100%" align="left">';
	echo "<tr><td colspan=\"{$heading_colspan}\">{$header}</td></tr>\n";
	echo '<tr><td colspan="4" bgcolor="black">&nbsp;</td>';
	foreach($permissions as $pid=>$pid_value)
	{ echo "<td colspan=\"{$permission_colspan}\" class=\"table_cheading\">P{$pid}: {$pid_value}</td>"; }
	echo '</tr><tr><td class="table_cheading">Last Name</td><td class="table_cheading">First Name</td><td class="table_cheading">Rank</td><td class="table_cheading">Unit</td>'."\n";
	
	$numpermissions = count($permissions);
	for($x=0;$x<$numpermissions;$x++)
	{
		echo '<td bgcolor="black">&nbsp;</td>';
		foreach($companies as $cid=>$cid_value)
		{ echo "<td class=\"verticaltext\">{$cid_value}</td>"; }
		echo "\n";
	}
	echo "</tr>\n";
	foreach($data as $uid=>$info)
	{
		echo '<tr>';
		foreach($info as $value)
		{ echo "<td rowspan=\"{$info_colspan}\">{$value}</td>"; }
		foreach($battalions as $bid=>$battalion)
		{
			foreach($permissions as $pid=>$permission)
			{
				echo "<td>{$battalion}</td>";
				foreach($companies as $cid=>$company)
				{
					if(isset($user_permissions[$uid][$pid][$bid][$cid]))
					{ echo '<td class="table_cheading">X</td>'; }
					else
					{ echo '<td>&nbsp;</td>'; }
				}
				echo "\n";
			}
			echo "</tr>\n";
		}
		flush();
	}
	
	echo '</table></td></tr></table>';
}
else
{
    echo com_siteheader("Invalid permissions - Permission Report");
    echo "Invalid permissions.";
}

echo com_sitefooter();

?>
