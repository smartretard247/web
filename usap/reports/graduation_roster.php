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


    $date = 0;
    $input['start_date'] = $val->check("date",$_GET['start_date'],"Start Date");
    $input['end_date'] = $val->check('date',$_GET['end_date'],'End Date');

    //default sql where values
    $report["where"] = " 1 ";

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

    $header =  "<strong>Graduation Roster for " . htmlentities(strtoupper($_GET['start_date'])) . " - " . htmlentities(strtoupper($_GET['end_date'])) . " --- " . $report["unit"] . "</strong>";

    echo com_siteheader("Graduation Roster - " . $report['unit']);

    if(!isset($_REQUEST["export2"]))
    { echo "Export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>Excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>Word</a>\n"; }

    echo "<table border=\"0\" width=\"100%\">\n";

    $count_query = "select c.mos, c.class_number, count(*) as cnt
            from main m ,student s left join class c
            on s.class_id = c.class_id, battalion b, company co
            where m.pcs = 0 and m.id = s.id  and m.battalion = b.battalion_id and m.company = co.company_id
            and m.not_graduating = 0 and c.grad_date BETWEEN {$input['start_date']} AND {$input['end_date']}
            and m.pers_type in ($pt_string) and {$report['where']}
            group by c.class_id
            order by m.last_name, m.first_name, m.middle_initial, m.ssn";

// removed: or c.grad_date < curdate())
// on all queries

    $result = mysql_query($count_query) or die("Error getting count: " . mysql_error());
    if(mysql_num_rows($result) > 0)
    {
        echo '<tr><td><table border="1" width="50%" align="left"><tr class="table_cheading"><td>Class</td><td>Count</td></tr>';
        $total = 0;
        while($row = mysql_fetch_assoc($result))
        {
            echo "<tr align=\"center\"><td>{$row['mos']}-{$row['class_number']}</td><td>{$row['cnt']}</td></tr>";
            $total += $row['cnt'];
        }
        echo "<tr><td class=\"table_heading\" colspan=\"2\">Total: $total</td></tr></table></td></tr>\n";
    }

    $query = "select m.id, m.last_name, m.first_name, m.middle_initial as mi, concat(m.rank,m.promotable) as rank,
              m.mos, c.class_number as class, concat(co.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as unit,
              s.assignment, if(s.honor_grad='Y','Y','&nbsp;') as honor_grad, if(s.dist_grad='Y','Y','&nbsp;') as dist_grad,
              if(s.high_pt='Y','Y','&nbsp;') as high_pt
              from main m ,student s left join class c
              on s.class_id = c.class_id, battalion b, company co
              where m.pcs = 0 and m.id = s.id  and m.battalion = b.battalion_id and m.company = co.company_id
              and m.not_graduating = 0 and c.grad_date BETWEEN {$input['start_date']} AND {$input['end_date']}
              and m.pers_type in ($pt_string) and {$report['where']}
              order by m.last_name, m.first_name, m.middle_initial, m.ssn";

    $roster = new roster($query);
    $roster->setheader($header);
    $roster->link_page("data_sheet.php");
    $roster->link_column(0);
    $roster->sethidecolumn(0);
    $roster->setReportName('graduationroster');
	$roster->allowUserOrderBy(TRUE);
    echo "<tr><td><p>&nbsp;</p>";
    echo $roster->drawroster();
    echo "</td></tr><tr><td><p>&nbsp;</p>\n";

    $query2 = "select m.id, m.last_name, m.first_name, m.middle_initial as mi, concat(m.rank,m.promotable) as rank,
               m.mos, c.class_number as class, concat(co.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as unit
               from main m ,student s left join class c
               on s.class_id = c.class_id, battalion b, company co
               where m.pcs = 0 and m.id = s.id  and m.battalion = b.battalion_id and m.company = co.company_id
               and m.not_graduating = 1 and (c.grad_date BETWEEN {$input['start_date']} AND {$input['end_date']})
               and m.pers_type in ($pt_string) and {$report['where']}
               order by m.last_name, m.first_name, m.middle_initial, m.ssn";

    $roster2 = new roster($query2);
    $roster2->setheader("Soldiers not graduating with their class");
    $roster2->link_page("data_sheet.php");
    $roster2->link_column(0);
    $roster2->sethidecolumn(0);
    $roster2->setReportName('notgraduatingroster');
	$roster2->allowUserOrderBy(TRUE);
    echo $roster2->drawroster();
    echo "</td></tr></table>\n";

}
else
{
    echo com_siteheader("Invalid permissions - Graduation Roster");
    echo "Invalid permissions.";
}

echo com_sitefooter();

?>