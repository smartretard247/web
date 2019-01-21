<?
//configuration

include('lib-common.php');
include($_CONF['path'] . 'classes/validate.class.php');
include($_CONF['path'] . 'classes/roster.class.php');
include($_CONF['path'] . 'smarty/Smarty.class.php');

$val = new Validate;
$smarty = new Smarty;
$smarty->template_dir = $_CONF['path'] . 'smarty/templates';
$smarty->compile_dir = $_CONF['path'] . 'smarty/templates_c';

//Default variables
$battalion = '';
$company = '';
$bad_id_list = '';
$bad_id = array();
$display = array();
$display['field'] = FALSE;
$display['error'] = FALSE;
$display['notice'] = FALSE;
$report['where'] = '';
$table_template = FALSE;

//Assign $_CONF array to Smarty
$smarty->assign('conf',$_CONF);

//Set default for 'edit_group_field'
if(!isset($_REQUEST['edit_group_field']))
{ $_REQUEST['edit_group_field'] = ''; }

//$display will hold everything being sent to Smarty
//Assign two select boxes, one for unit and one for edit field
$display['unit_select'] = unit_select(2,$battalion,$company);
$display['field_select'] = conf_select('edit_group_field',$_REQUEST['edit_group_field']);

//Process submit to edit all of the fields
if(isset($_REQUEST['edit_submit']))
{
    //Validate that current use has permission to edit soldiers (permission 2)
    //in the unit that was passed
    if($unit = $val->unit($_REQUEST['unit'],2))
    {
        //Extract battalion and company from unit
        $battalion = $unit[0];
        $company = $unit[1];

        //Determine course of action based upon what edit field was chosen
        switch($_REQUEST['edit_group_field'])
        {
            //=========================
            // Daily / Inactive Status
            //=========================
            case 'status':
                //Retrieve valid values for status and inactive status columns
                //in order to validate data that was passed
                $query = "SELECT status_id, type, applies_to FROM status";
                $rs = mysql_query($query) or die("Error retrieving status values: " . mysql_error());
                while($row = mysql_fetch_assoc($rs))
                { $_CONF['status'][$row['type']][$row['applies_to']][] = $row['status_id']; }
                $_CONF['status']['Inactive']['Permanent Party'][] = 'none';
                $_CONF['status']['Inactive']['Student'][] = 'none';

                //Retrieve current status of everyone in database for the chosen unit
                $query = "SELECT id, last_name, pers_type, status, inact_status, status_remark FROM main
                          WHERE battalion = $battalion and company = $company and pcs = 0";
                $rs = mysql_query($query) or die("Error retrieving current statuses: " . mysql_error());
                while($row = mysql_fetch_array($rs))
                {
                    //If a status was passed that matches the ID
                    //of the current row, proceed...
                    if(isset($_POST['status'][$row['id']]))
                    {
                        //Pull values from POST into shorter variables
                        //names for simplicity. The 'remark' is stripped of slashes
                        //so it can be compared to what's pulled out of the database correctly.
                        $status = (int)$_POST['status'][$row['id']];
                        $inact_status = (int)$_POST['inact_status'][$row['id']];
                        $status_remark = stripslashes($_POST['status_remark'][$row['id']]);

                        //If the status, inactive status, or remark has changed from
                        //what's in the database, proceed...
                        if($status != $row['status'] || $inact_status != $row['inact_status'] || $status_remark != $row['status_remark'])
                        {
                            //Validate that status and inactive status passed from form
                            //are correct for the current row's personnel type. If they are, proceed...
                            $applies_to = (in_array($row['pers_type'],$_CONF['perm_party'])) ? 'Permanent Party' : 'Student';
                            if(in_array($status,$_CONF['status']['Active'][$applies_to]) && in_array($inact_status,$_CONF['status']['Inactive'][$applies_to]))
                            {
                                //Update main table with current status, inactive status, and remark.
                                //Also add row to status_history table to keep a history of the change
                                $status_remark = addslashes($status_remark);
                                $query1 = "UPDATE main SET status = $status, inact_status = $inact_status, status_remark = '$status_remark' WHERE id = {$row['id']}";
                                $query2 = "INSERT INTO status_history (id, daily_status_id, inact_status_id, status_remark) VALUES
                                           ({$row['id']},$status,$inact_status,'$status_remark')";
                                $error = '';
                                $rs1 = mysql_query($query1);
                                $error .= mysql_error();
                                $rs2 = mysql_query($query2);
                                $error .= mysql_error();
                                if(!empty($error))
                                { $bad_id[] = $row['id']; }
                            }
                            else
                            {
                                //An invalid status or inactive status was passed. Add current ID to
                                //list of IDs to be retrieved when page is re-shown with those names
                                //that failed to update.
                                $bad_id[] = $row['id'];
                            }
                        }
                    }
                }
                if(empty($bad_id))
                { $display['notice'] = 'All updates were processed successfully.'; }
                else
                {
                    $display['error'] = 'Errors were encountered when updating the following records. Please check your data.';
                    $bad_id_list = 'and m.id IN (' . implode(',',$bad_id) . ') ';
                    $_REQUEST['initial_submit'] = 1;
                }
            break;

            //=========
            // Remarks
            //=========
            case 'remark':
                //Retrieve valid subject values
                $query = "SELECT remarks_subjects_id as rsid from remarks_subjects";
                $rs = mysql_query($query) or die("Error retrieving subjects: " . mysql_error());
                $_CONF['remarks_subjects'] = array();
                while($row = mysql_fetch_assoc($rs))
                { $_CONF['remarks_subjects'][] = $row['rsid']; }

                $passed_ids = array_keys($_REQUEST['remark']);
                $passed_id_list = "'" . implode("','",$passed_ids) . "'";

                $query = "SELECT m.id FROM main m, user_permissions up WHERE m.id IN ($passed_id_list) AND up.user_id = {$_SESSION['user_id']}
                          AND up.permission_id = 16 AND m.company = up.company_id AND m.battalion = up.battalion_id";
                $rs = mysql_query($query) or die("Error retriving IDs: " . mysql_error());
                while($row = mysql_fetch_assoc($rs))
                {
                    $id = $row['id'];
                    if(isset($_REQUEST['remark'][$id]) && !empty($_REQUEST['remark'][$id]))
                    {
                        if($subject = $val->conf($_REQUEST['subject'][$id],'remarks_subjects'))
                        {
                            $restricted = (isset($_REQUEST['restricted'][$id])) ? '1' : '0';
                            $query = "INSERT INTO remarks (id, subject, remark, entered_by, restricted) VALUES
                                      ($id,'$subject','{$_REQUEST['remark'][$id]}',{$_SESSION['user_id']},$restricted)";
                            $rs2 = mysql_query($query);
                            if(mysql_error())
                            { $bad_id[] = $row['id']; }
                        }
                        else
                        {
                            //Invalid status, keep track of ID to show again
                            $bad_id[] = $row['id'];
                        }
                    }
                }
                if(empty($bad_id))
                { $display['notice'] .= 'All remarks were processed successfully.'; }
                else
                {
                    $display['error'] = 'Errors were encountered when entering remarks the following records. Please check your data.';
                    $bad_id_list = 'and m.id IN (' . implode(',',$bad_id) . ') ';
                    $_REQUEST['initial_submit'] = 1;
                }
            break;

            //=====
            // CAC
            //=====
            case 'cac':
                $passed_ids = array_keys($_REQUEST['cac']);
                $passed_id_list = "'" . implode("','",$passed_ids) . "'";

                $query = "SELECT m.id, m.cac FROM main m, user_permissions up WHERE m.id IN ($passed_id_list) AND up.user_id = {$_SESSION['user_id']}
                          AND up.permission_id = 16 AND m.company = up.company_id AND m.battalion = up.battalion_id";
                $rs = mysql_query($query) or die("Error retriving IDs: " . mysql_error());
                while($row = mysql_fetch_assoc($rs))
                {
                    $id = $row['id'];
                    if(isset($_REQUEST['cac'][$id]) && $_REQUEST['cac'][$id] != $row['cac'])
                    {
                        if($val->conf($_REQUEST['cac'][$id],'yn'))
                        {
                            $query2 = "UPDATE main SET cac = '{$_REQUEST['cac'][$id]}' WHERE id = $id";
                            $rs2 = mysql_query($query2);
                            if(mysql_error())
                            { $bad_id[] = $row['id']; }
                        }
                        else
                        {
                            //Invalid CAC status
                            $bad_id[] = $row['id'];
                        }
                    }
                }
                if(empty($bad_id))
                { $display['notice'] .= 'All CAC updates were processed successfully. '; }
                else
                {
                    $display['error'] = 'Errors were encountered when entering remarks the following records. Please check your data.';
                    $bad_id_list = 'and m.id IN (' . implode(',',$bad_id) . ') ';
                    $_REQUEST['initial_submit'] = 1;
                }
            break;

            //=====
            // TDA
            //=====
            case 'tda':
                $passed_ids = array_keys($_REQUEST['comment']);
                $passed_id_list = "'" . implode("','",$passed_ids) . "'";

                $query = "SELECT m.id, t.id AS id2, t.assigned_tda_id, t.working_tda_id, t.comment FROM main m LEFT JOIN tda_assigned t
                          ON m.id = t.id WHERE m.id IN ($passed_id_list) AND m.battalion = $battalion AND m.company = $company";
                $result = mysql_query($query) or die('Unable to validate ID list and retrieve TDA data: ' . mysql_error());
                while($row = mysql_fetch_assoc($result))
                {
                    if(isset($_REQUEST['comment'][$row['id']]))
                    {
                        $aid = (int)$_REQUEST['assigned_tda_id'][$row['id']];
                        $wid = (int)$_REQUEST['working_tda_id'][$row['id']];
                        $comment = htmlentities($_REQUEST['comment'][$row['id']],ENT_QUOTES);

                        if($aid != $row['assigned_tda_id'] || $wid != $row['working_tda_id'] || $row['comment'] != $comment)
                        {
                            if(empty($aid) && empty($wid) && empty($comment))
                            {
                                $query = "DELETE FROM tda_assigned WHERE id = {$row['id']}";
                            }
                            else
                            {
                                if(!$aid) { $aid = 'NULL'; }
                                if(!$wid) { $wid = 'NULL'; }

                                if(empty($row['id2']))
                                {
                                    $query = "INSERT INTO tda_assigned (id, assigned_tda_id, working_tda_id, comment) VALUES
                                              ({$row['id']},$aid,$wid,'$comment')";
                                }
                                else
                                {
                                    $query = "UPDATE tda_assigned SET assigned_tda_id = $aid, working_tda_id = $wid,
                                              comment = '$comment' WHERE id = {$row['id']}";
                                }
                            }

                            $rs2 = mysql_query($query);
                            echo mysql_error();
                            if(!$rs2)
                            { $bad_id[] = $row['id']; }
                        }


                    }
                }
                if(empty($bad_id))
                { $display['notice'] .= 'All TDA updates were processed successfully. '; }
                else
                {
                    $display['error'] = 'Errors were encountered when entering data the following records. Please check your data.';
                    $bad_id_list = 'and m.id IN (' . implode(',',$bad_id) . ') ';
                    $_REQUEST['initial_submit'] = 1;
                }
            break;

            //=========
            // Default
            //=========
            default:
                $display['error'] = 'Error: Invalid FIELD chosen to edit.';
            break;
        }
    }
    else
    { $display['error'] = 'Error: Invalid UNIT chosen to edit.'; }
}


