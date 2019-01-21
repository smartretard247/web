<?
//define variables
$_CONF = array();
$errormsg = array();

$_CONF['start_date'] = '20020507';

//path to usap
$_CONF["path"] = "";

//web address to root folder (no trailing /)
// change https to http for local use, also add USAP directory !!! <===========================
$_CONF['web'] = 'http://' . $_SERVER['SERVER_NAME'] . ":4001/usap";
$_CONF['html'] = $_CONF['web'];
// why 2 references with the same value??? "web" and "html" <===========================
$_CONF['current_page'] = 'https://' . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'];
$_CONF['current_page_with_query_string'] = $_CONF['current_page'] . '?' . @$_SERVER['QUERY_STRING'];

//web address to admin folder (no trailing /)
$_CONF['admin_html'] = $_CONF['html'] . '/admin';

//database connection information
$_CONF["db_host"] = "192.168.1.100:7188";  // 7188 port added for local use only <===========================
$_CONF["db_user"] = "Jeezy";
$_CONF["db_password"] = "Bliss20106";
$_CONF["db_name"] = "USAP";

//shows html table containing debug values (on/off)
$_CONF["debug_mode"] = "off";

//*** not implemented ***
//writes tracing data to log file
// use com_verbose(" __message here__ ");
// to write a message to the log file
//can also be enabled in an individual file
//$_CONF["verbose"] = "off";
//turn on or off error logging
// use com_error(" __error here__ ");
// to log an error
$_CONF["log_errors"] = "on";
//*** not implemented ***

//file to log errors to (also used for verbose logging)
$_CONF["error_log"] = "f:/error.log";

//check password against rules (on/off)
$_CONF["check_password"] = "on";
$_CONF["min_password_length"] = 8;

$_CONF['months'] = array("January","February","March","April","May","June","July","August","September","October","November","December");

//Used to check date of birth
//for a minimum age
$_CONF['min_age'] = 17;

//values for various select boxes
$_CONF["blood_type"] = array('A-','A','A+','B-','B','B+','AB-','AB','AB+','O-','O','O+');

$_CONF["component"] = array("Regular Army","Army Reserves","National Guard","Civilian","Air Force","Navy","Marines","Other");
$_CONF["component_abbrev"] = array("RA","ER","NG","CIV","AF","NVY","MRN","OTHR");

$_CONF["rank"] = array('PVT','PV2','PFC','SPC','CPL','SGT','SSG','SFC','MSG','1SG','SGM','CSM','WO1','WO2','WO3','WO4','WO5','2LT','1LT','CPT','MAJ','LTC','COL','BG','MG','LG','CIVILIAN','GS4','GS5','GS6','GS7','GS8','GS9','GS10','GS11','GS12','GS13','GS14','None');
$_CONF["gender"] = array("M","F");
$_CONF["location"] = array("Organic","Attached","Detached");
$_CONF["platoon"] = array("HQ","1","2","3","4","5","6","7","8","MED","INACT");
$_CONF["yn"] = array("Y","N");
$_CONF["dental_category"] = array('1','2','3','4');
$_CONF["shift"] = array("None","Day","Swing","Mid", "First", "Second", "Third", "4th");
$_CONF["marital_status"] = array("Single","Married","Married (Dual Military)","Divorced");
$_CONF["education"] = array("Not Graduated High School","GED","High School Graduate","Some college","Associates Degree","Bachelors Degree","Masters Degree","PHD");

$_CONF["address_type"] = array("None","Home-of-Record","Contact","Next-of-Kin","HRAP","Exodus","Local / Off-Post","Emergency Contact","Weekend Pass","Other");
$_CONF['race'] = array('Asian Pacific','Black','Hispanic','Native American','Other','White');

$_CONF["pers_type"] = array("IET","Non-IET","Permanent Party","Civilian","WO Student");
$_CONF["students"] = array("IET","Non-IET","WO Student");
$_CONF["perm_party"] = array("Permanent Party","Civilian");
$_CONF['military'] = array('Permanent Party','IET','Non-IET','WO Student');

