<?
function set_access_permission($permission_id)
{
    global $_CONF;
    
    //Determine if user has logged in or timeout
    if(!isset($_SESSION["user_id"])) { $error = 1; }
    //Determine if user has necessary permission id
    elseif(!isset($_SESSION['permissions']) || !in_array($permission_id,$_SESSION["permissions"])) { $error = 2; }   
    //Ensure IP of user matches IP stored when user logged into system. Otherwise log them off
    else  {
        $query = "SELECT 1 FROM users WHERE user_id = {$_SESSION['user_id']} AND IP = '{$_SERVER['REMOTE_ADDR']}'";
        $result = mysql_query($query) or die("Error checking IP address: " . mysql_error());
        if(!mysql_num_rows($result)) { $error = 6; }
    }
    if(isset($error)) {
        $qs = (isset($_SERVER['QUERY_STRING'])) ? $_SERVER['QUERY_STRING'] : '';
        $_SESSION["redirect_to"] = $_CONF['web'] . $_SERVER['SCRIPT_NAME'] . '?' . $qs;
        header("Location: " . $_CONF['html'] . "/login.php?error=$error");
        exit();
    } // end if
    return 1;
} // end function

function check_permission()
{
    $args = func_GET_args();  // determine how many arguments were sent thru check_permissions
    $num_args = count($args);
    $return = 0;

    for($x=0;$x<$num_args;$x++)
    {
        if(in_array($args[$x],$_SESSION["permissions"])) { //check if argument items exists in the permissions list
            $return++;
        } // end if
    } //end for

    return $return;
}  // end function
?>