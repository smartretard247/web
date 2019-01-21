<?
//configuration file
include("../lib-common.php");
//admin functions
include("lib-admin.php");
include("../classes/validate.class.php");
include("../classes/user.class.php");

//ensure user has permission to view
//this page. if not, show error and exit.
if(!check_permission(26))
{
    echo com_siteheader("Unauthorized Access");
    echo "Unauthorized Access: You do not have permission to access this area";
    echo com_sitefooter();
    exit();
}

if($_SESSION['user_id'] == $_GET['id'])
{
    echo com_siteheader("Unauthorized Access");
    echo "<color=#ff0000><center><table><b>Unauthorized Access:</b> <i>You can not edit your own user account!</i></table></center>";
    echo com_sitefooter();
    exit();
}

//default variables
$val = new validate;

//display header
echo com_siteheader("Edit User");
//display menu
echo admin_menu();

//see if reset password was selected
if(isset($_GET['reset_password']))
{
    $input['id'] = $val->fk_constraint($_REQUEST['id'],"users","user_id");
    $input['id'] = $val->id($_REQUEST['id'],26);
    if(isset($_GET['random_password']))
    { $input['password'] = $val->generate_password(8); }
    else
    { $input['password'] = $val->check("password",$_REQUEST['new_password'],"password"); }
    if(isset($_GET['change_password']))
    { $input['change_pw'] = 0; }
    else
    { $input['change_pw'] = 1; }

    if($val->iserrors())
    { echo $val->geterrors(); }
    else
    {
        $query = "update users set password = password('" . $input['password'] . "'), change_password = "
                . $input['change_pw'] . " where user_id = " . $input['id'];
        $result = mysql_query($query) or die("reset password error: " . mysql_error());
        if(mysql_affected_rows())
        { echo "<br><center><font size='+1'>password has been changed to: " . $input['password'] . "</font></center>"; }
        else
        { echo "failed to update password!"; }
    }
}

//see if an id was passed to the page
if(isset($_REQUEST['id']) && !isset($_GET['reset_password']))
{
    if($val->fk_constraint($_REQUEST['id'],"users","user_id"))
    {
        if($input['id'] = $val->id($_REQUEST['id'],26))
        {
            $u = new user;
            $u->set_user($input['id']);

            if(isset($_POST['up_submit']))
            { //echo "There was an error executing the query."; //handle error
            	//exit();
            	echo $u->process_user_permissions(); }

            if(!isset($_POST['delete_user']))
            {
                echo $u->user_info();
                echo $u->draw_all_permission_boxes(3);
            }
        }
        else
        { echo "Unauthorized Access: You do not have permission to edit this user."; }
    }
    else
    { echo "Invalid User: You must first create a user before you can modify permissions."; }
}
else
{
    if(isset($_REQUEST['id']))
    { $input['id'] = (int)$_REQUEST['id']; }
    else
    { $input['id'] = 0; }

    ?>
    <br>
    <form method='get' action='<?=$_SERVER['SCRIPT_NAME']?>'>
    <table border='1' width='90%' cellpadding='5' cellspacing='0'>
      <col width='50%' align='right'></col>
      <col width='50%' align='left'></col>
      <tr>
        <td colspan='2' class="table_heading"><font size='+1'>Edit User Permissions</font></td>
      </tr>
      <tr>
        <td>Choose the soldier to edit permissions on:</td>
        <td>
          <?=admin_choose_users($input['id'])?>
          <input type='submit' name='submit' value='Edit User Permissions' class="button">
        </td>
      </tr>
      <tr>
        <td align="right">Reset Password:</td>
        <td>
          <input type="text" name="new_password" value="" size="12">
          <input type="checkbox" name="random_password">Random
          <input type="checkbox" name="change_password">Must change password
          <input type="submit" name="reset_password" value="Reset Password" class="button">
        </td>
    </table>
    </form>
    <?
}

echo com_sitefooter();

?>
