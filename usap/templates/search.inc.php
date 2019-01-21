<?php
if(isset($_GET['advanced']) && $_GET['advanced'] == 1)
{ $url = "<a href='" . $_SERVER['SCRIPT_NAME'] . "?advanced=0'>(simple)</a>"; }
else
{ $url = "<a href='" . $_SERVER['SCRIPT_NAME'] . "?advanced=1'>(advanced)</a>"; }

$unit_select = "<select name='unit'><option value='all'>all</option>\n";
$result = mysql_query("select battalion_id, battalion from battalion order by battalion");
while($row = mysql_fetch_row($result))
{
    $unit_select.= "<option value='" . $row[0] . "'";
    if(isset($_GET['unit']) && $_GET['unit'] == $row[0])
    { $unit_select .= " selected"; }
    $unit_select .= ">" . $row[1] . "</option>\n"; }
$unit_select .= "</select>\n";

?>
<table width="80%" border="1" cellspacing="2" cellpadding="2" align="center">
  <tr class="table_heading">
    <td colspan="2">Search the USAP Database:</td>
  </tr>
  <tr>
    <td align="center" width="34%">
        <form method="get" action="<?php=$_SERVER["SCRIPT_NAME"]?>" name="search_form">
        <p>Search For: <input type="text" name="search_text" size="25"> <?php=$url?></p>
        <?php
            if(isset($_GET['advanced']) && $_GET['advanced'] == 1)
            {
                echo "<table border=1 cellspacing='1' cellpadding='1' width='50%'><tr><td align='center'>\n";
                echo "limit to unit: $unit_select <input type='checkbox' name='type[]' value='active'";
                if(isset($_GET['type']) && in_array("active",$_GET['type'])) { echo " checked"; }
                echo ">active <input type='checkbox' name='type[]' value='pcs'";
                if(isset($_GET['type']) && in_array("pcs",$_GET['type'])) { echo " checked"; }
                echo ">pcs</td></tr><tr><td align='center'>\n";
                foreach($_CONF['pers_type'] as $type)
                {
                    echo "<input type='checkbox' name='pers_type[]' value='$type'";
                    if(isset($_GET['pers_type']) && in_array($type,$_GET['pers_type'])) { echo " checked"; }
                    echo ">$type&nbsp;&nbsp;";
                }
                echo "</td></tr></table>\n";
                echo "<input type='hidden' name='advanced' value='1'>\n";
            }
        ?>
        <p>Results per page: <?php=conf_select("results_per_page",$_COOKIE["results_per_page"])?><input type="submit" name="search_submit" value="search" class="button"></p>
        <p><input type="checkbox" name="sounds_like" value="1"> sounds like <input type="checkbox" name="export2excel" value="1"> export to excel</p>
        </form>
    </td>
  </tr>
  <tr>
    <td width="66%" valign="top">
        <p>you can search the database by social security number or by name.</p>
        <p><b>ssn: </b><font size="2">enter with or without dashes (-). entering only four numbers will search the last four digits only</font></p>
        <p><b>name: </b><font size="2">enter last name first. provide a first name, or part of one, to narrow your search. last name and first
            name should be seperated by a comma, i.e. <i>smith, john</i> or <i>smith, j</i> or <i>smi</i>. if you are unsure of the spelling of the last name,
            enter the name, and check the <b>sounds like</b> box to search for names that sound the same. <b>sounds like</b> will
            match the last name only.</font></p>
        <p><b>excel: </b><font size="2">check the box to show all results in microsoft excel</font></p>
    </td>
  </tr>
</table>
<script language="javascript">
document.search_form.search_text.focus();
</script>
