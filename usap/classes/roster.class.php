<?

/***********************************************
* this file contains funtions used to generate *
* rosters based off of database result sets    *
* passed to it                                 *
***********************************************/

class roster
{
    //variables
    var $sql = "";
    var $col = array();
    var $table = array();
    var $where = array();
    var $group = array();
    var $order = array();
    var $limit = "";
    var $header = "";
    var $link_column = array();
    var $link_page = "";
    var $link_query_string = array();
    var $hide_columns = array();
    var $show_column_headers = 1;
    var $data_font_size = "small";
    var $header_font_size = "small";
    var $javascript = TRUE;
    var $generated_roster = '';
    var $reportname = '';
    var $allowuserorderby = FALSE;
 

    /**************************
    * constructor. can be passed
    * entire query
    ***************************/
    function roster($sql = "")
    {
        if(strlen($sql) > 0)
        { $this->setsql($sql); }
    }

    /**************************
    * creates the html for the
    * roster
    ***************************/
    function drawroster()
    {
        global $_CONF;
        $retval = "";

        if($query = $this->makequery())
        {
            $result = mysql_query($query);
            if($e = mysql_error())
            { $retval .= "roster query error in >>$query<<: " . $e; }
            else
            {
                $num_rows = mysql_num_rows($result);
                $this->query_rows = $num_rows;
                
                if($num_rows > 1000)
                { $this->javascript = FALSE; }
                
                $num_cols = mysql_num_fields($result);

                $colspan = $num_cols - count($this->hide_columns);

                $retval .= "<table border='1' cellspacing='0' cellpadding='4' align='left' width='100%'>\n";

                for($x=0;$x<$num_cols;$x++)
                { $retval .+ "<col align='left'></col>\n"; }

                if(strlen($this->header) > 0)
                { $retval .= "<tr id='header'><th style='font-size:" . $this->header_font_size . "' colspan='" . $colspan . "'>" . $this->header . "</th></tr>\n"; }

                $column_headers = "<tr id='column_headers' bgcolor='" . $_CONF['up']['main_color'] . "' style='font-size:" . $this->header_font_size . "'>\n";
                
                if($this->allowuserorderby)
                { $url = preg_replace("/&{$this->reportname}[^&]+/",'',$_CONF['current_page_with_query_string']); }
                $sortasc = '';
                $sortdesc = '';
               
                
                for($x=0;$x<$num_cols;$x++)
                {
                    $column[$x] = mysql_field_name($result,$x);
    
                    if(!in_array($x,$this->hide_columns))
                    { 
	                    if($this->allowuserorderby)
	                    {
		                    $sortasc = "<a class=\"headerlink\" href=\"{$url}&{$this->reportname}%5Buserorderby%5D={$column[$x]}%20ASC\">&darr;</a>";
		                    $sortdesc = "<a class=\"headerlink\" href=\"{$url}&{$this->reportname}%5Buserorderby%5D={$column[$x]}%20DESC\">&uarr;</a>";
	                    }
	                    $column_headers .= "<th>{$sortasc}" . str_replace("_"," ",$column[$x]) . "{$sortdesc}</th>\n";                     	
	                }
                }
                $column_headers .= "</tr>\n";
                
                if($this->show_column_headers)
                { $retval .= $column_headers; }
                
                $bgcolor = $_CONF['up']['row_one_color'];

                $col = 0;
                $jsname = "h" . uniqid("");

                while($row = mysql_fetch_row($result))
                {
                    $this->query_id = $row[0];
                    
                    if(isset($_REQUEST['export2']) || $this->javascript == FALSE)
                    { $tr_info = ""; }
                    else
                    {
                        if($col++ & 1)
                        { $tr_info=" id=\"" . $jsname . $col . "\" bgcolor=\"{$_CONF['up']['row_one_color']}\" onmouseover=\"if(document.layers) { document.layers['" . $jsname . $col . "'].bgcolor='{$_CONF['up']['row_highlight_color']}' } else { if(document.all) { document.all['" . $jsname . $col . "'].style.background='{$_CONF['up']['row_highlight_color']}' } }\" onmouseout=\"if(document.layers) { document.layers['" . $jsname . $col . "'].bgcolor='{$_CONF['up']['row_one_color']}' } else { if(document.all) { document.all['" . $jsname . $col . "'].style.background='{$_CONF['up']['row_one_color']}' } }\" "; }
                        else
                        { $tr_info=" id=\"" . $jsname . $col . "\" bgcolor=\"{$_CONF['up']['row_two_color']}\" onmouseover=\"if(document.layers) { document.layers['" . $jsname . $col . "'].bgcolor='{$_CONF['up']['row_highlight_color']}' } else { if(document.all) { document.all['" . $jsname . $col . "'].style.background='{$_CONF['up']['row_highlight_color']}' } }\" onmouseout=\"if(document.layers) { document.layers['" . $jsname . $col . "'].bgcolor='{$_CONF['up']['row_two_color']}' } else { if(document.all) { document.all['" . $jsname . $col . "'].style.background='{$_CONF['up']['row_two_color']}' } }\" "; }
                    }

                    if(strlen($this->link_page) > 0 && !isset($_REQUEST['export2']))
                    {
                        $tr_info .= " onclick=\"location.href='" . $_CONF['html'] . '/' . $this->link_page;
                        if($c = count($this->link_column))
                        {
                            $tr_info .= "?";
                            for($cc=0;$cc<$c;$cc++)
                            { $tr_info .= $column[$this->link_column[$cc]] . "=" . urlencode($row[$this->link_column[$cc]]); }
                        }
                        if(count($this->link_query_string))
                        {
                            $tr_info .= "&";
                            $tr_info .= implode("&",$this->link_query_string);
                        }
                        $tr_info .= "';\" ";
                    }

                    $retval .= "<tr " . $tr_info . " style='font-size:" . $this->data_font_size . "'>\n";

                    $td_count = 0;
                    foreach($row as $val)
                    {
                        if(!in_array($td_count++,$this->hide_columns))
                        {
                            if($val == "") { $val = "&nbsp;"; }
                            $retval .= "<td>" . $val . "</td>\n";
                        }
                    }
                    $retval .= "</tr>\n";
                }

                $retval .= "<tr><td colspan='" . $colspan . "' align='left'><strong>total: " . $num_rows . "</strong></td></tr>\n";
                $retval .= "</table>\n";
                
                $this->generated_roster = $retval;
            }
        }
        else
        { $retval .= "Unable to make valid query."; }

        return $retval;
    }

