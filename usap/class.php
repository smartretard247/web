<?
//configuration file
include("lib-common.php");
//validation routines
include($_CONF["path"] . "classes/validate.class.php");

//default values
$val = new validate;

if(isset($_GET["edit_class"]))
{
    header("location: " . $_CONF["html"] . "/edit_class.php?class_id=" . $_GET["class_id"]);
}

echo com_siteheader("Class Information");

if(!isset($_REQUEST["export2"]))
{ include($_CONF["path"] . "templates/choose_class.inc.php"); }

if(isset($_GET["class_id"]))
{
    $input["class_id"] = $val->fk_constraint($_GET["class_id"],"class","class_id");

    if($val->iserrors())
    { echo $val->geterrors(); }
    else
    {
        $query = "select "
                ."c.class_id, upper(date_format(c.start_date,'%d%b%y')) as start_date, "
                ."upper(date_format(c.eoc_date,'%d%b%y')) as eoc_date, "
                ."upper(date_format(c.ctt_date,'%d%b%y')) as ctt_date, "
                ."upper(date_format(c.trans_date,'%d%b%y')) as trans_date, "
                ."upper(date_format(c.stx_start,'%d%b%y')) as stx_start, "
                ."upper(date_format(c.stx_end,'%d%b%y')) as stx_end, "
                ."upper(date_format(c.grad_date,'%d%b%y')) as grad_date, "
                ."upper(date_format(c.pcs_date,'%d%b%y')) as pcs_date, c.mos, c.class_number, "
                ."b.battalion, co.company, c.inactive, c.extras, c.aot_type, c.phase "
            ."from "
                ."class c, battalion b, company co "
            ."where "
                ."c.class_id = {$input['class_id']} and c.company_id = co.company_id "
                ."and c.battalion_id = b.battalion_id";

        $result = mysql_query($query) or die("class select failed [$query]: " . mysql_error());

        if($row = mysql_fetch_assoc($result))
        {
            if($row['extras'])
            {
                $query = "SELECT field, value FROM class_extras WHERE class_id = {$row['class_id']}";
                $extra_result = mysql_query($query) or die("Error retrieving class extras: " . mysql_error());
            }
            else
            { $extra_result = FALSE; }

            //turn down error reporting to eliminate
            //notices from null values returned from
            //database
            error_reporting(E_ERROR | E_WARNING | E_PARSE);

            include($_CONF["path"] . "templates/view_class.inc.php");
        }
        else
        { echo '<span class="error">Unable to find class data.</span>'; }
    }
}

echo com_sitefooter();

?>