$_CONF["mos"] = array("00Z","01A","01C","15A","24A","25A","25B","25C","25F","25L","25M","25N","25P","25Q","25R","25S","25T","25U","25V","25W","25Z","27D",
                    "35D","35E","35F","35J","35L","35N","35R","35W","35Z","44A","46Q","46R","46Z","51A","51R",
                    "51Z","53A","55A","56A","56M","71L","74B","74C","74Z","75B",
                    "75H","79S","91B","92A","92G","92Y",
                    "250N","251A","918B","Other");
                    
$_CONF["daily_status_1"] = array("ACTIVE", "Other than Active"); // what is "other than active"??? - Inactive??? <===========================

$_CONF["apft_type"] = array("Student-Diag","Student-EOC","BCT-Diag","BCT-EOC","PP-Diag","PP-Record");
$_CONF['alt_events'] = array('N/A','2.5 Mile Walk','800 Yard Swim','6.2 Mile Stationary Bike','6.2 Mile Standard Bike');

$_CONF["results_per_page"] = array("10","20","30","40","50");
$_CONF["bct_location"] = array("Ft. Benning","Ft. Jackson","Ft. Knox","Ft. Leonardwood","Ft. Sill","Other");
$_CONF["aot_type"] = array("Unknown","NON-AOT","AOT-ECB","AOT-EAC","AOT-Strategic","AOT-Tactical");
$_CONF["phase"] = array("IV","V","V+","Other",'N/A');
$_CONF["pcs_type"] = array("PCS","Retirement","Chapter","ETS","DFR","Deceased");
$_CONF["chapter_type"] = array("None",
                        "Chapter 5-8 Involuntary Seperation Due to Parenthood",
                        "Chapter 5-11 Did Not Meet Medical Fitness Standards",
                        "Chapter 5-13 Personality Disorder",
                        "Chapter 5-14 Concealment of Arrest Record",
                        "Chapter 5-17 Other Designated Physical or Mental Conditions",
                        "Chapter 6 Dependency or Hardship",
                        "Chapter 7 Defective Enlistment",
                        "Chapter 8 Pregnancy",
                        "Chapter 9 Alcohol or Other Drug Failure",
                        "Chapter 11 Entry Level Status",
                        "Chapter 13 Unsatisfactory Performance",
                        "Chapter 14 Misconduct",
                        "Chapter 15 Homosexuality",
                        "Chapter 16 Changes in Service Obligation",
                        "Chapter 18 Failure to Meet Weight Standard");
$_CONF["profile"] = array("None","Temp","P1","P2","P3","P4");
$_CONF['ab_type'] = array("Contract","Volunteer");
$_CONF['religion'] = array("7th Day Adventist","Baptist","Buddhism","Christian","Episcopal","Greek Orthodox","Hindu",
                            "Jehovah Witness","Jewish","Lutheran","Methodist","Mormon/LDS","Muslim/Islam",
                            "Nazarene","No Preference","None","Other","Pentecostal","Presbyterian","Protestant","Roman Catholic",
                            "Wicca");
$_CONF['hrap'] = array('Eligible - Not Participating','Eligible - Participating','Not Eligible');

$_CONF['phase_roster'] = array('all' => 'All Assigned',
                               'out_of_phase' => 'All Out of Phase',
                               'phaseiv_phaseback' => 'Phase IV Phaseback',
                               'phasev_phaseback' => 'Phase V Phaseback',
                               'past_phasev_date' => 'Phase IV Not Phased Up',
                               'past_phaseva_date' => 'Phase V Not Phased Up',
                               'late_phasev' => 'Phased V Late',
                               'late_phaseva' => 'Phased V+ Late',
                               'outofphase_latephase' => 'All Out of Phase & Phased Late');
$_CONF['pcs_location'] = array('','CONUS','OCONUS');


//exodus information =====================================================================================
//exodus information on or off
$_CONF['exodus'] = "off"; //on or off
//turn on/off ability to edit exodus records
$_CONF['exodus_edit'] = "off";
$_CONF['exodus_edit_off'] = '15JAN11 17:00:00';
//id numbers of personnel allowed to edit exodus records
//after the edit ability has been turned off
$_CONF['exodus_edit_allowed'] = array(46816,68333,78571,93793,83305); // This should be a database table <===========================
//Dee Piper, MAJ Johnson, CPT Lawson, CPT Merritt, MSG Perry

