<?
include("lib-common.php");

//see if edit was chosen in com_choosesoldier
//and redirect to edit page with chosen id
if(isset($_GET["com_cs_action"]) && $_GET["com_cs_action"] == "view")
{
    header("location: " . $_CONF["html"] . "/data_sheet.php?id=" . $_GET["id"]);
    exit();
}

//include class to handle validation
include_once($_CONF["path"] . "classes/validate.class.php");

//create new validation class
$val = new validate;

//variable initialization
$error = array();
$allow_full_ssn = 0;
$require_phase_remark = 0;

//display header
$display = com_siteheader("USAP - Edit Soldier Information");

//do not display choose soldier dialog if 'nochoose='
//is passed. this is just an easy way to
//turn it off if necessary.
if(!isset($_REQUEST["nochoose"]))
{
    //display select boxes to choose soldier
    //for view or edit
    $display .= com_choosesoldier("data");
}


echo $display;

//restore soldier if restore button was pressed
if(isset($_GET['restore']))
{
    if($input['id'] = $val->id($_REQUEST['id'],3))
    {
        $query = "update main set pcs=0, pcs_date=0 where id = " . $input['id'];
        $result = mysql_query($query) or die("restore error" . mysql_error());
        if(mysql_affected_rows())
        { echo "soldier restored"; }
        else
        { echo "soldier not restored"; }
    }
    else
    { echo "invalid permissions to restore"; }
}
elseif(isset($_POST['move']))
{
    if($input['id'] = $val->id($_REQUEST['id'],27))
    {
        $input['new_battalion'] = $val->fk_constraint($_POST['battalion_id'],"battalion","battalion_id");
        $input['new_company'] = $val->fk_constraint($_POST['company_id'],"company","company_id");
        $val->unit($input['new_battalion'], $input['new_company'],0,1);

        if($val->iserrors())
        { echo $val->geterrors(); }
        else
        {
            $query = "update main set battalion = {$input['new_battalion']}, company={$input['new_company']} where id = {$input['id']}";
            $result = mysql_query($query) or die("Error in move soldier query: " . mysql_error());
            if(mysql_affected_rows())
            {
                $query = "delete from user_permissions where user_id = {$input['id']}";
                $result = mysql_query($query) or die("Error removing permissions for deleted soldier: " . mysql_error());
                echo "<div align='center' class='notice'>Soldier moved successfully.</div>";
            }
            else
            { echo "<div align='center' class='error'>Soldier not moved.</div>"; }
        }
    }
    else
    { echo "<span align='center' class='error'>Invalid permissions to move soldier.</span>"; }

    $_GET['id'] = $input['id'];
}

