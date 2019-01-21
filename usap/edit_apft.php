<?
//configuration values
require("lib-common.php");
//validation routine
require($_CONF["path"] . "classes/validate.class.php");

//see if view was chosen in com_choosesoldier
//and redirect to view page with chosen id
if(isset($_GET["com_cs_action"]) && $_GET["com_cs_action"] == "view")
{
    header("location: " . $_CONF["html"] . "/apft.php?id=" . $_GET["id"]);
    exit();
}

//create new validation object
$val = new validate;

//default values
$update = false; //do not update unless criteria are matched
$alt = false; //do not use alternate event unless criteria are matched
$javascript = "";
$options = "";

if(isset($_POST["type"]))
{
    setcookie("apft_type",$_POST["type"],time()+604800);
    $_COOKIE["apft_type"] = $_POST["type"];
}
elseif(!isset($_COOKIE["apft_type"]))
{
    $_COOKIE["apft_type"] = "diagnostic";
}

//display site header
$display = com_siteheader("Edit APFT");
$display .= com_choosesoldier("apft");
echo $display;

//check id passed to page to ensure user
//has permission to edit
if(isset($_REQUEST["id"]) && !$val->id($_REQUEST["id"],8))
{
    echo "You do not have permission to edit APFT for this soldier.";
    echo com_sitefooter();
    exit();
}

if(isset($_POST['delete']))
{
    if($input['apft_id'] = $val->fk_constraint($_POST['apft_id'],"apft","apft_id"))
    {
        if($val->id($_REQUEST["id"],9))
        {
            $query = "delete from apft where apft_id = " . $input['apft_id'];
            $result = mysql_query($query) or die("delete apft error: " . mysql_error());
            echo "<div class='notice'>APFT Deleted</div>";
            unset($_POST);
        }
        else
        { echo "Invalid Permissions."; }
    }
    else
    { echo "Invalid APFT Selection."; }
}
elseif(isset($_POST["apft_submit"]))
{
    $input['pu_exempt'] = 0;
    $input['su_exempt'] = 0;
    
    $input['apft_id'] = $val->fk_constraint($_POST["apft_id"],"apft","apft_id");
    //ensure that user has permission to edit apft
    //scores for the passed soldier id
    $input["id"] = $val->id($_POST["id"],8);
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

        $update_query = "update apft "
                ."set "
                    ."date = '" . $input["date"] . "',type = '" . $input["apft_type"] . "',raw_pu = '" . $input["raw_pu"] . "',"
                    ."raw_su = '" . $input["raw_su"] . "',raw_run = '" . $input["raw_run"] . "',su_score = '" . $input['su_score'] . "',"
                    ."pu_score = '" . $input['pu_score'] . "',run_score = '" . $input['run_score'] . "',alt_event = '" . $input["alt_event"] . "',"
                    ."total_score = '" . $input['total_score'] . "',age = '" . $age . "',"
                    ."height = '" . $input["height"] . "',weight = '" . $input["weight"] . "',rank = '" . $rank . "', pass_fail = '" . $input['pf'] . "', "
                    ."pu_exempt={$input['pu_exempt']}, su_exempt={$input['su_exempt']} "
                ."where "
                    ."apft_id = " . $input["apft_id"];
        $update_result = mysql_query($update_query) or die("apft update error [$update_query]: " . mysql_error());

        //update main table with new height and weight
        $hw_query = "update main set height = " . $input["height"] . ", weight = " . $input["weight"] . " where id = " . $input["id"];
        $hw_result = mysql_query($hw_query) or die("update height/weight query error [$hw_query]: " . mysql_error());

        if($input['raw_run'] == 9999)
        { $input['raw_run'] = 'DNF'; }

        $msg = "<p class='notice'>Updated -- PU: {$input['raw_pu']}({$input['pu_score']}), "
              ."SU: {$input['raw_su']}({$input['su_score']}), Run/Alt: {$input['raw_run']}"
              ."({$input['run_score']}) ";
        $msg .= "-- Total: {$input['total_score']}(" . strtoupper($input['pf']) . ")</p>\n";

        echo "<center>" . $msg . "</center>";

        unset($_POST);
        unset($msg);
    }
}

if(isset($_REQUEST["id"]))
{
    $name_query =   "select "
                ."m.first_name, m.last_name, a.apft_id, a.type, if(a.raw_pu=0,'',a.raw_pu) as raw_pu,"
                ."if(a.raw_su=0,'',a.raw_su) as raw_su, if(a.raw_run=0,'',a.raw_run) as raw_run, "
                ."a.alt_event, a.total_score, a.age, "
                ."upper(date_format(a.date,'%d%b%y')) as date, a.height, a.weight, a.pu_exempt, a.su_exempt "
            ."from "
                ."main m, apft a "
            ."where "
                ."m.id = a.id and m.id = '" . $_REQUEST["id"] . "' "
            ."order by "
                ."a.date desc";

    $name_result = mysql_query($name_query) or die("name select error [$name_query]: " . mysql_error());
    if($name_row = mysql_fetch_array($name_result))
    {
        $name = $name_row["last_name"] . ", " . $name_row["first_name"];

        do
        {
            $options .= "<option value='" . $name_row["apft_id"] . "'";
            if(isset($_POST["apft_id"]) && $_POST["apft_id"] == $name_row["apft_id"]) { $options .= " selected "; }
            $options .= ">" . $name_row["date"] . "</option>\n";
            $javascript .=   "db_height[" . $name_row["apft_id"] . "] = '" . $name_row["height"] . "';\n"
                    ."db_weight[" . $name_row["apft_id"] . "] = '" . $name_row["weight"] . "';\n"
                    ."db_type[" . $name_row["apft_id"] . "] = '" . $name_row["type"] . "';\n"
                    ."db_pu[" . $name_row["apft_id"] . "] = '" . $name_row["raw_pu"] . "';\n"
                    ."db_su[" . $name_row["apft_id"] . "] = '" . $name_row["raw_su"] . "';\n"
                    ."db_run[" . $name_row["apft_id"] . "] = '" . $name_row["raw_run"] . "';\n"
                    ."db_alt_event[" . $name_row["apft_id"] . "] = '" . $name_row["alt_event"] . "';\n"
                    ."db_date[" . $name_row["apft_id"] . "] = '" . $name_row["date"] . "';\n"
                    ."db_age[" . $name_row['apft_id'] . "] = " . $name_row['age'] . ";\n"
                    ."db_exempt_pu[" . $name_row['apft_id'] . "] = " . $name_row['pu_exempt'] . ";\n"
                    ."db_exempt_su[" . $name_row['apft_id'] . "] = " . $name_row['su_exempt'] . ";\n";
        }while($name_row = mysql_fetch_array($name_result));

        //turn down error reporting to elimnate
        //notices from null values returned from
        //database
        error_reporting(E_ERROR | E_WARNING | E_PARSE);
        if(isset($msg)) { echo $msg; }
        require($_CONF["path"] . "templates/edit_apft.inc.php");
    }
    else
    {
        echo "No APFT for this Soldier in USAP";
    }
}

echo com_sitefooter();
?>
