<?
#######################################
#
# class to validate user input
#
# type => string matching a method name that validates input
# input => user input
# label => placed at beginning of error message. user it
#   to label what variable is being checked
# allow_empty = 1 => allow empty string as input
# format = 0 => do not format $input
#
# check() function returns validated input
# or false on error.
#
#######################################

class validate
{
    //initialize variables
    var $error;

    //constructor. declare errors to be an array
    function validate()
    {
        $this->error = array();
    }

    //main function to check input
    //type: must match method_name in class
    //input: user input to be validated against "type"
    //label: value is used in error message. should be set
    //  to something that the user can relate to a field or variable
    //allow_empty: zero means empty is not allowed, one means input can be empty
    //format: used in "type" method to determine if input should be formatted or not
    //  zero means do not format, one means format
    function check($type, $input, $label, $allow_empty=0, $format = 1)
    {
        //initialization
        $retval = false;

        //make sure "type" is a valid method in this class
        if(method_exists($this,$type))
        {
            //see if input is empty
            if(strlen($input) == 0)
            {
                //if empty is allowed, return empty, otherwise
                //set an error.
                if($allow_empty == 0)
                { $this->error[] = $label . ": Input must contain a value"; }
                else
                { $retval = ""; }
            }
            else
            {
                //call method that
                //matches what's in the variable $type
                //this does the specific validation for each type
                //the case of $type does not matter, mos is same as mos
                $retval = $this->$type($input,$label,$format);
            }
        }
        else
        {
            //a function named $type is not present. set error
            $this->error[] = "Type " . $type . " is not valid";
        }

        //return formatted input, or false on error
        return $retval;
    }

    //validates a run time for apft
    function run_time($input,$label,$format)
    {
        $retval = false;

        $match = "[1-9]+[0-9]?[:]?[0-5][0-9]";

        if(ereg($match,$input))
        { $retval = str_replace(":","",$input); }
        else
        { $this->error[] = $label . ": Incorrect format. Only numbers and possible : allowed."; }

        return $retval;
    }

    //verifies ssn is in xxxxxxxxx, xxx-xx-xxxx, or xxx xx xxxx format
    function ssn($input,$label,$format)
    {
        $retval = false;

        //define possible formats
        $match = "^[0-9]{3}[- ]{0,1}[0-9]{2}[- ]{0,1}[0-9]{4}$";

        //see if input matches criteria
        if(ereg($match,$input))
        {
            //if formatting is on, remove any spaces
            //or - from ssn
            if($format == 1)
            { $retval = ereg_replace("[- ]","",$input); }
            else
            { $retval = $input; }
        }
        else { $this->error[] = $label . ": Incorrect format. Only numbers, spaces, or a - are allowed"; }

        return $retval;
    }

    function name($input,$label,$format)
    {
        $retval = false;

        //allow a possible ', -, or space in name. ' will
        //be replaced with \' by magic_quotes upon
        //form submission (so we search for \\\')
        $match = "^[a-z]+([- ]{1}|(\\\'))?[a-z]+$";

        if(eregi($match,$input))
        {
            //format is capitalized first letter,
            //rest are lowercase.
            if($format == 1)
            {
                $retval = ucwords(strtolower($input));
                if(substr($retval,0,2) == 'Mc')
                { $retval{2} = strtoupper($retval{2}); }

                $retval = preg_replace('/([^a-zA-Z])([a-zA-z])/e','"\\1".strtoupper("\\2")',stripslashes($retval));

                $find = array(' Iii',' Ii',' Iv');
                $repl = array(' III',' II',' IV');
                $retval = str_replace($find,$repl,$retval);
            }
            else
            { $retval = $input; }
        }
        else
        { $this->error[] = $label . ": Incorrect format. Only letters, one space, and possible ' or - characters are allowed"; }

        return $retval;
    }

    function aword($input,$label,$format)
    {
        $retval = false;

        //allow a word with only alphabetical characters.
        if(eregi("[^a-z]",$input))
        { $this->error[] = $label . ": Incorrect format. Only letters allowed, no spaces"; }
        else
        {
            //format is capitalized first letter,
            //rest are lowercase.
            if($format == 1)
            { $retval = ucfirst(strtolower($input)); }
            else
            { $retval = $input; }
        }

        return $retval;
    }

    function sword($input,$label,$format)
    {
        $retval = false;

        //allow word with alphanumeric infomation
        if(eregi("[^a-z0-9_-]",$input))
        { $this->error[] = $label . ": Incorrect format. Allowable characters are letters, numbers, _, or -. No spaces allowed."; }
        else
        { $retval = $input; }

        return $retval;
    }

