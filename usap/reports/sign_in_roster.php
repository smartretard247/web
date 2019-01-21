<?
include("../lib-common.php");

include("../classes/validate.class.php");
include("../classes/roster.class.php");

$val = new validate;
$pt = array();

//validate unit
if($unit = $val->unit($_GET['unit'],14))
{
    //default sql where values
    $report["where"] = " 1 ";

    //loop through and verify each platoon passed if it's in an array
    if(is_array($_GET['platoon']))
    {
        foreach($_GET['platoon'] as $platoon)
        {
            if($valid_platoon = $val->conf($platoon,"platoon"))
            { $valid_platoons[] = $valid_platoon; }
        }
    }

    //if any valid platoons were passed, add then to string to be
    //incorporated into sql query.
    if(count($valid_platoons) > 0)
    {
      $platoon_string = "'" . implode("','",$valid_platoons) . "'";
      $report['where'] .= "and m.platoon in ($platoon_string) ";
    }

    //loop through and verify each shift passed if it's in an array
    if(is_array($_GET['shift']))
    {
        foreach($_GET['shift'] as $shift)
        {
            if($valid_shift = $val->conf($shift,"shift"))
            { $valid_shifts[] = $valid_shift; }
        }
    }

    //if any valid shifts were passed, add then to string to be
    //incorporated into sql query.
    if(count($valid_shifts) > 0)
    {
        $shift_string = "'" . implode("','",$valid_shifts) . "'";
        $report['where'] .= "and s.shift in ($shift_string) ";
    }
    //loop through and verify each gender passed if it's in an array
    if(is_array($_GET['gender']))
    {
        foreach($_GET['gender'] as $gender)
        {
            if($valid_gender = $val->conf($gender,"gender"))
            { $valid_genders[] = $valid_gender; }
        }
    }

    //if any valid genders were passed, add then to string to be
    //incorporated into sql query.
    if(count($valid_genders) > 0)
    {
        $gender_string = "'" . implode("','",$valid_genders) . "'";
        $report['where'] .= "and m.gender in ($gender_string) ";
    }

    $battalion = $unit[0];
    $company = $unit[1];

    if(count($_GET['pers_type']) > 0)
    {
        //validate each of the personal types selected
        foreach($_GET['pers_type'] as $pers_type)
        {
            //$pt[] will contain all only valid personnel types
            if($val->conf($pers_type,"pers_type"))
            { $pt[] = $pers_type; }
        }
    }

    if(count($pt) > 0)
    {
        //create string of the chosen pers_types to use in query later
        $pt_string = "'" . implode("','",$pt) . "'";
        $report['where'] .= "and m.pers_type in ($pt_string) ";
    }

    //get text for battalion and company
    //and set where clause to limit to certain
    //battalion or company, if applicable.
    if($battalion == 0 && $company == 0)
    { $report['unit'] = '15 SIG BDE'; }
    else
    {
        $result = mysql_query("select battalion from battalion where battalion_id = $battalion") or die(mysql_error());
        $report["battalion"] = mysql_result($result,0);
        $report["where"] .= " and m.battalion = " . $battalion . " ";

        if($company == 0)
        { $report['company'] = ''; }
        else
        {
            $result = mysql_query("select company from company where company_id = " . $company) or die(mysql_error());
            $report["company"] = mysql_result($result,0);
            $report["where"] .= " and m.company = " . $company . " ";
        }

        $report["unit"] = $report["company"] . " " . $report["battalion"];
    }

    $date = strtoupper(date("dMY"));

    $header =  "<strong>" . $report['unit'] . " sign in roster for " . htmlentities($_GET['subject']) . " on $date</strong>";

    $comp = implode(",",$_CONF['component']);
    $comp_abbr = "'" . implode("','",$_CONF['component_abbrev']) . "'";

    echo com_siteheader("sign-in roster");

    if(!isset($_REQUEST["export2"]))
    { echo "export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>word</a>\n"; }

    $query = "select m.id, concat(m.last_name, ', ',m.first_name,' ',m.middle_initial) as name, "
            ."concat(m.rank,m.promotable) as rank, right(m.ssn,4) as ssn, '__________________________________________________' as signature "
            ."from main m left join student s on m.id = s.id, battalion b, company co "
            ."where m.pcs = 0 and m.battalion = b.battalion_id and m.company = co.company_id "
            ."and " . $report["where"]
           . " order by name, ssn";

    $roster = new roster($query);
    $roster->setheader($header);
    $roster->link_page("data_sheet.php");
    $roster->link_column(0);
    $roster->sethidecolumn(0);
    $roster->setReportName('signinroster');
	$roster->allowUserOrderBy(TRUE);
    echo $roster->drawroster();

}
else
{
    echo com_siteheader("invalid permissions - sign-in roster");
    if(count($_GET['pers_type']) == 0)
    { echo "no personnel type chosen."; }
    else
    { echo "invalid permissions.";  }
}

echo com_sitefooter();

?>