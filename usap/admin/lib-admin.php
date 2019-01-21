<?

function admin_menu()
{
    global $_CONF;

    $retval = "";

    $retval .= "<table border='1' cellpadding='5' cellspacing='0' align='center'>\n";
    $retval .= "<tr>\n";
    $retval .= "<td><a href='" . $_CONF['admin_html'] . "/index.php'>main</a></td>\n";
    $retval .= "<td><a href='" . $_CONF['admin_html'] . "/add_user.php'>add user</a></td>\n";
    $retval .= "<td><a href='" . $_CONF['admin_html'] . "/edit_user.php'>edit user</a></td>\n";
    $retval .= "<td><a href='" . $_CONF['admin_html'] . "/query.php'>custom query</a></td>\n";
    $retval .= "</tr>\n";
    $retval .= "</table>\n";

    return $retval;
}

function admin_choose_available_users($id)
{
    global $_CONF;

    $retval = "";

    $retval .= "<select name='id'>\n";

    $pt_string = "'" . implode("','",$_CONF['perm_party']) . "'";
    $result = mysql_query("select m.id, m.last_name, m.first_name, m.middle_initial,
                           m.rank, right(m.ssn,4) as ssn from main m left join users u on m.id = u.user_id, user_permissions up
                           where u.user_id is null and up.permission_id = 26 and up.user_id = {$_SESSION['user_id']} and 
                           m.company = up.company_id and m.pers_type in ($pt_string) and 
                           m.battalion = up.battalion_id order by m.last_name, m.first_name, m.middle_initial") or die(mysql_error());

    while($row = mysql_fetch_array($result))
    {
        $retval .= "<option value='" . $row['id'] . "' ";
        if($row['id'] == $id) { $retval .= "selected"; }
        $retval .= ">" . $row['last_name'] . ", " . $row['first_name'] . " " . $row['middle_initial'] . ", " . $row['rank'] . " - " . $row['ssn'] . "</option>\n";
    }

    $retval .= "</select>\n";

    return $retval;
}

function admin_choose_users($id)
{
    global $_CONF;
    $id = (int)($id);

    $retval = "";

    $retval .= "<select name='id'>\n";

    $result1 = mysql_query("create temporary table temp select battalion_id, company_id from user_permissions "
                ."where user_id = " . $_SESSION['user_id'] . " and permission_id = 26");

    $result = mysql_query("select m.id, m.rank, m.last_name, m.first_name, m.middle_initial, "
                ."right(m.ssn,4) as ssn, u.login from main m, users u, temp t where "
                ."m.id = u.user_id and m.battalion = t.battalion_id and m.company = t.company_id "
                ."order by m.last_name, m.first_name, m.middle_initial")
                or die("select pp error: " . mysql_error());

    while($row = mysql_fetch_array($result))
    {
        $retval .= "<option value='" . $row['id'] . "' ";
        if($row['id'] == $id) { $retval .= "selected"; }
        $retval .= ">" . $row['last_name'] . ", " . $row['first_name'] . " " . $row['middle_initial'] . ", " . $row['rank'] . " - " . $row['ssn'] . " ({$row['login']})</option>\n";
    }

    $retval .= "</select>\n";

    return $retval;
}

?>
