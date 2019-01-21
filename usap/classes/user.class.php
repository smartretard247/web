<?

/***********************
* this class contains functions
* to create and edit users
* and permissions
************************/

class user
{
    var $battalion = array();
    var $company = array();
    var $user_permission;
    var $permission;
    var $p_comment;
    var $user;

    function user()
    {
        $this->load_units();
        $this->load_permissions();

        return;
    }

    function load_permissions()
    {
        $this->permission = array();
        $this->p_comment = array();

        $result = mysql_query("select p.permission_id, p.permission, p.comment from permissions p order by permission_id asc") or die("permission select error: " . mysql_error());

        while($row = mysql_fetch_assoc($result))
        {
            $this->permission[$row['permission_id']] = $row['permission'];
            $this->p_comment[$row['permission_id']] = $row['comment'];
        }

        return;
    }

    function load_units()
    {
        $query = "select b.battalion, c.company, up.battalion_id, up.company_id from user_permissions up, "
                ."battalion b, company c where up.battalion_id = b.battalion_id and up.company_id = c.company_id "
                ."and up.permission_id = 25 and up.user_id = " . $_SESSION['user_id'] . " order by b.battalion, "
                ."c.company";
        $result = mysql_query($query) or die("select units error:" . mysql_error());

        while($row = mysql_fetch_assoc($result))
        {
            $this->battalion[$row['battalion_id']] = $row['battalion'];
            $this->company[$row['battalion_id']][$row['company_id']] = $row['company'];
        }

        return;
    }

    function load_user_permissions($id)
    {
        if(isset($this->user_permission)) { unset($this->user_permission); }
        $this->user_permission = array();
        $id = (int)($id);

        $result = mysql_query("select p.permission_id, up.battalion_id, up.company_id "
            ."from permissions p left join user_permissions up on p.permission_id = up.permission_id where "
            ."(up.user_id = " . $id . " || up.permission_id is null) order by p.permission_id") or die("load user permissions error: " . mysql_error());

        while($row = mysql_fetch_assoc($result))
        { $this->user_permission[$row['permission_id']][$row['battalion_id']][$row['company_id']] = 1; }

        return;
    }

    function draw_permission_box($permission)
    {
        $retval = "";

        $permission = (int)($permission);

        if(isset($this->permission[$permission]))
        {
            $retval .= "<table width='20%' border='1' cellspacing='0' cellpadding='1' align='center'>\n";
            $retval .= "<tr class='table_cheading'><td>P" . $permission . ": " . $this->permission[$permission] . "</td></tr>\n";

            if(strlen($this->p_comment[$permission]) > 0)
            { $retval .= "<tr><td class='example'>" . $this->p_comment[$permission] . "</td></tr>\n"; }

            foreach($this->battalion as $battalion_id => $battalion_text)
            {
                $num_companies = count($this->company[$battalion_id]);

                $retval .= "<tr><td align='center'>\n";
                $retval .= "<table border='0' cellspacing='0' cellpadding='1' align='center'>\n";
                $retval .= "<tr><td align='center' colspan='" . $num_companies . "'><strong>" . $battalion_text . "</strong></div></td></tr>\n";
                $retval .= "<tr>\n";

                foreach($this->company[$battalion_id] as $company_id => $company_text)
                {
                    $retval .= "<td align='center'><input type='checkbox' name='unit[]' value='" . $permission . "-" . $battalion_id . "-" . $company_id . "'";
                    if(isset($this->user_permission[$permission][$battalion_id][$company_id]))
                    { $retval .= " checked "; }
                    $retval .= ">" . $company_text . "</td>\n";
                }

                $retval .= "</tr>\n";
                $retval .= "</table>\n";
            }

            $retval .= "</td></tr>\n";
            $retval .= "</table>\n";
        }

        return $retval;
    }