    function string($input,$label,$format)
    {
        //already know string has a value, if required,
        //so just return input
        return mysql_real_escape_string($input);
    }

    function number($input,$label,$format)
    {
        $retval = false;

        //match anything not a number
        if(ereg("[^0-9]",$input))
        { $this->error[] = $label . ": Incorrect format. Input can only contain numbers"; }
        else
        { $retval = $input; }

        return $retval;
    }

    function float($input,$label,$format)
    {
        $retval = false;

        //match a number with a possible period as a decimal point
        if(ereg("^[0-9]+[.]?[0-9]+$",$input))
        { $retval = $input; }
        else
        { $this->error[] = $label . ": Incorrect format. Input can only contain numbers and a possible period as a decimal point."; }

        return $retval;
    }

    function password($input,$label,$format)
    {
        global $_CONF;
        $retval = false;

        if(strlen($input) < $_CONF['min_password_length'])
        { $this->error[] = "Password must be at least " . $_CONF['min_password_length'] . " characters long."; }
        else
        { $retval = $input; }

        return $retval;
    }

    //check that date matches 17nov75 or 17nov1975 format.
    //if it does, returned as mysql format date yyyymmdd
    function date($input,$label,$format)
    {
        $retval = false;

        //defaults
        $month_array = array("jan"=>"01","feb"=>"02","mar"=>"03","apr"=>"04","may"=>"05","jun"=>"06","jul"=>"07","aug"=>"08","sep"=>"09","oct"=>"10","nov"=>"11","dec"=>"12");
        $days_in_month = array(31,28,31,30,31,30,31,31,30,31,30,31);

        //verify date matches 17nov75 or 17nov1975 format
        if(eregi("^([0-9]{1,2})[ -]?(jan|feb|mar|apr|may|jun|jul|aug|sep|oct|nov|dec)[ -]?((19|20)?[0-9]{2})$",$input,$regs))
        {
            //convert month to number
            $regs[2] = strtolower($regs[2]);
            $month = $month_array[$regs[2]];

            //make days two digits if it's not already
            $day = $regs[1];
            if($day < 10 && strlen($day) == 1) { $day = "0" . $day; }
            if($regs[3] < 100)
            {
                //account for 2 digit year
                //< 30 = 20xx
                //> 30 = 19xx
                if($regs[3] < 30) { $year = "20" . $regs[3]; }
                else { $year = "19" . $regs[3]; }
            }
            else { $year = $regs[3]; }

            //verify day does not exceed days in month
            if(($day <= $days_in_month[$month-1] && $day > 0) || ($day==29 && $month==2 && $year%4==0))
            {
                $retval = $year.$month.$day;
            }
            else
            {
                $this->error[] = $label . ": Date has invalid day";
            }
        }
        else
        {
            $this->error[] = $label . ": Date does not match format of 17NOV75 or 17NOV1975";
        }

        return $retval;
    }

    //validate military time of day to hhmm(ss) or hh:mm(:ss)
    function mtime($input,$label,$format)
    {
        $retval = false;

        if(ereg("^(0?[0-9]|1[0-9]|2[0-3]):?([0-5][0-9])(:?[0-5][0-9])?",$input,$regs))
        {
            $minute = $regs[2];

            //check for hour less than 10
            if(strlen($regs[1]) == 1)
            { $hour = "0" . $regs[1]; }
            else
            { $hour = $regs[1]; }

            $retval = $hour . $minute . "00";
        }
        else
        { $this->error[] = $label . ": Time does not match format: Hour is 0-23, Minute is 00-59"; }

        return $retval;
    }

    //make sure symbol for mos is valid
    function mos($input,$label,$format)
    {
        $retval = false;

        //mos must match 000z or 00z format.
        //mos letter is converted to uppercase
        //regardless of formatting setting
        if(eregi("^[0-9]{2,3}[a-z]{1}$",$input))
        { $retval = strtoupper($input); }
        else
        { $this->error[] = $label . ": Incorrect format. MOS should be two or three numbers followed by a single letter."; }

        return $retval;
    }

