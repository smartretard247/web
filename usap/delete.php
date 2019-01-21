<?
//configuration files
include("lib-common.php");
//validation routines
include($_CONF["path"] . "/classes/validate.class.php");

//default values
$val = new validate;
$transfer = false;
$input["chapter_type"] = "";
$allow_empty_gaining_unit = 1;

//display site header
echo com_siteheader();

//see if id was passed to page
if(isset($_REQUEST["id"]))
{
    //ensure user has permission to pcs this soldier
    $input["id"] = $val->id($_REQUEST["id"],3);
    if(!$input["id"])
    { echo "invalid permissions. you do not have permissions to delete this soldier"; }
    else
    {
        $query = "select m.last_name, m.first_name, m.middle_initial, m.rank, b.battalion, c.company, m.pers_type from main m, battalion b, company c
                  where m.battalion = b.battalion_id and m.company = c.company_id and m.id = " . $input["id"];
        $result = mysql_query($query) or die("error with name select: " . mysql_error());
        list($last_name, $first_name, $middle_initial, $rank, $battalion, $company, $pers_type) = mysql_fetch_row($result);

        //see if "delete" button was pushed
        if(isset($_REQUEST["delete"]))
        {
            //validate user input
            $input["pcs_type"] = $val->conf($_REQUEST["pcs_type"],"pcs_type","pcs type");
            $input["pcs_date"] = $val->check("date",$_REQUEST["pcs_date"],"pcs date");
            $input["pcs_remark"] = $val->check("string",$_REQUEST["pcs_remark"],"pcs remark",1);

            //if pcs_type was chapter, validate chapter select box
            if($input["pcs_type"] == "Chapter")
            {
                $input["chapter_type"] = $val->conf($_REQUEST["chapter_type"],"chapter_type","chapter type");
                if($input["chapter_type"] == $_CONF["chapter_type"][0]) {$val->error[] = "chapter type cannot be none"; }
            }

            //if pcs_type was transfer, validate transfer unit and set flag
            if($input["pcs_type"] == $_CONF["pcs_type"][0])
            { $allow_empty_gaining_unit = 0; }

            $input["gaining_unit"] = $val->check("string",$_REQUEST["gaining_unit"],"gaining unit",$allow_empty_gaining_unit);

            //check for errors
            if($val->iserrors())
            { echo $val->geterrors(); }
            else
            {
                //insert pcs data into main table
                $query = "update main set pcs = 1,pcs_date = '" . $input["pcs_date"] . "', pcs_type = '" . $input["pcs_type"] . "',"
                        ."pcs_remark = '" . $input["pcs_remark"] . "', ets_chapter_type = '" . $input["chapter_type"] . "', "
                        ."gaining_unit = '" . $input["gaining_unit"] . "' "
                        ."where id = " . $input["id"];

                $result = mysql_query($query) or die("delete update error: " . mysql_error());

                //delete permission for the user to log on, if they had it.
                $query = "delete from user_permissions where user_id = " . $input['id'] . " and permission_id = 10";
                $result = mysql_query($query) or die("delete permission error: " . mysql_error());
                if(in_array($pers_type,$_CONF['perm_party']))
                {
                    $message = "The following person has been deleted from a unit's Alpha Roster within USAP:\n\n"
                              ."$rank $last_name, $first_name $middle_initial ($company-$battalion)\n\n"
                              ."This person's USAP account has been disabled by removing the permission to\n"
                              ."log into USAP.\n\n\n"
                              ."This is an automated message, do not reply to it.";
                    //15rsbhelpdesk@gordon.army.mil
                    mail("15rsbhelpdesk@gordon.army.mil","USAP Deletion",$message,"From: USAP\r\n");
                }

                echo "<hr><br><font size='5'><center><strong>Deletion Successful</strong></center></font>\n";
                echo "<br><center>Please contact an administrator to restore a soldier.</center>\n";
            }
        }
        else
        {
            ?>
                <form>
                <input type='hidden' name='id' value='<?=$input["id"]?>'>
                    <table border='1' cellpadding='3' width='80%' align='center'>
                        <tr><td class="table_heading"><span class="heading">Delete: <?=$rank . " " . $last_name . ", " . $first_name . " " . $middle_initial?></span></td></tr>
                        <tr><td>Completing this form will remove the soldier from your unit and reports. <br>
              <strong><font size="+1">NOTICE: DO NOT USE THIS FORM FOR A MOVE WITHIN THE BRIGADE.</font></strong><br>
              Ask your Battalion IMO for Move Soldier permission and you will use the Edit Soldier page to switch a unit on a soldier.</td></tr>
                        <tr><td>
                            <table border='0' cellpadding='2' width='100%' align='center'>
                                <tr><td>choose type of departure:</td></tr>
                                <tr><td><?=conf_select("pcs_type",$_REQUEST["pcs_type"])?></td></tr>
                                <tr><td>enter date of departure:</td></tr>
                                <tr><td><input name='pcs_date' maxlength='9' size='10' value='<?=strtoupper(date("dMY"))?>'></td></tr>
                                <tr><td>gaining unit:<font size='smaller'><i>Enter the unit and post the soldier is PCSing to.<i></font></td></tr>
                                <tr><td><input type='text' name='gaining_unit' size='20'></td></tr>
                                <tr><td>if ets / chapter, choose type: </td></tr>
                                <tr><td><?=conf_select("chapter_type",$_REQUEST["ets_chapter_type"])?></td></tr>
                                <tr><td>remarks:</td></tr>
                                <tr><td><input type='text' name='pcs_remark' size='40'></td></tr>
                                <tr><td><input type='submit' value='delete' name='delete' class="button">&nbsp;&nbsp;<input type='submit' value='cancel' name='cancel' class="button"></td></tr>
                            </table>
                        </td></tr>
                    </table>
                </form>
            <?
        }
    }
}

echo com_sitefooter();

?>