//date of exodus
$_CONF['exodus_date'] = '18DEC2010';
//date that people return from exodus
$_CONF['exodus_end'] = "03JAN2011";
//datetime that people returning before
//will be considered "returning early"
//Format: YYYYMMDDHHMMSS
$_CONF['exodus_return_early'] = "20110102000000";

//date people leave for 3K
$_CONF['three_kings_start'] = "03JAN2011";
//date people return from 3K
$_CONF['three_kings_end'] = "07JAN2011";

//date to start/end collecting exodus data
$_CONF['exodus_data_start'] = "01OCT2010";
$_CONF['exodus_data_end'] = "10JAN2011";

$_CONF['exodus_modes'] = array("POV","BUS","AIR","RAIL");
$_CONF['exodus_pov_types'] = array("Driver","Passenger");
$_CONF['exodus_status'] = array("Unconfirmed Travel Plans",
                                "Exodus Leave (Return)",
                                "Exodus PCS (Not Returning)",
                                "Three Kings Leave (Return)",
                                "Three Kings PCS (Not Returning)",
                                "Holding Company / Exodus Leave",
                                "Holding Company / PCS",
                                "Holding Company - On Post",
                                "Holding Company - Off Post",
                                "Returned",
                                "Planned Air Atlanta",
                                "Planned Air Augusta",
                                "Planned Bus",
                                "Planned POV",
                                "Planned Three Kings",
                                "Planned Holding Company",
                                "NG/ER PCS Prior to Exodus",
                                "Other PCS/Chapter Prior to Exodus");

$_CONF['exodus_ticket_status'] = array("Unconfirmed","In Hand");
$_CONF['exodus_airports'] = array("Atlanta","Augusta","BWI");
$_CONF['exodus_airlines'] = array("Air Aruba","Air Canada","Air France","Air Jamaica","Air Trans",
                                  "Alaska Air","America West","American","Atlantic Southeast",
                                  "British Airways","Canadian Airlines","Carnival",
                                  "Continental","Delta","Frontier","Gulf Air",
                                  "Hawaiian Airlines","Horizon Air","Independence Air","Jet Express",
                                  "Kiwi Airlines","Korean Air","Lufthansa","Mexicana",
                                  "Midway Airlines","Midwest Express","Northwest","Olympic Airways",
                                  "Southwest","Spirit Airlines","Swiss Air","Tower Air","TWA",
                                  "United","US Airways","US Airways Express","Vanguard Airlines",
                                  "Virgin Atlantic","West Jet","World Airways","Bus to Airport Only");
$_CONF['gate_numbers'] = array('2','3','31','36','38','50','4A','4B','4C','5','6A','6B','7','8A','8B','9A','9B','9C','9D','N/A');
//exodus information ends ===========================================================================================================


//Master Driver settings
$_CONF['license_type'] = array('Bus','5-ton');

//color settings
$_CONF['up']['main_color'] = "orange";
$_CONF['up']['font_color'] = "black";
$_CONF['up']['background_color'] = "white";
$_CONF['up']['error_color'] = "red";
$_CONF['up']['notice_color'] = "blue";

$_CONF['up']['row_one_color'] = "#ffffff";
$_CONF['up']['row_two_color'] = "#dddddd";
$_CONF['up']['row_highlight_color'] = "#ffcc99";

/*************************
* SECURITY (S2) SETTINGS *
*************************/
$_CONF['clearance_status'] = array('None','Resubmit','Incomplete','Prenom','Chapter','Derog','Derog - Conditional','PMB','OPM Corrections','Not Issued',
                                   'TDP','RTU','Reclass','Secret','SSBI Required','Top Secret','No Clearance Required','NAC Only',
                                   'Upscope','Top Secret/SCI','Interim Secret','Interim Top Secret','Denied Interim Secret',
                                   'Denied Interim Top Secret','Awaiting Green Mailer','Pending NAC Closure');
