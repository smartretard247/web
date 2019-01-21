<?
include("config.php");
include("lib-database.php");
//include_once($_CONF["path"] . "classes/validate.class.php");

$submit=false;

if (@$_POST['id']) {
  $id=$_POST['id'];
  $submit=true;
} else {
  $id=$_GET['id'];
}
$query = "SELECT concat(rank,' ',last_name,', ',first_name,if(middle_initial<>'',concat(' ',middle_initial,'. '),'')) as full_name
          FROM main
		  WHERE id=$id";
$result = mysql_query($query)or die("query error [$location_query]: " . mysql_error());
$row=mysql_fetch_assoc($result);

// validation ================================================================
// no amount of days for temp profiles
if (strtotime(@$_POST['ped']) == strtotime(@$_POST['psd'])&&(strtolower(@$_POST['ptype']) == strtolower(@$_CONF['profile'][1]))) $submit = false;
// no limitions selected
if (count(@$_POST['limitation'])==0) $submit = false;
// experitation date lower than start date
if (strtotime(@$_POST['ped']) - strtotime(@$_POST['psd']) < 0) $submit = false;
// other selected without entry in the textarea
if (isset($_POST['limitation'])&&in_array('oth',@$_POST['limitation'])&&(trim(@$_POST['other'])=="")) $submit = false;
// profile_reason is blank
if (trim(@$_POST['profile_reason'])=="") $submit=false;
// validation ================================================================
if (!$submit) {
?>
<html>
<head><title>Add Profile</title>
<script type="text/javascript" src="include/calendarDateInput.js"></script>
</head>
<body>
<h3 align="center">Add Profile Information for <u><? echo $row['full_name']; ?></u></h3>
<form name="profile" action="<?=$_SERVER['PHP_SELF']; ?>" method="POST">
<input type="hidden" name="id" value="<?=$id?>">
<table border="0" align="center">
<tr>
  <td colspan="3">
      Type of profile : <select name="ptype">
      <option <?=(isset($_POST['ptype'])&&$_POST['ptype']=='TEMP') ? "SELECTED" : ""; ?> value="TEMP">Temp</option>
      <option <?=(isset($_POST['ptype'])&&$_POST['ptype']=='P1') ? "SELECTED" : ""; ?> value="P1">P1</option>
      <option <?=(isset($_POST['ptype'])&&$_POST['ptype']=='P2') ? "SELECTED" : ""; ?> value="P2">P2</option>
      <option <?=(isset($_POST['ptype'])&&$_POST['ptype']=='P3') ? "SELECTED" : ""; ?> value="P3">P3</option>
      <option <?=(isset($_POST['ptype'])&&$_POST['ptype']=='P4') ? "SELECTED" : ""; ?> value="P4">P4</option>
    </select> <? if (strtotime(@$_POST['ped']) == strtotime(@$_POST['psd'])&&(strtolower(@$_POST['ptype']) == strtolower(@$_CONF['profile'][1])))  echo "<font color='red'><strong><- Select different dates for this type of profile!!</strong></font>"; ?>
	          <?=(strtotime(@$_POST['ped']) - strtotime(@$_POST['psd']) < 0) ? "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='red'><strong>Expiration Date can't be <u>Prior</u> of Start Date!!</strong></font>" : ""; ?>
	<hr/>
  </td>
</tr>
<tr>
  <td align="center">Profile Start Date</td><td align="center">Profile Expiration Date</td><td align="center"><?=(trim(@$_POST['profile_reason'])==""&&!isset($_GET['id'])) ? "<font color='red'><strong>Please Enter a Reason!!</strong></font>" : "Reason" ?></td>
</tr>
<tr>
  <td><script>DateInput('psd', true, 'YYYY-MM-DD'<? echo (isset($_POST['psd'])) ? ", '". $_POST['psd']."'" : ""; ?>)</script></td><td><script>DateInput('ped', true, 'YYYY-MM-DD'<? echo (isset($_POST['ped'])) ? ", '". $_POST['ped'] ."'": ""; ?>)</script></td><td><input type="text" name="profile_reason" size="50" value="<?=@$_POST['profile_reason']; ?>"></input></td>
</tr>
<tr>
<td colspan="2" align="center" width="50%">Events that SM may <u><strong>NOT</strong></u> Performed:<hr width="80%"/></td><td align="center">Recommended Events:<hr  width="80%"/></td>
</tr>
<tr>
  <td colspan="2" width="50%" valign="top">
	<input type="checkbox" name="limitation[]" value="run" <?=(isset($_POST['limitation'])&&in_array('run',$_POST['limitation'])) ? "checked" : ""; ?>> RUN, JUMP AND MARCH IN FORMATION<BR/>
    <input type="checkbox" name="limitation[]" value="upp" <?=(isset($_POST['limitation'])&&in_array('upp',$_POST['limitation'])) ? "checked" : ""; ?>> UPPER BODY EXERCISE<BR/>
    <input type="checkbox" name="limitation[]" value="low" <?=(isset($_POST['limitation'])&&in_array('low',$_POST['limitation'])) ? "checked" : ""; ?>> LOWER BODY EXERCISE<BR/>
    <input type="checkbox" name="limitation[]" value="abs" <?=(isset($_POST['limitation'])&&in_array('abs',$_POST['limitation'])) ? "checked" : ""; ?>> ABDOMINAL EXERCISE<BR/>
    <input type="checkbox" name="limitation[]" value="uwa" <?=(isset($_POST['limitation'])&&in_array('uwa',$_POST['limitation'])) ? "checked" : ""; ?>> UNLIMITED WALKING<BR/>	
    <input type="checkbox" name="limitation[]" value="sit" <?=(isset($_POST['limitation'])&&in_array('sit',$_POST['limitation'])) ? "checked" : ""; ?>> SIT-UPS<BR/>
    <input type="checkbox" name="limitation[]" value="pus" <?=(isset($_POST['limitation'])&&in_array('pus',$_POST['limitation'])) ? "checked" : ""; ?>> PUSH-UPS<BR/>
  </td>
  <td valign="top">
    <input type="checkbox" name="limitation[]" value="ust" <?=(isset($_POST['limitation'])&&in_array('ust',$_POST['limitation'])) ? "checked" : ""; ?>> UNLIMITED STRETCHING<BR/>
    <input type="checkbox" name="limitation[]" value="uru" <?=(isset($_POST['limitation'])&&in_array('uru',$_POST['limitation'])) ? "checked" : ""; ?>> UNLIMITED RUNNING<BR/>
    <input type="checkbox" name="limitation[]" value="bik" <?=(isset($_POST['limitation'])&&in_array('bik',$_POST['limitation'])) ? "checked" : ""; ?>> BIKE AT OWN PACE/DISTANCE<BR/>
    <input type="checkbox" name="limitation[]" value="tst" <?=(isset($_POST['limitation'])&&in_array('tst',$_POST['limitation'])) ? "checked" : ""; ?>> STRETCHING AS TOLERATED<BR/>
    <input type="checkbox" name="limitation[]" value="rop" <?=(isset($_POST['limitation'])&&in_array('rop',$_POST['limitation'])) ? "checked" : ""; ?>> RUN AT OWN PACE/DISTANCE<BR/>
    <input type="checkbox" name="limitation[]" value="wop" <?=(isset($_POST['limitation'])&&in_array('wop',$_POST['limitation'])) ? "checked" : ""; ?>> WALK AT OWN PACE/DISTANCE<BR/>
	<input type="checkbox" name="limitation[]" value="gym" <?=(isset($_POST['limitation'])&&in_array('gym',$_POST['limitation'])) ? "checked" : ""; ?>> GYM RECOMENDED<BR/>
  </td>
</tr>
<tr>
  <td colspan="3" width="50%">
    <input type="checkbox" name="limitation[]" value="oth" <?=(isset($_POST['limitation'])&&in_array('oth',$_POST['limitation'])) ? "checked" : ""; ?>> OTHER : <?=(isset($_POST['limitation'])&&in_array('oth',$_POST['limitation'])&&(trim($_POST['other'])=="")) ? "<font color='red'><strong><- Other selected with no entry!!</strong></font>" : ""; ?>
	<?=(count(@$_POST['limitation'])==0&&!isset($_GET['id'])) ? "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='red'><strong>Please Select Limitations!!</strong></font>" : "" ?>
	<TEXTAREA NAME="other" COLS=80 ROWS=4><?=@$_POST['other'] ?></TEXTAREA><hr/>
  </td>
</tr>
<tr>
  <td colspan="2" width="50%"align="center"><img src="images/icons/plus.png" title="Submit" onClick="document.profile.submit()"></td><td align="center"><img src="images/icons/remove.png" title="Cancel" onClick="window.close()"></td>
</tr>
</table>
</form>
</body>
</html>
<? 
exit();
} else {
  //echo "Profile is submited<br>";
  $limit = "";
  $flag=0;
  if (count($_POST['limitation'])>0) {
    foreach ($_POST['limitation'] as $value) {
      Switch ($value) {
	    case 'upp':
	      $limit .= "No Upper Body Exercise. ";
	      break;
	    case 'low':
	      $limit .= "No Lower Body Exercise ";
	      break;
	    case 'abs':
	      $limit .= "No Abdominal Exercise. ";
	      break;
	    case 'ust':
	      $limit .= "No Unlimited Stretching. ";
	      break;
    	  case 'run':
	      $limit .= "No Run, Jump or March in formation. ";
	      break;
	    case 'uru':
	      if (!in_array('run',$_POST['limitation'])) $limit .= "No Unlimited Running. ";
	      break;
	    case 'uwa':
	      $limit .= "No Unlimited Walking. ";
	      break;
	    case 'bik':
	      $limit .= "No Unlimited Stretching. ";
	      break;
	    case 'sit':
	    case 'pus':
		    if (in_array('sit',$_POST['limitation'])&&in_array('pus',$_POST['limitation'])) {
			  if ($flag==0) {
		        $limit .= "No Push-ups, Sit-ups. ";
				$flag=1; }
			  }
		    else {
			  if (in_array('sit',$_POST['limitation'])) {
		        $limit .= "No Sit-ups. ";}
              else {
                $limit .= "No Push-ups. ";}
			}
	      break;
	    case 'tst':
		  $limit .= "Stretching as Tolerated. ";
	      break;
	    case 'rop':
		  $limit .= "Run own Pace and Distance. ";
	      break;
	    case 'wop':
		  $limit .= "Walk own Pace and Distance. ";
	      break;
		case 'gym':
		  $limit .= "Gym PT Recomended. ";
	      break;
		case 'oth':
		  $limit .= (count($_POST['limitation'])>0) ? " Also, " . str_replace(".","",$_POST['other']) . "." : str_replace(".","",$_POST['other']);
		  break;
	  } // end switch
    } // end foreach
  }// end if (count...
$limit = rtrim($limit);
echo "<html>\n";
echo "<body>\n";
if (in_array('run',$_POST['limitation'])&&in_array('sit',$_POST['limitation'])&&in_array('pus',$_POST['limitation'])) $limit .= " (NO APFT)";
//echo "SM {$row['full_name']} has been diagnosticated with " . strtoupper($_POST['profile_reason']) ." and the SM <br/>will have the following limitation" . ((count($_POST['limitation'])>1) ? "s" : "") . " : <br/>";
//echo "$limit<br/>\n";
}
$diff = abs(strtotime($_POST['psd']) - strtotime($_POST['ped']));
//$years = floor($diff / (365*60*60*24));
//$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
//$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
$profile_length = floor($diff / (60*60*24));
//echo "This profile is good for $profile_length days.<br/>";

//printf("%d years, %d months, %d days\n", $years, $months, $days);

$query = "select 1 from profile where profile = '{$_POST['ptype']}' and profile_start = '{$_POST['psd']}'
          and profile_length = $profile_length and profile_reason = '{$_POST['profile_reason']}'
          and id = {$_POST['id']}";
					  
$profile_result = mysql_query($query) or die("Error checking if profile has changed: " . mysql_error());

if(mysql_num_rows($profile_result) == 0) {
  $profile_insert = "INSERT INTO profile (ID,profile, profile_start, profile_length, profile_reason, profile_limitations)
                    VALUES ({$_POST['id']},'{$_POST['ptype']}','{$_POST['psd']}',$profile_length,'{$_POST['profile_reason']}',
                   '$limit')";
//echo $profile_insert;
$profile_insert_result = mysql_query($profile_insert) or die("Error inserting profile: " . mysql_error()); }
else {
echo "<script language=\"JavaScript\">alert('This profile is already in the system');</script> ";
}
?>
<script language="JavaScript">
  window.close();
  opener.location.reload();
</script>
echo "</body>";
echo "</html>";