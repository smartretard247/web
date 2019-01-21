<?
include("../lib-common.php");

include("../classes/validate.class.php");
include("../classes/roster.class.php");

$val = new validate;
$valid_platoons = array();
$rollup['where'] = '';

//validate unit
if($unit = $val->unit($_GET['unit'],14))
{
    //default sql where values
    $report["where"] = " 1 ";

    //loop through and verify each platoon passed if it's in an array
    if(isset($_GET['platoon']) && is_array($_GET['platoon']))
    {
        foreach($_GET['platoon'] as $platoon)
        {
            if($valid_platoon = $val->conf($platoon,"platoon"))
            { $valid_platoons[] = $valid_platoon; }
        }

        //if any valid platoons were passed, add then to string to be
        //incorporated into sql query.
        if(count($valid_platoons) > 0)
        {
          $platoon_string = "'" . implode("','",$valid_platoons) . "'";
          $report['where'] .= "and m.platoon in ($platoon_string) ";
        }
    }

    //loop through and verify each shift passed if it's in an array
    if(isset($_GET['shift']) && is_array($_GET['shift']))
    {
        foreach($_GET['shift'] as $shift)
        {
            if($valid_shift = $val->conf($shift,"shift"))
            { $valid_shifts[] = $valid_shift; }
        }

        //if any valid shifts were passed, add then to string to be
        //incorporated into sql query.
        if(count($valid_shifts) > 0)
        {
            $shift_string = "'" . implode("','",$valid_shifts) . "'";
            $report['where'] .= "and s.shift in ($shift_string) ";
        }
    }
    
    
    //loop through and verify each gender passed if it's in an array
    if(isset($_GET['gender']) && is_array($_GET['gender']))
    {
        foreach($_GET['gender'] as $gender)
        {
            if($valid_gender = $val->conf($gender,"gender"))
            { $valid_genders[] = $valid_gender; }
        }


        //if any valid genders were passed, add then to string to be
        //incorporated into sql query.
        if(count($valid_genders) > 0)
        {
            $gender_string = "'" . implode("','",$valid_genders) . "'";
            $report['where'] .= "and m.gender in ($gender_string) ";
        }
    }

    //loop through and verify each phase passed if it's in an array
    if(isset($_GET['phase']) && is_array($_GET['phase']))
    {
        foreach($_GET['phase'] as $phase)
        {
            if($valid_phase = $val->conf($phase,"phase"))
            { $valid_phases[] = $valid_phase; }
        }


        //if any valid genders were passed, add then to string to be
        //incorporated into sql query.
        if(count($valid_phases) > 0)
        {
            $phase_string = "'" . implode("','",$valid_phases) . "'";
            $report['where'] .= "and s.phase in ($phase_string) ";
        }
    }
    
    switch($_GET['phase_roster'])
    {
        case "out_of_phase":
            //Three Conditions for "out of phase":
            //1: Phase IV Phaseback
            //2: Past Phase V Date
            //3: Past Phase V+ Date
            $report['where'] .= "
            and (
                 (s.phase='IV' AND m.Arrival_Date != s.date_phaseiv)
                 OR
                 (s.phase='IV' AND m.Arrival_Date + INTERVAL 28 DAY < CURDATE())
                 OR
                 (s.phase IN ('IV','V') AND m.Arrival_Date + INTERVAL 77 DAY < CURDATE())
                )";
        break;
        
        case "phaseiv_phaseback":
            $report['where'] .= " and (s.phase='IV' AND m.Arrival_Date != s.date_phaseiv) ";
        break;
        
        case "phasev_phaseback":
            $report['where'] .= " and (phase='V' AND date_phasev > date_phaseva AND date_phaseva != 0) ";
        break;
        
        case "past_phasev_date":
            $report['where'] .= " and (s.phase='IV' AND (s.date_phaseiv = m.arrival_date OR s.date_phaseiv IS NULL) AND m.Arrival_Date + INTERVAL 28 DAY < CURDATE()) ";
        break;
        
        case "past_phaseva_date":
            $report['where'] .= " and (s.phase = 'V' AND m.Arrival_Date + INTERVAL 77 DAY < CURDATE()) ";
        break;
        
        case "late_phasev":
            $report['where'] .= " and m.arrival_date + INTERVAL 28 DAY < s.date_phasev ";
        break;
        
        case "late_phaseva":
            $report['where'] .= " and m.arrival_date + INTERVAL 77 DAY < s.date_phaseva ";
        break;
        
        case "outofphase_latephase":
            $report['where'] .= "
            and (
                 (s.phase='IV' AND m.Arrival_Date != s.date_phaseiv)
                 OR
                 (s.phase='IV' AND m.Arrival_Date + INTERVAL 28 DAY < CURDATE())
                 OR
                 (s.phase IN ('IV','V') AND m.Arrival_Date + INTERVAL 77 DAY < CURDATE())
                 OR 
                 (m.arrival_date + INTERVAL 28 DAY < s.date_phasev)
                 OR
                 (m.arrival_date + INTERVAL 77 DAY < s.date_phaseva)
                )";
        break;
    }
    
    $battalion = $unit[0];
    $company = $unit[1];

    //get text for battalion and company
    //and set where clause to limit to certain
    //battalion or company, if applicable.
    if($battalion == 0 && $company == 0)
    { $report['unit'] = '15 SIG BDE'; }
    else
    {
        $result = mysql_query("select battalion from battalion where battalion_id = $battalion") or die(mysql_error());
        $report["battalion"] = mysql_result($result,0);
        $report["where"] .= " and m.battalion = $battalion ";
        $rollup['where'] .= " and m.battalion = $battalion "; 

        if($company == 0)
        { $report['company'] = ''; }
        else
        {
            $result = mysql_query("select company from company where company_id = " . $company) or die(mysql_error());
            $report["company"] = mysql_result($result,0);
            $report["where"] .= " and m.company = $company ";
            $rollup['where'] .= " and m.company = $company "; 
        }

        $report["unit"] = $report["company"] . " " . $report["battalion"];
    }

    $date = strtoupper(date("dMY"));

    $header =  "<strong>Phase Roster: " . $report["unit"] . "  --- $date</strong>";

    echo com_siteheader("Phase Roster: " . $report["unit"] . "  --- $date");

    $rollup_query = "select
                        concat(c.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as unit,
                        sum(if(phase='IV',1,0)) as phaseiv,
                        sum(if(phase='V',1,0)) as phasev,
                        sum(if(phase='V+',1,0)) as phaseva,
                        sum(if(phase NOT IN ('IV','V','V+'),1,0)) as phaseother,
                        sum(if(phase = 'IV' AND m.arrival_date != date_phaseiv,1,0)) AS phaseiv_phaseback,
                        sum(if(phase='V' AND date_phasev > date_phaseva AND date_phaseva != 0,1,0)) AS phasev_phaseback,
                        sum(if(phase='IV' AND (date_phaseiv = m.arrival_date OR date_phaseiv IS NULL) AND m.arrival_date + INTERVAL 28 DAY < CURDATE(),1,0)) AS phaseiv_notphased,
                        sum(if(phase='V' AND m.arrival_date + INTERVAL 77 DAY < CURDATE(),1,0)) AS phasev_notphased,
                        m.battalion, m.company
                     from student, main m, battalion b, company c
                     where m.id = student.id and m.pcs=0 and m.pers_type = 'IET' and m.company = c.company_id and m.battalion = b.battalion_id
                        {$rollup['where']}
                     group by m.battalion, m.company
                     order by b.battalion, c.company";

    $rollup_result = mysql_query($rollup_query) or die("Error getting rollup: " . mysql_error());
    
    echo '<table><tr><td>';
    if(!isset($_REQUEST['export2']))
    {
        $rollup_total = array('phaseiv' => 0, 'phasev' => 0, 'phaseva' => 0, 'phaseother' => 0,
                        'phaseiv_phaseback' => 0, 'phasev_phaseback' => 0, 'phaseiv_notphased' => 0,
                        'phasev_notphased' => 0, 'out_of_phase' => 0);
    
        echo '<table border="1" align="left" cellpadding="2" width="100%">';
        echo '<tr><th>Unit</th><th>Phase IV</th><th>Phase V</th><th>Phase V+</th><th>Other Phase</th>
              <th>Phase IV Phaseback</th><th>Phase V Phaseback</th><th>Phase IV Not Phased</th><th>Phase V Not Phased</th></tr>';
        while($row = mysql_fetch_assoc($rollup_result))
        {
            $y = 0;
            $u = $row['battalion'] .'-'. $row['company'];
            echo '<tr>';
            echo "<td>{$row['unit']}</td>";
            echo "<td align=\"center\"><a href=\"{$_CONF['html']}/reports/platoon_roster.php?unit=$u&pers_type[]=IET&phase[]=IV\">{$row['phaseiv']}</td>";
            echo "<td align=\"center\"><a href=\"{$_CONF['html']}/reports/platoon_roster.php?unit=$u&pers_type[]=IET&phase[]=V\">{$row['phasev']}</td>";
            echo "<td align=\"center\"><a href=\"{$_CONF['html']}/reports/platoon_roster.php?unit=$u&pers_type[]=IET&phase[]=" . urlencode('V+') . "\">{$row['phaseva']}</td>";
            echo "<td align=\"center\"><a href=\"{$_CONF['html']}/reports/platoon_roster.php?unit=$u&pers_type[]=IET&phase[]=Other\">{$row['phaseother']}</td>";
            echo "<td align=\"center\"><a href=\"{$_CONF['html']}/reports/phase_roster.php?unit=$u&phase_roster=phaseiv_phaseback\">{$row['phaseiv_phaseback']}</td>";
            echo "<td align=\"center\"><a href=\"{$_CONF['html']}/reports/phase_roster.php?unit=$u&phase_roster=phasev_phaseback\">{$row['phasev_phaseback']}</td>";
            echo "<td align=\"center\"><a href=\"{$_CONF['html']}/reports/phase_roster.php?unit=$u&phase_roster=past_phasev_date\">{$row['phaseiv_notphased']}</td>";
            echo "<td align=\"center\"><a href=\"{$_CONF['html']}/reports/phase_roster.php?unit=$u&phase_roster=past_phaseva_date\">{$row['phasev_notphased']}</td>";
            echo "</tr>\n";
            
            $rollup_total['phaseiv'] += $row['phaseiv'];
            $rollup_total['phasev'] += $row['phasev'];
            $rollup_total['phaseva'] += $row['phaseva'];
            $rollup_total['phaseother'] += $row['phaseother'];
            $rollup_total['phaseiv_phaseback'] += $row['phaseiv_phaseback'];
            $rollup_total['phasev_phaseback'] += $row['phasev_phaseback'];
            $rollup_total['phaseiv_notphased'] += $row['phaseiv_notphased'];
            $rollup_total['phasev_notphased'] += $row['phasev_notphased'];
            $rollup_total['out_of_phase'] += $row['phaseiv_phaseback'] + $row['phasev_phaseback']
                                            + $row['phaseiv_notphased'] + $row['phasev_notphased'];
        }
        if(!$company)
        {
            echo "<tr><th>Total:</th>
            <th><a href=\"{$_CONF['html']}/reports/platoon_roster.php?unit=$battalion-$company&pers_type[]=IET&phase[]=IV\">{$rollup_total['phaseiv']}</a></th>
            <th><a href=\"{$_CONF['html']}/reports/platoon_roster.php?unit=$battalion-$company&pers_type[]=IET&phase[]=V\">{$rollup_total['phasev']}</a></th>
            <th><a href=\"{$_CONF['html']}/reports/platoon_roster.php?unit=$battalion-$company&pers_type[]=IET&phase[]=" . urlencode('V+') . "\">{$rollup_total['phaseva']}</a></th>
            <th><a href=\"{$_CONF['html']}/reports/platoon_roster.php?unit=$battalion-$company&pers_type[]=IET&phase[]=Other\">{$rollup_total['phaseother']}</a></th>
            <th><a href=\"{$_CONF['html']}/reports/phase_roster.php?unit=$battalion-$company&phase_roster=phaseiv_phaseback\">{$rollup_total['phaseiv_phaseback']}</a></th>
            <th><a href=\"{$_CONF['html']}/reports/phase_roster.php?unit=$battalion-$company&phase_roster=phasev_phaseback\">{$rollup_total['phasev_phaseback']}</a></th> 
            <th><a href=\"{$_CONF['html']}/reports/phase_roster.php?unit=$battalion-$company&phase_roster=past_phasev_date\">{$rollup_total['phaseiv_notphased']}</a></th>
            <th><a href=\"{$_CONF['html']}/reports/phase_roster.php?unit=$battalion-$company&phase_roster=past_phaseva_date\">{$rollup_total['phasev_notphased']}</a></th>
            </tr>";
        }
        echo "<tr><th colspan=\"5\" align=\"right\">Total Out of Phase:</th><th colspan=\"4\">
              <a href=\"{$_CONF['html']}/reports/phase_roster.php?unit=$battalion-$company&phase_roster=out_of_phase\">
              {$rollup_total['out_of_phase']}</a></th></tr>
              </table></td></tr><tr><td><br />";
    }
    
    if(!isset($_REQUEST["export2"]))
    { echo "<p>Export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>Excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>Word</a></p>\n"; }

    echo "Note: Projected Phase dates are calculated from Arrival Date (28 days for V, 77 days for V+).</p>";
    
    $query = "select m.id, m.Last_Name, m.First_Name, CONCAT(m.rank, m.promotable) AS Rank,
              right(m.ssn,4) as SSN, m.platoon as PLT, s.Phase, upper(date_format(m.arrival_date,'%d%b%y')) AS Arrival,
              CONCAT(IF(s.phase='IV' AND m.Arrival_Date != s.date_phaseiv,'%%',''),UPPER(DATE_FORMAT(s.date_phaseiv,'%d%b%y'))) as Phase_IV,
              CONCAT(IF(s.phase='IV' AND m.Arrival_Date + INTERVAL 28 DAY < CURDATE(),'%%',''),UPPER(DATE_FORMAT(m.Arrival_Date + INTERVAL 28 DAY,'%d%b%y'))) as Proj_Phase_V,
              CONCAT(IF(m.arrival_date + INTERVAL 28 DAY < s.date_phasev,'%%',''),UPPER(DATE_FORMAT(s.date_phasev,'%d%b%y'))) as Phase_V,
              CONCAT(IF(s.phase IN ('IV','V') AND m.Arrival_Date + INTERVAL 77 DAY < CURDATE(),'%%',''),UPPER(DATE_FORMAT(m.Arrival_Date + INTERVAL 77 DAY,'%d%b%y'))) as 'Proj_Phase_V+',
              CONCAT(IF(m.arrival_date + INTERVAL 77 DAY < s.date_phaseva,'%%',''),UPPER(DATE_FORMAT(s.date_phaseva,'%d%b%y'))) as 'Phase_V+',
              concat(co.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as Unit, 
              CONCAT('&nbsp;%',m.id,'%') as Remark from main m ,student s,
              battalion b, company co
              where m.pcs = 0 and m.id = s.id  and m.battalion = b.battalion_id and m.company = co.company_id
              and m.pers_type = 'IET' and {$report['where']}
              order by m.last_name, m.first_name, m.middle_initial, m.ssn";

    $roster = new roster($query);
    $roster->setheader($header);
    $roster->link_page("data_sheet.php");
    $roster->link_column(0);
    $roster->sethidecolumn(0);
	$roster->setReportName('phaserostersort');
	$roster->allowUserOrderBy(TRUE);    
    $r = $roster->drawroster();
    
    //Make dates marked with %% an error class (to highlight them)
    $r = preg_replace('/%%(\w{7})/','<font color="red">$1</font>',$r);
    
    //Replace %XXX% markers (where XXX is ID of soldier)
    //with latest Phasing remark (if there is one)
    //First, create temp table to gather dates of latest phasing remark
    $query2 = "CREATE TEMPORARY TABLE temp_table SELECT r.id, MAX(r.time) as time FROM remarks r, main m WHERE r.id = m.id and 
               m.pers_type = 'IET' AND r.subject = 16 GROUP BY r.id";
    $rs2 = mysql_query($query2) or die("Error creating temp table: " . mysql_error());
    
    //get text of latest remark and put into $remarks array
    //relating the key of the array to the soldier's ID
    $query3 = "SELECT r.id, r.remark FROM remarks r, temp_table t WHERE r.id = t.id and r.time = t.time";
    $rs3 = mysql_query($query3) or die("Error selecting recent Phasing remarks: " . mysql_error());
    $remarks = array();
    while($row = mysql_fetch_assoc($rs3))
    { $remarks[$row['id']] = $row['remark']; }
    
    //Replace %XXX% markers with any text that was retrieved. @ symbol is
    //to suppress warning messages generated by a matching ID that does not
    //have a cooresponding element in the $remarks array. 
    $r = preg_replace('/%([0-9]+)%/e','@$remarks[\\1]',$r);
        
    //Display roster
    echo $r;
    
    //End outside table
    echo "</td></tr></table>";
}
else
{
    echo com_siteheader("Invalid Permissions - Phase Roster");
    echo "Invalid Permissions";
}

echo com_sitefooter();

?>