$_CONF['derog_issue'] = array('None','CCC Eval','CMHS Eval','Citizenship','Debts','LOI','Police','RFI','UCMJ','Other');
$_CONF['meps'] = array("Unknown","Albany, NY","Albuquerque, NM","Amarillo, TX","Anchorage, AK","Atlanta, GA",
    "Baltimore, MD","Beckley, WV","Boise, ID","Boston, MA","Buffalo, NY","Butte, MT","Charlotte, NC",
    "Chicago, IL","Cleveland, OH","Columbus, OH","Dallas, TX","Denver. CO","Des Moines, IA","Detroit, MI",
    "El Paso, TX","Fargo, ND","Fort Dix, NJ","Fort Jackson, SC","Harrisburg, PA","Honolulu, HI","Houston, TX",
    "Indianapolis, IN","Jackson, MS","Jacksonville, FL","Kansas City, MO","Knoxville, TN","Lansing, MI","Little Rock, AR",
    "Los Angeles, CA","Louisville, KY","Memphis, TN","Miami, FL","Milwaukee, WI","Minneapolis, MN","Montgomery, AL",
    "Nashville, TN","New Orleans, LA","New York, NY","Oklahoma City, OK","Omaha, NE","Phoenix, AZ","Pittsburgh, PA",
    "Portland, ME","Portland, OR","Raleigh, NC","Richmond, VA","Sacramento, CA","Salt Lake City, UT","San Antonio, TX",
    "San Diego, CA","San Jose, CA","San Juan, PR","Seattle. WA","Shreveport, LA","Sioux Falls, SD","Spokane, WA",
    "Springfield, MA","St. Louis, MO","Syracuse, NY","Tampa, FL");


/*********************************
* Alternate Event Scoring (APFT) *
*********************************/
$_CONF["apft_type"] = array("Student-Diag","Student-EOC","BCT-Diag","BCT-EOC","PP-Diag","PP-Record");
$_CONF['alt_event'] = array('N/A','2.5 Mile Walk','800 Yard Swim','6.2 Mile Bike');

$_CONF['alt_scores'] = array('800 Yard Swim' =>
                                             array('male' => array(2000, 2030, 2100, 2130, 2200, 2230, 2300, 2400, 2430, 2500),
                                                   'female' => array(2100, 2130, 2200, 2230, 2300, 2330, 2400, 2500, 2530, 2600)),
                             '6.2 Mile Bike' =>
                                             array('male' => array(2400, 2430, 2500, 2530, 2600, 2700, 2800, 3000, 3100, 3200),
                                                   'female' => array(2500, 2530, 2600, 2630, 2700, 2800, 3000, 3200, 3300, 3400)),
                             '2.5 Mile Walk' =>
                                             array('male' => array(3400, 3430, 3500, 3530, 3600, 3630, 3700, 3730, 3800, 3830),
                                                   'female' => array(3700, 3730, 3800, 3830, 3900, 3930, 4000, 4030, 4100, 4230)));

/*********************
* EDIT GROUP OPTIONS *
*********************/
$_CONF['edit_group_field'] = array('status' => 'Daily / Inactive Status', 'remark' => 'Remarks', 'cac' => 'CAC', 'tda' => 'TDA Positions');

/**************
* TDA OPTIONS *
**************/
$_CONF['tda_year'] = 2005;


/*************************
* Events and Events Type *
*************************/

$_CONF['event'] = Array('Leave' => Array('Ordinary','Emergency','Convalescent','PTDY','PCS','Terminal'),
                        'Duty' => Array('BNSD','BDESD'));


//various error messages
$errormsg[0] = "You have entered an invalid login or password. passwords are case sensitive. Please enter login information again. When utilizing CAC Logon, this may mean your card is not registered.";
$errormsg[1] = "Your login has expired or is invalid. Please log in again.";
$errormsg[2] = "You do not have permission to access this page. Please return to the previous page or log in as another user with the correct permissions.";
$errormsg[3] = "You must have cookies enabled to use this program. Cookies allow the program to store small amounts of information on your computer. If you do not know how to enable cookies, please see your unit IMO";
$errormsg[4] = "You must have javascript enabled to use this program. Javascript enables the web pages to check and verify information provided.";
$errormsg[5] = "Generic Error. Contact your administrator.";
$errormsg[6] = "You have been logged out because you logged in at another computer. ";
$errormsg[7] = "There was an error using your DoD CAC for login. Contact SPC Matthews 791-5878.";
$errormsg[8] = "You're CAC is not registered and your account is required to use CAC for logon. Please register your CAC by <a href='cac/portal/cacRegPortal.php' target=_blank>clicking here.</a>"
?>
