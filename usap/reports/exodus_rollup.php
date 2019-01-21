<?
include("../lib-common.php");

include($_CONF['path'] . '/classes/validate.class.php');
include($_CONF['path'] . '/classes/roster.class.php');

$val = new validate;

echo com_siteheader("HBL Rollup - " . strtoupper(date("dMY")));

$show_links = TRUE;

if(!isset($_REQUEST["export2"]))
{ echo "Export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>Excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>Word</a>\n"; }
else
{ $show_links = FALSE; }

$exodus_leave_early_date = date('YmdHis',strtotime($_CONF['exodus_date']));
$exodus_leave_late_date = date('YmdHis',strtotime($_CONF['exodus_date'] . ' + 1 day'));

$tk_leave_early_date = date('YmdHis',strtotime($_CONF['three_kings_start']));
$tk_leave_late_date = date('YmdHis',strtotime($_CONF['three_kings_start'] . ' + 1 day'));

$exodus_return_early_date = date('YmdHis',strtotime($_CONF['exodus_end'] . ' 00:00:00'));
$tk_return_early_date = date('YmdHis',strtotime($_CONF['three_kings_end'] . ' 00:00:00'));

$exodus_return_late_date = date('YmdHis',strtotime($_CONF['exodus_end'] . " +1 day"));
$tk_return_late_date = date('YmdHis',strtotime($_CONF['three_kings_end'] . " +1 day"));