    //this function verifies the current user
    //has the correct permissions for the id of
    //the soldier being modified.
    //checks the battalion and company of the "id" soldier
    //against the battalion and company of the logged in
    //user with the permission of $permission
    //
    //error reporting can be turned off, in the case
    //that you just want to see if the id and permission
    //match, but don't want an error set.
    function id($id, $permission, $hide_error = 0)
    {
        $retval = false;
        $e = "";

        if($this->fk_constraint($id,"main","id") && $this->fk_constraint($permission,"permissions","permission_id"))
        {

            //query to check matching battalion and company
            //between main and user_permissions
            $query = "select 1 from main m, user_permissions up where m.battalion = up.battalion_id and m.company = up.company_id and m.id = " . $id . " and up.permission_id = " . $permission . " and up.user_id = " . $_SESSION["user_id"];
            $result = mysql_query($query);

            //check for query error.
            if(mysql_error())
            { $e = "ID validation query error."; }
            else
            {
                //if row is returned, id and permission match
                //return id, otherwise set error.
                if(mysql_num_rows($result) == 1)
                { $retval = $id; }
                else
                { $e = "Invalid soldier id and permission pair"; }
            }
        }

        //only set an error message is $hide_error is zero and
        //$e has something assigned to it.
        if($hide_error == 0)
        {
            if(strlen($e) > 0)
            { $this->error[] = $e; }
        }

        return $retval;
    }

    //validate user has permission
    //to add/edit/delete/view class information
    function cclass($class_id, $permission, $hide_error = 0)
    {
        $retval = false;
        $e = "";

        if($this->fk_constraint($class_id,"class","class_id") && $this->fk_constraint($permission,"permissions","permission_id"))
        {

            //query to check matching battalion and company
            //between main and user_permissions
            $query = "select 1 from class c, user_permissions up where c.battalion_id = up.battalion_id and c.company_id = up.company_id and c.class_id = " . $class_id . " and up.permission_id = " . $permission . " and up.user_id = " . $_SESSION["user_id"];
            $result = mysql_query($query);

            //check for query error.
            if(mysql_error())
            { $e = "Class_id validation query error."; }
            else
            {
                //if row is returned, id and permission match
                //return id, otherwise set error.
                if(mysql_num_rows($result) == 1)
                { $retval = $class_id; }
                else
                { $e = "Invalid class id and permission pair"; }
            }
        }

        //only set an error message is $hide_error is zero and
        //$e has something assigned to it.
        if($hide_error == 0)
        {
            if(strlen($e) > 0)
            { $this->error[] = $e; }
        }

        return $retval;
    }

    //this function should be called directly.
    //it will verify the $input is present in
    //the appropriate $_CONF array designated by $key
    function conf($input, $key, $label = "")
    {
        global $_CONF;

        $retval = false;

        //account for y/n checkboxes. if checkbox is
        //checked, value will be "y", if it's not checked, it'll
        //be empty, so make it equal to "n"
        if($key == "yn" && $input == "")
        { $input = "N"; }

        //ensure _CONF array is set with key $key
        if(isset($_CONF[$key]))
        {
            //see if $input is in the $_CONF array
            if(in_array($input,$_CONF[$key]))
            { $retval = $input; }
            else
            { $this->error[] = $key . " " . $label . ": Incorrect value"; }
        }
        else
        { $this->error[] = $key . ": Invalid CONF value"; }

        return $retval;
    }

    //this function validates that the $input value
    //is valid for the passed table and column. this
    //will prevent any values being entered that don't
    //match up in another table.
    //this function should be called on its own,
    //not with check()
    function fk_constraint($input, $table, $column, $allow_none = 0)
    {
        $retval = false;

        if($input == "none" && $allow_none == 1)
        { $retval = $input; }
        else
        {
            $result = mysql_query("select 1 from " . $table . " where " . $column . " = '" . $input . "'");

            if($e = mysql_error())
            { $this->error[] = $table . "." . $column . ": FK query error: " . $e; }
            else
            {
                if(mysql_num_rows($result) == 1)
                { $retval = $input; }
                    else
                { $this->error[] = $table . "." . $column . ": Foreign key constraint error."; }
            }
        }

        return $retval;
    }

