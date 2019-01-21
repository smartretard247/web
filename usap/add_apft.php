<?

#################################################
#
# this file allows you to add apft scores
#
#################################################

//include common files
include("lib-common.php");
include($_CONF["path"] . "classes/validate.class.php");

if(isset($_POST['data_sheet']))
{ header("Location: {$_CONF['html']}/data_sheet.php?id={$_REQUEST['id']}"); exit(); }
if(isset($_POST['view']))
{ header("Location: {$_CONF['html']}/apft.php?id={$_REQUEST['id']}"); exit(); }

//if user does not have
//permissions to add apft scores
//display error and exit
if(!check_permission(7))
{
    echo com_siteheader("incorrect permissions");
    echo "you do not have permissions to add apft scores";
    echo com_sitefooter();
    exit();
}

//if type was chosen, save to cookie
//to use next time the page is loaded
if(isset($_POST["apft_type"]))
{
    setcookie("apft_type",$_POST["apft_type"],time()+604800);
    $_COOKIE["apft_type"] = $_POST["apft_type"];
}
elseif(!isset($_COOKIE["apft_type"]))
{ $_COOKIE["apft_type"] = "diagnostic"; }

//default values
$insert = false; //do not insert unless criteria are matched
$alt = false; //do not use alternate event unless criteria are matched
$options = "";
$javascript = "";

//display header
echo com_siteheader("add apft");

//determine if form was submitted
if(isset($_POST["apft_submit"]))
{
    //new validation object
    $val = new validate;

    $input['pu_exempt'] = 0;
    $input['su_exempt'] = 0;
    
    //ensure that user has permission to add apft
    //scores for the passed soldier id
    $input["id"] = $val->id($_POST["id"],7);
    //validate height, weight, type, and date
    $input['height'] = $val->check("number",$_POST["height"],"height");
    $input['weight'] = $val->check("number",$_POST["weight"],"weight");
    $input['apft_type'] = $val->conf($_POST["apft_type"],"apft_type");
    $input['date'] = $val->check("date",$_POST['date'],"date");
    $input['alt_event'] = $val->conf($_POST['alt_event'],'alt_event');
    
    if(isset($_POST['age']) && $_POST['age'] != "")
    {
        if($_POST['age'] < 70 && $_POST['age'] > 16)
        { $input['age'] = (int)$_POST['age']; }
        else
        { $val->error[] = "Age: Incorrect format or value (between 17 and 70)"; }
    }
    else
    { $input['age'] = ""; }


    //validate raw scores
    $total_events = 3;
    if(isset($_POST['exempt_pu']))
    {
        $total_events -= 1;
        $input['raw_pu'] = 0;
        $input['pu_score'] = 0;
        $input['pu_exempt'] = 1;
    }
    else
    { $input['raw_pu'] = $val->check("number",$_POST["raw_pu"],"raw pushups"); }
    
    if(isset($_POST['exempt_su']))
    {
        $total_events -= 1;
        $input['raw_su'] = 0;
        $input['su_score'] = 0;
        $input['su_exempt'] = 1;
    }
    else
    { $input['raw_su'] = $val->check("number",$_POST["raw_su"],"raw situps"); }

    if(isset($_POST['dnf']))
    {
        if(strlen($_POST['raw_run']) > 0)
        { $val->error[] = "raw run and dnf cannot both be given"; }
        else
        { $input['raw_run'] = 9999; }
    }
    else
    { $input['raw_run'] = $val->check("run_time",$_POST['raw_run'],"raw run"); }
  
    //show errors if some were raised
    if($val->iserrors())
    {
        $msg = $val->geterrors();
    }
    else
    {
        if($input['age'] > 0)
        {
            $age_query = "select a.category, m.gender, m.rank,{$input['age']} from age_categories a, main m "
                        ."where ({$input['age']} between a.min_age and a.max_age) and m.id = {$input['id']}";
        }
        else
        {
            //get age category, gender, rank, and age from main table
            $age_query =    "select "
                    ."a.category, m.gender,m.rank, year(current_date) - year(dob) - (if(dayofyear(dob)>dayofyear(current_date),1,0)) "
                    ."as age from age_categories a, main m "
                    ."where "
                    ."(year(current_date) - year(dob) - (if(dayofyear(dob)>dayofyear(current_date),1,0)) between a.min_age "
                    ."and a.max_age) and m.id = " . $input["id"];
        }

        $age_result = mysql_query($age_query) or die("age category select error [$age_query]: " . mysql_error());

        list($age_category,$gender,$rank,$age) = mysql_fetch_row($age_result);
        if($gender == "M") { $gender = "male"; } else { $gender = "female"; }

        //determine pu score
        if($input["raw_pu"] > 0)
        {
            $pu_query = "select age" . $age_category . " from ".$gender."_pushups where repetitions <= " . $input["raw_pu"] . " order by age" . $age_category . " desc limit 1";
            $pu_result = mysql_query($pu_query) or die("pu select error [$pu_query]: " . mysql_error());
            $input['pu_score'] = mysql_result($pu_result,0);
        }
        else { $input['pu_score'] = 0; }

        //determine su score
        if($input["raw_su"] > 0)
        {
            $su_query = "select age" . $age_category . " from ".$gender."_situps where repetitions <= " . $input["raw_su"] . " order by age" . $age_category . " desc limit 1";
            $su_result = mysql_query($su_query) or die("su select error [$su_query]: " . mysql_error());
            $input['su_score'] = mysql_result($su_result,0);
        }
        else { $input['su_score'] = 0; }

        //determine run score
        if($input["raw_run"] > 0)
        {
            if($_POST['alt_event'] != 'N/A')
            {
                if($input['raw_run'] <= $_CONF['alt_scores'][$_POST['alt_event']][$gender][$age_category-1])
                { $input['run_score'] = 60; }
                else
                { $input['run_score'] = 0; }
            }
            else
            {
                $run_query = "select age" . $age_category . " from ".$gender."_run where time >= " . $input["raw_run"] . " order by time asc limit 1";
                $run_result = mysql_query($run_query) or die("run select error [$run_query]: " . mysql_error());
                $input['run_score'] = mysql_result($run_result,0);
            }
        }
        else { $input['run_score'] = 0; }

        //check and see if input type is bct and
        //set passing score to 50 in each event
        if(substr($input['apft_type'],0,3) == "BCT")
        { $passing = 49; }
        else
        { $passing = 59; }
       
        //add up score and see if score passes
        $input['total_score'] = $input['pu_score'] + $input['su_score'] + $input['run_score'];
        $pass = 0;
        if($input['pu_score'] > $passing) { $pass += 1; }
        if($input['su_score'] > $passing) { $pass += 1; }
        if($input['run_score'] > $passing) { $pass += 1; }
        if($pass == $total_events) { $input['pf'] = "pass"; } else { $input['pf'] = "fail"; }

        $insert_query =     "insert into apft "
                        ."(id,type,raw_pu,pu_score,raw_su,su_score,raw_run,run_score,"
                        ."alt_event,total_score,age,date,height,weight,rank, pass_fail,pu_exempt, su_exempt) "
                    ."values "
                        ."('" . $input["id"] . "','" . $input["apft_type"] . "','" . $input["raw_pu"] . "',"
                        ."'" . $input['pu_score'] . "','" . $input["raw_su"] . "','" . $input['su_score'] . "',"
                        ."'" . $input["raw_run"] . "','" . $input['run_score'] . "','" . $input["alt_event"] . "',"
                        .$input['total_score'] . ","
                        ."$age,'" . $input['date'] . "','" . $input["height"] ."','" . $input["weight"]
                        . "','$rank','" . $input['pf'] . "',{$input['pu_exempt']},{$input['su_exempt']})";

        $insert_result = mysql_query($insert_query) or die("apft insert error [$insert_query]: " . mysql_error());

        //update main table with new height and weight
        $hw_query = "update main set height = " . $_POST["height"] . ", weight = " . $_POST["weight"] . " where id = " . $_POST["id"];
        $hw_result = mysql_query($hw_query) or die("update height/weight query error [$hw_query]: " . mysql_error());

        if($input['raw_run'] == 9999)
        { $input['raw_run'] = 'DNF'; }

        $msg = "<p class='notice'>Added</font> -- PU: " . $input["raw_pu"] . " (" . $input['pu_score'] . "), SU: " . $input["raw_su"] . " (" . $input['su_score'] . "), ";
        $msg .= "Run/Alt: " . $input["raw_run"] . " (" . $input['run_score'] . ") ";
        $msg .= "-- Total: " . $input['total_score'] . " (" . strtoupper($input['pf']) . ")</p>\n";

        unset($_POST);
    }
}

