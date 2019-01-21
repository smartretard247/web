<?
include("../lib-common.php");

include("../classes/validate.class.php");
include("../classes/roster.class.php");

$val = new validate;
$pt = array();
$ssn_length = 4;

//validate unit
if($unit = $val->unit($_GET['unit'],14))
{
    $battalion = $unit[0];
    $company = $unit[1];

    //default sql where values
    $report["where"] = " m.pcs = 0 and m.pers_type='iet' ";

    //get text for battalion and company
    //and set where clause to limit to certain
    //battalion or company, if applicable.
    if($battalion == 0 && $company == 0)
    {
        $report['unit'] = '15 SIG BDE';
        $report['where'] .= ' and m.company != 11 ';
    }
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

    echo com_siteheader("Exodus Rollup By Hour " . $report['unit'] . " --- $date");

    if(!isset($_REQUEST["export2"]))
    { echo "Export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>Excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>Word</a>\n"; }

    if(isset($_REQUEST['Time_Code']))
    {
        if(isset($_REQUEST['mode']) && $_REQUEST['mode']=='Ret')
        {
            $mode = 'Ret';
            $date = '20040102060000';
        }
        else
        {
            $mode = 'Dep';
            $date = '20031218060000';
        }

        $code = (int)$_REQUEST['Time_Code'];
        if(isset($_REQUEST['airport']) && $_REQUEST['airport'] == 'augusta')
        { $report['where'] .= " and e.{$mode}_airport='augusta' "; }
        else
        { $report['where'] .= " and e.{$mode}_airport='atlanta' "; }

        $query = "select m.id, m.Rank, m.Last_Name, m.First_Name, m.Middle_Initial as MI, right(m.ssn,4) as SSN,
                concat(c.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as Unit, m.Platoon as PL,
                e.{$mode}_Mode, e.{$mode}_Airport, DATE_FORMAT(e.{$mode}_datetime,'%d%b%y %T') as {$mode}_DateTime, e.Exodus_Status, e.Comment
                from main m, battalion b, company c, exodus e WHERE m.id = e.id and m.battalion = b.battalion_id
                and m.company = c.company_id and e.dep_mode = 'air' and
                floor((unix_timestamp({$mode}_datetime) - unix_timestamp($date)) / 7200) = $code
                and {$report['where']}
                order by m.last_name, m.first_name, mi";

        $header =  "<strong>Exodus Rollup By Hour ($mode): " . $report["unit"] . " --- $date </strong>";

        $roster = new roster($query);
        $roster->setheader($header);
        $roster->link_page("data_sheet.php");
        $roster->link_column(0);
        $roster->sethidecolumn(0);
        echo $roster->drawroster();

    }
    else
    {
        if(isset($_REQUEST['mode']) && $_REQUEST['mode']=='Ret')
        {
            $mode = 'Ret';
            $date = '20040102060000';
        }
        else
        {
            $mode = 'Dep';
            $date = '20031218060000';
        }

        echo '<table border="0"><tr><td>';

        $query = "SELECT floor((unix_timestamp({$mode}_datetime) - unix_timestamp($date)) / 7200) as Time_Code, {$mode}_Mode, {$mode}_Airport,
                  upper(date_format(min({$mode}_datetime),'%d%b%y %T')) as Start_Time,
                  upper(date_format(max({$mode}_datetime),'%d%b%y %T')) as End_Time,
                  count(*) as Num_Soldiers from exodus e, main m
                  where e.id = m.id and e.{$mode}_mode = 'air' and e.{$mode}_airport='atlanta' and {$report['where']}
                  group by {$mode}_airport, Time_Code order by {$mode}_airport, {$mode}_datetime";

        $header =  "<strong>Exodus Air-Atlanta Rollup By Hour ($mode): " . $report["unit"] . " --- $date </strong>";

        $roster = new roster($query);
        $roster->setheader($header);
        $roster->link_page("reports/exodus_rollup_by_hour.php");
        $roster->link_column(0);
        $roster->link_query_string("unit=$battalion-$company");
        $roster->link_query_string("airport=atlanta");
        $roster->link_query_string("mode=$mode");
        $roster->sethidecolumn(0);
        echo $roster->drawroster();

        echo '</td></tr><tr><td>';

        $query = "SELECT floor((unix_timestamp({$mode}_datetime) - unix_timestamp($date)) / 7200) as Time_Code, {$mode}_Mode, {$mode}_Airport,
                  upper(date_format(min({$mode}_datetime),'%d%b%y %T')) as Start_Time,
                  upper(date_format(max({$mode}_datetime),'%d%b%y %T')) as End_Time,
                  count(*) as Num_Soldiers from exodus e, main m
                  where e.id = m.id and e.{$mode}_mode = 'air' and e.{$mode}_airport='augusta' and {$report['where']}
                  group by {$mode}_airport, Time_Code order by {$mode}_airport, {$mode}_datetime";

        $header =  "<strong>Exodus Air-Augusta Rollup By Hour ($mode): " . $report["unit"] . " --- $date </strong>";

        $roster = new roster($query);
        $roster->setheader($header);
        $roster->link_page("reports/exodus_rollup_by_hour.php");
        $roster->link_column(0);
        $roster->link_query_string("unit=$battalion-$company");
        $roster->link_query_string("airport=augusta");
        $roster->link_query_string("mode=$mode");
        $roster->sethidecolumn(0);
        echo $roster->drawroster();

        echo '</td></tr></table>';

    }
}
else
{
    echo com_siteheader("Invalid permissions - Exodus Rollup By Hour");
    echo "Invalid Permissions.";
}

echo com_sitefooter();

?>