<form method='post' action='<?php=$_SERVER["SCRIPT_NAME"]?>'>
  <table width="90%" border="1" cellspacing="1" cellpadding="1">
    <col width="30%"></col>
    <col width="70%"></col>
    <tr>
      <td colspan="2" class="table_cheading">Add Remark</td>
    </tr>
    <tr>
      <td>Choose Soldier:</td>
      <td> <?php
$soldier_query= "select "
            ."m.id, m.last_name, m.first_name, right(m.ssn,4) as ssn "
        ."from "
            ."main m, user_permissions up "
        ."where "
            ."m.battalion = up.battalion_id and m.company = up.company_id and "
            ."up.user_id = " . $_SESSION["user_id"] . " and up.permission_id = 16 "
        ."order by "
            .$_COOKIE["remark_nsort"] . " asc";

$soldier_result = mysql_query($soldier_query) or die("soldier select error [" . $soldier_query . "]: " . mysql_error());
if(mysql_num_rows($soldier_result) > 0)
{
    echo "<select name='id'>\n";
    echo "<option value=''>choose soldier...</option>\n";
    while($soldier_row = mysql_fetch_array($soldier_result))
    {
        echo "<option value='" . $soldier_row["id"] . "' ";
        if(isset($_REQUEST["id"]) && $_REQUEST["id"] == $soldier_row["id"]) { echo " selected "; }
        echo ">";
        if($_COOKIE["remark_nsort"] == $sort_last)
        {
            echo $soldier_row["last_name"] . ", " . $soldier_row["first_name"] . " - " . $soldier_row["ssn"];
        }
        else
        {
            echo $soldier_row["ssn"] . " - " . $soldier_row["last_name"] . ", " . $soldier_row["first_name"];
        }
        echo "</option>\n";
    }
    echo "</select>\n";
}
?>
        &nbsp;&nbsp;<input type="submit" name="data_sheet" value="Go To Data Sheet" class="button">
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>sort by
        <input type="radio" name="sort_order" value="<?php=$sort_last?>" <?php if($_COOKIE["remark_nsort"] == $sort_last) { echo " checked "; } ?>>
        last name
        <input type="radio" name="sort_order" value="<?php=$sort_ssn?>" <?php if($_COOKIE["remark_nsort"] == $sort_ssn) { echo " checked "; } ?>>
        ssn
        <input type="submit" name="sort_submit" value="sort" class="button">
      </td>
    </tr>
    <tr>
      <td>choose subject:</td>
      <td><?php echo subject_select($_POST["subject"]); ?></td>
    </tr>
    <tr>
      <td valign="top">enter remark:</td>
      <td>
        <textarea name="remark" wrap="physical" cols="70" rows="5"><?php=$_POST["remark"]?></textarea>
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>
        <input type="checkbox" name="restricted" value="1"> <strong>Restricted Remark</strong>
        <br>
        <span class="example">Restricted remarks are viewable by a smaller set of users, usually restricted to Commanders and 1SG/CSM only.</span>
      </td>
    <tr>
      <td>
        <div align="center">
          <input type="submit" name="remark_submit" value="enter" class="button">
        </div>
      </td>
      <td>&nbsp;</td>
    </tr>
  </table>
</form>