//Process initial submission of 'unit' and 'field'
//or redisplay
if(isset($_REQUEST['initial_submit']))
{
    //Validate that user has permission to edit
    //for the unit that was passed
    if($unit = $val->unit($_REQUEST['unit'],2))
    {
        //Extract battalion and company from unit.
        $battalion = $unit[0];
        $company = $unit[1];

        $report['where'] .= " m.company = $company AND m.battalion = $battalion AND m.pcs = 0 ";

        //Turn on display of template
        //that shows list of names and fields
        //to edit
        $display['fields'] = TRUE;

        //Common fields that'll be used in every query
        $common_fields = "m.id, concat(m.last_name, ', ', m.first_name, ' ', m.middle_initial) as name,
                          concat(m.rank,m.promotable) as rank,
                          left(m.ssn,4) as ssn, m.platoon as plt ";

        //Validate list options and create SQL to pass to query
        $report['where'] .= $val->conf_to_sql($_REQUEST['pers_type'],'pers_type','pers_type','m');
        $report['where'] .= $val->conf_to_sql($_REQUEST['platoon'],'platoon','platoon','m');
        $report['where'] .= $val->conf_to_sql($_REQUEST['shift'],'shift','shift','s');

        if(isset($_REQUEST['status']) && count($_REQUEST['status']) == 1)
        { $report['where'] .= ($_REQUEST['status'][0]=='active') ? ' AND m.inact_status = 0 ' : ' and m.inact_status > 0 '; }


        $x = 0;

        //Determine what to display based on what edit field was chosen
        switch($_REQUEST['edit_group_field'])
        {
            //=========================
            // Daily / Inactive Status
            //=========================
            case 'status':
                //Set name of template used to display table of names
                $table_template = "edit_group_daily_inactive_status.tpl";

                //Retrieve current status, inactive status, and status remark
                //on all soldiers in chosen unit
                $query = "SELECT $common_fields, m.pers_type, m.status, m.inact_status, m.status_remark FROM main m left join student s on m.id = s.id
                          WHERE $bad_id_list {$report['where']} order by m.last_name, m.first_name";

                $rs = mysql_query($query) or die("Error retriving names: " . mysql_error());
                while($row = mysql_fetch_assoc($rs))
                {
                    //Loop through each row and set results into the
                    //$display array to display later in the template
                    $display['bgcolor'][] = (++$x & 1) ? $_CONF['up']['row_highlight_color'] : '';
                    $display['id'][] = $row['id'];
                    $display['name'][] = $row['name'];
                    $display['rank'][] = $row['rank'];
                    $display['ssn'][] = $row['ssn'];
                    $display['plt'][] = $row['plt'];

                    //Determine if the "status" drop-down should apply to
                    //student or PP statuses based on the personnel type of the current row
                    $applies_to = (in_array($row['pers_type'],$_CONF['perm_party'])) ? 'permanent party' : 'student';
                    //Set name for status and inactive status drop downs to be an array
                    //with the key set to the user ID of the current row
                    $status_name = 'status[' . $row['id'] . ']';
                    $inact_status_name = 'inact_status[' . $row['id'] . ']';

                    //Create the status and inactive status drop down boxes
                    //and status remark text box
                    $display['daily_status_select'][] = status_select2($row['status'],$applies_to,'active',0,$status_name);
                    $display['inact_status_select'][] = status_select2($row['inact_status'],$applies_to,'inactive',1,$inact_status_name);
                    $display['status_remark'][] = '<input type="text" name="status_remark[' . $row['id'] . ']" size="20" maxlength="25" value="' . htmlentities($row['status_remark']) . '">';
                }
            break;

            //=========
            // Remarks
            //=========
            case 'remark':
                //Set name of template used to display the table of names
                $table_template = 'edit_group_remarks.tpl';
                $subject_select = subject_select();

                //Query to pull common fields from database
                $query = "SELECT $common_fields FROM main m WHERE
                          1 $bad_id_list and {$report['where']} ORDER BY m.last_name, m.first_name";
                $rs = mysql_query($query) or die('Error selecting names: ' . mysql_error());
                while($row = mysql_fetch_assoc($rs))
                {
                    //Loop through each row and set results into the
                    //$display array to display later in the template
                    $display['bgcolor'][] = (++$x & 1) ? $_CONF['up']['row_highlight_color'] : '';
                    $display['id'][] = $row['id'];
                    $display['name'][] = $row['name'];
                    $display['rank'][] = $row['rank'];
                    $display['ssn'][] = $row['ssn'];
                    $display['plt'][] = $row['plt'];
                    $display['subject_select'][] = str_replace("name='subject'","name='subject[{$row['id']}]'",$subject_select);
                }
            break;

            //=====
            // CAC
            //=====
            case 'cac':
                $table_template = 'edit_group_cac.tpl';

                $Y_select = conf_select('yn','Y',0,0,'cacname');
                $N_select = conf_select('yn','N',0,0,'cacname');

                $query = "SELECT $common_fields, CAC FROM main m WHERE {$report['where']} $bad_id_list
                          ORDER BY m.last_name, m.first_name";
                $rs = mysql_query($query) or die("Error loading CAC info: " . mysql_error());
                while($row = mysql_fetch_assoc($rs))
                {
                    //Loop through each row and set results into the
                    //$display array to display later in the template
                    $display['bgcolor'][] = (++$x & 1) ? $_CONF['up']['row_highlight_color'] : '';
                    $display['id'][] = $row['id'];
                    $display['name'][] = $row['name'];
                    $display['rank'][] = $row['rank'];
                    $display['ssn'][] = $row['ssn'];
                    $display['plt'][] = $row['plt'];
                    $cac_select = $row['CAC'] == 'Y' ? $Y_select : $N_select;
                    $display['cac_select'][] = str_replace('cacname',"cac[{$row['id']}]",$cac_select);
                }
            break;

            //=====
            // TDA
            //=====
            case 'tda':
                $table_template = 'edit_group_tda.tpl';

                $query = "SELECT $common_fields, m.battalion, m.company, m.pers_type,
                          t.assigned_tda_id, t.working_tda_id, t.comment
                          FROM main m LEFT JOIN tda_assigned t ON m.id = t.id
                          WHERE {$report['where']} $bad_id_list
                          ORDER BY m.last_name, m.first_name";
                $rs = mysql_query($query) or die("Error loading CAC info: " . mysql_error());
                while($row = mysql_fetch_assoc($rs))
                {
                    //Loop through each row and set results into the
                    //$display array to display later in the template
                    $display['bgcolor'][] = (++$x & 1) ? $_CONF['up']['row_highlight_color'] : '';
                    $display['id'][] = $row['id'];
                    $display['name'][] = $row['name'];
                    $display['rank'][] = $row['rank'];
                    $display['ssn'][] = $row['ssn'];
                    $display['plt'][] = $row['plt'];

                    $name1 = "assigned_tda_id[{$row['id']}]";
                    $name2 = "working_tda_id[{$row['id']}]";

                    $display['tda_assigned_select'][] = tda_select($row['battalion'], $row['company'], $row['pers_type'], $row['assigned_tda_id'],'',$name1);
                    $display['tda_working_select'][] = tda_select($row['battalion'], $row['company'], $row['pers_type'], $row['working_tda_id'],'working',$name2);
                    $display['comment'][] = '<input type="text" name="comment['.$row['id'].']" size="20" maxlength="255" value="'.$row['comment'].'">';

                }
            break;

            //=========
            // Default
            //=========
            default:
                $display['fields'] = FALSE;
                $display['error'] = 'Error: Invalid FIELD chosen to edit.';
            break;
        }
    }
    else
    { $display['error'] = 'Error: Invalid UNIT chosen to edit.'; }
}

//$display will hold everything being sent to Smarty
//Assign two select boxes, one for unit and one for edit field
$display['unit_select'] = unit_select(2,$battalion,$company);
$display['unit'] = $battalion . '-' . $company;
$display['field_select'] = conf_select('edit_group_field',$_REQUEST['edit_group_field'],0,0,'',1);
$display['edit_group_field'] = htmlentities($_REQUEST['edit_group_field']);

//Assign display variable to Smarty
$smarty->assign('display',$display);

//Fetch table and assign result for final template
if($table_template)
{
    $fields_table = $smarty->fetch($table_template);
    $smarty->assign('fields_table',$fields_table);
}

//Display header, smarty template (parsed), and footer
echo com_siteheader("USAP - Group Edit");
echo $smarty->fetch('edit_group.tpl');
echo com_sitefooter();

?>