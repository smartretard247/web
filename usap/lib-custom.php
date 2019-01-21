<?
//function to return current date in military format
function military_date($time) {
    return date("d",$time) . strtoupper(date("M",$time)) . date("Y",$time);
}

// Create drop down list with Batallions names and ID's
function battalion_select($name='battalion_id',$selected='')
{
    $result = mysql_query("select battalion, battalion_id from battalion order by battalion asc");

    $retval = "<select name='$name' size='1'>\n";
    while($row = mysql_fetch_row($result))
    {
        $retval .= "<option value='{$row[1]}'";
        if($row[1] == $selected) { $retval .= " selected"; }
        $retval .= ">{$row[0]}</option>\n"; }
    $retval .= "</select>\n";

    return $retval;
}
// Create drop down list with Units names and ID's
function company_select($name='company_id',$selected='')
{
    $result = mysql_query("select company, company_id from company where company != 'all' order by company asc");

    $retval = "<select name='$name' size='1'>\n";
    while($row = mysql_fetch_row($result))
    {
        $retval .= "<option value='{$row[1]}'";
        if($row[1] == $selected) { $retval .= " selected"; }
        $retval .= ">{$row[0]}</option>\n"; }
    $retval .= "</select>\n";

    return $retval;
}

//creates a select box filled with
//battalion-company listings that the user
//has access to.
function unit_select($permission,$battalion = '', $company = '')
{
    $edit_self = 0;
    $retval = "";

    if(is_array($permission))
    { $p = ' up.permission_id IN (' . implode(',',$permission) . ') '; }
    else
    { $p = " up.permission_id = $permission "; }
	
	$query = "SELECT b.battalion_id, b.battalion, c.company_id, c.company 
	          FROM battalion b, company c, user_permissions up 
			  WHERE up.battalion_id = b.battalion_id and up.company_id = c.company_id and $p and up.user_id = {$_SESSION['user_id']} 
			  GROUP BY b.battalion, c.company order by b.battalion, c.company" ;

    $result = mysql_query($query) or die("user select error: " . mysql_error());

    if(mysql_num_rows($result) > 0)
    {
        $retval .="<select size='1' name='unit' class='text_box'>\n";
        while($row = mysql_fetch_array($result))
        {
            $retval .="<option value='{$row['battalion_id']}-{$row['company_id']}' ";
            if("$battalion-$company" == "{$row['battalion_id']}-{$row['company_id']}")
            {   $retval .=" selected "; }
            $retval .=">$row[battalion] - $row[company]</option>\n";
            if($row['battalion_id'] == $_SESSION['battalion_id'] && $row['company_id'] == $_SESSION['company_id'])
            { $edit_self = 1; }
        }
        $retval .="</select>\n";
    }
    //if user is editing themselves, just echo current unit and do not
    //allow them to change it.
    if(isset($_GET['id']) && $_GET['id'] == $_SESSION['user_id'] && $edit_self == 0)
    {
        $retval = "";
        $id = (int)($_GET['id']);
        $result = mysql_query("select b.battalion_id, b.battalion, c.company_id, c.company from battalion b, company c, main m where m.id = " . $id . " and m.battalion = b.battalion_id and m.company = c.company_id") or die("edit self query error: " . mysql_error());
        $row = mysql_fetch_assoc($result);

        $retval .= $row['company'] . "-" . $row['battalion'] . "\n";;
        $retval .= "<input type='hidden' name='unit' value='" . $row['battalion_id'] . "-" . $row['company_id'] . "'>\n";
    }

    return($retval);
}

