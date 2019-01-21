<?
include('lib-common.php');
include($_CONF['path'] . 'classes/validate.class.php');
include($_CONF['path'] . 'classes/roster.class.php');
include($_CONF['path'] . 'smarty/Smarty.class.php');

$val = new Validate;
$smarty = new Smarty;
$smarty->template_dir = $_CONF['path'] . 'smarty/templates';
$smarty->compile_dir = $_CONF['path'] . 'smarty/templates_c';

echo com_siteheader("Dental Update");

//require permission 31 to access this page
if(!check_permission(31))
{
    echo "Invalid Permissions";
    echo com_sitefooter();
    exit();
}

//see if data has been submitted
if(isset($_POST['data']))
{
    //validate unit passed for Permission 31
    if($i = $val->unit($_POST["unit"],31))
    {
        $battalion = $i[0];
        $company = $i[1];
    }
    else
    { 
        echo "Invalid Permissions";
        echo com_sitefooter();
        exit();
    }
    
    //Get names of everyone in unit
    $res = mysql_query("select right(ssn,4) as ssn, last_name, first_name from main where battalion = $battalion and company = $company and pcs=0 and pers_type = 'permanent party'");
    while($r = mysql_fetch_assoc($res))
    { $roster[$r['ssn'].strtoupper($r['last_name']).strtoupper($r['first_name'])] = $r['last_name'] . ', ' . $r['first_name'] . '(' . $r['ssn'] . ')'; }
    
    //match the ssn, names, date, and dental cat in the data
    preg_match_all("%XX-([0-9]{4}) +([A-Z]+) ([A-Z]+)[ 1-5A-Z/]+ ([1-4]) +([0-9]{1,2})/([0-9]{1,2})/([0-9]{4})%",$_POST['data'],$match);
    /*
    $match[ ]
    1 = SSN
    2 = Last_Name
    3 = First_Name
    4 = Dental Cat
    5 = Month
    6 = Day
    7 = Year
    */
    $c = count($match[0]);
    
    //loop through results. 
    for($x=0;$x<$c;$x++)
    {
        $ssn = $match[1][$x];
        $ln = $match[2][$x];
        $fn = $match[3][$x];
        //see if current data row is in list of
        //personnel in unit
        if(isset($roster[$ssn.$ln.$fn]))
        {       
            //reformat date for MySQL
            $d = date('Ymd',mktime(0,0,0,$match[5][$x],$match[6][$x],$match[7][$x]));            
            $sql = "UPDATE main SET dental_category = {$match[4][$x]}, dental_date = $d WHERE RIGHT(ssn,4) = {$match[1][$x]} AND Last_Name ='{$match[2][$x]}' AND First_Name='{$match[3][$x]}'";
            //insert data
            $res = mysql_query($sql);
            if(mysql_error())
            { $result['bad'][] = "$ln, $fn ($ssn) -- " . mysql_error(); }
            else
            {
                //if insert was good, remove this name from $roster
                //the names left over in $roster at the end are
                //names that are not on the dental report
                unset($roster[$ssn.$ln.$fn]);
                $result['good'][] = "$ln, $fn ($ssn)";
            }
        }
        //the current name doesn't match one in this
        //unit. add to 'nfu' -> Not Found in USAP
        else
        { $result['nfu'][] = "$ln, $fn ($ssn)"; }
    }
    //left over names in $roster are not in
    //the dental report 'nfr' -> Not Found in Roster
    if(count($roster) > 0)
    {
        foreach($roster as $value)
        { $result['nfr'][] = $value; }
    }
}
//url of page
$url['action'] = $_SERVER['PHP_SELF'];

//sort names alphabetically
//@ will suppress warnings
@sort($result['good']);
@sort($result['nfu']);
@sort($result['nfr']);

//assign to template
$smarty->assign('url',$url);
$smarty->assign('result',$result);
$smarty->assign('unit_select',unit_select(31));

//display template
echo $smarty->fetch("dental_update.tpl");

//display footer
echo com_sitefooter();
?>
