<?
set_time_limit(0);
include("../lib-common.php");

include("../classes/validate.class.php");
include("../classes/roster.class.php");

//default values
$val = new validate;
$ssn_length = 9;

if(isset($_GET['class_id']))
{
    if(is_array($_GET['class_id']))
    {
        foreach($_GET['class_id'] as $ci)
        {
            if($valid_id = $val->cclass($ci,13))
            { $class_id[] = $valid_id; }
            if(!$val->cclass($ci,12))
            { $ssn_length = 4; }
        }
    }
    elseif($valid_id = $val->cclass($_GET['class_id'],13))
    { $class_id[] = $valid_id; }

    if(count($class_id) == 0)
    {
        echo com_siteheader("hrap report");
        echo "please choose a class";
    }
    else
    {
        $report['class_id'] = implode(",",$class_id);

        $date = strtoupper(date("dMY"));

        $header =  "<strong>hrap report --- $date</strong>";

        echo com_siteheader("hrap report --- $date");

        if(!isset($_REQUEST["export2"]))
        { echo "export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>word</a>\n"; }

        $comp = implode(",",$_CONF['component']);
        $comp_abbr = "'" . implode("','",$_CONF['component_abbrev']) . "'";

        $query1 = "create temporary table hrap_temp (index(id)) select * from address where type = 'hrap'";
        $result = mysql_query($query1) or die("temp table error; " . mysql_error());

        $query = "select m.id, c.Class_Number, m.Last_Name, m.First_Name, m.middle_initial as MI, concat(m.rank,m.promotable) as Rank, "
                ."right(m.ssn," . $ssn_length . ") as SSN, "
                ."if(m.education='not graduated high school','None',if(m.education='ged','GED','HSG')) as 'HSG / GED', "
                ."year(current_date) - year(dob) - (if(dayofyear(dob)>dayofyear(current_date),1,0)) as Age, m.gender as Gen, m.Race, "
                ."upper(date_format(c.grad_date,'%d%b%y')) as Grad_Date, "
                ."elt(find_in_set(m.component,'" . $comp . "')," . $comp_abbr . ") as Comp, m.Mos, s.HRAP,"
                ."concat(a.street1, ' ', a.street2) as Address, a.City, a.State, a.Zip, a.Phone1,  "
                ."concat(co.company,b.battalion+0) as Unit "
                ."from main m left join student s on m.id = s.id left join hrap_temp a on m.id = a.id "
                ."left join class c on s.class_id = c.class_id, company co, battalion b "
                ."where m.pcs=0 and c.class_id in (" . $report['class_id'] . ") and m.company = co.company_id and m.battalion = b.battalion_id "
                ."order by c.class_number asc, m.last_name, m.first_name";

        $roster = new roster($query);
        $roster->setheader($header);
        $roster->link_page("data_sheet.php");
        $roster->link_column(0);
        $roster->sethidecolumn(0);
        $roster->setReportName('hrapreportone');
		$roster->allowUserOrderBy(TRUE);
        echo $roster->drawroster();
    }
}
else
{
    echo com_siteheader("hrap report");
    echo "please choose a class";
}

echo com_sitefooter();

?>
