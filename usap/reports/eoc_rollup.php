<?
include("../lib-common.php");

include("../classes/validate.class.php");
include("../classes/roster.class.php");

$val = new validate;
$errors = 0;

//validate unit
if($unit = $val->unit($_GET['unit'],14))
{
    $battalion = $unit[0];
    $company = $unit[1];

    $report['where'] = " 1 ";
    
    //validate start and end dates
    $_GET['start_date'] = htmlentities(strtoupper($_GET['start_date']));
    $_GET['end_date']   = htmlentities(strtoupper($_GET['end_date']));
    
    $input['start_date'] = $val->check('date',$_GET['start_date'],'EOC Start Date');
    $input['end_date']   = $val->check('date',$_GET['end_date'],'EOC End Date');
    if($input['start_date'] == $input['end_date'])
    { $_GET['end_date'] = ''; }
    else
    { $_GET['end_date'] = ' - ' . $_GET['end_date']; }
    
    if($input['start_date'] > $input['end_date'])
    { $val->error[] = "Start Date must be before End Date"; }
    
    $errors = $val->iserrors();

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

    $header =  "<strong>EOC Report: {$_GET['start_date']}{$_GET['end_date']} --- {$report['unit']}</strong>";

    echo com_siteheader("EOC Report: {$_GET['start_date']}{$_GET['end_date']} --- {$report['unit']}");

    if($errors)
    { echo $val->geterrors(); }
    else
    {
        if(!isset($_REQUEST["export2"]))
        { echo "export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>word</a>\n"; }

        $t_query = "create temporary table apft_temp1 (primary key(id), index(date)) select a.id, max(a.date) as date
                    from apft a where a.date between {$input['start_date']} and {$input['end_date']} and a.type='student-eoc'
                    group by id";
        $result = mysql_query($t_query) or die("Error getting maximum date: " . mysql_error());
               
        $t_query = "create temporary table apft_temp2 (primary key(id)) select a.id, if(a.pu_exempt,'EXEMPT',a.raw_pu) as raw_pu, a.pu_score, 
                    if(a.su_exempt,'EXEMPT',a.raw_su) as raw_su, a.su_score, if(a.raw_run=9999,'DNF',a.raw_run) as raw_run, a.run_score, 
                    a.total_score, a.pass_fail, a.date from main m, apft a, apft_temp1 t where m.id = a.id and a.id = t.id and a.date = t.date and
                    {$report['where']} group by id";
        $result = mysql_query($t_query) or die("Error creating temp table: " . mysql_error());
      
        $query = "select m.id, concat(m.last_name, ', ', m.first_name, ' ', m.middle_initial) as Name, concat(m.rank,m.promotable) as Rank,
                  upper(date_format(t.date,'%d%b%y')) as Date,
                  t.Raw_PU, t.PU_Score, t.Raw_SU, t.SU_Score, t.Raw_Run, t.Run_Score, t.total_score as Score,
                  concat(if(t.pass_fail='fail','N','Y'),'-',sum(if(a.date>={$input['start_date']} and a.date<={$input['end_date']} and a.type='student-eoc',1,0))) as Passed,
                  concat(c.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as Unit
                  from main m, apft a, apft_temp2 t, company c, battalion b where m.id = a.id and m.id = t.id and m.company = c.company_id
                  and m.battalion = b.battalion_id and {$report['where']}
                  group by a.id order by name";

        $roster = new roster($query);
        $roster->setheader($header);
        $roster->link_page("data_sheet.php");
        $roster->link_column(0);
        $roster->sethidecolumn(0);
        $roster->setReportName('eocrollup');
		$roster->allowUserOrderBy(TRUE);
        $r = $roster->drawroster();
        
        //match parts of table to count number of passes and failures
        $rollup['First Time Pass'] = substr_count($r,'>Y-1<');
        $rollup['First Time Fail'] = substr_count($r,'>N-1<');
        $rollup['First Time Tested'] = $rollup['First Time Pass'] + $rollup['First Time Fail'];
        $rollup['First Time Pass Rate'] = sprintf('%01.2f%%',$rollup['First Time Pass'] / $rollup['First Time Tested'] * 100);
        $rollup['Second Time Pass'] = substr_count($r,'>Y-2<');
        
        preg_match_all('/>Y-([3-9]|[0-9]{2})</',$r,$m);
        $rollup['Third Or More Pass'] = count($m[0]);
        preg_match_all('/>N-([2-9]|[0-9]{2})</',$r,$m);
        $rollup['Two Or More Failure'] = count($m[0]);
        $rollup['Total Overall Pass'] = $rollup['First Time Pass'] + $rollup['Second Time Pass'] + $rollup['Third Or More Pass'];
        $rollup['Total Overall Fail'] = $rollup['First Time Fail'] + $rollup['Two Or More Failure'];
        $rollup['Total Tested'] = $rollup['Total Overall Pass'] + $rollup['Total Overall Fail'];
        $rollup['Overall Pass Rate'] = sprintf('%01.2f%%',$rollup['Total Overall Pass'] / $rollup['Total Tested'] * 100);
        $rollup['Overall Fail Rate'] = sprintf('%01.2f%%',$rollup['Total Overall Fail'] / $rollup['Total Tested'] * 100);        
        
        echo '<table border="0" width="100%" align="left"><tr><td>';
        echo '<table border="1" align="left">';
        echo "<tr><td colspan=\"2\" class=\"table_cheading\">EOC APFT Statistics for {$_GET['start_date']}{$_GET['end_date']} --- {$report['unit']}</td></tr>";
        foreach($rollup as $key=>$value)
        { echo "<tr><td class=\"column_name\">$key</td><td align=\"center\">$value</td></tr>\n"; }
        //Display Roster
        if(isset($_GET['stats_only']))
        {
            unset($r);
            echo "</table></td></tr></table>";
        }
        else
        { echo "</table></td></tr><tr><td><p>&nbsp;</p>$r</td></tr></table>"; }
    }
}
else
{
    echo com_siteheader("invalid permissions - eoc rollup");
    echo "invalid permissions.";
}

echo com_sitefooter();

?>