//if edit form has been submitted process updates
if(isset($_POST["edit_submit"]))
{
    //turn down error reporting to elimnate
    //notices from null values returned from
    //database or post values not being present
    error_reporting(E_ERROR | E_WARNING | E_PARSE);

    //check for soldier living off post
    if(isset($_POST["off_post"]))
    {
        $_POST["building_number"] = "off_post";
    }

    //check if user has permission to view full ssn
    //and therefore edit full ssn on soldier id.
    //hide error reporting on id() function.
    if($val->id($_POST["id"],12,1))
    {
        $allow_full_ssn = 1;
        $input["ssn"]       = $val->check("ssn",$_POST["ssn"],"ssn");
    }

    //validate current user has permission "2" (edit soldier)
    //for the soldier id that was passed or
    //check for user trying to edit their own information.
    if($_POST['id'] == $_SESSION['user_id'])
    { $input['id'] = $_SESSION['user_id']; }
    else
    { $input['id']          = $val->id($_POST["id"],2); }

    if($i = $val->unit($_POST["unit"],2))
    {
        $input["battalion"] = $i[0];
        $input["company"]   = $i[1];
    }

    $input["last_name"]     = $val->check("name",$_POST["last_name"],"last name");
    $input["first_name"]    = $val->check("aword",$_POST["first_name"],"first name");
    $input["middle_initial"]= $val->check("aword",substr($_POST["middle_initial"],0,1),"middle_initial",1);
    $input["gender"]        = $val->conf($_POST["gender"],"gender");
    $input["rank"]          = $val->conf($_POST["rank"],"rank");
    

    if(isset($_POST['promotable']))
    { $input['promotable'] = "(P)"; }

    $input["ets"]           = $val->check("date",$_POST["ets"],"ets",1);
    $input["mos"]           = $val->conf($_POST["mos"],"mos");
    $input["component"]     = $val->conf($_POST["component"],"component");
    $input["location"]      = $val->conf($_POST["location"],"location");

    if($input["location"] != "Organic")
    {
        $input['detached_bn']   = $val->fk_constraint($_POST['detached_bn'],'battalion','battalion_id');
        $input['detached_co']   = $val->fk_constraint($_POST['detached_co'],'company','company_id');
        $input['attached_bn']   = $val->fk_constraint($_POST['attached_bn'],'battalion','battalion_id');
        $input['attached_co']   = $val->fk_constraint($_POST['attached_co'],'company','company_id');
        $input["effective"]     = $val->check("date",$_POST["assigned_date"],"assigned date");
        $input["position"]      = $val->check("string",$_POST["assigned_position"],"assigned position");
        $input["reason"]        = $val->check("string",$_POST["assigned_reason"],"assigned reason");

        if($input['detached_bn'] == $input['attached_bn'] && $input['detached_co'] == $input['attached_co'])
        { $val->error[] = "Detached and Attached units cannot be the same."; }

        if($input['location'] == 'Detached')
        {
            if($input['battalion'] != $input['detached_bn'] || $input['company'] != $input['detached_co'])
            { $val->error[] = "Detached From Unit and current Unit must match when soldier's Location is set to Detached"; }
            else
            {
                if($input['attached_bn'] == 5)
                { $input['attached_co'] = 10; }
                elseif($val->unit($input['attached_bn'],$input['attached_co'],0,1))
                {
                    $input['location'] = 'Attached';
                    $input['battalion'] = $input['attached_bn'];
                    $input['company'] = $input['attached_co'];
                }
            }
        }
        elseif($input['location'] == 'Attached')
        {
            $val->unit($input['detached_bn'],$input['detached_co'],0,1);

            if($input['battalion'] != $input['attached_bn'] || $input['company'] != $input['attached_co'])
            { $val->error[] = "Attached To Unit and current Unit must match when soldier's Location is set to Attached"; }
        }
    }

    $input["date_entered_service"]  = $val->check("date",$_POST["date_entered_service"],"date entered service",1);
    $input["pcs_date"]      = $val->check("date",$_POST['pcs_date'],"pcs date",1);
    $input['pcs_location'] = $val->conf($_POST['pcs_location'],'pcs_location');
    $input["us_citizen"]        = $val->conf($_POST["us_citizen"],"yn","us citizen");
    $input["cac"]        = $val->conf($_POST["cac"],"yn","CAC");

    $input["platoon"]       = $val->conf($_POST["platoon"],"platoon");
    $input["building_number"]   = $val->check("sword",strtoupper($_POST["building_number"]),"building number",0,0);
    $input["room_number"]   = ($_POST["room_number"]);
    $input["flagged"]       = $val->conf($_POST["flagged"],"yn",'Flagged');
    if($input["flagged"] == "y") { $allow_empty_flag_date = 0; } else { $allow_empty_flag_date = 1; }
    $input["flag_date"]     = $val->check("date",$_POST["flag_date"],"flag date",$allow_empty_flag_date);
    $input["arrival_date"]  = $val->check("date",$_POST["arrival_date"],"arrival date");

    //If a soldier's pers_type changes, then you can't validate
    //the status passed, since it may not apply to the new pers_type
    //so set them to zero.
    if($_POST['pers_type'] == $_POST['old_pers_type'])
    {
        if($i = $val->status($_POST["status"],$_POST["pers_type"]))
        {
            $input["status"]    = $i[0];
            $input["pers_type"] = $i[1];
        }
        if($i2 = $val->status($_POST['inact_status'],$_POST['pers_type'],1))
        { $input['inact_status']= $i2[0]; }
    }
    else
    {
        $input['pers_type'] = $val->conf($_POST['pers_type'],"pers_type");
        $input['status'] = 0;
        $input['inact_status'] = 0;
    }
    $input['old_pers_type'] = $val->conf($_POST['old_pers_type'],"pers_type");

    $input['dor']           = $val->check("date",$_POST['dor'],'Date of Rank');


    $input['tda_comment'] = htmlentities($val->check('string',$_POST['tda_comment'],'TDA Comment',1),ENT_QUOTES);
    if(in_array($input['pers_type'],$_CONF['perm_party']) && isset($_POST['assigned_tda_id']))
    {
        if(!empty($_POST['assigned_tda_id']))
        { $input['assigned_tda_id'] = $val->fk_constraint($_POST['assigned_tda_id'],'tda','tda_id'); }

        if(!empty($_POST['working_tda_id']))
        { $input['working_tda_id'] = $val->fk_constraint($_POST['working_tda_id'],'tda','tda_id'); }
    }

    $input["status_remark"] = $val->check("string",$_POST["status_remark"],"status remark",1);
    $input["blood_type"]    = $val->conf($_POST["blood_type"],"blood_type");
    $input["dental_category"]   = $val->conf($_POST["dental_category"],"dental_category");
    $input["hiv_date"]      = $val->check("date",$_POST["hiv_date"],"hiv date",1);
    $input["height"]        = $val->check("number",$_POST["height"],"height");
    $input["weight"]        = $val->check("number",$_POST["weight"],"weight");
    $input["hair_color"]    = $val->check("aword",$_POST["hair_color"],"hair color");
    $input["eye_color"]     = $val->check("aword",$_POST["eye_color"],"eye color");
    $input["marital_status"]= $val->conf($_POST["marital_status"],"marital_status");
    $input["num_dependents"]= $val->check("number",$_POST["num_dependents"],"num dependents");

    if($input["dob"] = $val->check("date",$_POST["dob"],"dob"))
    {
        if(strtotime($input['dob']) > strtotime('16 years ago'))
        { $val->error[] = 'Invalid Date of Birth'; }
    }

    $input["religion"]      = $val->conf($_POST["religion"],"religion");
    $input["education"]     = $val->conf($_POST["education"],"education");
    $input["colleges"]      = $val->check("string",$_POST["colleges"],"colleges",1);
    $input["special_skills"]= $val->check("string",$_POST["special_skills"],"special skills",1);
    $input["race"]          = $val->conf($_POST["race"],"race");
    $input["sports"]        = $val->check("string",$_POST["sports"],"sports",1);
    $input["email"]         = $val->check("akoemail",$_POST["email"],"email",1);

    //check to see if any pov info was given. if so, all of it must be given
    $pov_empty_allowed = 1;
    if(strlen($_POST["pov_make"] . $_POST["pov_model"] . $_POST["pov_year"] . $_POST["pov_state"] . $_POST["pov_tag"]) > 0)
    { $pov_empty_allowed = 0; }

    $input["pov_make"]      = $val->check("string",$_POST["pov_make"],"pov make",$pov_empty_allowed);
    $input["pov_model"]     = $val->check("string",$_POST["pov_model"],"pov model",$pov_empty_allowed);
    $input["pov_year"]      = $val->check("number",$_POST["pov_year"],"pov year",$pov_empty_allowed);
    $input["pov_state"]     = $val->check("aword",strtoupper($_POST["pov_state"]),"pov state",$pov_empty_allowed);
    $input["pov_tag"]       = $val->check("string",strtoupper($_POST["pov_tag"]),"pov tag",$pov_empty_allowed);

    $input['post_decal']    = $val->check("string",strtoupper($_POST['post_decal']),'Post Decal',1);
    $input['center_access_decal'] = $val->check('string',strtoupper($_POST['center_access_decal']),'Center Access Decal',1);
    $input['housing_decal'] = $val->check("string",strtoupper($_POST['housing_decal']),'Housing Decal',1);

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

    //loop through all addresses. if type is not set to none
    //or delete is not checked, then most of the information is required
    $at_least_one_address = 0;
    $y = 0;
    $num_addresses = count($_POST["address_type"]);

    for($x=0;$x<$num_addresses;$x++)
    {
        //ensure address type is not set to none
        //and delete is not checked. if either is true
        //then the address information will not be included in
        //the new set. all previous addresses are deleted
        //except for those validated here.
        //if the delete_id matches the current address_id,
        //then the address will not be included in the new
        //input and will be "deleted"
        if($_POST["address_type"][$x] != "None")
        {
            //if "address_delete" is not set, then only a new address is being sent
            //so it must be validated. if "address_delete" is set, but it's not
            //equal to the current "address_id" then the address must also be validated.
            if(!isset($_POST["address_delete"]) || $_POST["address_delete"][$y] != $_POST["address_id"][$x])
            {
                $at_least_one_address = 1;
                if(isset($_POST["address_id"][$x]))
                {
                    $input["address_id"][]      = $val->fk_constraint($_POST["address_id"][$x],"address","address_id");
                }
                $input["address_type"][]= $val->conf($_POST["address_type"][$x],"address_type");
                $input["name"][]        = $val->check("string",$_POST["name"][$x],"address #" . ($x+1) . ": name",1);
                $input["relationship"][]= $val->check("string",$_POST["relationship"][$x],"address #" . ($x+1) . ": relationship",1);
                $input["street1"][]     = $val->check("string",$_POST["street1"][$x],"address #" . ($x+1) . ": street1 ");
                $input["street2"][]     = $val->check("string",$_POST["street2"][$x],"address #" . ($x+1) . ": street2 ",1);
                $input["city"][]        = $val->check("string",$_POST["city"][$x],"address #" . ($x+1) . ": city ");
                $input["state"][]       = $val->check("aword",strtoupper($_POST["state"][$x]),"address #" . ($x+1) . ": state ");
                $input["zip"][]         = $val->check("sword",$_POST["zip"][$x],"address #" . ($x+1) . ": zip ");
                $input["phone1"][]      = $val->check("string",$_POST["phone1"][$x],"address #" . ($x+1) . ": phone1 ");
                $input["phone2"][]      = $val->check("string",$_POST["phone2"][$x],"address #" . ($x+1) . ": phone2 ",1);
                $input["country"][]     = $val->check("string",$_POST["country"][$x],"address #" . ($x+1) . ": country ");
            }
        }
        //the count of delete_id can be different than the
        //number of addresses because it's a checkbox. $y
        //keeps track of the count for it.
        elseif($_POST["address_delete"][$y] == $_POST["address_id"][$x])
        { $y++; }
    }

    //ensure at least one address was given
    if($at_least_one_address == 0) { $val->error[] = "address: at least one address is required"; }

    //validate following information only if the soldier
    //is a student and the pers_type didn't change on this
    //update. If it changes, the soldier may no longer be a
    //student or just became a student, and these validations
    //would not ever be true.
    if(in_array($input['pers_type'],$_CONF['students']) && $input['pers_type'] == $input['old_pers_type'])
    {
        if(!empty($_POST['class_id']) && $_POST['class_id'] != 'none')
        { $input["class_id"]    = $val->fk_constraint($_POST["class_id"],"class","class_id",1); }
        elseif($_POST['class_id'] == 'none')
        { $input['class_id'] = '0'; }
        else
        { $input['class_id'] = 'class_id'; }

        $input["shift"]         = $val->conf($_POST["shift"],"shift");
        $input["airborne"]      = $val->conf($_POST["airborne"],"yn","airborne");
        $input["hrap"]          = $val->conf($_POST["hrap"],"hrap","HRAP");
        $input["birth_city"]    = $val->check("string",$_POST["birth_city"],"birth city");
        $input["birth_state"]   = $val->check("aword",strtoupper($_POST["birth_state"]),"birth state");
        $input["birth_country"] = $val->check("string",$_POST["birth_country"],"birth country");
        $input["civilian_occupation"]   = $val->check("string",$_POST["civilian_occupation"],"civilian occupation",1);
        $input["swim"]          = $val->conf($_POST["swim"],"yn","swim");
        $input["swim_date"]     = $val->check("date",$_POST["swim_date"],"swim date",1);
        $input["heat"]          = $val->conf($_POST["heat"],"yn","heat");
        $input["heat_date"]     = $val->check("date",$_POST["heat_date"],"heat date",1);
        $input["cold"]          = $val->conf($_POST["cold"],"yn","cold");
        $input["cold_date"]     = $val->check("date",$_POST["cold_date"],"cold date",1);
        $input["ctt"]           = $val->conf($_POST["ctt"],"yn","ctt");
        $input["ctt_date"]      = $val->check("date",$_POST["ctt_date"],"ctt date",1);

        if($_POST['not_graduating'] == 1)
        { $input['not_graduating'] = 1; }
        else
        { $input['not_graduating'] = 0; }

        $input["bct_location"]  = $val->conf($_POST["bct_location"],"bct_location");
        $input["aot_type"]      = $val->conf($_POST["aot_type"],"aot_type");
        $input['assignment']    = $val->check("string",$_POST['assignment'],"assignment",1);
        $input['honor_grad']    = $val->conf($_POST['honor_grad'],"yn","honor graduate");
        $input['dist_grad']     = $val->conf($_POST['dist_grad'],"yn","distinguished graduate");
        $input['meps']          = $val->conf($_POST['meps'],'meps','MEPS Station');
        $input['academic_avg']  = $val->check("string",$_POST['academic_avg'],'Academic Average',1);
        $input['test_failures'] = $val->check("number",$_POST['test_failures'],'Test Failures',1);

        $input['phase']         = $val->conf($_POST['phase'],"phase");
        $input['old_phase']     = $val->conf($_POST['old_phase'],'phase');
        $allow_empty_iv = 1;
        $allow_empty_v = 1;
        $allow_empty_va = 1;
        if($input['phase'] != $input['old_phase'])
        {
            switch($input['phase'])
            {
                case 'IV':
                    $allow_empty_iv = 0;
                break;
                case 'V':
                    $allow_empty_v = 0;
                break;
                case 'V+':
                    $allow_empty_va = 0;
                break;
            }

            if(($input['old_phase'] == 'V+' && ($input['phase'] == 'V' || $input['phase'] == 'IV')) || ($input['old_phase'] == 'V' && $input['phase'] == 'IV'))
            { $require_phase_remark = 1; }
        }
        $input['date_phaseiv']  = $val->check('date',$_POST['date_phaseiv'],'Phase IV Date',$allow_empty_iv);
        $input['date_phasev']   = $val->check('date',$_POST['date_phasev'],'Phase V Date',$allow_empty_v);
        $input['date_phaseva']  = $val->check('date',$_POST['date_phaseva'],'Phase V+ Date',$allow_empty_va);

        if($input['honor_grad'] == "y" && $input['dist_grad'] == "y")
        { $val->error[] = "Soldier cannot be honor and distinguished graduate"; }

        $input['high_pt']       = $val->conf($_POST['high_pt'],"yn","high pt award");

        //see if it's exodus time and validate information passed
        if(isset($_POST['add_to_exodus']))
        { $input['add_to_exodus'] = '1'; }
        if(isset($_POST['remove_from_exodus']))
        { $input['add_to_exodus'] = '0'; }

        if($val->exodus($input['pers_type']))
        {
            if(($_CONF['exodus_edit'] == "on" && time() <= strtotime($_CONF['exodus_edit_off'])) || in_array($_SESSION['user_id'],$_CONF['exodus_edit_allowed']))
            {
                $input['exodus_status']     = $val->conf($_POST['exodus_status'],"exodus_status","exodus status");
                $input['exodus_comment']    = $val->check('string',$_POST['exodus_comment'],'Exodus Comment',1);

                if($input['exodus_status'] == 'Returned')
                {
                    $query = "UPDATE exodus SET returned = 1, comment='{$input['exodus_comment']}' WHERE id = {$input['id']}";
                    $rs = mysql_query($query) or die('Unable to set exodus status to returned: ' . mysql_error());
                }
                else
                {
                    //check for unconfirmed plan or holding company on/off post.
                    //if that's the choice, then the exodus items are not required
                    $blank_exodus = array("Unconfirmed Travel Plans","Holding Company - On Post","Holding Company - Off Post","Planned Air Atlanta","Planned Air Augusta","Planned Bus","Planned POV","Planned Three Kings","Planned Holding Company","NG/ER PCS Prior to Exodus","Other PCS/Chapter Prior to Exodus");
                    if(!in_array($input['exodus_status'],$blank_exodus))
                    //if($input['exodus_status'] != $_CONF['exodus_status'][0] && $input['exodus_status'] != $_CONF['exodus_status'][6] && $input['exodus_status'] != $_CONF['exodus_status'][7])
                    {
                        //check for pcs. if so, then return information is not checked or stored
                        if(stristr($input['exodus_status'],"pcs"))
                        {
                            $returning = 0;
                            $input['ret_mode'] = "none";
                        }
                        else
                        { $returning = 1; }

                        $input['dep_date']          = $val->check("date",$_POST['dep_date'],"departure date");
                        $input['dep_time']          = $val->check("mtime",$_POST['dep_time'],"departure time");
                        $input['dep_datetime']      = $input['dep_date'] . $input['dep_time'];
                        if($returning)
                        {
                            $input['ret_date']          = $val->check("date",$_POST['ret_date'],'return date');
                            $input['ret_time']          = $val->check("mtime",$_POST['ret_time'],'return time');
                            $input['ret_datetime']      = $input['ret_date'] . $input['ret_time'];
                        }

                        $input['dep_mode']          = $val->conf($_POST['dep_mode'],"exodus_modes","departure mode");
                        switch(strtolower($input['dep_mode']))
                        {
                            case "pov":
                                $input['dep_pov_type'] = $val->conf($_POST['dep_pov_type'],"exodus_pov_types","departure pov type");
                            break;
                            case "bus":
                                $input['dep_ticket_status'] = $val->conf($_POST['dep_ticket_status'],"exodus_ticket_status","departure bus ticket status");
                                if(strcasecmp($input['dep_ticket_status'],"in hand")==0)
                                { $input['bought_dep_bustic_onpost'] = $val->conf($_POST['bought_dep_bustic_onpost'],"yn","bought departure bus ticket on post"); }
                                else
                                { $input['bought_dep_bustic_onpost'] = "N"; }
                                $input['bus_dest_city'] = $val->check("string",$_POST['bus_dest_city'],'Bus Destination City');
                                $input['bus_dest_state'] = strtoupper($val->check("aword",$_POST['bus_dest_state'],'Bus Destination State'));
                                $input['gate'] = $val->conf($_POST['gate'],'gate_numbers','Exodus Bus Gate');
                            break;
                            case "air":
                                $input['dep_airport'] = $val->conf($_POST['dep_airport'],"exodus_airports","departure airport");
                                $input['dep_airline'] = $val->conf($_POST['dep_airline'],"exodus_airlines","departure airline");
                                if($input['dep_airline'] == 'Bus to Airport Only')
                                {
                                    if($input['dep_airport'] != 'Atlanta')
                                    { $val->error[] = 'Cannot depart from Augusta with "Bus to Airport Only"'; }
                                    $input['dep_flight_num'] = '1';
                                    $input['dep_time'] = '130000';
                                    $input['dep_datetime'] = $input['dep_date'] . $input['dep_time'];
                                }
                                else
                                { $input['dep_flight_num'] = $val->check("sword",$_POST['dep_flight_num'],"departure flight number"); }

                                if(strcasecmp($input['dep_airport'],"atlanta")==0)
                                {
                                    $input['dep_air_bus_ticket'] = $val->conf($_POST['dep_air_bus_ticket'],'yn','departure air bus ticket');
                                    $input['bought_dep_bustic_onpost'] = $val->conf($_POST['bought_dep_bustic_onpost2'],"yn","bought departure bus ticket on post");
                                }
                            break;
                        }

                        if($returning)
                        {
                            $input['ret_mode']          = $val->conf($_POST['ret_mode'],"exodus_modes","return mode");
                            switch(strtolower($input['ret_mode']))
                            {
                                case "pov":
                                    $input['ret_pov_type'] = $val->conf($_POST['ret_pov_type'],"exodus_pov_types","return pov type");
                                break;
                                case "bus":
                                    $input['ret_ticket_status'] = $val->conf($_POST['ret_ticket_status'],"exodus_ticket_status","return bus ticket status");
                                break;
                                case "air":
                                    $input['ret_airport'] = $val->conf($_POST['ret_airport'],"exodus_airports","return airport");
                                    $input['ret_airline'] = $val->conf($_POST['ret_airline'],"exodus_airlines","return airline");
                                    $input['ret_flight_num'] = $val->check("sword",$_POST['ret_flight_num'],"return flight number");
                                    if(strcasecmp($input['ret_airport'],"atlanta")==0)
                                    { $input['ret_air_bus_ticket'] = $val->conf($_POST['ret_air_bus_ticket'],"yn","return air bus ticket"); }
                                    if(strcasecmp($input['ret_airline'],'bus to airport only')==0)
                                    { $val->error[] = 'Return Airport cannot be "Bus to Airport Only". Please choose "Bus" for return mode.'; }
                                break;
                            }
                        }
                    }
                }
            }
        }//end validating exodus data
    } //end validating student information

    //if soldier is airborne and airborne form info was already
    //shown, the validate data
    if($input['airborne'] == "Y" && isset($_POST['ab_type']))
    {
        $input['vol_date']  = $val->check("date",$_POST['vol_date'],"vol_date",1);
        $input['packet_init']       = $val->check("date",$_POST['packet_init'],"packet initialized",1);
        $input['ab_type']           = $val->conf($_POST['ab_type'],"ab_type","airborne type");
        $input['physical1']         = $val->check("date",$_POST['physical1'],"physical 1",1);
        $input['physical2']         = $val->check("date",$_POST['physical2'],"physical 2",1);
        $input['submit_4187']       = $val->check("date",$_POST['submit_4187'],"submit 4187",1);
        $input['packet_ti']         = $val->check("date",$_POST['packet_ti'],"packet turned in",1);
        $input['ab_remark']         = $val->check("string",$_POST['ab_remark'],"airborne remark",1);
    }


    $input["subject"] = $val->fk_constraint($_POST["subject"],"remarks_subjects","remarks_subjects_id");

    //Subject 16 is "Phasing"
    if($require_phase_remark && $input['subject'] != 16)
    { $val->error[] = 'Remark with subject of "Phasing" is required when Phase is reduced.'; }

    //if remark is set, but subject is none, give an error
    if($input['subject'] == 12 && strlen($_POST['remark']) > 0)
    { $val->error[] = "Please choose a subject for your remark"; }

    //if subject is not set to none, or a phase remark is required,
    //require a remark to be entered
    $remark_allow_empty = 1;
    if($input["subject"] != 12 || $require_phase_remark)
    { $remark_allow_empty = 0; }

    $input["remark"]        = $val->check("string",$_POST["remark"],"Remark",$remark_allow_empty);

    //run queries to update database with new data
    //if there are no errors so far
    if(!$val->iserrors())
    {
        //////////
        // MAIN //
        //////////

        //create query to update main table.
        //By validating $_POST["id"], we are
        //assured that the current user has permission
        //to edit this data
        $query = "UPDATE "
                ."main "
            ."SET "
                ."ssn=if(" . $allow_full_ssn . ",'" . $input["ssn"] . "',ssn),first_name='" . $input["first_name"] . "',last_name='"
                . $input["last_name"] . "',middle_initial='" . $input["middle_initial"] . "',"
                ."rank='" . $input["rank"] . "',promotable='" . $input['promotable'] . "',dob='" . $input["dob"] . "',company='" . $input["company"]
                . "',platoon='" . $input["platoon"] . "',ets='" . $input["ets"] . "',pers_type='"
                . $input["pers_type"] . "',blood_type='" . $input["blood_type"] . "',dental_category='"
                . $input["dental_category"] . "',security='" . $input["security"] . "',"
                ."hiv_date='" . $input["hiv_date"] . "',gender='" . $input["gender"] . "',marital_status='"
                . $input["marital_status"] . "',num_dependents='" . $input["num_dependents"] . "',"
                ."race='" . $input["race"] . "',mos='" . $input["mos"] . "',component='" . $input["component"]
                . "',building_number='" . $input["building_number"] . "',room_number='" . $input["room_number"] . "', "
                ."religion='" . $input["religion"] . "',education='" . $input["education"] . "',colleges='"
                . $input["colleges"] . "',arrival_date='" . $input["arrival_date"] . "',"
                ."pov_make='" . $input["pov_make"] . "',pov_model='" . $input["pov_model"] . "',pov_year='"
                . $input["pov_year"] . "',pov_state='" . $input["pov_state"] . "',pov_tag='" . $input["pov_tag"] . "',"
                ."status='" . $input["status"] . "',inact_status = '" . $input['inact_status'] . "',location='" . $input["location"] . "',date_entered_service='"
                . $input["date_entered_service"] . "',height='" . $input["height"] . "',"
                ."weight='" . $input["weight"] . "',hair_color='" . $input["hair_color"] . "',eye_color='"
                . $input["eye_color"] . "',us_citizen='" . $input["us_citizen"] . "',"
                ."special_skills='" . $input["special_skills"] . "',sports='" . $input["sports"]
                . "',entered_by='" . $_SESSION["user_id"]. "',"
                ."battalion='" . $input["battalion"] . "',status_remark='" . $input["status_remark"]
                . "',pcs_uic='" . $input["pcs_uic"] . "', "
                ."gaining_unit='" . $input["gaining_unit"] . "',pcs_date='" . $input["pcs_date"] . "',"
                ."flagged='" . $input['flagged'] . "', flag_date='" . $input['flag_date'] . "', email='" . $input["email"] . "', "
                ."pcs_date = '" . $input['pcs_date'] . "', "
                ."not_graduating = '" . $input['not_graduating'] . "', dor='{$input['dor']}', "
                ."post_decal = '{$input['post_decal']}', center_access_decal = '{$input['center_access_decal']}', "
                ."housing_decal = '{$input['housing_decal']}', cac ='{$input['cac']}', pcs_location='{$input['pcs_location']}' "
            ." WHERE "
                ."id=" . $input["id"];

        $result = mysql_query($query) or die("main update failed. query:$query <p>" . mysql_error());

        ////////////
        // REMARK //
        ////////////

        //If subject of remark is not "None"
        //then save remark in database
        if($input["subject"] != 12)
        {
            $query = "INSERT INTO remarks (id,subject,remark,entered_by) VALUES ('" . $_POST["id"] . "','" . $_POST["subject"] . "','" . $_POST["remark"] . "'," . $_SESSION['user_id'] . ")";
            $result = mysql_query($query) or die("remarks update failed. query: $query <p>" . mysql_error());
        }

        /////////////
        // STUDENT //
        /////////////

        //If personnel type is a student, save
        //student data in database
        if(in_array($input['pers_type'],$_CONF['students']) && $input['pers_type'] == $input['old_pers_type'])
        {
            $query = "UPDATE student SET
                      birth_city='{$input['birth_city']}', birth_state='{$input['birth_state']}',
                      birth_country='{$input['birth_country']}', civilian_occupation='{$input['civilian_occupation']}',
                      basic_training_post='{$input['bct_location']}',swim='{$input['swim']}',swim_date='{$input['swim_date']}',
                      heat='{$input['heat']}',heat_date='{$input['heat_date']}',cold='{$input['cold']}',cold_date='{$input['cold_date']}',
                      airborne='{$input['airborne']}',class_id={$input['class_id']},shift='{$input['shift']}',hrap='{$input['hrap']}',
                      aot_type = '{$input['aot_type']}', ctt = '{$input['ctt']}', ctt_date = '{$input['ctt_date']}',
                      phase = '{$input['phase']}', assignment = '{$input['assignment']}', honor_grad = '{$input['honor_grad']}',
                      dist_grad = '{$input['dist_grad']}', high_pt = '{$input['high_pt']}', add_to_exodus='{$input['add_to_exodus']}', meps='{$input['meps']}',
                      academic_avg = '{$input['academic_avg']}', test_failures='{$input['test_failures']}',
                      date_phaseiv = '{$input['date_phaseiv']}', date_phasev = '{$input['date_phasev']}', date_phaseva = '{$input['date_phaseva']}'
                      WHERE id={$input['id']}";

            $result = mysql_query($query) or die("student update failed. query: $query <p>" . mysql_error());
        }

        ///////////////////////
        // ATTACHED DETACHED //
        ///////////////////////

        //If soldier is attached or detached, save data in database
        if($input["location"] != "Organic")
        {
            $query = "REPLACE "
                    ."location (id,detached_bn,detached_co,attached_bn,attached_co,position,effective,reason) "
                ."VALUES "
                    ."('{$input['id']}',{$input['detached_bn']},{$input['detached_co']},{$input['attached_bn']},"
                    ."{$input['attached_co']},'{$input['position']}','{$input['effective']}',"
                    ."'{$input['reason']}')";

            $result = mysql_query($query) or die("location replace failed. query: $query <p>" . mysql_error());
        }
        else
        {
            $query = "delete from location where id = " . $input['id'];
            $result = mysql_query($query) or die("location delete failed. query: $query <p>" . mysql_error());
        }

        ////////////
        // ADDRESS //
        ////////////

        //delete old addresses, new ones will be inserted
        $query = "delete from address where id = " . $_POST["id"];
        $result = mysql_query($query) or die($val->error[] = "address delete error. query: $query <p> " . mysql_error());

        //get count of validated addresses
        $num_address = count($input["address_type"]);

        //addresses are already validated,
        //go ahead and insert them.
        for($x=0;$x<$num_address;$x++)
        {
            $query = "INSERT INTO "
                    ."address "
                    ."(id, type, name, relationship, street1, street2, city, state, zip, "
                    ."country, phone1, phone2) "
                ."VALUES "
                    ."('" . $input["id"] . "','" . $input["address_type"][$x] . "','" . $input["name"][$x] . "','" . $input["relationship"][$x] . "','" . $input["street1"][$x] . "',"
                    ."'" . $input["street2"][$x] . "','" . $input["city"][$x] . "','" . $input["state"][$x] . "','" . $input["zip"][$x] . "','" . $input["country"][$x] . "',"
                    ."'" . $input["phone1"][$x] . "','" . $input["phone2"][$x] . "')";

            $result = mysql_query($query) or die("address insert failed. query:$query <p>" . mysql_error());

            //add new address_id's to array
            $input["address_id"][$x] = mysql_insert_id();
        }

        /////////////////////
        // STATUS TRACKING //
        /////////////////////

        //add current status to tracking table, if it has changed
        $status_result = mysql_query("SELECT daily_status_id, inact_status_id FROM status_history WHERE id = " . $input["id"] . " ORDER BY date DESC LIMIT 1") or die("status check query error: " . mysql_error());
        $status_row = mysql_fetch_array($status_result);
        if(!isset($status_row["daily_status_id"]) || ($status_row["daily_status_id"] != $input["status"]) || ($status_row['inact_status_id'] != $input['inact_status']))
        {
            $status_insert = mysql_query("INSERT INTO status_history (id, daily_status_id, inact_status_id, date, status_remark) VALUES "
                ."(" . $input["id"] . "," . $input["status"] . "," . $input['inact_status'] . ",now(),'" . $input["status_remark"] . "')")
                or die("status history insert error: " . mysql_error());
        }

        /////////////////////
        // DELETE PROFILES //
        /////////////////////

        //Delete any profile records that were checked
        if(isset($_POST['profile_delete']) && count($_POST['profile_delete'])>0)
        {
            $pid = implode(',',$_POST['profile_delete']);
            $dp_query = "DELETE FROM profile WHERE ID = {$input['id']} AND profile_id IN ($pid)";
            $result = mysql_query($dp_query) or die("Error deleting profiles: " . mysql_error());
        }

        //////////////////////
        // PROFILE TRACKING //
        //////////////////////

        //add current profile status to tracking table, if it has changed
        if(isset($input['profile']))
        {
            $query = "SELECT 1 FROM profile WHERE profile = '{$input['profile']}' AND profile_start = {$input['profile_start']}
                      AND profile_length = {$input['profile_length']} AND profile_reason = '{$input['profile_reason']}' AND
                      profile_limitations = '{$input['profile_limitations']}' AND id = {$input['id']}";
            $profile_result = mysql_query($query) or die("Error checking if profile has changed: " . mysql_error());
            if(mysql_num_rows($profile_result) == 0)
            {
                $profile_query = "INSERT INTO profile (ID,profile, profile_start, profile_length, profile_reason, profile_limitations)
                                  VALUES ({$input['id']},'{$input['profile']}',{$input['profile_start']},{$input['profile_length']},'{$input['profile_reason']}',
                                  '{$input['profile_limitations']}')";
                $profile_insert = mysql_query($profile_query)
                    or die("Error inserting profile: " . mysql_error());
            }
        }

        //////////////
        // AIRBORNE //
        //////////////

        //Insert or update Airborne data if present
        if(isset($input['ab_type']) && $input['airborne'] == "Y")
        {
            //see if soldier has current ab row in table
            $result = mysql_query("SELECT 1 FROM airborne WHERE id = " . $input['id']);
            if(mysql_num_rows($result) > 0)
            {
                $ab_query = "UPDATE airborne SET vol_date = '" . $input['vol_date']
                    ."', packet_init = '" . $input['packet_init'] . "', type = '" . $input['ab_type']
                    ."', physical1 = '" . $input['physical1'] . "', physical2 = '" . $input['physical2']
                    ."', submit_4187 = '" . $input['submit_4187'] . "', packet_ti = '"
                    . $input['packet_ti'] . "', remark = '" . $input['ab_remark'] . "' WHERE id = "
                    . $input['id'];
            }
            else
            {
                $ab_query = "INSERT INTO airborne (id,vol_date,packet_init,type,physical1,"
                        ."physical2,submit_4187,packet_ti,remark) VALUES ({$input[id]},'"
                        ."{$input[vol_date]}','{$input[packet_init]}','"
                        ."{$input[ab_type]}','{$input[physical1]}','{$input[physical2]}','"
                        ."{$input[submit_4187]}','{$input[packet_ti]}','{$input[ab_remark]}')";
            }

            $result = mysql_query($ab_query) or die("ab insert error: " . mysql_error());
        }
        else
        {
            //delete any ab information for this soldier, if it's present
            $result = mysql_query("DELETE FROM airborne WHERE id = " . $input['id']) or die("airborne delete error: " . mysql_error());
        }

        ////////////
        // EXODUS //
        ////////////

        //check if exodus data is present. if it is
        //insert it into exodus table
        if(isset($input['exodus_status']) && $input['exodus_status'] != 'Returned')
        {
            $query = "REPLACE INTO exodus (id, entered, exodus_status, dep_datetime, "
                    ."ret_datetime, dep_mode, ret_mode, dep_pov_type, ret_pov_type, dep_ticket_status, "
                    ."ret_ticket_status, dep_airport, ret_airport, dep_airline, ret_airline, "
                    ."dep_flight_num, ret_flight_num, dep_air_bus_ticket, ret_air_bus_ticket,bought_dep_bustic_onpost,"
                    ."bus_dest_city,bus_dest_state,gate, comment) VALUES "
                    ."({$input[id]},now(),'{$input[exodus_status]}', "
                    ."'{$input[dep_datetime]}','{$input[ret_datetime]}','{$input[dep_mode]}', "
                    ."'{$input[ret_mode]}','{$input[dep_pov_type]}','{$input[ret_pov_type]}', "
                    ."'{$input[dep_ticket_status]}','{$input[ret_ticket_status]}','{$input[dep_airport]}', "
                    ."'{$input[ret_airport]}','{$input[dep_airline]}','{$input[ret_airline]}', "
                    ."'{$input[dep_flight_num]}','{$input[ret_flight_num]}','{$input[dep_air_bus_ticket]}', "
                    ."'{$input[ret_air_bus_ticket]}','{$input['bought_dep_bustic_onpost']}',"
                    ."'{$input[bus_dest_city]}','{$input[bus_dest_state]}','{$input['gate']}','{$input['exodus_comment']}')";
            $result = mysql_query($query) or die("exodus replace error: " . mysql_error());
        }

        /////////
        // TDA //
        /////////
        if(in_array($input['pers_type'],$_CONF['perm_party']))
        {
            //Delete any existing TDA information if empty data was submitted
            if(empty($insert['assigned_tda_id']) && empty($input['working_tda_id']) && empty($input['tda_comment']))
            {
                $query = "DELETE FROM tda_assigned WHERE id = {$input['id']}";
                $result = mysql_query($query) or die('Unable to delete TDA information: ' . mysql_error());
            }
            else
            {
                if(!$input['assigned_tda_id']) { $input['assigned_tda_id'] = 'NULL'; }
                if(!$input['working_tda_id']) { $input['working_tda_id'] = 'NULL'; }

                //Attempt to insert new data into table
                $query = "INSERT INTO tda_assigned (id, assigned_tda_id, working_tda_id, comment) VALUES
                          ({$input['id']}, {$input['assigned_tda_id']}, {$input['working_tda_id']}, '{$input['tda_comment']}')";
                $result = mysql_query($query);

                if(!$result)
                {
                    //If insert fails, try to update an existing row, otherwise fail
                    $query = "UPDATE tda_assigned SET assigned_tda_id = {$input['assigned_tda_id']},
                          working_tda_id = {$input['working_tda_id']}, comment = '{$input['tda_comment']}'
                          WHERE id = {$input['id']}";
                    $result = mysql_query($query) or die('Unable to add/edit TDA information: ' . mysql_error());
                }
            }
        }
    }

    //if errors from form being submitted, display them
    if($val->iserrors())
    { echo $val->geterrors(); }
    else
    {
        //show successful message and erase the form data
        echo "<center><p><font size='5' color'red'>soldier edited successfully!</font></p></center>";
    }

    $_GET["id"] = $input["id"];

}