    //this function validates the status of a soldier. it verifies
    //that the status id is valid from the status table and that
    //the personnel_type of the soldier matches the status type,
    //i.e. a student must be given a status that applies to students
    function status($status_id, $pers_type, $allow_none=0)
    {
        global $_CONF;
        $retval = false;

        if($allow_none == 1 && $status_id == "none")
        { $retval[0] = "0"; }
        else
        {
            if($this->fk_constraint($status_id, "status", "status_id") && $this->conf($pers_type,"pers_type","personnel type"));
            {
                if(in_array($pers_type,$_CONF["students"]))
                { $applies_to = "student"; }
                else
                { $applies_to = "permanent party"; }

                $result = mysql_query("select 1 from status where applies_to = '" . $applies_to . "' and "
                            ." status_id = " . $status_id);
                if($e = mysql_error())
                { $this->error[] = "Status check query error: " . $e; }
                else
                {
                    if(mysql_num_rows($result) == 1)
                    {
                        $retval[0] = $status_id;
                        $retval[1] = $pers_type;
                    }
                    else
                    {
                        $retval[0] = 0;
                        $retval[1] = $pers_type;
                    }
                }
            }
        }

        return $retval;
    }

    //this function validates that the current user has the
    //correct permissions to perform an action on the given unit.
    //this function, if validated, returns an array consisting of
    //[0] = battalion
    //[1] = company
    //this function also validates that battalion, company, and
    //permission are valid foreign key values and that the battalion-company
    //combo is a valid unit that someone is an administrator for.
    //
    //note: this function can be called with 2 - 4 parameters. unit
    //can be passed as a single string consisting of battalion_id and company_id,
    //seperated by an '-', or the battalion and company can be sent seperately.
    //permision_id should relate to one in the permissions table.
    //
    //usage: unit("3-4",12) -> battalion 3, company 4, permission 12
    //   unit(3,4,12)   -> battalion 3, company 4, permission 12
    //
    //The fourth paramter defaults to zero, but can be set to TRUE/1
    //to force the validation to fail if "All" is passed as the company.

    function unit()
    {
        //initialization
        $retval = false;
        $deny_all = 0;

        //determine number of arguments sent
        switch(func_num_args())
        {
            case 4:
                $deny_all = func_get_arg(3);
                $battalion = func_get_arg(0);
                $company = func_get_arg(1);
                $permission = func_get_arg(2);
            case 3:
                //Check for '-' in first argument
                $arg1 = func_get_arg(0);
                if(strstr($arg1,'-'))
                {
                    $sep = explode("-",$arg1);
                    $battalion = $sep[0];
                    $company = $sep[1];
                    $permission = func_get_arg(1);
                    $deny_all = func_get_arg(2);
                }
                else
                {
                    $battalion = func_get_arg(0);
                    $company = func_get_arg(1);
                    $permission = func_get_arg(2);
                }
            break;
            case 2:
                $unit = func_get_arg(0);
                $sep = explode("-",$unit);
                $battalion = $sep[0];
                $company = $sep[1];
                $permission = func_get_arg(1);
            break;
            //wrong number of argument sent, set error
            default:
                $this->error[] = "Unit: Invalid number of arguments to function.";
        }

        //ensure battalion, company, and permission are valid foreign key values
        $battalion = $this->fk_constraint($battalion,"battalion","battalion_id");
        $company = $this->fk_constraint($company,"company","company_id");
        if($permission)
        { $permission = $this->fk_constraint($permission,"permissions","permission_id"); }

        //Check if company is "ALL"
        if($deny_all && $company == 0)
        { $this->error[] = 'Invalid Company - Cannot select "All"'; }

        //If permission is zero, validate that
        //someone is administer for unit passed
        if($permission == 0)
        {
            $query = "select 1 from user_permissions where permission_id = 25 and company_id = $company and battalion_id = $battalion";
            if($result = mysql_query($query))
            {
                if(!mysql_num_rows($result))
                { $this->error[] = "Invalid Unit selected from Battalion and Company dropdowns."; }
            }
        }
        //ensure none of the values came out to false
        //before we submit the query
        elseif($permission !== false && $battalion !== false && $company !== false)
        {
            $query = "select 1 from user_permissions where user_id = " . $_SESSION["user_id"] . " and battalion_id = " . $battalion . " and company_id = " . $company . " and permission_id = " . $permission;
            $result = mysql_query($query);

            //if error in query, set error
            if(mysql_error())
            { $this->error[] = "Query error in validate::unit function: " . mysql_error(); }
            else
            {
                if(mysql_num_rows($result) == 1)
                {
                    //if one row is returned, set return array values
                    $retval[0] = $battalion;
                    $retval[1] = $company;
                }
                elseif(@$_POST['id'] == $_SESSION['user_id'] && $battalion == $_SESSION['battalion_id'] && $company == $_SESSION['company_id'] && $permission == 2)
                {
                    //if user is trying to edit (permission 2) themselves,
                    //return the unit as ok
                    $retval[0] = $battalion;
                    $retval[1] = $company;
                }
                //otherwise assume user does not have the correct
                //permissions for the given battalion and company
                else
                { $this->error[] = "User does not have permission " . $permission . " for Unit " . $battalion . "-" . $company . "."; }
            }
        }

        return $retval;
    }

