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

    $header =  "<strong>airborne msr report: " . $report["unit"] . " --- $date </strong>";

    echo com_siteheader("airborne msr report: " . $report['unit'] . " --- $date");

    if(!isset($_REQUEST["export2"]))
    { echo "export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>word</a>\n"; }

    $result = mysql_query("create temporary table temp select id, date from apft where type='student-eoc' or type='student-diag' group by id order by date desc") or die("apft temp table error: " . mysql_error());

    $query = "select m.id, m.MOS, concat(m.rank,m.promotable) as Rank, "
                ."a.type as Procured, m.Last_Name, "
                ."m.First_Name, m.middle_initial as MI, right(m.ssn,4) as SSN, "
                ."concat(co.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as Unit, "
                ."if(m.not_graduating,'h/o',upper(date_format(c.grad_date,'%d%b%y'))) as AIT_Date, "
                ."upper(date_format(a.packet_init,'%d%b%y')) as Packet_Init, "
                ."upper(date_format(a.vol_date,'%d%b%y')) as 'AB Vol Stat', "
                ."upper(date_format(a.physical1,'%d%b%y')) as AB_Phys_1, "
                ."upper(date_format(a.physical2,'%d%b%y')) as AB_Phys_2, "
                ."upper(date_format(t.date,'%d%b%y')) as APFT, "
                ."upper(date_format(a.submit_4187,'%d%b%y')) as '4187\'s', "
                ."upper(date_format(a.packet_ti,'%d%b%y')) as 'Packet_T/I', a.Remark "
                ."from main m left join temp t on m.id = t.id left join airborne a on m.id = a.id left join student s on m.id = s.id, "
                ."battalion b, company co, class c "
                ."where m.pcs = 0 and s.class_id = c.class_id and m.battalion = b.battalion_id and m.company = co.company_id "
                ."and s.airborne='y' and m.pers_type = 'iet' and " . $report["where"]
                ." order by m.last_name, m.first_name, m.middle_initial, m.ssn";

    $roster = new roster($query);
    $roster->setheader($header);
    $roster->link_page("data_sheet.php");
    $roster->link_column(0);
    $roster->sethidecolumn(0);
    $roster->setReportName('airbornemsrreport');
	$roster->allowUserOrderBy(TRUE);
    echo $roster->drawroster();

}
else
{
    echo com_siteheader("invalid permissions - alpha roster");
    if(count($_GET['pers_type']) == 0)
    { echo "no personnel type chosen."; }
    else
    { echo "invalid permissions.";  }
}

echo com_sitefooter();

?>