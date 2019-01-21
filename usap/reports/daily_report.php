<?
include("../lib-common.php");

include($_CONF['path'] . '/classes/validate.class.php');
include($_CONF['path'] . '/classes/roster.class.php');

$val = new validate;

if(isset($_REQUEST['pers_type']))
{ setcookie('daily_report_pers_type',$_REQUEST['pers_type'],time()+259200,'/','usap.gordon.army.mil',1); }

echo com_siteheader("Daily Report - " . strtoupper(date("dMY")));

$show_links = TRUE;

if(!isset($_REQUEST["export2"]))
{ echo "Export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>Excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>Word</a>\n"; }
else
{ $show_links = FALSE; }

if($unit = $val->unit($_GET['unit'],14))
{
    $battalion = $unit[0];
    $company = $unit[1];

    //default values
    $students = "'" . implode("','",$_CONF["students"]) . "'";
    $pp = "'" . implode("','",$_CONF['perm_party']) . "'";

    $report["where"] = " m.pcs = 0 ";

    if($_REQUEST['pers_type'] == 'student')
    {
        $report['where'] .= " and m.pers_type in ($students) ";
        $report['name'] = "Student Daily Report";
        $report['type'] = 'student';
    }
    else
    {
        $report['where'] .= " and m.pers_type in ($pp) ";
        $report['name'] = "Permanent Party Daily Report";
        $report['type'] = 'pp';
    }

    $CASE = '';
    $GROUP_BY = '';
    $ORDER_BY = '';

    $unit_array = array('1-0' => '73rd',
                        '2-0' => '369th',
                        '3-0' => '447th',
                        '4-0' => '551st',
                        '0-11' => 'SSD',
                        '0-7' => 'HHC');
    //get text for battalion and company
    //and set where clause to limit to certain
    //battalion or company, if applicable.
    if($battalion == 0 && $company == 0)
    {
        $report['unit'] = '15 SIG BDE';
        $unit_array['0-0'] = 'total';

        $CASE = "
            CASE
              WHEN m.battalion = 1 THEN '73rd'
              WHEN m.battalion = 2 THEN '369th'
              WHEN m.battalion = 3 THEN '447th'
              WHEN m.battalion = 4 THEN '551st'
              WHEN m.battalion = 0 AND m.company = 11 THEN 'SSD'
              WHEN m.battalion = 0 AND m.company = 7 THEN 'HHC'
              WHEN 1 THEN 'Other'
            END AS 'X_Unit',

            CASE
              WHEN m.battalion = 1 THEN 'a'
              WHEN m.battalion = 2 THEN 'b'
              WHEN m.battalion = 3 THEN 'c'
              WHEN m.battalion = 4 THEN 'd'	
              WHEN m.battalion = 0 AND m.company = 11 THEN 'e'
              WHEN m.battalion = 0 AND m.company = 7 THEN 'f'
              WHEN 1 THEN 'g'
            END AS 'X_Order_Column',
        ";

        $GROUP_BY = ' GROUP BY X_Unit ';
        $ORDER_BY = ' ORDER BY X_Order_Column ';
    }
    else
    {
        $result = mysql_query("select battalion from battalion where battalion_id = $battalion") or die(mysql_error());
        $report["battalion"] = mysql_result($result,0);

        //if($_REQUEST['pers_type'] == 'student')
        { $report["where"] .= " and m.battalion = $battalion "; }
        //else
        //{ $report['where'] .= " and (m.battalion = $battalion OR l.detached_bn = $battalion) "; }

        if($company == 0)
        {
            $report['company'] = '';

            $letter = 'a';
            $crit1 = '';
            $crit2 = '';

            $query = "SELECT c.company_id, c.company, c.order_id FROM company c ORDER BY c.order_id ASC";
            $result = mysql_query($query) or die('Unable to get companies: ' . mysql_error());
            while($r = mysql_fetch_assoc($result))
            {
                $crit1 .= "WHEN m.company = {$r['company_id']} THEN '{$r['company']}'\n";
                $crit2 .= "WHEN m.company = {$r['company_id']} THEN '" . $letter++ . "'\n";

                $unit_array[$battalion . '-' . $r['company_id']] = $r['company'];
            }
            $unit_array[$battalion . '-0'] = 'total';

            $CASE = "
                CASE
                    $crit1
                END AS 'X_Unit',

                CASE
                    $crit2
                END AS 'X_Order_Column',
            ";

            $GROUP_BY = ' GROUP BY X_Unit ';
            $ORDER_BY = ' ORDER BY X_Order_Column ';
        }
        else
        {
            $result = mysql_query("select company from company where company_id = " . $company) or die(mysql_error());
            $report["company"] = mysql_result($result,0);
            $data['Unit'][] = $report['company'];
            $unit_array["$battalion-$company"] = $report['company'];

            //if($_REQUEST['pers_type'] == 'student')
            { $report["where"] .= " and m.company = $company "; }
            //else
            //{ $report['where'] .= " AND (m.company = $company OR l.detached_co = $company) "; }

            $units = array();
        }

        $report["unit"] = $report["company"] . " " . $report["battalion"];
    }

    $report['date'] = strtoupper(date("dMY"));

    if($_REQUEST['pers_type'] == 'student')
    {
        /*****************
        * STUDENT REPORT *
        *****************/
        $columns = array(
            'Status'   => array(
                'Active'     => 'm.inact_status=0',
                'Inactive'   => 'm.inact_status!=0'),

            'Student Type' => array(
                'IET'        => 'm.pers_type=\'IET\'',
                'Non-IET'    => 'm.pers_type=\'Non-IET\'',
                'WO Student' => 'm.pers_type=\'WO Student\''),

            'Housing'  => array(
                'Off Post'   => 'm.building_number=\'off_post\'',
                'On Post'    => 'm.building_number!=\'off_post\''),

            'Gender'   => array(
                'Male'       => 'm.gender=\'M\'',
                'Female'     => 'm.gender=\'F\''),

            'Shift'    => array(
                'Day'        => 's.shift=\'Day\'',
                'Swing'      => 's.shift=\'Swing\'',
                'MID'        => 's.shift=\'MID\'',
		'First'        => 's.shift=\'First\'',
		'Second'        => 's.shift=\'Second\'',
		'Third'        => 's.shift=\'Third\'',
		'4th'        => 's.shift=\'4th\'',
                'Other'      => 's.shift NOT IN (\'Day\',\'Swing\',\'MID\',\'First\',\'Second\',\'Third\',\'4th\')'),

            'Inactive Status' => array()
            );

        //Optional values
        //'class' => CSS class to apply to element
        //'dataname_class' => CSS class for data name of row
        //'total_class' => CSS class for total at end of row
        //'space' => Insert "spacer" row after this element
        //'unit'  => Insert "unit" row after this element
        //'no_total' => TRUE=do not show total
        $column_decoration = array(
            'Inactive Status' => array(
                'class' => 'table_heading',
                'unit'  => TRUE)
            );

        //Retrieve Status columns
        //to and add into $columns to make query
        $active_columns = array();
        $inact_columns = array();

        $inact_query = "SELECT status_id, status, type FROM status WHERE applies_to='student' ORDER BY type DESC, status ASC";
        $result = mysql_query($inact_query) or die('Error getting status codes: ' . mysql_error());
        while($r = mysql_fetch_assoc($result))
        {
            if($r['type'] == 'Inactive')
            {
                $inact_columns[$r['status']] = array(
                    "<!--{$r['status_id']}-->IET Graduate"   => "m.pers_type='IET' AND c.grad_date<=curdate() AND m.inact_status={$r['status_id']}",
                    "<!--{$r['status_id']}-->IET Non-Graduate" => "m.pers_type='IET' AND (c.grad_date>curdate() or isnull(c.grad_date)) AND m.inact_status={$r['status_id']}",
                    "<!--{$r['status_id']}-->Non-IET Graduate" => "m.pers_type='Non-IET' and c.grad_date<=curdate() AND m.inact_status={$r['status_id']}",
                    "<!--{$r['status_id']}-->Non-IET Non-Graduate" => "m.pers_type='Non-IET' and (c.grad_date>curdate() or isnull(c.grad_date)) AND m.inact_status={$r['status_id']}");
                $column_decoration[$r['status']] = array('no_total' => TRUE);
            }
            else
            {
                $active_columns['Daily Status (<72Hrs or CCIR)'][$r['status']] = "m.status = {$r['status_id']}";
            }
        }

        $inact_total_columns['Total Inactives'] = array(
            'Total Holdovers' => 'c.grad_date<=curdate() AND m.inact_status!=0 AND m.inact_status != 28',
            'Total Other Inactives' => '(c.grad_date>curdate() or isnull(c.grad_date)) AND m.inact_status!=0 AND m.inact_status != 28',
            'Total Holdunders' => 'm.inact_status = 28');

        $column_decoration['Daily Status (<72Hrs or CCIR)'] = array(
            'class' => 'table_heading',
            'unit' => TRUE);

        $columns = array_merge($columns,$inact_columns,$inact_total_columns,$active_columns);
    }
    else
    {
        /*************************
        * PERMANENT PARTY REPORT *
        *************************/
        $columns = array(
            'Personnel' => array(
                'Permanent Party - Organic' => 'm.pers_type = \'Permanent Party\' AND m.location=\'Organic\'',
                'Permanent Party - Attached' => 'm.pers_type = \'Permanent Party\' AND m.location=\'Attached\'',
                'Civilian - Organic' => 'm.pers_type=\'Civilian\' AND m.location = \'Organic\'',
                'Civilian - Attached' => 'm.pers_type=\'Civilian\' AND m.location = \'Attached\'',
                'Sub-Total' => 'm.location != \'Detached\''
                )
            );

        if($battalion == 0 && $company == 0)
        {
            $columns['Personnel']['Permanent Party - Detached'] = 'm.pers_type = \'Permanent Party\' AND m.location = \'Detached\'';
            $columns['Personnel']['Civilian - Detached'] = 'm.pers_type = \'Civilian\' AND m.location = \'Detached\'';
            $columns['Personnel']['Total (with Detached)'] = '1';
        }
        else
        {
            if($company == 0)
            {
                $columns['Personnel']['Permanent Party - Detached'] = "m.pers_type = 'Permanent Party' AND (m.location = 'Detached' OR l.detached_bn = $battalion)";
                $columns['Personnel']['Civilian - Detached'] = "m.pers_type = 'Civilian' AND (m.location = 'Detached' OR l.detached_bn = $battalion)";
                $columns['Personnel']['Total (with Detached)'] = "1";
            }
            else
            {
                $columns['Personnel']['Permanent Party - Detached'] = "m.pers_type = 'Permanent Party' AND (m.location = 'Detached' OR (l.detached_bn = $battalion AND l.detached_co = $company))";
                $columns['Personnel']['Civilian - Detached'] = "m.pers_type = 'Civilian' AND (m.location = 'Detached' OR (l.detached_bn = $battalion AND l.detached_co = $company))";
                $columns['Personnel']['Total (with Detached)'] = "1";
            }
        }

        $columns['Status'] = array(
            'Present For Duty' => 'm.inact_status=0',
            'Absent' => 'm.inact_status!=0');

        $columns['By Grade'] = array(
            'Officers' => "m.rank IN ('2LT','1LT','CPT','MAJ','LTC','COL','BG')",
            'Enlisted' => "m.rank IN ('PVT','PV2','PFC','SPC','CPL','SGT','SSG','SFC','MSG','1SG','SGM','CSM')",
            'Warrant Officers' => 'LEFT(m.rank,1)=\'W\'',
            'Civilians' => "LEFT(m.rank,2) IN ('GS','CI')",
            'None' => 'm.rank = \'None\'');

        $columns['By Gender'] = array(
            'Male' => 'm.gender = \'M\'',
            'Female' => 'm.gender = \'F\'');

        $inact_query = "SELECT status_id, status, type FROM status WHERE applies_to='permanent party' ORDER BY type ASC, status ASC";
        $result = mysql_query($inact_query) or die('Error getting status codes: ' . mysql_error());
        while($r = mysql_fetch_assoc($result))
        {
            if($r['type'] == 'Active')
            { $columns['Active Status'][$r['status']] = "m.status = {$r['status_id']}"; }
            else
            { $columns['Inactive Status'][$r['status']] = "m.inact_status = {$r['status_id']}"; }
        }
        $columns['Active Status']['Other / Unknown'] = 'm.status = 0';

        $column_decoration['Personnel']['Sub-Total'] = array(
            'dataname_class' => 'table_bgcolor_heading',
            'class' => 'table_bgcolor_cheading');
        $column_decoration['Personnel']['Total (with Detached)'] = array(
            'dataname_class' => 'table_bgcolor_heading',
            'class' => 'table_bgcolor_cheading');
        $column_decoration['Personnel']['no_total'] = TRUE;
        $column_decoration['Status']['class'] = 'table_heading';
        $column_decoration['By Grade']['class'] = 'table_heading';
        $column_decoration['By Gender']['class'] = 'table_heading';
        $column_decoration['Active Status']['class'] = 'table_heading';
        $column_decoration['Active Status']['unit'] = TRUE;
        $column_decoration['Inactive Status']['class'] = 'table_heading';
        $column_decoration['Inactive Status']['unit'] = TRUE;


    }

    if(isset($_REQUEST['header']) && isset($_REQUEST['row_name']))
    {
        /**********************
        * SHOW BY NAME REPORT *
        **********************/

        if($val->unit($_GET['unit'],12))
        { $ssn_length = 9; }
        else
        { $ssn_length = 4; }

        $date = strtoupper(date("dMY"));

        $safe_header = ucwords(htmlentities($_REQUEST['header']));
        $safe_row_name = ucwords(htmlentities($_REQUEST['row_name']));

        if(isset($columns[$_REQUEST['header']]))
        {
            if($_REQUEST['row_name'] == 'Total')
            {
                $safe_row_name = 'Total';
                $report['where'] .= ' AND ((' . implode(' ) OR ( ',$columns[$_REQUEST['header']]) . ')) ';
            }
            elseif(isset($columns[$_REQUEST['header']][$_REQUEST['row_name']]))
            {
                $report['where'] .= " and {$columns[$_REQUEST['header']][$_REQUEST['row_name']]} ";
            }

            $heading =  "<strong> $safe_header - $safe_row_name: " . $report["unit"] . " --- $date </strong>";

            $comp = implode(",",$_CONF['component']);
            $comp_abbr = "'" . implode("','",$_CONF['component_abbrev']) . "'";

            if($_REQUEST['pers_type'] == 'student')
            {
                $query = "select m.id, m.Last_Name, m.First_Name, m.middle_initial AS MI, CONCAT(m.rank,m.promotable) as 			Rank,
                          right(m.ssn,$ssn_length) as SSN, m.Gender as Gen,
                          elt(find_in_set(m.component,'$comp'),$comp_abbr) as Comp,
                          m.platoon as PLT, m.MOS, upper(date_format(m.arrival_date,'%d%b%y')) as Arrival_Date,
                          c.class_number as Class, s.Shift, s.Phase as Phase,
                          concat(co.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as Unit from main m LEFT JOIN student s ON m.id = s.id left join class c
                          on s.class_id = c.class_id, battalion b, company co
                          where m.battalion = b.battalion_id and m.company = co.company_id
                          and {$report['where']} order by m.last_name, m.first_name, m.middle_initial, m.ssn";
            }
            else
            {
                $query = "SELECT m.id, m.Last_Name, m.First_Name, m.middle_initial AS MI, CONCAT(m.rank,m.promotable) AS Rank,
                          RIGHT(m.ssn,$ssn_length) AS SSN, m.Gender AS Gen, ELT(FIND_IN_SET(m.component,'$comp'),$comp_abbr) AS Comp,
                          m.platoon AS PLT, m.MOS, m.Location, s1.status as Daily_Status, s2.Status as Inactive_Status,
                          CONCAT(co.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as Unit
                          FROM main m LEFT JOIN location l ON m.id = l.id LEFT JOIN status s2 ON m.inact_status = s2.status_id,
                          battalion b, company co, status s1
                          WHERE m.battalion = b.battalion_id AND m.company = co.company_id AND
                          m.status = s1.status_id AND {$report['where']}
                          ORDER BY m.last_name, m.first_name, m.middle_initial, m.ssn";
            }

            //echo $query;
            $roster = new roster($query);
            $roster->setheader($heading);
            $roster->link_page("data_sheet.php");
            $roster->link_column(0);
            $roster->sethidecolumn(0);
            $roster->setReportName('bynamereport');
			$roster->allowUserOrderBy(TRUE);
            echo $roster->drawroster();
        }
    }
    else
    {
        /*********************
        * SHOW ROLLUP REPORT *
        *********************/

        $query = "SELECT \n";
        foreach($columns as $array2)
        {
            foreach($array2 as $key=>$value)
            {
                $key = addslashes($key);
                $query .= "GREATEST(0,SUM(IF($value,1,0))) AS '$key',\n";
            }
        }

        if($_REQUEST['pers_type'] == 'student')
        {
            $query .= "$CASE
                       COUNT(*) AS 'Total'
                       FROM main m LEFT JOIN student s on m.id = s.id LEFT JOIN class c ON s.class_id = c.class_id
                       WHERE {$report['where']}
                       $GROUP_BY
                       $ORDER_BY";
        }
        else
        {
            $query .= "$CASE
                      COUNT(*) AS 'Total'
                      FROM main m LEFT JOIN location l on m.id = l.id
                      WHERE {$report['where']}
                      $GROUP_BY
                      $ORDER_BY";
        }

        //echo "<br />" . nl2br(htmlentities($query)) . "<br />";

        $result = mysql_query($query) or die('Unable to get information: ' . mysql_error());

        while($r = mysql_fetch_assoc($result))
        {
            if(isset($r['X_Unit']))
            { $data['Unit'][] = $r['X_Unit']; }

            foreach($columns as $header=>$array2)
            {
                foreach($array2 as $col=>$value)
                {
                    $data[$header][$col][] = $r[$col];
                    @$data[$header]['Row_Total_' . $col] += $r[$col];
                    @$data[$header]['Total_'.$header] += $r[$col];
                }
            }
        }

        foreach($columns as $header=>$array2)
        {
            $tot = 0;
            foreach($array2 as $col=>$value)
            {
                foreach($data[$header][$col] as $key=>$num)
                {
                    @$data[$header]['Col_Total_'.$header][$key] += $num;
                }
            }
        }

        if(count($data['Unit']) > 1)
        { $extra = 2; }
        else
        { $extra = 1; }

        $num_cols1 = count($data['Unit']) + $extra;


        ?>
        <br>
          <table border="1" cellpadding="4" cellspacing="0" align="center">

            <tr class="table_bgcolor_cheading">
              <td colspan="<?=$num_cols1?>">
                <?=$report['name']?><br />
                <?=$report['unit']?><br />
                <?=$report['date']?>
              </td>
            </tr>
        <?php

        //Units
        $xunit_array = array_flip($unit_array);

        $unit_block = "<tr class=\"table_cheading\"><td>&nbsp;</td>";
        foreach($data['Unit'] as $unit)
        {
            if(isset($xunit_array[$unit]))
            {
                $unit_block .= "<td>";
                if($show_links)
                { $unit_block .= "<a href=\"{$_CONF['current_page']}?unit={$xunit_array[$unit]}&pers_type={$report['type']}\">"; }
                $unit_block .= $unit;
                if($show_links)
                { $unit_block .= "</a>"; }
                $unit_block .= "</td>\n";
            }
            else
            { $unit_block .= "<td>$unit</td>\n"; }
        }

        if(count($data['Unit'])>1)
        { $unit_block .= "<td>Total</td>\n"; }

        $unit_block .= "</tr>\n";

        $spacer_block = "<tr class=\"table_heading\">
                           <td colspan=\"{$num_cols1}\">&nbsp;</td>
                         </tr>\n";

        echo $unit_block;

        $default_heading_class = 'table_bgcolor_heading';
        $default_data_class    = 'table_bgcolor_cdata';
        $default_dataname_class = '';
        $default_total_class   = 'table_bgcolor_cheading';

        foreach($columns as $header=>$array2)
        {
            $heading_class = (isset($column_decoration[$header]['class'])) ? $column_decoration[$header]['class'] : $default_heading_class;
            echo "<tr class=\"{$heading_class}\">
                    <td colspan=\"{$num_cols1}\">$header</td>
                  </tr>\n";

            if(isset($column_decoration[$header]['unit']))
            { echo $unit_block; }
            if(isset($column_decoration[$header]['spacer']))
            { echo $spacer_block; }

            if(count($array2))
            {
                $display_total = 0;

                foreach($array2 as $row_name=>$v)
                {
                    if(array_sum($data[$header][$row_name]) > 0)
                    {
                        $display_total = 1;

                        $data_class = (isset($column_decoration[$header][$row_name]['class'])) ? $column_decoration[$header][$row_name]['class'] : $default_data_class;
                        $dataname_class = (isset($column_decoration[$header][$row_name]['dataname_class'])) ? $column_decoration[$header][$row_name]['dataname_class'] : $default_dataname_class;

                        echo "<tr><td class=\"{$dataname_class}\">{$row_name}</td>";

                        $count = 0;

                        foreach($data[$header][$row_name] as $value)
                        {
                            echo "<td class=\"{$data_class}\">";

                            if($value > 0)
                            {
                                $url_header = urlencode($header);
                                $url_row_name = urlencode($row_name);
                                if($show_links)
                                { echo  "<a href=\"{$_CONF['current_page']}?unit=" . $xunit_array[$data['Unit'][$count]] . "&pers_type={$report['type']}&header=$url_header&row_name=$url_row_name\">"; }
                                echo $value;
                                if($show_links)
                                { echo "</a>"; }
                            }
                            else
                            { echo "0"; }

                            $count++;
                            echo "</td>";
                        }
                        if(count($data['Unit'])>1)
                        {
                            $default_total_class = (isset($column_decoration[$header][$row_name]['total_class'])) ? $column_decoration[$header][$row_name]['total_class'] : $default_total_class;
                            echo "<td class=\"{$default_total_class}\">";

                            if(isset($xunit_array['total']))
                            {
                                if($show_links)
                                { echo "<a href=\"{$_CONF['current_page']}?unit={$xunit_array['total']}&pers_type={$report['type']}&header=$url_header&row_name=$url_row_name\">"; }
                                echo $data[$header]['Row_Total_'.$row_name];
                                if($show_links)
                                { echo "</a>"; }
                            }
                            else
                            { echo $data[$header]['Row_Total_'.$row_name]; }

                            echo "</td>\n";
                        }
                        echo "</tr>\n";
                    }
                }

                if($display_total && !isset($column_decoration[$header]['no_total']))
                {
                    $count = 0;
                    echo "<tr><td class=\"table_bgcolor_heading\">Total</td>";
                    foreach($data[$header]['Col_Total_' . $header] as $value)
                    {
                        echo "<td class=\"table_bgcolor_cheading\">";
                        if($value > 0)
                        {
                            if($show_links)
                            { echo "<a href=\"{$_CONF['current_page']}?unit=" . $xunit_array[$data['Unit'][$count++]] . "&pers_type={$report['type']}&header=$url_header&row_name=Total\">"; }
                            echo $value;
                            if($show_links)
                            { echo "</a>"; }
                        }
                        else
                        { echo "0"; }
                        echo "</td>";
                    }

                    if(count($data['Unit'])>1)
                    {
                        echo "<td class=\"{$default_total_class}\">";

                        if($show_links)
                        { echo "<a href=\"{$_CONF['current_page']}?unit={$xunit_array['total']}&pers_type={$report['type']}&header=$url_header&row_name=Total\">"; }

                        echo $data[$header]['Total_' . $header];

                        if($show_links)
                        { echo "</a>"; }

                        echo "</td>\n";
                    }
                    echo "</tr>\n";
                }
            }
        }
        ?>
          </table>
        <?
    }
}
else
{ echo "Invalid Permissions - Daily Report"; }

echo com_sitefooter();

?>