if($unit = $val->unit($_GET['unit'],14))
{
    $battalion = $unit[0];
    $company = $unit[1];

    //default values
    $students = "'" . implode("','",$_CONF["students"]) . "'";

    $report["where"] = " m.pcs = 0 AND m.pers_type = 'IET' AND m.battalion IN (1,2,3,4)";

        $report['name'] = "HBL Rollup Report";
        $report['type'] = 'student';

    $CASE = '';
    $GROUP_BY = '';
    $ORDER_BY = '';

    $unit_array = array('1-0' => '73rd',
                        '2-0' => '369th',
                        '3-0' => '447th',
                        '4-0' => '551st');
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
            END AS 'X_Unit',

            CASE
              WHEN m.battalion = 1 THEN 'a'
              WHEN m.battalion = 2 THEN 'b'
              WHEN m.battalion = 3 THEN 'c'
              WHEN m.battalion = 4 THEN 'd'
            END AS 'X_Order_Column',
        ";

        $GROUP_BY = ' GROUP BY X_Unit ';
        $ORDER_BY = ' ORDER BY X_Order_Column ';
    }
    else
    {
        $result = mysql_query("select battalion from battalion where battalion_id = $battalion") or die(mysql_error());
        $report["battalion"] = mysql_result($result,0);

        $report["where"] .= " and m.battalion = $battalion ";

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

            $report["where"] .= " and m.company = $company ";

            $units = array();
        }

        $report["unit"] = $report["company"] . " " . $report["battalion"];
    }

    $report['date'] = strtoupper(date("dMY"));

    /*****************
    * STUDENT REPORT *
    *****************/

    $columns = array(
        'Participating' => array(
            'Total' => '1'
        ),

        'AIR' => array(
            'Atlanta' => 'e.dep_airport=\'Atlanta\'',
            'Augusta' => 'e.dep_airport=\'Augusta\'',
            'Total AIR' => 'e.dep_airport IN (\'Atlanta\',\'Augusta\')',
            'Atlanta Bus Ticket In-Hand' => 'e.dep_air_bus_ticket=\'Y\' and e.dep_airport=\'Atlanta\'',
            'Atlanta Bus Ticket Unconfirmed' => 'e.dep_air_bus_ticket!=\'Y\' and e.dep_airport=\'Atlanta\'',
            'Bought Atlanta Bus Ticket on Ft. Gordon' => 'e.dep_air_bus_ticket=\'Y\' and e.bought_dep_bustic_onpost=\'Y\' and e.dep_airport=\'Atlanta\'',
            'Bought Atlanta Bus Ticket off Ft. Gordon' => 'e.dep_air_bus_ticket=\'Y\' and e.bought_dep_bustic_onpost=\'N\' and e.dep_airport=\'Atlanta\''
        ),

        'BUS' => array(
            'Total BUS' => 'e.dep_mode=\'BUS\'',
            'Bus Ticket In-Hand' => 'e.dep_ticket_status=\'In Hand\' AND e.dep_mode=\'BUS\'',
            'Bus Ticket Unconfirmed' => 'e.dep_ticket_status=\'Unconfirmed\' AND e.dep_mode=\'BUS\'',
            'Bus Gate Unknown' => 'e.dep_mode=\'BUS\' AND (e.gate IS NULL OR e.gate = \'N/A\' OR e.gate=\'\')',
            'Bought Bus Ticket on Ft. Gordon' => 'e.dep_mode=\'BUS\' AND e.bought_dep_bustic_onpost=\'Y\'',
            'Bought Bus Ticket off Ft. Gordon' => 'e.dep_mode=\'BUS\' AND e.bought_dep_bustic_onpost=\'N\''
        ),

        'POV' => array(
            'Driver' => 'e.dep_mode=\'POV\' AND e.dep_pov_type=\'Driver\'',
            'Passenger' => 'e.dep_mode=\'POV\' AND e.dep_pov_type=\'Passenger\''
        ),

        'Tentative Plans' => array(
            'Planned Air Atlanta' => 'e.exodus_status=\'Planned Air Atlanta\'',
            'Planned Air Augusta' => 'e.exodus_status=\'Planned Air Augusta\'',
            'Planned Bus' => 'e.exodus_status=\'Planned Bus\'',
            'Planned POV' => 'e.exodus_status=\'Planned POV\'',
            'Planned Three Kings' => 'e.exodus_status=\'Planned Three Kings\'',
            'Planned Holding Company' => 'e.exodus_status=\'Planned Holding Company\''
        ),

        'Unconfirmed' => array(
            'Total Unconfirmed' => 'e.exodus_status=\'Unconfirmed Travel Plans\''
        ),

        'Holding Company' => array(
            'Exodus Leave' => 'e.exodus_status=\'Holding Company / Exodus Leave\'',
            'PCS' => 'e.exodus_status=\'Holding Company / PCS\'',
            'On Post' => 'e.exodus_status=\'Holding Company - On Post\'',
            'Off Post' => 'e.exodus_status=\'Holding Company - Off Post\''
        ),

        'Three Kings' => array(
            'Three Kings Leave & Return' => 'e.exodus_status=\'Three Kings Leave (Return)\'',
            'Three Kings PCS' => 'e.exodus_status=\'Three Kings PCS (Not Returning)\''
        ),

        'Returned' => array(
            'Returned From Leave' => 'e.returned=1 AND NOT LOCATE(\'PCS\',e.exodus_status)',
            'Not Returned From Leave' => '((e.returned=0 AND NOT LOCATE(\'PCS\',e.exodus_status)) OR e.exodus_status IS NULL)',
            'Not Returning (PCS)' => 'LOCATE(\'PCS\',e.exodus_status)'
        ),

        'Misc' => array(
            'Exodus PCS - OCONUS' => 'e.exodus_status NOT IN (\'NG/ER PCS Prior to Exodus\',\'Other PCS/Chapter Prior to Exodus\') AND LOCATE(\'PCS\',e.exodus_status) AND m.pcs_location=\'OCONUS\'',
            'NG/ER PCS Prior to Exodus' => 'e.exodus_status=\'NG/ER PCS Prior to Exodus\'',
            'Other PCS/Chapter Prior to Exodus' => 'e.exodus_status=\'Other PCS/Chapter Prior to Exodus\'',
            'Leaving Early' => "(e.dep_datetime > 0 AND (e.dep_datetime < $exodus_leave_early_date AND left(e.exodus_status,5) != 'Three') OR (e.dep_datetime < $tk_leave_early_date AND left(e.exodus_status,5) = 'Three'))",
            'Leaving Late' => "(e.dep_datetime > 0 AND (e.dep_datetime > $exodus_leave_late_date AND left(e.exodus_status,5) != 'Three') OR (e.dep_datetime > $tk_leave_late_date AND left(e.exodus_status,5) = 'Three'))",
            'Returning Early' => "(e.ret_datetime > 0 AND (e.ret_datetime < $exodus_return_early_date AND left(e.exodus_status,5) != 'Three') OR (e.ret_datetime < $tk_return_early_date AND left(e.exodus_status,5) = 'Three'))",
            'Returning Late' => "(e.ret_datetime > 0 AND (e.ret_datetime > $exodus_return_late_date AND left(e.exodus_status,5) != 'Three') OR (e.ret_datetime > $tk_return_late_date AND left(e.exodus_status,5) = 'Three'))",
            'Soldiers with Exodus Address' => 'ea.id IS NOT NULL',
            'Soldiers without Exodus Address' => 'NOT LOCATE(\'Prior\',e.exodus_status) AND NOT LOCATE(\'Holding\',e.exodus_status) AND ea.id IS NULL'
        )
    );

    $column_decoration = array(
        'Participating' => array(
            'no_total' => TRUE,
            'class' => 'table_heading'
        ),

        'AIR' => array(
            'no_total' => TRUE,
            'class' => 'table_heading',
            'Total AIR' => array(
                'class' => 'table_bgcolor_cheading',
                'dataname_class' => 'table_bgcolor_heading',
                'bgcolor_spacer' => TRUE
            ),
            'Atlanta Bus Ticket Unconfirmed' => array(
                'dataname_class' => 'table_bgcolor_error'
            ),
            'Bought Atlanta Bus Ticket off Ft. Gordon' => array(
                'dataname_class' => 'table_bgcolor_error'
            )
        ),

        'BUS' => array(
            'no_total' => TRUE,
            'class' => 'table_heading',
            'Total BUS' => array(
                'class' => 'table_bgcolor_cheading',
                'dataname_class' => 'table_bgcolor_heading',
                'bgcolor_spacer' => TRUE
            ),
            'Bus Ticket Unconfirmed' => array(
                'dataname_class' => 'table_bgcolor_error'
            ),
            'Bus Gate Unknown' => array(
                'dataname_class' => 'table_bgcolor_error'
            ),
            'Bought Bus Ticket off Ft. Gordon' => array(
                'dataname_class' => 'table_bgcolor_error'
            )
        ),

        'POV' => array(
            'class' => 'table_heading'
        ),

        'Tentative Plans' => array(
            'class' => 'table_heading'
        ),

        'Unconfirmed' => array(
            'class' => 'table_heading',
            'no_total' => TRUE,
            'unit' => TRUE
        ),

        'Holding Company' => array(
            'class' => 'table_heading'
        ),

        'Three Kings' => array(
            'class' => 'table_heading'
        ),

        'Returned' => array(
            'class' => 'table_heading',
            'no_total' => TRUE
        ),

        'Misc' => array(
            'class' => 'table_heading',
            'no_total' => TRUE,
            'unit' => TRUE,
            'Leaving Early' => array(
                'dataname_class' => 'table_bgcolor_error'
            ),
            'Leaving Late' => array(
                'dataname_class' => 'table_bgcolor_error'
            ),
            'Returning Early' => array(
                'dataname_class' => 'table_bgcolor_error'
            ),
            'Returning Late' => array(
                'dataname_class' => 'table_bgcolor_error'
            ),
            'Soldiers without Exodus Address' => array(
                'dataname_class' => 'table_bgcolor_error'
            )
        )
    );

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

            //Get exodus addresses
            $query = "CREATE TEMPORARY TABLE exodus_address (primary key(id)) SELECT id FROM address a WHERE a.type='Exodus' GROUP BY a.id";
            $rs = mysql_query($query) or die('Unable to get exodus address: ' . mysql_error());

            $query = "select m.id, m.Last_Name, m.First_Name, m.middle_initial AS MI, CONCAT(m.rank,m.promotable) as Rank,
                      right(m.ssn,$ssn_length) as SSN, m.Gender as Gen,
                      elt(find_in_set(m.component,'$comp'),$comp_abbr) as Comp, m.platoon as PLT,
                      e.Exodus_Status, e.Dep_Mode, e.Dep_Airport, UPPER(DATE_FORMAT(e.dep_datetime,'%d%b%y %H:%i')) as Dep_Time,
                      UPPER(DATE_FORMAT(e.ret_datetime,'%d%b%y %H:%i')) as Ret_Time,
                      concat(co.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as Unit
                      FROM main m LEFT JOIN exodus e ON m.id = e.id LEFT JOIN exodus_address ea ON m.id = ea.id,
                      battalion b, company co
                      where m.battalion = b.battalion_id and m.company = co.company_id
                      and {$report['where']} order by m.last_name, m.first_name, m.middle_initial, m.ssn";

            //echo $query;
            $roster = new roster($query);
            $roster->setheader($heading);
            $roster->link_page("data_sheet.php");
            $roster->link_column(0);
            $roster->sethidecolumn(0);
            echo $roster->drawroster();
        }
    }
    else
    {
        /*********************
        * SHOW ROLLUP REPORT *
        *********************/

        //Get exodus addresses
        $query = "CREATE TEMPORARY TABLE exodus_address (primary key(id)) SELECT id FROM address a WHERE a.type='Exodus' GROUP BY a.id";
        $rs = mysql_query($query) or die('Unable to get exodus address: ' . mysql_error());

        $query = "SELECT \n";
        foreach($columns as $array2)
        {
            foreach($array2 as $key=>$value)
            {
                $key = addslashes($key);
                $query .= "GREATEST(0,SUM(IF($value,1,0))) AS '$key',\n";
            }
        }

        $query .= "$CASE
                   COUNT(*) AS 'Total'
                   FROM main m LEFT JOIN exodus e ON m.id = e.id LEFT JOIN exodus_address ea ON m.id = ea.id
                   WHERE {$report['where']}
                   $GROUP_BY
                   $ORDER_BY";

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

        $bgcolor_spacer_block = "<tr>
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

                    if(isset($column_decoration[$header][$row_name]['spacer']))
                    { echo $spacer_block; }
                    elseif(isset($column_decoration[$header][$row_name]['bgcolor_spacer']))
                    { echo $bgcolor_spacer_block; }

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
{ echo "Invalid Permissions - HBL Rollup"; }

echo com_sitefooter();

?>