//create html select box filled with
//different possible status
//default is value passed to function
function status_select($value = '', $all = 0)
{
    $retval = '';

    $result = mysql_query("select max(length(status)) from status");
    $max_length = mysql_result($result,0);

    $result2 = mysql_query("select status_id,concat(rpad(status,$max_length,'.'),' (',type,' - ',applies_to,')') as c_status from status order by applies_to, type, status");
    $retval .="<select size='1' name='status' class='text_box'>\n";
    if($all == 1)
    { $retval .="<option value='all'>All</option>\n"; }

    while($row = mysql_fetch_array($result2))
    {
        $retval .='<option value="' . $row["status_id"] . '" ';
        if($value == $row["status_id"])
        {   $retval .=" selected "; }
        $retval .='>' . $row["c_status"] . '</option>\n';
    }
    $retval .="</select>\n";

    return($retval);
}

//create html select box filled with
//different possible status
//default is value passed to function
function status_select2($value = '', $applies_to="student", $type="active", $none = 0, $name='')
{
    global $_CONF;

    $matched_value = FALSE;

    $retval = '';
    if($applies_to != 'permanent party')
    { $applies_to = 'student'; }
    if($type != 'inactive')
    { $type = 'active'; }
    if(empty($name))
    {
        if($type == 'inactive')
        { $name = 'inact_status'; }
        else
        { $name = 'status'; }
    }

    if(isset($_CONF['status_select'.$applies_to.$type.$none]))
    { $retval = conf_select('status_select'.$applies_to.$type,$value,0,0,$name,1); }
    else
    {
        $result2 = mysql_query("select status_id,status from status where applies_to = '" . $applies_to . "' and type = '" . $type . "' order by status");
        $retval .="<select size='1' name='$name' class='text_box'>\n";
        //if($none)
        //{ $retval .= "<option value='none'>None (N/A)</option>"; }

        while($row = mysql_fetch_array($result2))
        {
            $_CONF['status_select'.$applies_to.$type][$row['status_id']] = $row['status'];
            $retval .="<option value='" . $row["status_id"] . "' ";
            if($value == $row["status_id"])
            {
                $retval .=" selected ";
                $matched_value = TRUE;
            }
            $retval .=">" . $row["status"] . "</option>\n";
        }
        $retval .="</select>\n";

        if($none)
        {
            $selected = ($matched_value) ? 0 : 1;
            $retval = add_option($retval,array('none'=>'None (N/A)'),$selected);
        }
    }

    return($retval);
}

//creates html select box filled with
//different types of remark subjects
//default is value passed to function
function subject_select($value = '')
{
    $retval = '';

    $result = mysql_query("select remarks_subjects_id, subject from remarks_subjects order by subject");

    $retval .="<select size='1' name='subject' class='text_box'>\n";
    while($row = mysql_fetch_array($result))
    {
        $retval .='<option value="' . $row["remarks_subjects_id"] . '" ';
        if($value == $row["remarks_subjects_id"])
        {   $retval .=" selected "; }
        $retval .='>' . $row["subject"] . '</option>\n';
    }
    $retval .="</select>\n";

    return($retval);
}

function driver_select($value = '')
{
    $retval = '';

    $result = mysql_query("select m.id, concat(m.last_name,', ',m.first_name,' ',IF(m.Middle_Initial='','',CONCAT(m.middle_initial,'. ')),m.rank,' - ',right(m.ssn,4)) AS name "
                        ."from main m, drivers d, user_permissions up WHERE m.id = d.id and "
                        ."up.user_id = {$_SESSION['user_id']} and "
                        ."up.permission_id = 29 and m.battalion = up.battalion_id and m.company = up.company_id "
                        ."group by m.id order by name asc");
    $retval .= "<select size='1' name='id' class='text_box'>\n";
    while($row = mysql_fetch_array($result))
    {
        $retval .= "<option value='{$row['id']}'";
        if($value == $row['id'])
        { $retval .= " selected"; }
        $retval .= ">{$row['name']}</option>\n";
    }
    $retval .= "</select>\n";

    return $retval;
}

