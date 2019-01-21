<?
//configuration file
include("config.php");
include("lib-database.php");
include("lib-custom.php");
//validation routines
include($_CONF["path"] . "classes/validate.class.php");

//default variables
$val = new validate;
//$search = array("last_four" => 0, "full_ssn" => 0, "last_name" => 0, "first_name" => 0);

//show input box to enter search
include($_CONF["path"] . "templates/search2.inc.php");

//determine if search has been submitted
if(isset($_GET["search_text"]) && strlen($_GET["search_text"]) > 0)
{
    //see if search text matches name
    if(eregi("^([a-z]{0,1}(\\\')?[a-z]+)[,]?[ ]?([a-z]+)?$",$_GET["search_text"],$match))
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
    //if search text does not match ssn or name, set error
    else
    { $val->error[] = "Bad search text, please enter again"; }

    //display errors if there are some
    if($val->iserrors())
    { echo $val->geterrors(); }
    else
    {
        //create where sql for query
        $where = " " . implode(" and ",$criteria) . " ";

        //create query to determine total rows
        $count_query =  "select count(*),max(m.id) from main m, battalion b, company c where " . $where . " and m.battalion = b.battalion_id and m.company = c.company_id";
        $count_result = mysql_query($count_query) or die("count query error: " . mysql_error() . " sql: " . $count_query);
        $total_rows = mysql_result($count_result,0);
        $total_pages = floor($total_rows / 20);
        
        if($total_rows > 0)
        {
            //$page is used in prev/next links
            if(!isset($_GET["page"])) { $_GET["page"] = 0; }
            $prev_page = max(0,$_GET["page"] - 1);
            $next_page = min($total_pages,$_GET["page"] + 1);

            $start_limit = 20 * $_GET["page"];
            $end_limit = min($total_rows,20 * ($_GET["page"] + 1));
            $query_string = eregi_replace("^page=[0-9]+&","",$_SERVER["QUERY_STRING"]);

            //create entire query
            $query = "select "
                    ."m.id, m.Last_Name, m.First_Name, "
                    ."m.Rank, concat(c.company,'-',if(0+b.battalion=0,b.battalion,0+b.battalion)) as Unit, "
                    ."if(m.pcs=0,'No','Yes') as pcs "
                 ."from "
                    ."main m, battalion b, company c "
                 ."where "
                    . $where . " and m.battalion = b.battalion_id and m.company = c.company_id and m.pers_type!='civilian'"
                 ."order by "
                    . $search_order_by . " ";
            if(!isset($_REQUEST["export2"]))
            {
                $query .= "limit "
                        . $start_limit . ",20";
            }

            $result = mysql_query($query) or die("search select error. query: " . $query . " error: " . mysql_error());
            //count how many rows were returned
            $num_results = mysql_num_rows($result);

            echo "<table border='1' cellspacing='1' cellpadding='1'>\n";
            echo "<tr class='table_heading'><th>Last Name</th><th>First Name</th><th>Rank</th><th>Unit</th><th>PCS?</th></tr>\n";

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
                    { $tr_info=" id=\"mtna" . $col . "\" bgcolor=\"{$_CONF['up']['row_one_color']}\" onmouseover=\"if(document.layers) { document.layers['mtna" . $col . "'].bgcolor='{$_CONF['up']['row_highlight_color']}' } else { if(document.all) { document.all['mtna" . $col . "'].style.background='{$_CONF['up']['row_highlight_color']}' } }\" onmouseout=\"if(document.layers) { document.layers['mtna" . $col . "'].bgcolor='{$_CONF['up']['row_one_color']}' } else { if(document.all) { document.all['mtna" . $col . "'].style.background='{$_CONF['up']['row_one_color']}' } }\" "; }
                    else
                    { $tr_info=" id=\"mtna" . $col . "\" bgcolor=\"{$_CONF['up']['row_two_color']}\" onmouseover=\"if(document.layers) { document.layers['mtna" . $col . "'].bgcolor='{$_CONF['up']['row_highlight_color']}' } else { if(document.all) { document.all['mtna" . $col . "'].style.background='{$_CONF['up']['row_highlight_color']}' } }\" onmouseout=\"if(document.layers) { document.layers['mtna" . $col . "'].bgcolor='{$_CONF['up']['row_two_color']}' } else { if(document.all) { document.all['mtna" . $col . "'].style.background='{$_CONF['up']['row_two_color']}' } }\" "; }
                }
                echo "<tr " . $tr_info . ">\n";
                echo "<td>" . $row["Last_Name"] . "</td>\n";
                echo "<td>" . $row["First_Name"] . "</td>\n";
                echo "<td>" . $row["Rank"] . "</td>\n";
                echo "<td>" . $row["Unit"] . "</td>\n";
                echo "<td>" . $row['pcs'] . "</td>\n";
                echo "</tr>\n";
            }

            echo "</table>\n";


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
        }
        else
        { echo "<p align='center'><font size='4'><b>Your search did not return any results</b></font></p>\n"; }
    }
}

//echo com_sitefooter();
?>