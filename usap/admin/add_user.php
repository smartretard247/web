<?
//configuration file
include("../lib-common.php");
//admin functions
include("lib-admin.php");
include("../classes/user.class.php");

if(!check_permission(26))
{
    echo com_siteheader("unauthorized access");
    echo "unauthorized access: you do not have permission to access this area";
    echo com_sitefooter();
    exit();
}

echo com_siteheader("add new user");
echo admin_menu();

if(isset($_REQUEST['create_user']))
{
    //add new user to database
    include("../classes/validate.class.php");
    $input = array();

    $val = new validate;

    $input['id'] = $val->fk_constraint($_REQUEST['id'],"main","id");
    $input['login'] = $val->login($_REQUEST['login']);
    if(isset($_REQUEST['random']))
    { $input['password'] = $val->generate_password(8); }
    else
    { $input['password'] = $val->check("password",$_REQUEST['password'],"password"); }

    if(isset($_REQUEST['change_password']) && $_REQUEST['change_password'] === '0')
    { $input['change_password'] = 0; }
    else
    { $input['change_password'] = 1; }

    if($val->iserrors())
    { echo $val->geterrors(); }
    else
    {
        $query = "insert into users (user_id, login, password, change_password) values (" . $input['id'] . ",'"
                .$input['login'] . "',password('" . $input['password'] . "')," . $input['change_password'] . ")";

        $result = mysql_query($query) or die("insert user error: " . mysql_error());

        $result = mysql_query("select last_name, first_name, middle_initial, rank, right(ssn,4) as ssn "
                            ."from main where id = " . $input['id']) or die("select user error: " . mysql_error());
        $row = mysql_fetch_array($result);
        $_REQUEST['login'] = "";

        ?>
        <br>
        <table border='1' width='70%' align='center' cellspacing='1' cellpadding='3'>
          <tr>
            <td class="table_heading">New user: <?=$row['last_name']?>, <?=$row['first_name']?> <?=$row['middle_initial']?>, <?=$row['rank']?> - <?=$row['ssn']?></td>
          </tr>
          <tr>
            <td>
              <table border='0' width='100%' align='center' cellpadding='0' cellspacing'3'>
                <tr>
                  <td>Login: <?=$input['login']?></td>
                </tr>
                <tr>
                  <td>Password: <?=$input['password']?></td>
                </tr>
                <tr>
                  <td>User <?=($input['change_password']) ? " does not have to " : " must ";?> change password at next login.</td>
                </tr>
                <tr>
                  <td>Click <a href='<?=$_CONF['admin_html']?>/edit_user.php?id=<?=$input['id']?>'>here</a> to edit permissions for this user.</td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
        <?
    }
}
if(!isset($_REQUEST['id']))
{ $_REQUEST['id'] = 0; }
?>
<br>
<form method='get' action='<?=$_SERVER['SCRIPT_NAME']?>'>
<table width='90%' border='1' cellpadding='3' cellpadding='0' align='center'>
  <col align='center'></col>
  <tr>
    <td class="table_heading"><font size='+1'>create new user</font></td>
  </tr>
  <tr>
    <td>
      <table width='100%' border='0' cellspacing='5' cellpadding='0'>
        <col width='50%' align='right' style="font-weight:bold"></col>
        <col width='50%' align='left'></col>
        <tr>
          <td>choose user:</td>
          <td><?=admin_choose_available_users($_REQUEST['id'])?></td>
        </tr>
        <tr>
          <td>login:</td>
          <td><input type='text' name='login' size='15' value='<?if(isset($_REQUEST['login'])) { echo htmlentities($_REQUEST['login']); }?>'></td>
        </tr>
        <tr>
          <td>password:</td>
          <td><input type='text' name='password' size='15' value='<?if(isset($_REQUEST['password'])) { echo htmlentities($_REQUEST['password']); }?>'</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td style="font-size:smaller"><input type='checkbox' name='random' value='1'<?if(isset($_REQUEST['random'])) { echo "checked"; } ?>>generate random password</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td style="font-size:smaller"><input type='checkbox' name='change_password' value='0' <?if(isset($_REQUEST['change_password'])) { echo "checked"; }?>>must change password on next login</td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td><input type='submit' name='create_user' value='create user' class="button"></td>
  </tr>
</table>
</form>
<?
echo com_sitefooter();
?>
