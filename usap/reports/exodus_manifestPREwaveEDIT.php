<?
set_time_limit(0);

include("../lib-common.php");

include("../classes/validate.class.php");
include("../classes/roster.class.php");

$val = new validate;
$valid_platoons = array();

$output = '';

$exodus_start_date = date('Ymd',strtotime($_CONF['exodus_date']));

function book($match)
{
    global $book_data;
    global $setup;
    global $airport;

    $battalion = $match[1];
    $group_id = $book_data['groups'][$battalion];

    if($airport=='Augusta')
    {
        if($book_data['old_group_id'] != $group_id)
        {
            if($book_data['reset_bus'])
            { $book_data['bus'] = $setup[$book_data['airport']]['bus'][$group_id]; }
            else
            { $book_data['bus']++; }

            $book_data['seat'] = 1;
            $book_data['old_group_id'] = $group_id;
        }
    }

    $retval = "<td>{$book_data['bus']}</td><td>{$book_data['seat']}";

    if(($book_data['seat']++ % $book_data['num_per_bus']) == 0)
    {
        $book_data['bus']++;
        $book_data['seat'] = 1;
    }

    return($retval);
}

echo com_siteheader('HBL 2010 Manifest');

//validate unit
if(1)
{
    $battalion = 0;
    $company = 0;

    //default sql where values
    $report["where"] = " 1 ";

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

    $report['where'] .= " and m.pers_type = 'IET' ";

    if(!isset($_REQUEST["export2"]))
    { echo "Export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>Excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>Word</a>\n"; }

    //Departure times from Ft. Gordon for each wave
    $setup['Atlanta']['depart'] = array('0001','0200','0300');
    $setup['Augusta']['depart'] = array('0100','0300','0500');

    //Start times of flights for each wave
    $setup['Atlanta']['start'] = array('060000','090100','130000');
    $setup['Augusta']['start'] = array('060000','080000','113500','140000','170000','194500');

    //End times of flights for each wave
    $setup['Atlanta']['end'] = array('090059','125959','235959');
    $setup['Augusta']['end'] = array('075959','113459','135959','165959','194459','235959');

    //Number of seats per bus for each airport
    $setup['Augusta']['per_bus'] = 44;
    $setup['Atlanta']['per_bus'] = 54;

    //Number of busses per wave
    $setup['Atlanta']['num_busses'] = 10;

    //Start Number/Letter of busses
    //for each group
    $setup['Atlanta']['bus'][1] = 1;
    $setup['Augusta']['bus'][1] = 'A';
    $setup['Augusta']['bus'][2] = 'H';

    //Flag to reset bus numbers/letter
    //for each wave
    $setup['Atlanta']['reset_bus'] = FALSE;
    $setup['Augusta']['reset_bus'] = TRUE;

    //Set up relationships between battalions
    //working out of the same deployment site
    //
    // Battalion_ID
    // 1 = 73
    // 2 = 369
    // 3 = 447
    // 4 = 551
    //
    //Format: array(Battalion_ID => Group_ID)
    //Group_ID = Any number
    //
    $setup['Atlanta']['group'] = array( 1 => 1,
                                        2 => 2,
                                        3 => 1,
                                        4 => 2);

    $setup['Augusta']['group'] = array( 1 => 1,
                                        2 => 1,
                                        3 => 2,
                                        4 => 1);

    echo '<table border="0" width="100%">';

    $links = '';

    foreach($setup as $airport => $airport_data)
    {
        //Initial data for Airport
        $book_data['airport'] = $airport;
        $book_data['bus'] = $airport_data['bus'][1];
        $book_data['reset_bus'] = $airport_data['reset_bus'];
        $book_data['group_id'] = 1;
        $book_data['old_group_id'] = 1;
        $book_data['num_per_bus'] = $airport_data['per_bus'];
        $book_data['seat'] = 1;
        $book_data['groups'] = $airport_data['group'];

        $order_column = "CASE\n";
        foreach($airport_data['group'] as $bn => $grp)
        {
            $order_column .= "WHEN m.battalion = $bn THEN $grp\n";
        }
        $order_column .= "END AS order_column\n";

        $links .= "<tr><td align='center'>";
        $num = count($airport_data['start']);
        //Loop through each wave
        for($x=0;$x<$num;$x++)
        {
            $book_data['wave'] = $x;

            $links .= "<a href='#{$airport}wave" . ($x+1) . "'>$airport Wave " . ($x+1) . "</a>&nbsp;&nbsp;&nbsp;";

            if($airport=='Atlanta')
            {
                $soldiers_per_wave = $airport_data['per_bus'] * $airport_data['num_busses'];

                $start = $x * $soldiers_per_wave;
                $end = $soldiers_per_wave;

                $query = "CREATE TEMPORARY TABLE temp1 (PRIMARY KEY(id)) SELECT e.id, e.dep_datetime
                          FROM main m, exodus e WHERE m.id = e.id
                          and m.pcs = 0 and e.dep_mode='AIR' and e.dep_airport='$airport' and
                          e.exodus_status!='Returned' and e.dep_datetime between 20101218060000 AND 20101218235959
                          and {$report['where']} ORDER BY e.dep_datetime ASC LIMIT $start,$end";
                $rs = mysql_query($query) or die('Unable to make temp table: ' . mysql_error());

                $query = "SELECT DATE_FORMAT(MIN(dep_datetime),'%H%i'), DATE_FORMAT(MAX(dep_datetime),'%H%i')
                          FROM temp1";
                $rs = mysql_query($query) or die('Unable to get MIN and MAX times: ' . mysql_error());
                list($min_time, $max_time) = mysql_fetch_row($rs);

                $header = "<a name='{$airport}wave" . ($x+1) . "'> $airport Manifest Wave ".($x+1).", Depart {$_CONF['exodus_date']} ".$airport_data['depart'][$x].", Flight Times from $min_time - $max_time";

                $query = "select m.id, $order_column, '%%%' as Bus, CONCAT('#',m.battalion,'#') as Seat, m.Rank, m.Last_Name,
                          m.First_Name, m.Middle_Initial as MI, right(m.ssn,4) as SSN,
                          concat(co.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as Unit,
                          m.Platoon as PL, e.Dep_Mode, upper(date_format(e.dep_datetime,'%d%b%y %H%i')) as Dep_DateTime,
                          e.Dep_Airline, upper(e.Dep_Flight_Num) as Dep_Flight_Number, e.Exodus_Status
                          from main m, company co, battalion b, exodus e, temp1 t
                          where m.id = e.id and m.id = t.id and m.pcs=0 and m.battalion = b.battalion_id and m.company = co.company_id and
                          e.exodus_status != 'returned' and e.dep_mode='air' and e.dep_airport='$airport'
                          and {$report['where']}
                          order by order_column, m.battalion, m.company, m.last_name, m.first_name";
            }
            else
            {
                $query = "select m.id, $order_column, '%%%' as Bus, CONCAT('#',m.battalion,'#') as Seat, m.Rank, m.Last_Name,
                          m.First_Name, m.Middle_Initial as MI, right(m.ssn,4) as SSN,
                          concat(co.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as Unit,
                          m.Platoon as PL, e.Dep_Mode, upper(date_format(e.dep_datetime,'%d%b%y %H%i')) as Dep_DateTime,
                          e.Dep_Airline, upper(e.Dep_Flight_Num) as Dep_Flight_Number, e.Exodus_Status
                          from main m, company co, battalion b, exodus e
                          where m.id = e.id and m.pcs=0 and m.battalion = b.battalion_id and m.company = co.company_id and
                          e.exodus_status != 'returned' and e.dep_mode='air' and e.dep_airport='$airport'
                          and e.dep_datetime between {$exodus_start_date}{$airport_data['start'][$x]} and {$exodus_start_date}{$airport_data['end'][$x]} and {$report['where']}
                          order by order_column, m.battalion, m.company, m.last_name, m.first_name";

                $header = "<a name='{$airport}wave" . ($x+1) . "'> $airport Manifest Wave ".($x+1).", Depart {$_CONF['exodus_date']} ".$airport_data['depart'][$x].", Flight Times from " . substr($airport_data['start'][$x],0,4) . " - " . substr($airport_data['end'][$x],0,4);
            }

            $roster = new roster($query);
            $roster->setheader($header);
            $roster->link_page("data_sheet.php");
            $roster->link_column(0);
            $roster->sethidecolumn(0);
            $roster->sethidecolumn(1);
            $roster->javascript = FALSE;
            $r = $roster->drawroster();
            $output .= "<tr><td>";
            $output .= preg_replace_callback('!<td>%%%</td>\n<td>#([0-9]+)#!','book',$r);
            $output .= "</td></tr>";
            unset($roster);

            //Reset data for next wave
            $book_data['seat'] = 1;
            $book_data['group_id'] = 1;

            //Reset bus number if airport is Augusta
            if($airport_data['reset_bus'])
            { $book_data['bus'] = $airport_data['bus'][1]; }

            if($airport=='Atlanta')
            { mysql_query("DROP TABLE temp1"); }
        }
        $links .= "<br>&nbsp;</td></tr>\n";
    }

    $links .= "<tr><td align='center'>Bus To Final Gate: ";

    foreach($_CONF['gate_numbers'] as $gate)
    {
        $links .= "<a href='#$gate'>$gate</a>&nbsp;&nbsp;&nbsp;";
        $header = "<a name='#$gate'>Bus To Final Gate $gate";

        $query = "select m.id, m.Rank, m.Last_Name, m.First_Name, m.Middle_Initial as MI, right(m.ssn,4) as SSN,
                  concat(co.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as Unit,
                  m.Platoon as PL, e.Dep_Mode, upper(date_format(e.dep_datetime,'%d%b%y %H%i')) as Dep_DateTime,
                  e.gate, e.Bus_Dest_City as Dest_City, e.Bus_Dest_State as Dest_State, e.Exodus_Status
                  from main m, company co, battalion b, exodus e
                  where m.id = e.id and m.pcs=0 and m.battalion = b.battalion_id and m.company = co.company_id and
                  e.exodus_status != 'returned' and e.dep_mode='bus' and e.gate='$gate' and left(e.exodus_status,6) = 'exodus'
                  and e.dep_datetime BETWEEN 20101218000000 AND 20101218235959 and {$report['where']}
                  order by b.Group_ID, m.battalion, m.company, m.last_name, m.first_name";

        $roster = new roster($query);
        $roster->setheader($header);
        $roster->link_page("data_sheet.php");
        $roster->link_column(0);
        $roster->javascript = FALSE;
        $roster->sethidecolumn(0);

        $output .= "<tr><td>";
        $output .= $roster->drawroster();
        $output .= "</td></tr>";
    }

    $links .= "<br>&nbsp;</td></tr>\n";

    $output .= "</table>";

    echo $links;
    echo $output;
}
else
{ echo "Invalid Permissions."; }

echo com_sitefooter();

?>
