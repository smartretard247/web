<?
include("lib-common.php");

//see if edit was chosen in com_choosesoldier
//and redirect to edit page with chosen id
if(isset($_GET["com_cs_action"]) && $_GET["com_cs_action"] == "edit")
{
    header("location: " . $_CONF["html"] . "/edit_soldier.php?id=" . $_GET["id"]);
    exit();
}

//include class to handle validation
include_once($_CONF["path"] . "classes/validate.class.php");

//create new validation class
$val = new validate;

//set defaults
$allow_full_view = 0;
$allow_full_ssn = 0;
$soldier_is_student = 0;
$soldier_has_address = 0;
$permission_id = 11;

//display header
$display = com_siteheader("USAP - View Soldier Information");

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

//determine if id has been passed in the url
if(isset($_GET["id"]))
{
    $id = (int)$_GET['id'];

    //see if current user has permission to
    //view full information on this soldier
    if($val->id($_GET["id"],11) || $_GET['id'] == $_SESSION['user_id'])
    { $allow_full_view = 1; }

    //see if current user has permission to view
    //full information for the unit the soldier
    //is _detached_ from, if the soldier is detached
    $query = "SELECT 1 FROM location l, user_permissions up where l.id = $id and up.user_id = {$_SESSION['user_id']}
              and up.permission_id = 11 and up.battalion_id = l.detached_bn and up.company_id = l.detached_co";
    $result = mysql_query($query) or die("Error checking for detached soldier: " . mysql_error());
    if(mysql_num_rows($result)>0)
    { $allow_full_view=1; }

    //see if current user has permission to
    //view full ssn on this soldier
    if($val->id($_GET["id"],12))
    { $allow_full_ssn = 1; }


    //retrieve data for given id
    $main_query =
    "SELECT if($allow_full_ssn,m.ssn,concat('XXX-XX-',right(m.ssn,4))) as ssn, m.first_name, m.last_name, 
            m.middle_initial, m.rank, ucase(date_format(m.dob,'%d%b%y')) as dob, to_days(curdate()) - to_days(m.dob) as age, c.company,
            m.platoon, ucase(date_format(m.ets,'%d%b%y')) as ets, m.pers_type, m.blood_type, 
            m.dental_category, m.security, ucase(date_format(m.hiv_date,'%d%b%y')) as hiv_date, 
            m.gender, m.marital_status, m.num_dependents, m.race, m.mos, m.component, 
            m.pcs_uic, m.building_number, m.room_number, m.religion, m.education, m.colleges, 
            ucase(date_format(m.arrival_date,'%d%b%y')) as arrival_date, to_days(curdate()) - to_days(m.arrival_date) as days_since_arrival, m.pov_make, m.pov_model, 
            m.pov_year, m.pov_state, m.pov_tag, m.gaining_unit, st.status, st2.status as inact_status, m.location, ucase(date_format(m.date_entered_service,'%d%b%y')) as date_entered_service,
            to_days(curdate()) - to_days(m.date_entered_service) as days_since_enter, m.height, m.weight, m.hair_color, m.eye_color, 
			m.us_citizen, m.special_skills, m.sports, b.battalion, ucase(date_format(m.pcs_date,'%d%b%y')) as pcs_date, m.status_remark, m.flagged, 
			ucase(date_format(m.flag_date,'%d%b%y')) as flag_date, m.email, m.pcs, m.pcs_type, m.pcs_remark, m.ets_chapter_type, m.profile, 
			ucase(date_format(m.profile_start,'%d%b%y')) as profile_start, ucase(date_format(m.profile_end,'%d%b%y')) as profile_end, 
			ucase(date_format(m.recovery_end,'%d%b%y')) as recovery_end, m.profile_reason, m.promotable, s2.clearance_status, s2.derog_issue,
            upper(date_format(s2.status_date,'%d%b%y')) as status_date, upper(date_format(m.dor,'%d%b%y')) as dor, 
			upper(date_format(m.dental_date,'%d%b%y')) as dental_date, m.post_decal, m.center_access_decal, m.housing_decal, m.cac, m.pcs_location
    FROM main m left join status st2 on m.inact_status = st2.status_id left join s2 on m.id = s2.id, 
	     company c, status st, battalion b 
	WHERE m.id= $id and m.company = c.company_id 
          and m.status = st.status_id
          and m.battalion = b.battalion_id";

    $main_result = mysql_query($main_query) or die("main select error [$main_query]: " . mysql_error());
    if($main_row = mysql_fetch_array($main_result))
    {
        //retrieve address information
        $address_query = "SELECT a.type, a.name, a.relationship, a.street1, a.street2, a.city, a.state, a.zip, a.country, a.phone1, a.phone2 
		                  FROM address a 
						  WHERE a.id = " . $_REQUEST["id"] . " order by a.type";
        $address_result = mysql_query($address_query) or die("existing address error[$address_query]: " . mysql_error());
        if(mysql_num_rows($address_result) > 0) { $soldier_has_address = 1; }

        //retrieve location information
        $location_query = "SELECT b1.battalion as detached_bn, b2.battalion as attached_bn,
                           c1.company as detached_co, c2.company as attached_co, l.position,
                           ucase(date_format(l.effective,'%d%b%y')) as effective, l.reason 
						   FROM location l, battalion b1, battalion b2, company c1, company c2 
						   WHERE l.id=" . (int)$_REQUEST['id'] . " and l.detached_bn = b1.battalion_id
                           and l.detached_co = c1.company_id and l.attached_bn = b2.battalion_id
                           and l.attached_co = c2.company_id";
        $location_result = mysql_query($location_query) or die("location select error [$location_query]: " . mysql_error());
        $location_row = mysql_fetch_array($location_result);

        //retrieve student information
        $student_query = "SELECT s.swim, ucase(date_format(s.swim_date,'%d%b%y')) as swim_date, s.heat,
                          ucase(date_format(s.heat_date,'%d%b%y')) as heat_date, s.cold, ucase(date_format(s.cold_date,'%d%b%y'))
                          as cold_date, s.airborne, c.class_id, c.class_number, c.mos, s.shift, s.hrap, s.birth_city,
                          s.birth_state, s.birth_country, s.civilian_occupation, s.basic_training_post, s.aot_type, s.ctt,
                          ucase(date_format(s.ctt_date,'%d%b%y')) as ctt_date, s.phase, s.meps, s.academic_avg, s.test_failures,
                          upper(date_format(s.date_phaseiv,'%d%b%y')) as date_phaseiv, upper(date_format(s.date_phasev,'%d%b%y')) as date_phasev,
                          upper(date_format(s.date_phaseva,'%d%b%y')) as date_phaseva, s.assignment
                          FROM student s left join class c on s.class_id = c.class_id where s.id = " . $_REQUEST["id"];
        $student_result = mysql_query($student_query) or die("student select error [$student_query]: " . mysql_error());
        if(mysql_num_rows($student_result) > 0)
        {
            $student_row = mysql_fetch_array($student_result);
            $soldier_is_student = 1;

            $student_row['proj_date_phasev'] = strtoupper(date('dMy',strtotime($main_row['arrival_date'] . ' + 28 days')));
            $student_row['proj_date_phaseva'] = strtoupper(date('dMy',strtotime($main_row['arrival_date'] . ' + 77 days')));

            //if student is airborne, retrieve airborne info
            if($student_row['airborne'] == "Y")
            {
                $ab_query = "SELECT upper(date_format(vol_date,'%d%b%y')) as vol_date, upper(date_format(packet_init,'%d%b%y')) as packet_init,
                                    type, upper(date_format(physical1,'%d%b%y')) as physical1, upper(date_format(physical2,'%d%b%y')) as physical2,
									upper(date_format(submit_4187,'%d%b%y')) as submit_4187, upper(date_format(packet_ti,'%d%b%y')) as packet_ti,
                                    remark 
							 FROM airborne 
							 WHERE id= $id";
                $ab_result = mysql_query($ab_query) or die("airborne query error: " . mysql_error());
                if(mysql_num_rows($ab_result) == 1)
                { $ab_row = mysql_fetch_assoc($ab_result); }
            }

            //retrive any exodus information
            if($_CONF['exodus'] == "on")
            {
                $query = "SELECT exodus_status, upper(date_format(dep_datetime,'%d%b%y')) as dep_date,
                                 dep_mode, ret_mode, dep_pov_type, ret_pov_type, dep_ticket_status,
                                 ret_ticket_status, dep_airport, ret_airport, dep_airline, ret_airline,
                                 dep_flight_num, ret_flight_num, dep_air_bus_ticket, ret_air_bus_ticket,
                                 upper(date_format(ret_datetime,'%d%b%y')) as ret_date,
                                 upper(date_format(ret_datetime,'%H%i')) as ret_time,
                                 upper(date_format(dep_datetime,'%H%i')) as dep_time, bought_dep_bustic_onpost,
                                 bus_dest_city, bus_dest_state, gate, comment, returned
                          FROM exodus 
						  WHERE id = $id";

                $result = mysql_query($query) or die("exodus select error: " . mysql_error());
                if(mysql_num_rows($result))
                { $exodus_row = mysql_fetch_assoc($result); }
                else
                { $exodus_row['exodus_status'] = $_CONF['exodus_status'][0]; }

                if($exodus_row['returned'] == 1)
                {
                    $exodus_row['old_exodus_status'] = $exodus_row['exodus_status'];
                    $exodus_row['exodus_status'] = 'Returned';
                }
            }
        }
        //select current profiles
        $query = "SELECT profile_id, profile, UPPER(DATE_FORMAT(profile_start,'%d%b%y')) as start,
                         IF(LEFT(profile,1)='P','Perm',UPPER(DATE_FORMAT(profile_start + INTERVAL profile_length DAY,'%d%b%y'))) AS profile_end,
                         IF(LEFT(profile,1)='P','Perm',UPPER(DATE_FORMAT(profile_start + INTERVAL LEAST(profile_length+90,profile_length*3) DAY,'%d%b%y'))) AS recovery_end,
                         profile_reason, profile_limitations 
				  FROM profile 
				  WHERE id = {$_GET['id']} AND ((CURDATE() >= profile_start AND CURDATE() <= (profile_start + INTERVAL LEAST(profile_length+90,profile_length*3) DAY))
                       OR (LEFT(Profile,1)='P'))
                  ORDER BY profile_start DESC LIMIT 10";
        $profile_result = mysql_query($query) or die("Error retrieving current profiles: " . mysql_error());
        while($p = mysql_fetch_assoc($profile_result))
        { $profile_row[] = $p; }

        if(in_array($main_row['pers_type'],$_CONF['perm_party']))
        {
            //Retrieve TDA Information
            $query = "SELECT t1.para, t1.ln, t1.position, t1.gr, t1.mdep, t1.req, t1.auth, t2.para as para2, t2.ln as ln2, 
			                 t2.position as position2, t2.gr as gr2, t2.mdep as mdep2, t2.req as req2, t2.auth as auth2, ta1.comment
                      FROM tda_assigned ta1 LEFT JOIN tda t1 ON ta1.assigned_tda_id = t1.tda_id, tda_assigned ta2 
					       LEFT JOIN tda t2 ON ta2.working_tda_id = t2.tda_id
                      WHERE ta1.id = {$_GET['id']} AND ta2.id = {$_GET['id']}";
            $result = mysql_query($query) or die('Unable to retrieve TDA information: ' . mysql_error());
            $tda_row = mysql_fetch_assoc($result);

            if(empty($tda_row['position']))
            { $tda_row['position'] = 'Excess'; }
            if(empty($tda_row['position2']))
            { $tda_row['position2'] = 'Excess'; }
        }

        //include page that has the html
        //to display the soldier data
        include_once($_CONF["path"] . "templates/view_soldier.inc.php");
    }
    else
    { echo "Soldier not found in database."; }
}

if(count($_GET) == 0 && count($_POST) == 0)
{ echo "<br><br><center><font size='+1'>if you do not have view permissions, you can <a href='" . $_CONF['html'] . "/search.php'>search</a> for a soldier and view basic information on them.</font></center>\n"; }

//show footer
echo com_sitefooter();

?>
