<?
#######################################
#
# the page will add soldiers into the database.
# it handles new additions, error checking and
# displaying, and the actual submission
#
# 1. when the page is first viewed, $_POST is not set
#    so an empty form is displayed
# 2. when the form is submitted, $_POST is set
#    and the values are checked to ensure the required
#    values are present and they are in the correct format
# 3. if no errors are encounted, the data is submitted to
#    the database and an empty form is displayed again
# 4. if errors are encounted, error messages are displayed
#    and the form is displayed again containing the
#    original (unvalidated) data the user entered.
#
#######################################

include("lib-common.php");

//variable initialization
$error = array();
$msg = "";

//ensure user has "add soldier" permission
//otherwise display notice
if(!check_permission(1))
{
    echo com_siteheader("incorrect permissions");
    echo "<center>you do not have the correct permissions to add a soldier</center>\n";
    //show footer and exit script. do not show add soldier form.
    echo com_sitefooter();
    exit();
}

//if user has submitted form, process values
if(isset($_POST["submit"]))
{
    //validation class definition
    include($_CONF["path"] . "classes/validate.class.php");

    //create new validation class
    $val = new validate;

    //default variables
    $main_record_id = 0;
    $error = array();
    $inserts = array();
    $input = array();

    //check for soldier living off post
    if(isset($_POST["off_POST"]))
    {
        $_POST["building_number"] = "Off_Post";
    }

    //validate user input

    //Validate user has permission to add to chosen
    //unit and ensure company passed is not "All"
    if($i = $val->unit($_POST["unit"],1,1))
    {
        $input["battalion"] = $i[0];
        $input["company"] = $i[1];
    }

    //validate ssn
    $input["last_name"] = $val->check("name",$_POST["last_name"],"last name");
    $input["first_name"] = $val->check("aword",$_POST["first_name"],"first name");
    $input["middle_initial"] = $val->check("aword",substr($_POST["middle_initial"],0,1),"middle_initial",1);
    $input["ssn"] = $val->check("ssn",$_POST["ssn"],"ssn");
    $input["gender"] = $val->conf($_POST["gender"],"gender");
    $input["rank"] = $val->conf($_POST["rank"],"rank");

    $input['promotable'] = (isset($_POST['promotable'])) ? "(P)" : '';

    $input["ets"] = $val->check("date",$_POST["ets"],"ets",1);
    $input["mos"] = $val->conf($_POST["mos"],"mos");
    $input["component"] = $val->conf($_POST["component"],"component");

    $input['location'] = $val->conf($_POST['location'],'location');
    if($input["location"] != "Organic")
    {
        $input['detached_bn']   = $val->fk_constraint($_POST['detached_bn'],'battalion','battalion_id');
        $input['detached_co']   = $val->fk_constraint($_POST['detached_co'],'company','company_id');
        $input['attached_bn']   = $val->fk_constraint($_POST['attached_bn'],'battalion','battalion_id');
        $input['attached_co']   = $val->fk_constraint($_POST['attached_co'],'company','company_id');
        $input["effective"]     = $val->check("date",$_POST["assigned_date"],"assigned date");
        $input["position"]      = $val->check("string",$_POST["assigned_position"],"assigned position");
        $input["reason"]        = $val->check("string",$_POST["assigned_reason"],"assigned reason");

        if($input['location'] == 'Detached')
        {
            if($input['battalion'] != $input['detached_bn'] || $input['company'] != $input['detached_co'])
            { $val->error[] = "Detached From and Unit must match when Location is set to Detached"; }
            else
            {
                if(!($input['detached_bn'] == 5 || $input['detached_co'] == 10))
                {
                    $input['location'] = 'Attached';
                    $input['battalion'] = $input['attached_bn'];
                    $input['company'] = $input['attached_co'];
                }
            }
        }
        elseif($input['location'] == 'Attached')
        {
            if($input['battalion'] != $input['attached_bn'] || $input['company'] != $input['attached_co'])
            { $val->error[] = "Attached To and Unit must match when Location is set to Attached"; }
        }
    }
    $input["date_entered_service"] = $val->check("date",$_POST["date_entered_service"],"date entered service",1);
    $input["us_citizen"] = $val->conf($_POST["us_citizen"],"yn","us citizen");
    $input["cac"] = $val->conf($_POST["cac"],"yn","Issued CAC");

    $input["platoon"] = $val->conf($_POST["platoon"],"platoon");
    $input["building_number"] = $val->check("sword",strtoupper($_POST["building_number"]),"building number",0,0);
    $input["arrival_date"] = $val->check("date",$_POST["arrival_date"],"arrival date");
    if($i = $val->status($_POST["status"],$_POST["pers_type"]))
    {
        if($i[0] == 0)
        {
            $val->error[] = "Invalid status selection for chosen Personnel Type";
            $input['status'] = '';
            $input['pers_type'] = '';
        }
        else
        {
            $input["status"] = $i[0];
            $input["pers_type"] = $i[1];
        }
    }

    $allow_empty_dor = ($input['pers_type'] == 'Permanent Party' && time() > strtotime("January 22nd, 2003"))? 0 : 1;
    $input['dor']           = $val->check("date",$_POST['dor'],'Date of Rank',$allow_empty_dor);

    $input["status_remark"] = $val->check("string",$_POST["status_remark"],"status remark",1);
    $input["blood_type"] = $val->conf($_POST["blood_type"],"blood_type");
    $input["dental_category"] = $val->conf($_POST["dental_category"],"dental_category");
    $input["hiv_date"] = $val->check("date",$_POST["hiv_date"],"hiv date",1);
    $input["height"] = $val->check("number",$_POST["height"],"height");
    $input["weight"] = $val->check("number",$_POST["weight"],"weight");
    $input["hair_color"] = $val->check("aword",$_POST["hair_color"],"hair color");
    $input["eye_color"] = $val->check("aword",$_POST["eye_color"],"eye color");
    $input["marital_status"] = $val->conf($_POST["marital_status"],"marital_status");
    $input["num_dependents"] = $val->check("number",$_POST["num_dependents"],"num dependents");

    if($input["dob"] = $val->check("date",$_POST["dob"],"dob"))
    {
        if(strtotime($input['dob']) > strtotime('16 years ago'))
        { $val->error[] = 'Invalid Date of Birth'; }
    }

    $input["religion"] = $val->conf($_POST["religion"],"religion");
    $input["education"] = $val->conf($_POST["education"],"education");
    $input["colleges"] = $val->check("string",$_POST["colleges"],"colleges",1);
    $input["special_skills"] = $val->check("string",$_POST["special_skills"],"special skills",1);
    $input["race"] = $val->conf($_POST["race"],"race");
    $input["sports"] = $val->check("string",$_POST["sports"],"sports",1);
    $input["email"] = $val->check("akoemail",$_POST["email"],"email",1);

    //check to see if any pov info was given. if so, all of it must be given
    if(strlen($_POST["pov_make"] . $_POST["pov_model"] . $_POST["pov_year"] . $_POST["pov_state"] . $_POST["pov_tag"]) > 0)
    { $pov_empty_allowed = 0; } else {$pov_empty_allowed = 1; }

    $input["pov_make"] = $val->check("string",$_POST["pov_make"],"pov make",$pov_empty_allowed);
    $input["pov_model"] = $val->check("string",$_POST["pov_model"],"pov model",$pov_empty_allowed);
    $input["pov_year"] = $val->check("number",$_POST["pov_year"],"pov year",$pov_empty_allowed);
    $input["pov_state"] = $val->check("aword",strtoupper($_POST["pov_state"]),"pov state",$pov_empty_allowed);
    $input["pov_tag"] = $val->check("string",strtoupper($_POST["pov_tag"]),"pov tag",$pov_empty_allowed);

    //if any profile was selected, require all dates and reason to be given
    $input["profile"] = $val->conf($_POST["profile"],"profile");
    if($input["profile"] != $_CONF["profile"][0])
    {
        //check for permanent profile. if so, do not require
        //end dates
        if($input['profile'] != $_CONF['profile'][1])
        { $allow_empty_profile_data = 1; }
        else
        { $allow_empty_profile_data = 0; }

        $input["profile_start"] = $val->check("date",$_POST["profile_start"],"profile start");
        $input['profile_length']= $val->check('number',$_POST['profile_length'],'Profile Length',$allow_empty_profile_data);
        if(!$input['profile_length']) { $input['profile_length'] = 0; }
        $input["profile_reason"]= $val->check("string",$_POST["profile_reason"],"profile reason");
        $input['profile_limitations'] = $val->check("string",$_POST['profile_limitations'],'Profile Limitations',1);
    }
    else
    { unset($input['profile']); }

    //loop through 3 addresses. if type is not set to none, then most of the information is required
    $at_least_one_address = 0;
    $num_addresses = count($_POST["address_type"]);
    for($x=0;$x<$num_addresses;$x++)
    {
        //ensure address type is not set to none
        if($_POST["address_type"][$x] != "None")
        {
            $at_least_one_address = 1;
            $input["address_type"][$x] = $val->conf($_POST["address_type"][$x],"address_type");
            $input["name"][$x] = $val->check("string",$_POST["name"][$x],"address #" . ($x+1) . ": name ",1);
            $input["relationship"][$x] = $val->check("string",$_POST["relationship"][$x],"address #" . ($x+1) . ": relationship ",1);
            $input["street1"][$x] = $val->check("string",$_POST["street1"][$x],"address #" . ($x+1) . ": street1 ");
            $input["street2"][$x] = $val->check("string",$_POST["street2"][$x],"address #" . ($x+1) . ": street2 ",1);
            $input["city"][$x] = $val->check("string",$_POST["city"][$x],"address #" . ($x+1) . ": city ");
            $input["state"][$x] = $val->check("aword",strtoupper($_POST["state"][$x]),"address #" . ($x+1) . ": state ");
            $input["zip"][$x] = $val->check("sword",$_POST["zip"][$x],"address #" . ($x+1) . ": zip ");
            $input["phone1"][$x] = $val->check("string",$_POST["phone1"][$x],"address #" . ($x+1) . ": phone1 ");
            $input["phone2"][$x] = $val->check("string",$_POST["phone2"][$x],"address #" . ($x+1) . ": phone2 ",1);
            $input["country"][$x] = $val->check("string",$_POST["country"][$x],"address #" . ($x+1) . ": country ");
        }
    }

    //ensure at least one address was given
    if($at_least_one_address == 0) { $val->error[] = "address: at least one address is required"; }

    //if soldier is a student, the following information is required
    if($input["pers_type"] != $_CONF['pers_type'][0]) { $student_allow_empty = 1; } else { $student_allow_empty = 0; }

    $input["birth_city"] = $val->check("string",$_POST["birth_city"],"birth city",$student_allow_empty);
    $input["birth_state"] = $val->check("aword",strtoupper($_POST["birth_state"]),"birth state",$student_allow_empty);
    $input["birth_country"] = $val->check("string",$_POST["birth_country"],"birth country",$student_allow_empty);
    $input["civilian_occupation"] = $val->check("string",$_POST["civilian_occupation"],"civilian occupation",1);

    $input["bct_location"] = $val->conf($_POST["bct_location"],"bct_location");
    $input["aot_type"] = $val->conf($_POST["aot_type"],"aot_type");
    $input["phase"] = $val->conf($_POST["phase"],"phase");
    if(isset($_POST['airborne']))
    { $input["airborne"] = $val->conf($_POST["airborne"],"yn","airborne"); }
    $input['meps'] = $val->conf($_POST['meps'],'meps');

    $input["subject"] = $val->fk_constraint($_POST["subject"],"remarks_subjects","remarks_subjects_id");

    //if subject is not set to none, require a remark to be entered
    if($input["subject"] != 12) { $remark_allow_empty = 0; } else { $remark_allow_empty = 1; }
    $input["remark"] = $val->check("string",$_POST["remark"],"remark",$remark_allow_empty);

    //determine if there were any validation errors.
    //so far. if there were not, try to insert
    //everything into database
    if(!$val->iserrors())
    {
        //default
        $insert_records = true;

        //cookies are set for fields that will have the
        //same data most of the time so they can be set
        //to this value the next time the add_soldier page
        //is loaded.
        setcookie("addsoldier_pers_type",$_POST["pers_type"],time()+604800);
        setcookie("mos",$_POST["mos"],time()+604800);
        setcookie("building_number",$_POST["building_number"],time()+604800);
        setcookie("unit",$_POST["unit"],time()+604800);


        //the info is inserted into multiple tables. if any one insert fails
        //then the previous inserts must be deleted or the table will
        //end up corrupt. this process does that.
        //simulated transactions!! ;)

        $query = "
        insert into main
            (ssn,first_name,last_name,middle_initial,rank,promotable,dob,company,platoon,ets,pers_type,blood_type,
            dental_category,hiv_date,gender,marital_status,num_dependents,race,mos,component,
            building_number,religion,education,colleges,arrival_date,pov_make,pov_model,pov_year,
            pov_state,pov_tag,status,location,date_entered_service,height,weight,hair_color,eye_color,
            us_citizen,special_skills,sports,entered_by,battalion,status_remark,email,dor,cac)
        values
            ('{$input['ssn']}',             '{$input['first_name']}',           '{$input['last_name']}',        '{$input['middle_initial']}',
             '{$input['rank']}',            '{$input['promotable']}',           '{$input['dob']}',              '{$input['company']}',
             '{$input['platoon']}',         '{$input['ets']}',                  '{$input['pers_type']}',        '{$input['blood_type']}',
             '{$input['dental_category']}', '{$input['hiv_date']}',             '{$input['gender']}',
             '{$input['marital_status']}',  '{$input['num_dependents']}',       '{$input['race']}',             '{$input['mos']}',
             '{$input['component']}',       '{$input['building_number']}',      '{$input['religion']}',         '{$input['education']}',
             '{$input['colleges']}',        '{$input['arrival_date']}',         '{$input['pov_make']}',         '{$input['pov_model']}',
             '{$input['pov_year']}',        '{$input['pov_state']}',            '{$input['pov_tag']}',          '{$input['status']}',
             '{$input['location']}',        '{$input['date_entered_service']}', '{$input['height']}',           '{$input['weight']}',
             '{$input['hair_color']}',      '{$input['eye_color']}',            '{$input['us_citizen']}',       '{$input['special_skills']}',
             '{$input['sports']}',          '{$_SESSION['user_id']}',           '{$input['battalion']}',        '{$input['status_remark']}',
             '{$input['email']}',           '{$input['dor']}',                  '{$input['cac']}')";

        $result = mysql_query($query);

        if(mysql_error())
        {
            //main insert did not work
            $val->error[] = "main: (" . mysql_error() . ")";
            $insert_records = false;
        }
        else
        {
            //first insert was successful. get id of record just inserted.
            $main_record_id = mysql_insert_id($link_id);
            $input['id'] = $main_record_id;
        }

        //only insert remarks if subject is not none
        //and main query did not fail.
        if($input["subject"] != 12 && $input["remark"] && $insert_records == true)
        {
            //insert data into remarks table
            $query = 'insert into remarks (id, subject, remark, entered_by) values ("' . $main_record_id . '","' . $input["subject"] . '","' . $input["remark"] . '",' . $_SESSION['user_id'] . ')';
            $result = mysql_query($query);

            if(mysql_error())
            {
                //remarks insert did not work. delete previous row from main
                $val->error[] = "remarks: (" . mysql_error() . ")";
                $result2 = mysql_query('delete from main where id = ' . $main_record_id);
                $insert_records = false;
            }
            else
            {
                //get id of row just created in case we have to
                //delete it.
                $remarks_record_id = mysql_insert_id($link_id);
            }
        }

        if($insert_records == true)
        {
            //main and remarks query successful, insert into student table
            $query = "insert into student (id,birth_city, birth_state, birth_country, civilian_occupation,
                      basic_training_post, aot_type, phase, airborne, meps) values ($main_record_id,'{$input['birth_city']}',
                      '{$input['birth_state']}','{$input['birth_country']}','{$input['civilian_occupation']}',
                      '{$input['bct_location']}','{$input['aot_type']}','{$input['phase']}',
                      '{$input['airborne']}','{$input['meps']}')";
            $result = mysql_query($query);

            if(mysql_error())
            {
                //insert did not work
                //delete two previous inserts
                $val->error[] = "student: (" . mysql_error() . ")";
                $result2 = mysql_query('delete from main where id = ' . $main_record_id);
                $result3 = mysql_query('delete from remarks where id = ' . $remarks_record_id);
                $insert_records = false;
            }
        }

        if($insert_records == true)
        {
            //loop through address blocks and create insert statement
            for($x=0;$x<3;$x++)
            {
                if(isset($input["address_type"][$x]))
                {
                    $inserts[] = '(' . $main_record_id . ',"' . $input["address_type"][$x] . '","' . $input["name"][$x] . '","' . $input["relationship"][$x] . '","' . $input["street1"][$x] . '","' . $input["street2"][$x] . '","' . $input["city"][$x] . '","' . $input["state"][$x] . '","' . $input["zip"][$x] . '","' . $input["country"][$x] . '","' . $input["phone1"][$x] . '","' . $input["phone2"][$x] . '")';
                }
            }
            //if address is given, insert into table
            if(count($inserts) > 0)
            {
                $query = "insert into address (id,type,name,relationship,street1,street2,city,state,zip,country,phone1,phone2) values " . implode(",",$inserts);
                $result = mysql_query($query);

                if(mysql_error())
                {
                    $val->error[] = "address: (" . mysql_error() . ")";
                    $insert_records = false;
                }
            }
        }

        if($insert_records == true)
        {
            //add current status to tracking table
            $status_insert = mysql_query("insert into status_history (id, daily_status_id, date, status_remark) values "
                    ."(" . $main_record_id . "," . $input["status"] . ",now(),'" . $input["status_remark"] . "')");
            if($e = mysql_error())
            {
                $val->error[] = "status_history: (" . $e . ")";
                $insert_records = false;
            }
        }

        if($insert_records == true && $input["location"] != "Organic")
        {
            $query = "replace "
                    ."location (id,detached_bn,detached_co,attached_bn,attached_co,position,effective,reason) "
                ."values "
                    ."('{$input['id']}',{$input['detached_bn']},{$input['detached_co']},{$input['attached_bn']},"
                    ."{$input['attached_co']},'{$input['position']}','{$input['effective']}',"
                    ."'{$input['reason']}')";

            $result = mysql_query($query) or die("location replace failed. query: $query <p>" . mysql_error());
        }

        if($insert_records == true && isset($input['profile']))
        {
            //add current profile status to tracking table
            $profile_query = "INSERT INTO profile (ID,profile, profile_start, profile_length, profile_reason, profile_limitations)
                              VALUES ({$input['id']},'{$input['profile']}',{$input['profile_start']},{$input['profile_length']},'{$input['profile_reason']}',
                              '{$input['profile_limitations']}')";
            $profile_insert = mysql_query($profile_query)
                or die("Error inserting profile: " . mysql_error());
        }

    }

    //if errors from form being submitted, display them
    if($val->iserrors())
    {
        $msg = $val->geterrors();
    }
    else
    {
        //show successful message and erase the form data
        $msg = "<p align='center'><font size='5' color'blue'>soldier added successfully!</font></p>\n";
        $msg .= "<br>\n";
        $msg .= "<p align='center'><a href='" . $_CONF["html"] . "/data_sheet.php?id=" . $main_record_id . "'>view</a>";
        $msg .= " / ";
        $msg .= "<a href='" . $_CONF["html"] . "/edit_soldier.php?id=" . $main_record_id . "'>edit</a></p>\n";
        unset($_POST);
    }
}

//display header
echo com_siteheader("usap - add new soldier");

//display message from adding soldier
echo $msg;

//turn down error reporting to elimnate
//notices from null values returned from
//database or post values not being present
error_reporting(E_ERROR | E_WARNING | E_PARSE);

//include add soldier form so user can correct the data
//or enter new data
include($_CONF["path"] . "templates/add_soldier.inc.php");

//display footer
echo com_sitefooter();

?>