function class_select($permission = 4,$value = '',$active_only=0)
{
    $retval = '';
    if($active_only)
    {
        $result = mysql_query("select c.class_id, c.class_number, c.mos from class c, student s, main m, user_permissions up where "
                ."c.battalion_id = up.battalion_id and c.company_id = up.company_id and up.permission_id = $permission "
                ."and c.inactive = 0 and up.user_id = '" . $_SESSION["user_id"] . "' and c.class_id = s.class_id and m.id = s.id "
                ."and m.pcs=0 group by c.class_id order by c.mos, c.class_number");
    }
    else
    {
        $result = mysql_query("select c.class_id, c.class_number, c.mos from class c, user_permissions up where "
                ."c.battalion_id = up.battalion_id and c.company_id = up.company_id and up.permission_id = $permission "
                ."and c.inactive = 0 and up.user_id = '" . $_SESSION["user_id"] . "' order by c.mos, c.class_number");
    }

    if(mysql_num_rows($result) > 0)
    {

        $retval .="<select size='1' name='class_id' class='text_box'>\n";
        $retval .="<option value='none'>None</option>\n";
        while($row = mysql_fetch_array($result))
        {
            $retval .='<option value="' . $row["class_id"] . '"';
            if($value == $row["class_id"])
            {   $retval .=" selected"; }
            $retval .='>' . $row["mos"] . ' -- ' . $row["class_number"] . "</option>\n";
        }
        $retval .="</select>\n";
    }

    return $retval;
}

//creates a html select drop down box from a
//$_CONF array matching the passed key
//if make_array == 1, then an [] will be appended
//onto the select name so that the results are an array
function conf_select($conf_key, $selected = "",$make_array=0,$make_multi=0,$name="",$use_keys=0)
{
    global $_CONF;
    $retval = "";

    if(isset($_CONF[$conf_key]))
    {
        if(strlen($name) == 0)
        { $name = $conf_key; }

        $retval .= "<select name='" . $name;

        if($make_array == 1) { $retval .= "[]"; }
        $retval .= "' class='text_box'";

        if($make_multi > 0)
        { $retval .= " multiple size='$make_multi'>\n"; }
        else
        { $retval .= " size='1'>\n"; }

        foreach($_CONF[$conf_key] as $key => $value)
        {
            $option_value = ($use_keys) ? $key : $value;
            $retval .= "<option value='" . $option_value . "'";
            if($option_value == $selected)
            { $retval .= " selected"; }
            $retval .= ">" . $value . "</option>\n";
        }

        $retval .= "</select>\n";
    }
    else
    {
        $retval = "invalid conf key";
    }
    return $retval;
}

//Adds in an <option> to the <select> box
//in the $select_text
function add_option($select_text, $option, $selected=0)
{
    $retval = $select_text;

    $selected = ($selected) ? ' selected' : '';

    if(is_array($option))
    { list($option_value,$option_display) = each($option); }
    else
    {
        $option_value = $option;
        $option_display = $option;
    }

    if(!empty($option))
    { $retval = preg_replace("/>/",">\n<option value=\"{$option_value}\"$selected>{$option_display}</option>",$select_text,1); }
    return $retval;
}

function add_attribute($select_text, $attribute)
{
    $retval = $select_text;

    if(!empty($attribute))
    { $retval = preg_replace("/>/"," $attribute>",$select_text,1); }

    return $retval;
}

function soldier_select($id,$permission_id)
{
    $retval = '';
    $id = (int)$id;
    $permission_id = (int)$permission_id;

    $result = mysql_query("SELECT m.id, CONCAT(m.last_name,', ',m.first_name,' ',m.middle_initial,' ',m.rank,' - ',right(m.ssn,4)) "
                    ."AS name FROM main m, user_permissions up WHERE up.user_id = {$_SESSION['user_id']} and up.permission_id = $permission_id "
                    ."and up.battalion_id = m.battalion and up.company_id = m.company order by name asc");

    $retval .= "<select name='id' size='1' class='text_box'>\n";
    while($row = mysql_fetch_assoc($result))
    {
        $retval .= "<option value='{$row['id']}'";
        if($id == $row['id']) { $retval .= " selected"; }
        $retval .= ">{$row['name']}</option>\n";
    }
    $retval .= "</select>\n";

    return $retval;
}

