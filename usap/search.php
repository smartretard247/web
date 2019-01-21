<?
//configuration file
include("lib-common.php");
//validation routines
include($_CONF["path"] . "classes/validate.class.php");

//default variables
$val = new validate;
$search = array("last_four" => 0, "full_ssn" => 0, "last_name" => 0, "first_name" => 0);

//set cookie to remember results per page
if(isset($_GET["results_per_page"]))
{
    setcookie("results_per_page",$_GET["results_per_page"],time()+10000);
    $_COOKIE["results_per_page"] = $_GET["results_per_page"];
}
elseif(!isset($_COOKIE["results_per_page"]))
{ $_COOKIE["results_per_page"] = "10"; }

//display site header
$display = com_siteheader("search the usap database");

//determine if search has been submitted
if(isset($_GET["search_text"]) && strlen($_GET["search_text"]) > 0)
{
    //see if search text is all numbers (ssn)
    if(ereg("^[0-9]+$",$_GET["search_text"]))
    {
        //if length is four, create sql to match last four of ssn
        if(strlen($_GET["search_text"]) == 4)
        { $criteria[] = "right(m.ssn,4) = " . $_GET["search_text"]; }
        //else if length is 9, create sql to match entire ssn
        elseif(strlen($_GET["search_text"]) == 9)
        { $criteria[] = "m.ssn = " . $_GET["search_text"]; }
        //if length is not 4 or 9, number is not valid. create error
        else
        { $val->error[] = "invalid social security number entered"; }

        //create sql to order result
        $search_order_by = " m.ssn asc ";
    }
    //see if search text matches name
    elseif(eregi("^([a-z]{0,1}(\\\')?[a-z]+)[,]?[ ]?([a-z]+)?$",$_GET["search_text"],$match))
    {
        //if sounds_like was checked, create sql to use soundex
        if(isset($_GET["sounds_like"]))
        { $criteria[] = "soundex(m.last_name) = soundex('" . $match[1] . "')"; }
        //else create sql to match first part of last name
        else
        { $criteria[] = "m.last_name like '" . $match[1] . "%'"; }

        //if first name was given, create sql to match first part
        if(strlen($match[3]) > 0)
        { $criteria[] = "m.first_name like '" . $match[3] . "%'"; }

        //create sql to order results
        $search_order_by = " m.last_name asc, m.first_name asc ";
    }
	elseif (strlen($_GET['search_text'])==18) {
	  // CAC Card barcode reading
	  $cac = true; // Flag to indicate possible cac barcode detected
	  $criteria[] = "c.cac_code='{$_GET['search_text']}'";
	  //echo "cac<br/>\n";
	  
	}
    //if search text does not match ssn or name, set error
    else
    { $val->error[] = "bad search text, please enter again"; }

    if(isset($_GET['type']) && count($_GET['type']) == 1)
    {
        if($_GET['type'][0] == "active")
        { $criteria[] = " m.pcs = 0 "; }
        else
        { $criteria[] = " m.pcs = 1 "; }
    }

    if(isset($_GET['pers_type']) && count($_GET['pers_type']) > 0)
    {
        //validate each of the personal types selected
        foreach($_GET['pers_type'] as $pers_type)
        {
            //$pt[] will contain all only valid personnel types
            if($val->conf($pers_type,"pers_type"))
            { $pt[] = $pers_type; }
        }
        //create string of the chosen pers_types to use in query later
        $pt_string = "'" . implode("','",$pt) . "'";
        $criteria[] = " m.pers_type in ($pt_string) ";
    }

    if(isset($_GET['unit']) && $_GET['unit'] != "all")
    {
        $bid = (int)$_GET['unit'];
        $criteria[] = " m.battalion = $bid ";
    }

    //display errors if there are some
    if($val->iserrors())
    {
        echo $display . $val->geterrors();
        include($_CONF["path"] . "templates/search.inc.php");
    }
    else
    {
        //create where sql for query
        $where = " " . implode(" and ",$criteria) . " ";

        //create query to determine total rows
        $count_query =  "select count(*),max(m.id) from main m, battalion b, company c where " . $where . " and m.battalion = b.battalion_id and m.company = c.company_id";
        $count_result = mysql_query($count_query) or die("count query error: " . mysql_error() . " sql: " . $count_query);
        $total_rows = mysql_result($count_result,0);
        $total_pages = floor($total_rows / $_COOKIE["results_per_page"]);

        if($total_rows == 1)
        {
            header("location: " . $_CONF['html'] . "/data_sheet.php?id=" . mysql_result($count_result,0,1));
            exit();
        }
        elseif($total_rows > 0)
        {
            //display header
            echo $display;

            //$page is used in prev/next links
            if(!isset($_GET["page"])) { $_GET["page"] = 0; }
            $prev_page = max(0,$_GET["page"] - 1);
            $next_page = min($total_pages,$_GET["page"] + 1);

            $start_limit = $_COOKIE["results_per_page"] * $_GET["page"];
            $end_limit = min($total_rows,$_COOKIE["results_per_page"] * ($_GET["page"] + 1));
            $query_string = eregi_replace("^page=[0-9]+&","",$_SERVER["QUERY_STRING"]);
			
			// TO DO: CREATE SECONDARY QUERY TO LOOK FOR SM BASE ON CAC BARCODE

            //create entire query
            $query = "SELECT m.id, right(m.ssn,4) as ssn, m.last_name, m.first_name, m.middle_initial, m.gender,
                             m.rank, m.mos, concat(c.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as unit
                      FROM main m, battalion b, company c
                      WHERE $where and m.battalion = b.battalion_id and m.company = c.company_id
                      ORDER BY $search_order_by";
					  
            if(!isset($_REQUEST["export2"]))
            {
                $query .= "limit "
                        . $start_limit . "," . $_COOKIE["results_per_page"];
            }

            $result = mysql_query($query) or die("search select error. query: " . $query . " error: " . mysql_error());
            //count how many rows were returned
            $num_results = mysql_num_rows($result);

            //create table to show results
            if(!isset($_REQUEST["export2"]))
            {
                echo "<table border='0' width='98%' cellspacing='1' cellpadding='1' align='center'>\n";
                echo "<tr>\n";
                echo "<td><font size='4'><b>your search returned " . $total_rows . " results. displaying results " . ($start_limit+1);
                if($start_limit+1 != $end_limit) { echo " - " . $end_limit; }
                echo ":</b></font></td>\n";
                echo "<td align='right'>export to: <a href='" . $_SERVER["SCRIPT_NAME"] . "?page=0&export2=excel&" . $query_string . "'>excel</a> / <a href='" . $_SERVER["SCRIPT_NAME"] . "?page=0&export2=word&" . $query_string . "'>word</a></td>\n";
                echo "<tr><td colspan='2'><i>click on row to be taken to data sheet for that soldier</i></td></tr>\n";
                echo "</table>\n";
            }

            echo "<table border='1' cellspacing='1' cellpadding='1' align='center' width='98%'>\n";
            echo "<tr class='table_heading'><th>Last Name</th><th>First Name</th><th>MI</th><th>SSN</th><th>Rank</th><th>Gen</th><th>Unit</th></tr>\n";

            $col = 0;
            $bgcolor = "#ffffff";

            while($row = mysql_fetch_array($result))
            {
                if(!isset($_REQUEST["export2"]))
                {
                    //creates code to highlight column on mouseover and to make whole row
                    //a hyperlink to data_sheet.php.
                    //***requires m.id to be in $row[0] in above select***.
                    if($col++ & 1)
                    { $tr_info=" id=\"mtna" . $col . "\" bgcolor=\"{$_CONF['up']['row_one_color']}\" onmouseover=\"if(document.layers) { document.layers['mtna" . $col . "'].bgcolor='{$_CONF['up']['row_highlight_color']}' } else { if(document.all) { document.all['mtna" . $col . "'].style.background='{$_CONF['up']['row_highlight_color']}' } }\" onmouseout=\"if(document.layers) { document.layers['mtna" . $col . "'].bgcolor='{$_CONF['up']['row_one_color']}' } else { if(document.all) { document.all['mtna" . $col . "'].style.background='{$_CONF['up']['row_one_color']}' } }\" onclick=\"location.href='data_sheet.php?id=" . $row[0] . "';\" "; }
                    else
                    { $tr_info=" id=\"mtna" . $col . "\" bgcolor=\"{$_CONF['up']['row_two_color']}\" onmouseover=\"if(document.layers) { document.layers['mtna" . $col . "'].bgcolor='{$_CONF['up']['row_highlight_color']}' } else { if(document.all) { document.all['mtna" . $col . "'].style.background='{$_CONF['up']['row_highlight_color']}' } }\" onmouseout=\"if(document.layers) { document.layers['mtna" . $col . "'].bgcolor='{$_CONF['up']['row_two_color']}' } else { if(document.all) { document.all['mtna" . $col . "'].style.background='{$_CONF['up']['row_two_color']}' } }\" onclick=\"location.href='data_sheet.php?id=" . $row[0] . "';\" "; }
                }
                echo "<tr " . $tr_info . ">\n";
                echo "<td>" . $row["last_name"] . "</td>\n";
                echo "<td>" . $row["first_name"] . "</td>\n";
                echo "<td>" . $row["middle_initial"] . "&nbsp;</td>\n";
                echo "<td><a href='" . $_CONF["html"] . "/data_sheet.php?id=" . $row["id"] . "'>" . $row["ssn"] . "</a></td>\n";
                echo "<td>" . $row["rank"] . "</td>\n";
                echo "<td>" . $row["gender"] . "</td>\n";
                echo "<td>" . $row["unit"] . "</td>\n";
                echo "</tr>\n";
            }

            echo "</table>\n";

            if(!isset($_REQUEST["export2"]))
            {
                echo "<center>\n";

                if($_GET["page"] != 0)
                { echo "<a href='" . $_SERVER["SCRIPT_NAME"] . "?page=0&" . $query_string . "'>&lt;&lt;</a>&nbsp;&nbsp;\n"; }
                else
                { echo "&lt;&lt;&nbsp;&nbsp;\n"; }

                $min_page = max(0,$_GET["page"]-5);
                $max_page = min($total_pages,$_GET["page"]+5);
                for($x=$min_page;$x<=$max_page;$x++)
                {
                    if($_GET["page"] == $x)
                    { echo "<b>[" . ($x+1) . "]</b>&nbsp;\n"; }
                    else
                    { echo "<a href='" . $_SERVER["SCRIPT_NAME"] . "?page=" . $x . "&" . $query_string . "'>[" . ($x+1) . "]</a>&nbsp;\n"; }
                }

                if($_GET["page"] != $total_pages)
                { echo "&nbsp;<a href='" . $_SERVER["SCRIPT_NAME"] . "?page=" . $total_pages . "&" . $query_string . "'>&gt;&gt;</a>\n"; }
                else
                { echo "&nbsp;&gt;&gt;\n"; }

                echo "</center>\n";
            }
        }
        else
        {
            echo $display . "<p align='center'><font size='4'><b>your search did not return any results</b></font></p>\n";
            include($_CONF["path"] . "templates/search.inc.php");
        }
    }
}
//if search text was given, show input box to enter search
else
{
    echo $display;
    include($_CONF["path"] . "templates/search.inc.php");
}

echo com_sitefooter();
?>