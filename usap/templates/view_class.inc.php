<table width="90%" border="1" align="center">
  <?php
    if(!isset($_REQUEST["export2"]))
    {
        echo "<tr><td><table width='100%' border='0' align='center'><tr><td>\n";

        if(!isset($_REQUEST["export2"]))
        {
            echo "Export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>Excel</a>";
            echo " / ";
            echo "<a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>Word</a>\n";
        } 

        echo "</td><td align='right'>\n";

        if($val->cclass($_REQUEST["class_id"],5))
        { echo " <a href='" . $_CONF["html"] . "/edit_class.php?class_id=" . $_REQUEST["class_id"] . "'>Edit</a> "; }
        echo "</td></tr></table></td></tr>\n";
    }
  ?>
  <tr class="table_heading">
    <td>View Class Information</td>
  </tr>
<?php
if($row['inactive'] == 1)
{
    ?>
    <tr>
        <td class="notice">This class has been deleted. All students assigned to the class have PCSd.</td>
    </tr>
    <?php
}
?>
  <tr>
    <td>
<input type='hidden' name='class_id' value='<?php=$class_id?>'>

      <table width="100%" border="0" cellspacing="2" cellpadding="2">
        <col width="20%" />
        <col width="80%" />
        <tr>
          <td class="column_name">MOS:</td>
          <td class="data"> <?php=$row["mos"]?> </td>
        </tr>
        <tr>
          <td class="column_name">Class Number:</td>
          <td class="data"> <?php=$row["class_number"]?> </td>
        </tr>
        <tr>
          <td class="column_name">Unit</td>
          <td class="data"><?php=$row["battalion"]?>--<?php=$row["company"]?></td>
        </tr>
        <tr>
          <td class="column_name">Start Date</td>
          <td class="data"> <?php=$row["start_date"]?> </td>
        </tr>
        <?php if(!empty($row['eoc_date'])) { ?>
        <tr>
          <td class="column_name">EOC Date</td>
          <td class="data"> <?php=$row["eoc_date"]?> </td>
        </tr>
        <?php }
           if(!empty($row['ctt_date'])) { ?>
        <tr>
          <td class="column_name">CTT Date</td>
          <td class="data"> <?php=$row["ctt_date"]?> </td>
        </tr>
        <?php }
           if(!empty($row['trans_date'])) { ?>
        <tr>
          <td class="column_name">Transition Date</td>
          <td class="data"> <?php=$row["trans_date"]?> </td>
        </tr>
        <?php }
           if(!empty($row['stx_start'])) { ?>
        <tr>
          <td class="column_name">STX Start Date</td>
          <td class="data"> <?php=$row["stx_start"]?> </td>
        </tr>
        <?php }
           if(!empty($row['stx_end'])) { ?>           
        <tr>
          <td class="column_name">STX End Date</td>
          <td class="data"> <?php=$row["stx_end"]?> </td>
        </tr>
        <?php } ?>
        <tr>
          <td class="column_name">Graduation Date</td>
          <td class="data"> <?php=$row["grad_date"]?> </td>
        </tr>
        <tr>
          <td class="column_name">PCS Date</td>
          <td class="data"> <?php=$row["pcs_date"]?> </td>
        </tr>
        <tr>
          <td class="column_name">AOT Type</td>
          <td class="data"> <?php=$row["aot_type"]?> </td>
        </tr>
	<tr>
          <td class="data"> <i>Shift data is set on a per-soldier(student) basis; <b>not</b> on a class basis.</i></td>
        </tr>

            
       <?php
        if($extra_result)
        {
            while($erow = mysql_fetch_assoc($extra_result))
            {
                echo "<tr><td class=\"column_name\">{$erow['field']}</td>
                      <td class=\"data\">{$erow['value']}</td></tr>";
            }
        }
        ?>
      </table>
	
    </td>
  </tr>
</table>
</p>
<table width="90%" border="1" align="center">
<tr class="table_heading">
<td>Class Roster(USAP)</td>
</tr>
<?php
if ($_REQUEST["showRoster"] == "true") {
$report['class_id'] = $_REQUEST["class_id"];	

if(isset($_REQUEST["export2"])) {
//include("/lib-common.php");
//include("f:\usap\classes\validate.class.php");
//include("f:\classes\roster.class.php");
}

$val = new validate;

//if none is chosen, display message

//other wise validate unit
if($report['class_id'] = $val->cclass($_GET['class_id'],13))
{
	if(isset($_REQUEST["export2"])) { 
		echo "<td>Class not exported to excel. Use reports for this!</td>";
		exit(); 
	}
    //get class information
    $result = mysql_query("select class_number, mos from class where class_id = " . $report['class_id']) or die("class info query error: " . mysql_error());
    list($report['class_number'], $report['class_mos']) = mysql_fetch_row($result);

    $date = strtoupper(date("dMY"));

	
    $comp = implode(",",$_CONF['component']);
    $comp_abbr = "'" . implode("','",$_CONF['component_abbrev']) . "'";

    $query = "select m.id, m.Last_Name, m.First_Name, m.middle_initial as MI, concat(m.rank,m.promotable) as Rank, right(m.ssn,4) as SSN, m.Gender AS Gen, "
            ."elt(find_in_set(m.component,'" . $comp . "')," . $comp_abbr . ") as Comp, "
            ."m.platoon as PLT, m.MOS, s.Shift, s.Phase as PH, "
            ."concat(co.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as Unit from main m ,student s left join class c "
            ."on s.class_id = c.class_id, battalion b, company co "
            ."where m.pcs = 0 and m.id = s.id  and m.battalion = b.battalion_id and m.company = co.company_id "
            ."and c.class_id = " . $report['class_id']
            . " order by m.last_name, m.first_name, m.middle_initial, m.ssn";

    $roster = new roster($query);
   
    $roster->link_page("data_sheet.php");
    $roster->link_column(0);
    $roster->sethidecolumn(0);
    $roster->setReportName('classroster');
	$roster->allowUserOrderBy(FALSE);
	echo"<td class=' '>";    echo $roster->drawroster(); echo "</td>";

}
else
{
    echo "invalid permissions.";
}





} else {
	echo "<td class='coloumn_name'>Data suppressed for load speed. Click <a href='class.php?class_id=" . $_REQUEST["class_id"] . "&view_class=Go&showRoster=true'>here</a> to see the roster.</td>";
}
?>
</table>