    function draw_all_permission_boxes($num_across=1)
    {
        global $_CONF;

        $retval = "";
        $count = 1;
        $np = count($this->permission) - 1;
        $width = floor(100 / $num_across);

        $retval .= "<br><form method='post' action='" . $_SERVER['SCRIPT_NAME'] . "'>\n";
        $retval .= "<input type='hidden' name='id' value='" . $this->user . "'>\n";
        $retval .= "<table width='98%' border='0' cellpadding='3' cellspacing='3' align='center'>\n";
        $retval .= "<tr><td colspan='" . $num_across . "'><table border='1' align='center'><tr><td><input type='checkbox' name='delete_user' value='1'> delete this user</td></tr></table></td></tr>\n";
        $retval .= "<tr>\n";

        foreach($this->permission as $permission => $permission_text)
        {
            $retval .= "<td width='" . $width . "%' align='center'>\n";
            $retval .= $this->draw_permission_box($permission);
            $retval .= "</td>\n";
            if($count % $num_across == 0)
            {
                $retval .= "</tr><tr><td colspan=\"$num_across\" align=\"center\"><input type=\"submit\" class=\"button\" name=\"up_submit\" value=\"Update User Permissions\"></td></tr><tr>\n";
            }
            $count++;
        }

        switch(--$count % $num_across)
        {
            case 1:
                $retval .= "<td>&nbsp;</td>\n";
            case 2:
                $retval .= "<td>&nbsp;</td>\n";
                break;
        }

        $retval .= "</tr>\n</table>\n</form>\n";

        return $retval;
    }

    function set_user($id)
    {
        $id = (int)($id);

        $this->user = $id;
        $this->load_user_permissions($id);

        return;
    }

    function process_user_permissions()
    {
        global $_CONF;
        $retval = "";
        $count = 0;

        if(isset($_POST['delete_user']))
        {
            $result = mysql_query("delete from users where user_id = " . $this->user) or die("delete user error: " . mysql_error());
            $result = mysql_query("delete from user_permissions where user_id = " . $this->user) or die("delete user_permissions error: " . mysql_error());

            $retval .= "<br><center><strong>user deleted. click <a href='" . $_CONF['admin_html'] . "/add_user.php'>here</a> to create a new user</strong></center>";
            return $retval;
        }

        if(isset($_POST['unit']) && count($_POST['unit'] > 0))
        {
            $insert = array();

            foreach($_POST['unit'] as $unit)
            {
                $split = explode("-",$unit);
                $permission = $split[0];
                $battalion = $split[1];
                $company = $split[2];

                //ensure battalion/company pair that's passed is one that
                //the user has loaded and permission to access.
                if(isset($this->company[$battalion][$company]))
                { $insert[] = "(" . $this->user . "," . $battalion . "," . $company . "," . $permission . ")"; }
            }
            if($count = count($insert))
            { $inserts = implode(",",$insert); }

        }

        $result = mysql_query("lock tables user_permissions write") or die("failed to lock up (write)");
        $result = mysql_query("delete from user_permissions where user_id = " . $this->user) or die("failed to delete up");
        if($count)
        { $result = mysql_query("insert into user_permissions (user_id, battalion_id, company_id, permission_id) values " . $inserts) or die("insert user_permissions error: " . mysql_error()); }
        $result = mysql_query("unlock tables") or die("failed to unlock tables");

        unset($this->battalion);
        unset($this->company);

        $this->load_units();
        $this->load_user_permissions($this->user);

        $retval .= "<br><center><strong>user permissions updated</strong></center>";

        return $retval;
    }

    function user_info()
    {
        $retval = "";

        $result = mysql_query("select last_name, first_name, middle_initial, rank, right(ssn,4) as ssn "
                            ."from main where id = " . $this->user) or die("select user error: " . mysql_error());
        $row = mysql_fetch_array($result);

        $retval .= "<br><table border='1' width='70%' align='center' cellspacing='1' cellpadding='3'>\n";
        $retval .= "<tr>\n";
        $retval .= "<td class='table_heading'>Edit Permissions For: " . $row['last_name'] . ", " . $row['first_name'] . " " . $row['middle_initial'] . ", " . $row['rank'] . " - " . $row['ssn'] . "</td>\n";
        $retval .= "</tr>\n";
        $retval .= "</table>\n";

        return $retval;
    }
}