    function sethidecolumn($col)
    {
        if(is_int($col))
        { $this->hide_columns[] = $col; }

        return;
    }

    function link_query_string($string)
    {
        $this->link_query_string[] = $string;

        return;
    }

    /**************************
    * set entire query to be used
    * to generate roster
    ***************************/
    function setsql($sql)
    {
        $this->sql = $sql;
        return;
    }

    /*************************
    * creates query based on passed
    * columns, tables, group by, and
    * order by values
    **************************/
    function makequery()
    {
        $query = false;

        if(strlen($this->sql) > 0)
        { $query = $this->sql; }
        else
        {
            $col = $this->getcolumn();
            $tab = $this->gettable();
            $whr = $this->getwhere();
            $grp = $this->getgroup();
            $ord = $this->getorder();
            $lim = $this->limit;

            $query = "select $col from $tab $whr $grp $ord $lim";

        }
        
        if($this->user_order_by())
        { $query = preg_replace('/order by(.*)(LIMIT|$)/is','ORDER BY '.$this->userorderby.'\2',$query); }

        return $query;
    }
    
    function user_order_by()
    {
		$retval = FALSE;
				
		if($this->allowuserorderby && isset($_REQUEST[$this->reportname]['userorderby']))
		{
			$this->userorderby = preg_replace('/(\w+)(\s+\w+).*/','`\1`',$_REQUEST[$this->reportname]['userorderby']);
			$retval = TRUE;
		}
		
		return $retval;
	}
	
