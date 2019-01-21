<?
include("../lib-common.php");

include("../classes/validate.class.php");
include("../classes/roster.class.php");

$val = new validate;
$errors = 0;

    $battalion = $unit[0];
    $company = $unit[1];
    
    $date = strtoupper(date("dMY"));

    $header =  "<strong>BedCheck Roster Report: <? echo $date ?> </strong>";

    echo com_siteheader("Bedcheck Report --- {$date}");


        if(!isset($_REQUEST["export2"]))
        { echo "export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=excel'>excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"] . "&export2=word'>word</a>\n"; }

            
        $query = "SELECT
		`main`.`First_Name`, `main`.`Middle_Initial`, `main`.`SSN`, `main`.`Rank`,
		`main`.`Platoon`, `main`.`Pers_Type`, `main`.`Gender`, `main`.`MOS`,
		`main`.`Room_Number`, `status`.`Status`, `status`.`Type`, `main`.`Last_Name`,
		`student`.`Shift`, `class`.`Class_Number` FROM	`main`
		Inner Join `status` ON `status`.`Status_ID` = `main`.`Status`
		Inner Join `student` ON `student`.`ID` = `main`.`ID`
		Inner Join `class` ON `class`.`Class_ID` = `student`.`Class_ID`";

        $roster = new roster($query);
        $roster->setheader($header);
        $roster->link_page("data_sheet.php");
        $roster->link_column(0);
        $roster->sethidecolumn(0);
        $roster->setReportName('eocrollup');
		$roster->allowUserOrderBy(TRUE);
        $r = $roster->drawroster();
        
echo com_sitefooter();

?>