$name_query =   "select "
            ."m.id, m.last_name, m.first_name, m.height, m.weight "
        ."from "
            ."main m, user_permissions up "
        ."where "
            ."m.battalion = up.battalion_id and m.pcs=0 and m.company = up.company_id "
            ."and up.user_id = '" . $_SESSION["user_id"] . "' and up.permission_id = 7 "
        ."order by "
            ."m.last_name, m.first_name";

$name_result = mysql_query($name_query) or die("name select error [$name_query]: " . mysql_error());
if(mysql_num_rows($name_result) > 0)
{
    while($name_row = mysql_fetch_array($name_result))
    {
        $options .= "<option value='" . $name_row["id"] . "'";
        if(isset($_REQUEST["id"]) && $_REQUEST["id"] == $name_row["id"]) { $options .= " selected "; }
        $options .= ">" . $name_row["last_name"] . ", " . $name_row["first_name"] . "</option>\n";
        $javascript .= "db_height[" . $name_row["id"] . "] = " . $name_row["height"] . "; db_weight[" . $name_row["id"] . "] = " . $name_row["weight"] . ";\n";
    }
}

//turn down error reporting to elimnate
//notices from null values returned from
//database
error_reporting(E_ERROR | E_WARNING | E_PARSE);

if(isset($msg)) { echo $msg; }
include($_CONF["path"] . "templates/add_apft.inc.php");

echo com_sitefooter();

?>
