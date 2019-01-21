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
if(!check_permission(28))
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

    //validate data
    $input["last_name"] = $val->check("name",$_POST["last_name"],"last name");
    $input["first_name"] = $val->check("aword",$_POST["first_name"],"first name");
    $input["middle_initial"] = $val->check("aword",substr($_POST["middle_initial"],0,1),"middle_initial",1);
    $input["ssn"] = $val->check("ssn",$_POST["ssn"],"ssn",1);
    if($input['ssn'] == 0)
    { $input['ssn'] = mt_rand(1,99999); }

    $input["gender"] = $val->conf($_POST["gender"],"gender");
    $input["rank"] = $val->conf($_POST["rank"],"rank");

    $input["location"] = $val->conf($_POST['location'],"location");

    $input["us_citizen"] = 'Y';
    if($i = $val->unit($_POST["unit"],28,1))
    {
        $input["battalion"] = $i[0];
        $input["company"] = $i[1];
    }
    $input["platoon"] = 'HQ';
    $input["status"] = 62;
    $input["inact_status"] = 0;
    $input['pers_type'] = $val->conf($_POST['perm_party'],"perm_party");

    $input["status_remark"] = "Entered via special form. You may not be able to edit this soldier.";
    $input["dob"] = $val->check("date",$_POST["dob"],"dob",1);
    $input["email"] = $val->check("akoemail",$_POST["email"],"email",1);

    //determine if there were any validation errors.
    //so far. if there were not, try to insert
    //everything into database
    if(!$val->iserrors())
    {
        //default
        $insert_records = true;

        $query = '
        insert into main
            (ssn,first_name,last_name,middle_initial,rank,promotable,dob,company,platoon,ets,pers_type,blood_type,
            dental_category,security,hiv_date,gender,marital_status,num_dependents,race,mos,component,
            building_number,religion,education,colleges,arrival_date,pov_make,pov_model,pov_year,
            pov_state,pov_tag,status,location,date_entered_service,height,weight,hair_color,eye_color,
            us_citizen,special_skills,sports,entered_by,battalion,status_remark,email,
            profile,profile_start,profile_end,recovery_end,profile_reason)
        values'
            .'("' . $input["ssn"]. '","' .$input["first_name"]. '","' .$input["last_name"]. '","'
            .$input["middle_initial"]. '","' .$input["rank"]. '","' . $input['promotable'] . '","' .$input["dob"]. '","'
            . $input["company"] . '","' .$input["platoon"]. '","' .$input["ets"]. '","' .$input["pers_type"]
            . '","' .$input["blood_type"]. '","' .$input["dental_category"]. '","' .$input["security"]. '","'
            .$input["hiv_date"]. '","' .$input["gender"]. '","' .$input["marital_status"]. '","'
            .$input["num_dependents"]. '","' .$input["race"]. '","' .$input["mos"]. '","'
            .$input["component"]. '","' .$input["building_number"]. '","' .$input["religion"]. '","'
            .$input["education"]. '","' .$input["colleges"]. '","' .$input["arrival_date"]. '","'
            .$input["pov_make"]. '","' .$input["pov_model"]. '","' .$input["pov_year"]. '","'
            .$input["pov_state"]. '","' .$input["pov_tag"]. '","' .$input["status"]. '","'
            .$input["location"]. '","' .$input["date_entered_service"]. '","' .$input["height"]. '","'
            .$input["weight"]. '","' .$input["hair_color"]. '","' .$input["eye_color"]. '","'
            .$input["us_citizen"]. '","' .$input["special_skills"]. '","' .$input["sports"]. '","'
            .$_SESSION["user_id"]. '","' . $input["battalion"] . '","' .$input["status_remark"] . '","' . $input["email"] . '","'
            .$input["profile"] . '","' . $input["profile_start"] . '","' . $input["profile_end"] . '","'
            .$input["recovery_end"] . '","' . $input["profile_reason"] . '")';

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
        $msg = "<br><p align='center'><font size='5' color'blue'>Addition Successfull</font></p>\n";
        $msg .= "<br>\n";
        $msg .= "<p align='center'><a href='" . $_CONF["html"] . "/data_sheet.php?id=" . $main_record_id . "'>view</a>";
        $msg .= " / ";
        $msg .= "<a href='" . $_CONF["html"] . "/edit_soldier.php?id=" . $main_record_id . "'>edit</a></p>\n";
        unset($_POST);
    }
}

//display header
echo com_siteheader("USAP - Add Special");

//display message from adding soldier
//echo $msg;

//turn down error reporting to elimnate
//notices from null values returned from
//database or post values not being present
error_reporting(E_ERROR | E_WARNING | E_PARSE);

//include add soldier form so user can correct the data
//or enter new data
include($_CONF["path"] . "templates/add_special.inc.php");

//display footer
echo com_sitefooter();

?>
