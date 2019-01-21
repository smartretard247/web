<?php
if(!isset($_REQUEST["export2"]))
{
    ?>

    <form action="<?php=$_SERVER["SCRIPT_NAME"]?>" method="get">
      <table width="90%" border="0" cellspacing="1" cellpadding="1" align="center">
        <tr>
          <td width="50%">
            <p>sort remarks by:
              <input type="radio" name="remark_vsort" value="time" <?phpif($_COOKIE["remark_vsort"] == "time") { echo " checked "; } ?>>
              date
              <input type="radio" name="remark_vsort" value="subject" <?phpif($_COOKIE["remark_vsort"] == "subject") { echo " checked "; } ?>>
              subject
              <input type="submit" name="sort_submit" value="sort" class="button">
              <input type="hidden" name="id" value="<?php=$_REQUEST["id"]?>">
            </p>
          </td>
          <td align="right">limit by subject:
            <?php echo subject_select($_REQUEST["subject"]); ?>
            <input type="submit" name="submit_subject_limit" value="go" class="button">
          </td>
      </tr>
    </table>
    </form>
<?php
}
?>
  <table border="0">
    <tr>
      <td>
        <table width="98%" border="1" cellspacing="1" cellpadding="1" align="center">
      <?php
        if(!isset($_REQUEST["export2"]))
        {
            echo "<tr><td colspan='5'><table width='100%' border='0' align='center' cellpadding='3'><tr><td>\n";

            if(!isset($_REQUEST["export2"]))
            {
                echo "export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>excel</a>";
                echo " / ";
                echo "<a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>word</a>\n";
            }

            echo "</td><td align='right'>\n";

            if($val->id($_REQUEST["id"],17) || $val->id($_REQUEST["id"],23))
            { echo " <a href='" . $_CONF["html"] . "/edit_remark.php?id=" . $_REQUEST["id"] . "'>edit</a> "; }
            if($val->id($_REQUEST['id'],11))
            { echo " <a href='{$_CONF['html']}/data_sheet.php?id={$_REQUEST['id']}'>Data Sheet</a>"; }
            echo "</td></tr></table></td></tr>\n";
        }
      ?>
          <tr>
            <td colspan="5"><b><?php=$name?></b></td>
          </tr>
          <tr class="table_heading">
            <td width="1%">R</td>
            <td width="10%">
              <div align="center">Date</div>
            </td>
            <td width="20%">
              <div align="center">Subject</div>
            </td>
            <td width="5%">By</td>
            <td width="64%">Remark</td>
          </tr>
        <?php
        do
        {
            ?>
              <tr>
                <td width="1%"<?php=($remark_row['restricted']==1)?' bgcolor="red">X':'>&nbsp;'?></td>
                <td width="10%">
                  <div align="center">&nbsp;<?php=$remark_row["time"]?></div>
                </td>
                <td width="20%">
                  <div align="center">&nbsp;<?php=$remark_row["subject"]?></div>
                </td>
                <td width="5%">&nbsp;<a href='<?php=$_CONF["html"]?>/data_sheet.php?id=<?php=$remark_row["eb_id"]?>'><?php=$remark_row["entered_by"]?></a></td>
                <td width="64%"><?php=nl2br($remark_row["remark"])?>&nbsp;</td>
              </tr>
            <?php
        }while($remark_row = mysql_fetch_array($remark_result));
        ?>
        </table>
      </td>
    </tr>
    <tr>
      <td>
        <br>
        <table border="1" width="98%" cellspacing="1" cellpadding="1" align="center">
          <tr class="table_heading">
            <td>Security (S2) Remarks</td>
          </tr>
          <tr>
            <td>
              <?php 
                if(isset($s2_row['remark']) && strlen($s2_row['remark']) > 0)
                { echo $s2_row['remark'];  }
                else
                { echo "No remarks."; }
              ?>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>