	function setReportName($name)
	{ $this->reportname = $name; }
	
	function allowUserOrderBy($flag)
	{ $this->allowuserorderby = $flag; }			

    /**************************
    * set header row to appear above
    * roster layout
    ***************************/
    function setheader($text)
    {
        if(strlen($text) > 0) { $this->header = $text; }
        return;
    }

    /**************************
    * set and get columns to be
    * used in query
    ***************************/
    function setcolumn($col)
    {
        if(is_array($col))
        {
            foreach($col as $val)
            { $this->col[] = $val; }
        }
        elseif(strlen($col) > 0)
        { $this->col[] = $val; }

        return;
    }

    function getcolumn()
    {
        $col_string = "";

        if(count($this->col) > 0)
        { $col_string = implode(",",$this->col); }

        return $col_string;
    }

    /**************************
    * set and get tables to be
    * used in query
    ***************************/
    function settable($table)
    {
        if(is_array($table))
        {
            foreach($table as $val)
            { $this->table[] = $val; }
        }
        elseif(strlen($table) > 0)
        { $this->table[] = $val; }

        return;
    }

    function gettable()
    {
        $table_string = "";

        if(count($this->table) > 0)
        { $table_string = implode(",",$this->table); }

        return $table_string;
    }

    /*************************
    * set and get group by clauses
    * to be used in query
    **************************/
    function setgroup($group)
    {
        if(is_array($group))
        {
            foreach($group as $val)
            { $this->group[] = $val; }
        }
        elseif(strlen($group) > 0)
        { $this->group[] = $group; }

        return;
    }

    function getgroup()
    {
        $group_string = "";

        if(count($this->group) > 0)
        { $group_string = "group by " . implode(",",$this->group); }

        return $group_string;
    }

    /************************
    * set and get order by clauses
    * to be used in query
    *************************/
    function setorder($order)
    {
        if(is_array($order))
        {
            foreach($order as $val)
            { $this->order[] = $val; }
        }
        elseif(strlen($order) > 0)
        { $this->order[] = $order; }

        return;
    }

    function getorder()
    {
        $order_string = "";

        if(count($this->order) > 0)
        { $order_string =  "order by " . implode(",",$this->order); }

        return $order_string;
    }

    /*************************
    * set and get where clause
    * to be used in query
    **************************/
    function setwhere($where)
    {
        if(is_array($where))
        {
            foreach($where as $val)
            { $this->where[] = $val; }
        }
        elseif(strlen($where) > 0)
        { $this->where[] = $where; }
    }

    function getwhere()
    {
        $where_string = "";

        if(count($this->where) > 0)
        { $where_string = " where " . implode(" and ",$this->where); }

        return $where_string;
    }

    /*************************
    * set a limit to be used in the
    * query
    **************************/
    function setlimit($start,$length)
    {
        $this->limit = " limit " . $start . "," . $length;
        return;
    }

    /*************************
    * set link page. when a row
    * is clicked, the user will be
    * sent to this page. 
    **************************/
    function link_page($page = "")
    {
        if(strlen($page) > 0)
        { $this->link_page = $page; }

        return;
    }

    /*************************
    * set a column number in the
    * result to be  used in the
    * query string of the link page
    * expects integer of which column
    * to pass and variable name passed
    * is same as column name. can be passed
    * an array, or called multiple times.
    **************************/
    function link_column($var)
    {
        if(is_array($var))
        {
            foreach($var as $column)
            {
                if(is_int($column))
                { $this->link_column[] = $column; }
            }
        }
        elseif(is_int($var))
        { $this->link_column[] = $var; }

        return;
    }

    /******************************
    * Function to turn ON or OFF
    * the showing of column headers
    * that match the column names
    * pulled from the database
    *******************************/
    function show_column_headers($val = 1)
    { $this->show_column_headers = $val; }
        
    function setdatafontsize($size)
    { $this->data_font_size = $size; }

    function setheaderfontsize($size)
    { $this->header_font_size = $size; }

}
?>