    //validate that soldier identified by id should be
    //part of exodus
    function exodus($pers_type)
    {
        global $_CONF;
        $retval = false;

        //if(strtolower($_CONF['exodus']) == 'on' && in_array($pers_type,$_CONF['students']))
        if(strtolower($_CONF['exodus']) == 'on' && $pers_type == 'IET')
        {
            $now = time();
            $data_start = strtotime($_CONF['exodus_data_start']);
            $data_end = strtotime($_CONF['exodus_data_end']);

            //ensure time is between start and end, first
            if($now > $data_start && $now < $data_end)
            { $retval = TRUE; }
        }

        return $retval;
    }

    function akoemail($input,$label,$format)
    {
        $retval = false;

        if(eregi("[0-9a-z_.-]@us.army.mil",$input))
        { $retval = strtolower($input); }
        else
        { $this->error[] = "$label: Address must be AKO (username@us.army.mil) address"; }

        return $retval;

    }

    function iserrors()
    {
        //return a count of the errors
        if($c = count($this->error)) { return $c; } else { return false; }
    }

    function geterrors()
    {
        $retval = "";

        //if there are no errors, return nothing
        if($this->iserrors())
        {
            $retval = "<table class='error' border='0' cellpadding='0' cellspacing='0' align='center' width='80%'>\n"
                     ."<tr><td><u>The following errors were encountered:</u></td></tr>\n";

            $es = implode("</td></tr>\n<tr><td>",$this->error);

            $retval .= "<tr><td>$es</td></tr>\n";

            $retval .= "</table></font>\n";
        }

        return $retval;
    }

    //generates random password up to 13 characters long
    function generate_password($length)
    {
        $pass = uniqid("");

        if($length < 13)
        { $pass = substr($pass,0,$length); }

        return $pass;
    }

    function login($login)
    {
        $retval = false;

        if(strlen($login) == 0)
        {$this->error[] = "Login: Input must contain a value"; }
        else
        {
            $result = mysql_query("select 1 from users where login = '" . $login . "'");

            if($e = mysql_error())
            { $this->error[] = "User query error: " . $e; }
            else
            {
                if(mysql_num_rows($result) != 0)
                { $this->error[] = "Username already in use, choose another."; }
                else
                { $retval = $login; }
            }
        }

        return $retval;
    }

    function pop_error()
    {
        if(count($this->error)>0)
        { array_pop($this->error); }

        return TRUE;
    }

    //******************************************************//
    // FUNCTION TO VALIDATE PASSED ARRAY TO CONF VARIABLE   //
    // AND MAKE SQL STRING NECESSARY TO MATCH PASSED VALUES //
    //******************************************************//

    function conf_to_sql(&$value,$conf_key,$db_column,$tbl_prefix='',$sql_values_as_integer=0)
    {
        $valid_list = '';
        $retval = FALSE;
print_r($value);

        //Determine whether to create SQL values as strings
        //or integers. Integers do not have a delimiter, while
        //strings will use a single quotation mark.
        $delimiter = ($sql_values_as_integer) ? '' : "'";

        //If $value is an array, cycle through each element,
        //and validate against the passed $_CONF key. If valid,
        //add to list of valid values to be put into SQL string
        if(!empty($value) && is_array($value))
        {
            foreach($value as $k=>$v)
            {
                if($this->conf($v,$conf_key))
                { $valid_list .= $delimiter. $v . $delimiter . ','; }
            }
            //Strip off last comma
            $valid_list = substr($valid_list,0,-1);
        }
        //If value is not an array, validate against passed $_CONF key
        //and put into valid value list to be put into SQL string
        elseif(!empty($value))
        {
            if($this->conf($value,$conf_key))
            { $valid_list = $delimiter . $value . $delimiter; }
        }

        //If valid list is not empty, create SQL string to return
        if(!empty($valid_list))
        {
            //If table prefix is set and the last character is not
            //a period, add the period to the string.
            if(!empty($tbl_prefix) && substr($tbl_prefix,-1) != '.')
            { $tbl_prefix .= '.'; }
            $retval = " AND {$tbl_prefix}{$db_column} IN ($valid_list) ";
        }

        return $retval;

    }

}

?>