function show($text)
{ if($_SESSION['user_id'] == 1) echo $text; }

function early_bird()
{
    global $_CONF;

    $local_file = $_CONF['path'] . "templates/ebird.html";

    if(file_exists($local_file))
    { include($local_file); }
    else
    { echo "<strong>No current news found."; }

    return;
}

function tda_select($battalion, $company, $pers_type, $tda_id='', $type='',$name='')
{
    global $_CONF;
    global $val;
    $query = '';
    $where = '';
    $having = '';
    $columns = '';
    $tables = '';

    //See if user does not have permission to choose from
    //BDE wide TDA permissions, then limit to specific Battalion
    //and Company. Since an error is logged that we don't need,
    //the last error is "popped" or removed.
    if($val->unit($battalion,$company,33))
    {
        $columns .= ', concat(c.company,\'-\',if(0+b.battalion=0,b.battalion,0+b.battalion)) as Unit ';
        $tables .= ', battalion b, company c ';
        $where .= ' AND t.battalion_id = b.battalion_id AND t.company_id = c.company_id ';
    }
    else
    {
        if(empty($tda_id))
        { $where .= " AND t.battalion_id = $battalion AND t.company_id = $company "; }
        else
        {  $where .= " AND ((t.battalion_id = $battalion AND t.company_id = $company) OR ta.assigned_tda_id = $tda_id) "; }

        $val->pop_error();
    }

    //If personnel type is civilian, limit results
    //to those having a grade of 'GS'
    if($pers_type == 'Civilian')
    { $where .= " AND t.br = 'GS' "; }
    else
    { $where .= " AND t.br != 'GS' "; }

    //When type is "working", we're looking for the complete list
    //of TDA positions without caring whether they are filled or
    //not. Otherwise we only want to retrieve rows that are not
    //yet filled or matching the specific ID that was passed.
    if($type=='working')
    {
        if(empty($name))
        { $name = 'working_tda_id'; }
    }
    else
    {
        $having = ' HAVING remaining > 0 ';
        if(empty($name))
        { $name = 'assigned_tda_id'; }

        if(!empty($tda_id))
        {
            $where .= " AND ta.assigned_tda_id = $tda_id ";
            $having = '';
        }
    }

    $query = "SELECT t.tda_id, t.para, t.ln, t.section, t.position, t.gr, t.auth - COUNT(ta.assigned_tda_id) AS remaining {$columns}
              FROM tda t LEFT JOIN tda_assigned ta ON t.tda_id = ta.assigned_tda_id $tables
              WHERE t.year = {$_CONF['tda_year']} $where GROUP BY t.tda_id $having
              ORDER BY t.tda_id ASC";

    $result = mysql_query($query) or die("Error getting TDA positions: ($query)" . mysql_error());

    $retval = "<select name=\"$name\" size=\"1\">
                 <option value=\"0\">Excess</option>";

    while($row = mysql_fetch_assoc($result))
    {
        if(isset($row['Unit']))
        { $unit = " ({$row['Unit']})"; }
        else
        { $unit = ''; }

        if(is_numeric($row['gr']))
        { $row['gr'] = 'GS' . $row['gr']; }

        $retval .= "<option value=\"{$row['tda_id']}\"";
        if($row['tda_id'] == $tda_id)
        { $retval .= ' selected'; }
        $retval .= ">{$row['para']}/{$row['ln']}{$unit} - {$row['section']} - {$row['position']}/{$row['gr']} ({$row['remaining']})</option>";
    }

    $retval .= "</select>";

    return $retval;
}

function permission_select()
{
	$retval = '<select name="permission_id[]" multiple size="5">';
	$query = "SELECT permission_id, permission FROM permissions ORDER BY permission_id ASC";
	$result = mysql_query($query);
	while($r = mysql_fetch_assoc($result))
	{ $retval .= "<option value=\"{$r['permission_id']}\">{$r['permission']}</option>\n"; }
	$retval .= '</select>';
	return $retval;
}
?>