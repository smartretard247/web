<?
include("../lib-common.php");

include("../classes/validate.class.php");
include("../classes/roster.class.php");

$val = new validate;
$pt = array();

//validate unit
if($unit = $val->unit($_GET['unit'],14))
{
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
        //create string of the chosen pers_types to use in query later
        $pt_string = "'" . implode("','",$pt) . "'";
    }
    else
    { $pt_string = "''"; }

    //see if any pp types were selected. if so, generate a pp
    //style alpha roster (no class info), otherwise generate
    //student style alpha roster
    $roster_type = 'student';

    foreach($_CONF['perm_party'] as $pp)
    {
        if(in_array($pp,$pt))
        { $roster_type = 'pp'; }
    }

    //default sql where values
    $report["where"] = " 1 ";

    if($ms = $val->conf($_GET['marital_status'],"marital_status"))
    {
        $report['where'] .= " and m.marital_status = '$ms' ";

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

        $header =  "<strong>marriage report ($ms): " . $report["unit"] . " --- $date </strong>";

        $comp = implode(",",$_CONF['component']);
        $comp_abbr = "'" . implode("','",$_CONF['component_abbrev']) . "'";

        echo com_siteheader("marriage report ($ms):" . $report['unit'] . " --- $date");

        if(!isset($_REQUEST["export2"]))
        { echo "export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>word</a>\n"; }

        switch($roster_type)
        {
            case "pp":
                $query = "select m.id, m.last_name, m.first_name, m.middle_initial as mi, "
                    ."concat(m.rank,m.promotable) as rank, right(m.ssn,4) as ssn, m.gender, "
                    ."m.platoon as plt, m.mos,m.pers_type, "
                    ."elt(find_in_set(m.component,'" . $comp . "')," . $comp_abbr . ") as comp, "
                    . "concat(co.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as unit from main m, battalion b, company co "
                    ."where m.pcs = 0 and m.battalion = b.battalion_id and m.company = co.company_id "
                    ."and m.pers_type in (" . $pt_string . ") and " . $report["where"]
                    . " order by m.last_name, m.first_name, m.middle_initial, m.ssn";
                break;

            case "student":
                $query = "select m.id, m.last_name, m.first_name, m.middle_initial as mi, "
                    ."concat(m.rank,m.promotable) as rank, right(m.ssn,4) as ssn, m.gender, "
                    ."elt(find_in_set(m.component,'" . $comp . "')," . $comp_abbr . ") as comp, "
                    ."m.platoon as plt, m.mos, c.class_number as class, s.shift, "
                    ."concat(co.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as unit from main m ,student s left join class c "
                    ."on s.class_id = c.class_id, battalion b, company co "
                    ."where m.pcs = 0 and m.id = s.id  and m.battalion = b.battalion_id and m.company = co.company_id "
                    ."and m.pers_type in (" . $pt_string . ") and " . $report["where"]
                    . " order by m.last_name, m.first_name, m.middle_initial, m.ssn";
                break;
        }
        $roster = new roster($query);
        $roster->setheader($header);
        $roster->link_page("data_sheet.php");
        $roster->link_column(0);
        $roster->sethidecolumn(0);
        $roster->setReportName('marriagereport');
		$roster->allowUserOrderBy(TRUE);
        echo $roster->drawroster();
    }
}
else
{
    echo com_siteheader("invalid permissions - marriage report");
    echo "invalid permissions.";
}

echo com_sitefooter();

?>