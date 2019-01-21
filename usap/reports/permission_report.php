<?
include("../lib-common.php");

include("../classes/validate.class.php");
include("../classes/roster.class.php");

$val = new validate;
$pt = array();
$ssn_length = 4;

//validate unit
if($unit = $val->unit($_GET['unit'],14))
{
    $battalion = $unit[0];
    $company = $unit[1];

    //default sql where values
    $report["where"] = " 1 ";

    $result = mysql_query("select battalion from battalion where battalion_id = $battalion") or die(mysql_error());
    $report["battalion"] = mysql_result($result,0);

    $result = mysql_query("select company from company where company_id = " . $company) or die(mysql_error());
    $report["company"] = mysql_result($result,0);

    $report["unit"] = $report["battalion"] .' - ' . $report["company"];

    $date = strtoupper(date("dMY"));

    $header =  "<strong>Permission Report: " . $report["unit"] . " --- $date </strong>";

    echo com_siteheader("Permission Report: " . $report['unit'] . " --- $date");

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

    $query = "select permission from permissions order by permission_id asc";
    $result = mysql_query($query);
    $num_permissions = mysql_num_rows($result);
    while($row = mysql_fetch_row($result))
    { $perm[] = $row[0]; }

    $query = "
    select m.id, m.last_name, m.first_name, m.rank, up.permission_id, p.permission, 
    concat(c.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as unit
    from main m, permissions p, company c, battalion b, user_permissions up
    where m.id = up.user_id and up.permission_id = p.permission_id and m.pcs=0 
        and m.battalion = b.battalion_id and m.company = c.company_id
        and up.battalion_id = $battalion and up.company_id = $company
    order by m.battalion, m.company, m.last_name, m.first_name, up.permission_id asc";

    $old_name = '';
    $flag = 0;

    $num_columns = 4 + $num_permissions;

    ?>
    <table border="1" cellspacing="0" cellpadding="4" width="100%" align="left">
      <tr>
        <th colspan="<?=$num_columns?>"><?=$header?></th>
      </tr>
      <tr class="table_heading">
        <th>Last Name</th>
        <th>First Name</th>
        <th>Rank</th>
        <th>Unit</th>
        <?
          foreach($perm as $p)
          { echo "<th>$p</th>\n"; }
        ?>
      </tr>

    <?

    $cnt = 0;
    $num_names = 0;
    
    $result = mysql_query($query) or die("Error in Query: $query<br>" . mysql_error());
    while($row = mysql_fetch_assoc($result))
    {
        $name = $row['last_name'] . $row['first_name'] . $row['rank'];
        if($name != $old_name)
        {      
            if($flag)
            { 
                while($cnt++ < $num_permissions)
                { echo "<td>&nbsp;</td>"; }

                echo "</tr>\n<tr>"; 
            } 
            else 
            { $flag = 1; }

            echo "<td>{$row['last_name']}</td><td>{$row['first_name']}</td><td>{$row['rank']}</td><td>{$row['unit']}</td>";
            $num_names++;
            $old_name = $name;
            $cnt = 0;
        }

        while($row['permission'] != $perm[$cnt++])
        { echo "<td>&nbsp;</td>"; }

        echo "<td class=\"table_cheading\">X</td>";                   
    }

    ?>
      </tr>
      <tr>
        <td colspan="<?=$num_columns?>"><strong>Total: <?=$num_names?></td>
      </tr>
    </table>
    <?
      if(!isset($_REQUEST["export2"]))
      { echo "</td></tr></table>"; }

}
else
{
    echo com_siteheader("Invalid permissions - Permission Report");
    if(count($_GET['pers_type']) == 0)
    { echo "No personnel type chosen."; }
    else
    { echo "Invalid permissions.";  }
}

echo com_sitefooter();

?>
