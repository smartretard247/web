<?
include("../lib-common.php");

include("../classes/validate.class.php");
include("../classes/roster.class.php");

$val = new validate;
$pt = array();
$columns = '';

//validate unit
if($unit = $val->unit($_GET['unit'],14))
{
    $battalion = $unit[0];
    $company = $unit[1];

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

    //$exodus_start = strtotime($_CONF['exodus_start']);
    $exodus_date = strtotime($_CONF['exodus_date']);
    $exodus_end = strtotime($_CONF['exodus_end'] . " +1 day");
    $tk_start = strtotime($_CONF['three_kings_start']);
    $tk_end = strtotime($_CONF['three_kings_end'] . " +1 day");

    mysql_query("create temporary table all_exodus (primary key (id)) select m.id from main m, student s left join
                class c on s.class_id = c.class_id where m.id = s.id and m.pcs = 0 and m.pers_type = 'iet' and
                {$report['where']}") or die(mysql_error());

    switch($_GET['mode'])
    {
        case "air":
        /******
        * air *
        ******/
            $report['where'] .= " and e.exodus_status != 'returned' and e.dep_mode='air' ";
            $title = "air total";
            $columns = 'e.Dep_Airport, upper(date_format(e.dep_datetime,\'%d%b%y %H%i\')) as Dep_DateTime, ';

            if(isset($_GET['airport']))
            {
                switch($_GET['airport'])
                {
                    case "atlanta":
                        $report['where'] .= " and e.dep_airport='atlanta' ";
                        $title = "air atlanta";
                    break;
                    case "augusta":
                        $report['where'] .= " and e.dep_airport='augusta' ";
                        $title = "air augusta";
                    break;
                }
            }
            if(isset($_GET['ticket_status']))
            {
                switch($_GET['ticket_status'])
                {
                    case "in_hand":
                        $report['where'] .= " and e.dep_air_bus_ticket='Y' ";
                        $title = "air atlanta (bus ticket in hand)";
                    break;
                    case "unconfirmed":
                        $report['where'] .= " and e.dep_air_bus_ticket='N' ";
                        $title = "air atlanta (bus ticket unconfirmed)";
                    break;
                }
            }
            if(isset($_GET['ticket']))
            {
                switch($_GET['ticket'])
                {
                    case "onpost":
                        $report['where'] .= " and e.bought_dep_bustic_onpost='Y' and e.dep_air_bus_ticket='Y' ";
                        $title = "Air Atlanta (Bus Ticket Bought On Post)";
                    break;
                    case "offpost":
                        $report['where'] .= " and e.bought_dep_bustic_onpost='N' and e.dep_air_bus_ticket='Y' ";
                        $title = "Air Atlanta (Bus Ticket Bought Off Post)";
                    break;
                }
            }
        break;

        case "bus":
        /******
        * bus *
        ******/
            $report['where'] .= " and e.exodus_status != 'returned' and e.dep_mode = 'bus' and left(e.exodus_status,5) != 'three'";
            $title = "bus total";
            $columns = 'upper(date_format(e.dep_datetime,"%d%b%y %H:%i")) as Dep_DateTime, e.Gate, e.Dep_Ticket_Status, e.bought_dep_bustic_onpost as Bought_Ticket_On_Ft_Gordon, upper(e.bus_dest_city) as Destination_City, upper(e.bus_dest_state) as Destination_State, ';

            if(isset($_GET['ticket_status']))
            {
                switch($_GET['ticket_status'])
                {
                    case "in_hand":
                        $report['where'] .= " and e.dep_ticket_status='in hand' ";
                        $title = "bus (ticket in hand)";
                    break;
                    case "unconfirmed":
                        $report['where'] .= " and e.dep_ticket_status='unconfirmed' ";
                        $title = "bus (ticket status unconfirmed)";
                    break;
                    case "ftgordon":
                        $report['where'] .= " and e.bought_dep_bustic_onpost='Y' and e.dep_ticket_status='in hand' ";
                        $title = "bus (ticket bought on ft. gordon)";
                    break;
                    case "off_ftgordon":
                        $report['where'] .= " and e.bought_dep_bustic_onpost='N' and e.dep_ticket_status='in hand' ";
                        $title = "Bus (Ticket Bought Off Ft. Gordon)";
                    break;
                    case "without_gate":
                        $report['where'] .= " and (e.gate is null or e.gate = '' or e.gate='n/a') ";
                        $title = 'Bus - Soldiers without Gate Numbers';
                    break;
                }
            }
        break;

        case "pov":
        /******
        * pov *
        ******/
            $report['where'] .= " and e.exodus_status != 'returned' and e.dep_mode = 'pov' ";
            $title = "pov total";
            $columns = 'e.Dep_POV_Type, ';

            if(isset($_GET['status']))
            {
                switch($_GET['status'])
                {
                    case "driver":
                        $report['where'] .= " and e.dep_pov_type = 'driver' ";
                        $title = "pov drivers";
                    break;
                    case "passenger":
                        $report['where'] .= " and e.dep_pov_type = 'passenger' ";
                        $title = "pov passengers";
                    break;
                }
            }
        break;

        case "hold":
        /******************
        * holding company *
        ******************/
            $report['where'] .= " and left(e.exodus_status,4) = 'hold' ";
            $title = "holding company total";
            $columns = 'm.Gender as Gen, upper(date_format(e.dep_datetime,\'%d%b%y %H%i\')) as Dep_DateTime, ';
            if(isset($_GET['status']))
            {
                switch($_GET['status'])
                {
                    case "exodus":
                        $report['where'] .= " and e.exodus_status = 'holding company / exodus leave' ";
                        $title = "holding company (exodus leave)";
                    break;
                    case "pcs":
                        $report['where'] .= " and e.exodus_status = 'holding company / pcs' ";
                        $title = "holding company (pcs)";
                    break;
                    case "on_POST":
                        $report['where'] .= " and e.exodus_status = 'holding company - on post' ";
                        $title = "holding company (on post)";
                    break;
                    case "off_POST":
                        $report['where'] .= " and e.exodus_status = 'holding company - off post' ";
                        $title = "holding company (off post)";
                    break;
                }
            }
        break;

        case "3k":
        /**************
        * three kings *
        **************/
            $columns = 'm.Gender as Gen, e.Dep_Airport, upper(date_format(e.dep_datetime,\'%d%b%y %H%i\')) as Dep_DateTime, ';

            switch($_GET['status'])
            {
                case "return":
                    $report['where'] .= " and e.exodus_status = 'three kings leave (return)' ";
                    $title = "Three Kings (Return)";
                break;

                case "pcs":
                    $report['where'] .= " and e.exodus_status = 'three kings pcs (not returning)' ";
                    $title = "Three Kings PCS (Not Returning)";
                break;

                default:
                    $report['where'] .= " and (e.exodus_status = 'three kings pcs (not returning)' OR e.exodus_status = 'three kings leave (return)') ";
                    $title = "Three Kings Total";
                break;
            }
        break;

        case "unconfirmed":
        /**************
        * unconfirmed *
        **************/
            $report['where'] .= " and (e.exodus_status = 'unconfirmed travel plans' or e.exodus_status is null) ";
            $title = "unconfirmed travel plans";
        break;

        case "return":
        /***********
        * returned *
        ***********/
            $report['where'] .= " and e.exodus_status = 'returned' ";
            $title = "Soldiers Who Have Returned From Exodus";
        break;

        case "not_return":
        /***************
        * not returned *
        ***************/
            $report['where'] .= " and e.exodus_status != 'returned' and locate('PCS',e.exodus_status)=0 ";
            $title = "Soldiers Who Have Not Returned From HBL";
            $columns = 'e.Ret_Mode, e.Ret_Airport, upper(date_format(e.ret_datetime,\'%d%b%y %H%i\')) as Ret_DateTime, ';
        break;

        case "pcsing":
        /*********
        * PCSing *
        *********/
            $report['where'] .= " and locate('PCS',e.exodus_status) AND NOT LOCATE('prior',e.exodus_status)";
            $title = "Soldiers who are PCSing and Not Returning After Exodus";
        break;

        case "ng_er_pcs":
        /****************************
        * NG/ER PCS Prior to Exodus *
        ****************************/
            $report['where'] .= " and e.exodus_status = 'ng/er pcs prior to exodus' ";
            $title = "National Guard / Reserve Soldiers PCSing Prior to Exodus";
        break;

        case "other_pcs":
        /****************************
        * Other PCS Prior to Exodus *
        ****************************/
            $report['where'] .= " and e.exodus_status = 'other pcs/chapter prior to exodus' ";
            $title = "Soldiers PCSing Prior to Exodus (Not including NG/ER)";
        break;

        case "leave_early":
        /**************
        * leave early *
        **************/
            $exodus_leave_early_date = date('YmdHis',strtotime($_CONF['exodus_date']));
            $tk_leave_early_date = date('YmdHis',strtotime($_CONF['exodus_date']));

            $report['where'] .= " and e.dep_datetime>0 and
              ((e.dep_datetime < $exodus_leave_early_date AND left(e.exodus_status,5) != 'Three')
              OR
              ( e.dep_datetime < $tk_leave_early_date AND left(e.exodus_status,5) = 'Three')) ";
            $title = "Soldiers Leaving Early";
            $columns = 'e.Dep_Airport, upper(date_format(e.dep_datetime,\'%d%b%y %H%i\')) as Dep_DateTime, ';
        break;

        case "leave_late":
        /*************
        * leave late *
        *************/
            $exodus_leave_late_date = date('YmdHis',strtotime($_CONF['exodus_date'] . ' + 1 day'));
            $tk_leave_late_date = date('YmdHis',strtotime($_CONF['three_kings_start'] . ' + 1 day'));

            $report['where'] .= " and e.dep_datetime>0 and
              ((e.dep_datetime >= $exodus_leave_late_date AND left(e.exodus_status,5) != 'Three')
              OR
              ( e.dep_datetime >= $tk_leave_late_date AND left(e.exodus_status,5) = 'Three')) ";
            $title = "Soldiers Leaving Late";
            $columns = 'e.Dep_Airport, upper(date_format(e.dep_datetime,\'%d%b%y %H%i\')) as Dep_DateTime, ';
        break;

        case "return_early":
        /***************
        * return early *
        ***************/
            $report['where'] .= " and e.ret_datetime > 0 and e.ret_datetime < {$_CONF['exodus_return_early']} ";
            $return_early = strtotime($_CONF['exodus_return_early']);
            $t = strtoupper(date('Hi, dMy',$return_early));
            $title = "Soldiers Returning Early (Before $t)";
            $columns = "e.Ret_Mode, e.Ret_Airport, upper(date_format(e.ret_datetime,'%d%b%y %H%i')) as Ret_DateTime, ";
        break;

        case "return_late":
        /**************
        * return late *
        **************/
            $report['where'] .= " and
                ((unix_timestamp(e.ret_datetime) > $exodus_end and e.exodus_status != 'Three Kings Leave (Return)')
                or (unix_timestamp(e.ret_datetime) > $tk_end and e.exodus_status = 'Three Kings Leave (Return)')) ";
            $title = "Soldiers Returning Late";
            $columns = 'e.Ret_Mode, e.Ret_Airport, upper(date_format(e.ret_datetime,\'%d%b%y %H%i\')) as Ret_DateTime, ';
        break;

        case "planned":
        /**********
        * Planned *
        **********/
            $report['where'] .= "and left(e.exodus_status,4) = 'plan' ";
            $title = "Tentative Plans";
        break;

        case "planned_air_atlanta":
            $report['where'] .= "and e.exodus_status = 'Planned Air Atlanta' ";
            $title = "Planned Air Atlanta";
        break;

        case "planned_air_augusta":
            $report['where'] .= "and e.exodus_status = 'Planned Air Augusta' ";
            $title = "Planned Air Augusta";
        break;

        case "planned_bus":
            $report['where'] .= "and e.exodus_status = 'Planned Bus' ";
            $title = "Planned Bus";
        break;

        case "planned_pov":
            $report['where'] .= "and e.exodus_status = 'Planned POV' ";
            $title = "Planned POV";
        break;

        case "planned_three_kings":
            $report['where'] .= "and e.exodus_status = 'Planned Three Kings' ";
            $title = "Planned Three Kings";
        break;

        case "planned_holding_company":
            $report['where'] .= "and e.exodus_status = 'Planned Holding Company' ";
            $title = "Planned Holding Company";
        break;

        /*****************
        * Exodus Address *
        *****************/
        case "with_address":
            $query = "select m.id, m.Rank, m.Last_Name, m.First_Name, m.Middle_Initial AS MI, right(m.ssn,4) as SSN,
                      concat(co.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as Unit, m.Platoon as PL, e.Exodus_Status,
                      a.Street1, a.Street2, a.City, a.State, a.ZIP, a.Country, a.Phone1, a.Phone2
                      from main m, company co, battalion b, all_exodus ae left join exodus e on ae.id = e.id, address a where ae.id = a.id and
                      m.id = ae.id and m.battalion = b.battalion_id and m.company = co.company_id and a.type='Exodus' and
                      {$report['where']} order by m.last_name, m.first_name, m.middle_initial";
            $title = "Soldiers With Exodus Addresses";
        break;
        case "without_address":
            $result = mysql_query("create temporary table has_e_address (primary key(id)) select a.id from address a, all_exodus ae
                                   where ae.id = a.id and a.type = 'exodus' group by id") or die("Error creating temp exodus address table: " . mysql_error());

            $query = "select m.id, m.Rank, m.Last_Name, m.First_Name, m.Middle_Initial AS MI, right(m.ssn,4) as SSN,
                      concat(co.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as Unit, m.Platoon as PL, e.Exodus_Status
                      from main m, company co, battalion b,
                      all_exodus ae left join has_e_address a on ae.id = a.id left join exodus e on ae.id = e.id
                      where m.id = ae.id and m.battalion = b.battalion_id and m.company = co.company_id and a.id is null and
                      e.exodus_status NOT IN ('ng/er pcs prior to exodus','other pcs/chapter prior to exodus') and
                      {$report['where']} order by m.last_name, m.first_name, m.middle_initial";
            $title = "Soldiers Without Exodus Addresses";
        break;

        case "not_part":
            $query = "select m.id, m.Rank, m.Last_Name, m.First_Name, m.middle_initial as MI, right(m.ssn,4) as SSN,
                      concat(co.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as Unit, m.Platoon
                      from main m, all_exodus ae, exodus e, battalion b, company co where m.id = ae.id and m.id = e.id and
                      m.pcs = 0 and m.battalion = b.battalion_id and m.company = co.company_id and
                      m.pers_type = 'iet' and LOCATE('prior',e.exodus_status) and {$report['where']} order by m.last_name, m.first_name, mi";
            $title = "Soldiers Not Participating in Exodus";
        break;

        default:
            $columns = 'e.Dep_POV_Type, e.Dep_Ticket_Status, e.Dep_Airport, e.Dep_Airline, e.Dep_Flight_Num, e.Dep_Air_Bus_Ticket, e.Bought_Dep_BusTic_OnPost as Bought_Bus_Ticket_On_Ft_Gordon, e.Bus_Dest_City, e.Bus_Dest_State, ';
        break;
    }
    //end mode switch

    $date = strtoupper(date("dMY"));

    $header = "{$report['unit']} Exodus Status - $title - $date";
    echo com_siteheader("{$report['unit']} Exodus Status - $title - $date");

    if(!isset($_REQUEST["export2"]))
    { echo "export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>word</a>\n"; }

    error_reporting(0);

    echo "<br><br>\n";

    if(!isset($query))
    {
        $query = "select m.id, m.Rank, m.Last_Name, m.First_Name, m.Middle_Initial as MI, right(m.ssn,4) as SSN,
                concat(co.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as Unit, m.Platoon as PL,
                e.Dep_Mode, $columns e.Exodus_Status, e.Comment
                from main m, company co, battalion b, all_exodus ae left join exodus e on ae.id = e.id
                where m.id = ae.id and m.battalion = b.battalion_id and m.company = co.company_id and
                {$report['where']} order by m.last_name, m.first_name, m.middle_initial";
    }
    $roster = new roster($query);
    $roster->setheader($header);
    $roster->link_page("data_sheet.php");
    $roster->link_column(0);
    $roster->sethidecolumn(0);
    echo $roster->drawroster();

}
else
{ echo com_siteheader("invalid permissions - exodus status"); }

echo com_sitefooter();

?>