//if id has been passed in url,
//look up data for the id and display
//edit form
if(isset($_GET["id"]))
{
    //ensure viewer has permission 2 (edit soldier)
    //for the id that was passed or is trying to
    //edit their own information
    if($_GET['id'] != $_SESSION['user_id'] && (!$val->id($_GET["id"],2,1)))
    {
        //user does not have permission to
        //edit this soldier. display message,
        //footer, and exit.
        echo "invalid permissions";
        echo com_sitefooter();
        exit();
    }

    //$_GET['id'] is safe to use in queries after this point

    //check if user has permission to view full ssn
    //and therefore edit full ssn on soldier id.
    if($val->id($_GET["id"],12,1))
    {
        $allow_full_ssn = 1;
    }

    //retrieve information from database
    $main_query =
        "SELECT "
            ."m.ssn, m.first_name, m.last_name, m.middle_initial, m.rank, m.promotable, "
            ."upper(date_format(m.dob,'%d%b%y')) as dob, m.company, m.platoon, upper(date_format(m.ets,'%d%b%y')) "
            ."as ets, m.pers_type, m.blood_type, m.dental_category, m.security, "
            ."upper(date_format(m.hiv_date,'%d%b%y')) as hiv_date, m.gender, m.marital_status, "
            ."m.num_dependents, m.race, m.mos, m.component, m.pcs_uic, m.building_number, m.room_number, "
            ."m.religion, m.education, m.colleges, upper(date_format(m.arrival_date,'%d%b%y')) as "
            ."arrival_date, m.pov_make, m.pov_model, m.pov_year, m.pov_state, m.pov_tag, "
            ."m.gaining_unit, m.status, m.inact_status, m.location, "
            ."upper(date_format(m.date_entered_service,'%d%b%y')) as date_entered_service, m.height, "
            ."m.weight, m.hair_color, m.eye_color, m.us_citizen, m.special_skills, m.sports, "
            ."m.battalion, upper(date_format(m.pcs_date,'%d%b%y')) as pcs_date, m.status_remark, "
            ."m.flagged, upper(date_format(m.flag_date,'%d%b%y')) as flag_date, m.email, "
            ."m.pcs, m.pcs_type, m.pcs_remark, m.ets_chapter_type, m.profile, ucase(date_format(m.profile_start,'%d%b%y')) as profile_start, "
            ."ucase(date_format(m.profile_end,'%d%b%y')) as profile_end, ucase(date_format(m.recovery_end,'%d%b%y')) as recovery_end, "
            ."m.profile_reason,m.not_graduating, upper(date_format(m.dor,'%d%b%y')) as dor, "
            ."m.post_decal, m.center_access_decal, m.housing_decal, m.cac, m.pcs_location "
        ."FROM "
            ."main m WHERE m.id = " . $_GET['id'];

    $main_result = mysql_query($main_query) or die("main query error: " . mysql_error());

    //if no row is returned, show
    //choose soldier dialog only
    if(mysql_num_rows($main_result) != 1)
    {
        echo "Warning: Two rows retrieved. Database corruption.";
    }
    else
    {
        //retrieve row from result set
        $main_row = mysql_fetch_array($main_result);
        //set applies to value based on personnel type
        //of data retrieved. used later to select appropriate
        //status selection box
        if(in_array($main_row['pers_type'],$_CONF['perm_party']))
        { $applies_to = "permanent party"; }
        else
        { $applies_to = "student"; }

        //retrieve information from student table
        $student_query =
            "SELECT swim, upper(date_format(swim_date,'%d%b%y')) as swim_date, heat,
            upper(date_format(heat_date,'%d%b%y')) as heat_date, cold, upper(date_format(cold_date,'%d%b%y'))
            as cold_date, airborne, class_id, shift, hrap, birth_city, birth_state,
            birth_country, civilian_occupation, basic_training_POST, aot_type, phase,
            ctt, upper(date_format(ctt_date,'%d%b%y')) as ctt_date, assignment, honor_grad, add_to_exodus,
            dist_grad, high_pt, meps, academic_avg, test_failures,
            upper(date_format(date_phaseiv,'%d%b%y')) as date_phaseiv,
            upper(date_format(date_phasev,'%d%b%y')) as date_phasev,
            upper(date_format(date_phaseva,'%d%b%y')) as date_phaseva
            FROM student WHERE id = {$_GET['id']}";

        $student_result = mysql_query($student_query) or die("student query error: " . mysql_error());
        if(mysql_num_rows($student_result) != 1)
        {
            //if no row is returned, set string to empty array.
            //this is important for later on when the script
            //will expect a value in student_row
            $student_row = array();
        }
        else
        {
            //if row is found, assign it to variable
            $student_row = mysql_fetch_array($student_result);

            if(empty($student_row['date_phaseiv']))
            { $student_row['date_phaseiv'] = $main_row['arrival_date']; }
            $student_row['proj_date_phasev'] = strtoupper(date('dMy',strtotime($main_row['arrival_date'] . ' + 28 days')));
            $student_row['proj_date_phaseva'] = strtoupper(date('dMy',strtotime($main_row['arrival_date'] . ' + 77 days')));

            //if student is airborne, retrieve airborne info
            if($student_row['airborne'] == "Y")
            {
                $ab_query = "SELECT upper(date_format(vol_date,'%d%b%y')) as vol_date, "
                        ."upper(date_format(packet_init,'%d%b%y')) as packet_init, "
                        ."type, upper(date_format(physical1,'%d%b%y')) as physical1, "
                        ."upper(date_format(physical2,'%d%b%y')) as physical2, "
                        ."upper(date_format(submit_4187,'%d%b%y')) as submit_4187, "
                        ."upper(date_format(packet_ti,'%d%b%y')) as packet_ti, "
                        ."remark FROM airborne WHERE id=" . $_GET['id'];
                       
                $ab_result = mysql_query($ab_query) or die("airborne query error: " . mysql_error());
                if(mysql_num_rows($ab_result) == 1)
                { $ab_row = mysql_fetch_assoc($ab_result); }
            }

            //retrive any exodus information
            if($_CONF['exodus'] == "on")
            {
                $query = "SELECT exodus_status, upper(date_format(dep_datetime,'%d%b%y')) as dep_date, "
                        ."dep_mode, ret_mode, dep_pov_type, ret_pov_type, dep_ticket_status, "
                        ."ret_ticket_status, dep_airport, ret_airport, dep_airline, ret_airline, "
                        ."dep_flight_num, ret_flight_num, dep_air_bus_ticket, ret_air_bus_ticket, "
                        ."upper(date_format(ret_datetime,'%d%b%y')) as ret_date, "
                        ."upper(date_format(ret_datetime,'%H%i')) as ret_time, "
                        ."upper(date_format(dep_datetime,'%H%i')) as dep_time, "
                        ."bought_dep_bustic_onpost, bus_dest_city, bus_dest_state, gate, comment, returned "
                        ."FROM exodus WHERE id = {$_GET['id']}";

                $result = mysql_query($query) or die("exodus select error: " . mysql_error());
                if(mysql_num_rows($result))
                {
                    $exodus_row = mysql_fetch_assoc($result);
                    if($exodus_row['exodus_status'] == "")
                    { $exodus_row['exodus_status'] = "Unconfirmed Travel Plans"; }
                    elseif($exodus_row['returned'] == 1)
                    {
                        $exodus_row['old_exodus_status'] = $exodus_row['exodus_status'];
                        $exodus_row['exodus_status'] = 'Returned';
                    }
                }
            }
        }

        //retrieve location information from database
        $location_query =
            "SELECT detached_bn,detached_co,attached_bn,attached_co, position, upper(date_format(effective,'%d%b%y')) "
            ."AS effective, reason FROM location WHERE id = " . $_GET["id"];

        $location_result = mysql_query($location_query) or die("location query error: " . mysql_error());
        if(mysql_num_rows($location_result) != 1)
        {
            $location_row = array();
        }
        else
        {
            $location_row = mysql_fetch_array($location_result);
        }

        //retrieve address information from database
        $x = 0;
        $address_result = mysql_query("SELECT address_id, type, name, relationship, street1, street2, city, state, zip, phone1, phone2, country FROM address WHERE id = " . $_GET["id"] . " order by type") or die("existing address error: " . mysql_error());
        while($a = mysql_fetch_array($address_result))
        {
            $address_row["address_id"][$x] = $a["address_id"];
            $address_row["address_type"][$x] = $a["type"];
            $address_row["name"][$x] = $a["name"];
            $address_row["relationship"][$x] = $a["relationship"];
            $address_row["street1"][$x] = $a["street1"];
            $address_row["street2"][$x] = $a["street2"];
            $address_row["city"][$x] = $a["city"];
            $address_row["state"][$x] = $a["state"];
            $address_row["zip"][$x] = $a["zip"];
            $address_row["phone1"][$x] = $a["phone1"];
            $address_row["phone2"][$x] = $a["phone2"];
            $address_row["country"][$x] = $a["country"];
            $x++;
        }

        //select current profiles
        $query = "SELECT profile_id, profile, UPPER(DATE_FORMAT(profile_start,'%d%b%y')) as start,
                  IF(LEFT(profile,1)='P','Perm',UPPER(DATE_FORMAT(profile_start + INTERVAL profile_length DAY,'%d%b%y'))) AS profile_end,
                  IF(LEFT(profile,1)='P','Perm',UPPER(DATE_FORMAT(profile_start + INTERVAL LEAST(profile_length+90,profile_length*3) DAY,'%d%b%y'))) AS recovery_end,
                  profile_reason, profile_limitations FROM profile WHERE id = {$_GET['id']}
                  AND ((CURDATE() >= profile_start AND CURDATE() <= (profile_start + INTERVAL LEAST(profile_length+90,profile_length*3) DAY))
                  OR (LEFT(Profile,1)='P'))
                  ORDER BY profile_start DESC LIMIT 10";
        $profile_result = mysql_query($query) or die("Error retrieving current profiles: " . mysql_error());
        while($p = mysql_fetch_assoc($profile_result))
        { $profile_row[] = $p; }

        //select current tda position
        $query = "SELECT ta.assigned_tda_id, ta.working_tda_id, ta.comment
                  FROM tda_assigned ta LEFT JOIN tda t ON ta.assigned_tda_id = t.tda_id AND ta.working_tda_id = t.tda_id
                  WHERE ta.id = {$_GET['id']} AND (t.year = {$_CONF['tda_year']} OR t.year IS NULL)";
        $result = mysql_query($query) or die('Error retrieving TDA positions: ' . mysql_error());

        $tda_row = mysql_fetch_assoc($result);

        //turn down error reporting to elimnate
        //notices from null values returned from
        //database
        error_reporting(E_ERROR | E_WARNING | E_PARSE);

        //include html that creates edit form
        include($_CONF["path"] . "templates/edit_soldier.inc.php");
    }
}

echo com_sitefooter();
?>
