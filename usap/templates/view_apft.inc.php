<table width="60%" border="1" cellspacing="1" cellpadding="1">
  <?php
    if(!isset($_REQUEST["export2"]))
    {
        echo "<tr><td colspan='2'><table width='100%' border='0' align='center'><tr><td>\n";

        if(!isset($_REQUEST["export2"]))
        {
            echo "export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>Excel</a>";
            echo " / ";
            echo "<a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>Word</a>\n";
        }

        echo "</td><td align='right'>\n";

        if($val->id($_REQUEST["id"],8))
        { echo " <a href='" . $_CONF["html"] . "/edit_apft.php?id=" . $_REQUEST["id"] . "'>Edit</a>&nbsp;&nbsp;"; }
        echo "<a href='{$_CONF['html']}/data_sheet.php?id={$_REQUEST['id']}'>Data Sheet</a>"; 
        echo "</td></tr></table></td></tr>\n";
    }
  ?>
  <tr>
    <td colspan="2"><span class="example">Name (Last, First, Middle)</span><br>
      <span class="column_name"><?php=$apft_row["last_name"]?>, <?php=$apft_row["first_name"]?> <?php=$apft_row["middle_initial"]?></span></td>
  </tr>
  <tr>
    <td><span class="example">SSN</span><br>
      <span class="column_name"><?php=$apft_row["ssn"]?></span></td>
    <td><span class="example">Gender</span><br>
      <span class="column_name"><?php=$apft_row["gender"]?></span></td>
  </tr>
  <tr>
    <td colspan="2"><span class="example">Unit</span><br>
      <span class="column_name"><?php=$apft_row["company"]?> <?php=$apft_row["battalion"]?></span></td>
  </tr>
</table>
<br>
    <?php
    do
    {
        if(substr($apft_row['type'],0,3) == "BCT")
        { $passing = 50; }
        else
        { $passing = 60; }
    ?>
      <table width="60%" border="1" cellspacing="1" cellpadding="1">
        <tr>
          <td><span class="example">Date</span><br>
            <span class="column_name"><?php=$apft_row["date"]?></span></td>
          <td><span class="example">Rank</span><br>
            <span class="column_name"><?php=$apft_row["rank"]?></span></td>
          <td><span class="example">Age</span><br>
            <span class="column_name"><?php=$apft_row["age"]?></span></td>
        </tr>
        <tr>
          <td><span class="example">Height</span><br>
            <span class="column_name"><?php=$apft_row["height"]?></span></td>
          <td><span class="example">Weight</span><br>
            <span class="column_name"><?php=$apft_row["weight"]?></span></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>
            <div align="center"><font size="5">Push-ups</font></div>
          </td>
          <td><span class="example">Raw Score</span><br>
            <span class="column_name"><center><?php=$apft_row["raw_pu"]?></center></span></td>
          <td <?phpif($apft_row['pu_score']<$passing && $apft_row['pu_exempt']==0) { echo "bgcolor='{$_CONF['up']['error_color']}'"; }?>><span class="example">Points</span><br>
            <span class="column_name"><center><?php echo ($apft_row['pu_exempt']==1)?'Exempt':$apft_row["pu_score"]?></center></span></td>
        </tr>
        <tr>
          <td>
            <div align="center"><font size="5">Sit-ups</font></div>
          </td>
          <td><span class="example">Raw Score</span><br>
            <span class="column_name"><center><?php=$apft_row["raw_su"]?></center></span></td>
          <td <?phpif($apft_row['su_score']<$passing && $apft_row['su_exempt']==0) { echo "bgcolor='{$_CONF['up']['error_color']}'"; }?>><span class="example">Points</span><br>
            <span class="column_name"><center><?php echo ($apft_row['su_exempt']==1)?'Exempt':$apft_row["su_score"]?></center></span></td>
        </tr>
        <tr>
          <td>
            <div align="center"><font size="5">2-Mile Run / Alt Event</font></div>
          </td>
          <td><span class="example">Raw Score</span><br>
            <span class="column_name"><center><?php=$apft_row["raw_run"]?></center></span></td>
          <td <?phpif($apft_row['run_score']<$passing) { echo "bgcolor='{$_CONF['up']['error_color']}'"; }?>><span class="example">Points</span><br>
            <span class="column_name"><center><?php=$apft_row["run_score"]?></center></span></td>
        </tr>
        <tr>
          <td>
          <?php
          if(!($apft_row["alt_event"] == '' || $apft_row["alt_event"] == "n/a"))
          {
              ?>
              <span class="example">Alternate Event</span><br>
              Event: <span class="column_name"><?php=$apft_row["alt_event"]?></span> (<a href="<?php=$_CONF['html']?>/reports/profile_history_report.php?id=<?php=$_REQUEST['id']?>">Click here for Profile History</a>)<br>
              <?php
          }//end alt_event check
          else
          {
              echo "&nbsp;";
          }
          ?>
          </td>
          <td valign="top">
              <div align="left"><span class="example">Type</span></div><br>
              <span class="column_name"><center><?php=$apft_row["type"]?></center></span>
          </td>
          <td valign="top" <?phpif($apft_row['pass_fail']=="fail") { echo "bgcolor='{$_CONF['up']['error_color']}'"; }?>>
            <div align="left"><span class="example">Total Score</span></div><br>
            <span class="column_name"><center>
            <?php=$apft_row["total_score"]?>
            </center></span>
          </td>
        </tr>
      </table>
      <br>
    <?php
    }while($apft_row = mysql_fetch_array($apft_result));
?>