<hr width="90%" align="center">
<table width="90%" border="0" cellspacing="2" cellpadding="2" align="center">
  <tr>
    <td width="40%">
      <div align="center" class="heading">View Class</div>
    </td>
    <td width="20%">
      <div align="center"></div>
    </td>
    <td class="heading" width="40%">
      <div align="center">Edit Class</div>
    </td>
  </tr>
  <tr>
    <td width="40%"><?php
if(check_permission(13))
{
        ?>
    <form method="get" action="<?php=$_SERVER["SCRIPT_NAME"]?>">
        <div align="center">
          <p> <?php echo class_select(13); ?>
            <input type="submit" name="view_class" value="Go" class="button">
          </p>
        </div>
        </form>
      <?php
}
else
{
    echo "<p align='center'>No view permissions</p>";
}
?> </td>
    <td width="20%" align="center" valign="middle"> <?php
if(check_permission(4))
{
    ?> <a href="add_class.php">Add Class</a> <?php
}
else
{
    echo "&nbsp;";
}
?> </td>
    <td width="40%"> <?php
if(check_permission(5))
{
        ?>
    <form method="get" action="<?php=$_SERVER["SCRIPT_NAME"]?>">
        <div align="center">
          <p> <?php echo class_select(5); ?>
            <input type="submit" name="edit_class" value="Go" class="button">
          </p>
        </div>
       </form>
        <?php
}
else
{
    echo "<p align='center'>No edit permissions</p>";
}
?> </td>
  </tr>
</table>
</form>