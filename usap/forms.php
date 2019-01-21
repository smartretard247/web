<?php
include("config.php");
include("lib-database.php");

$submit=false;

if (@$_POST['id']) {
  $id=$_POST['id'];
  $submit=true;
} else {
  $id=$_GET['id'];
} // end if


  $form_query = "select * from forms";
  $result = mysql_query($form_query);
  $select_form="<select name='form_type' onchange='submit();'";
  if (@$_POST['action']=="data_entry") $select_form .= " disabled='disabled'>\n"; else $select_form .= ">\n";

  $select_form .="  <option></option>\n";
  while ($row = mysql_fetch_array($result)) {
    $select_form .=  ($_POST[form_type]==(substr($row['form_type'],-1).$row['form_number'])) ? "  <option value='". substr($row['form_type'],-1) . "{$row['form_number']}' selected='selected'>{$row['form_type']} Form {$row['form_number']} - {$row['description']}</option>\n" : "  <option value='". substr($row['form_type'],-1) . "{$row['form_number']}'>{$row['form_type']} Form {$row['form_number']} - {$row['description']}</option>\n";;
  }// wend
  $select_form .="</select>\n";

  $query = "SELECT concat(rank,' ',last_name,', ',first_name,if(middle_initial<>'',concat(' ',middle_initial,'. '),'')) as full_name
            FROM main
		    WHERE id=$id";
  $result = mysql_query($query)or die("query error [$query]: " . mysql_error());
  $row    = mysql_fetch_assoc($result);


if (@$_POST['action']!=="print") {
?>
<HTML>
<HEAD><title>Form Selection</title>
<?php if ($submit) {echo "<script type=\"text/javascript\" src=\"include/calendarDateInput.js\"></script>";} ?>

</HEAD>
<BODY>
<h2 align="center">Select Form for <u><strong><?php=$row['full_name']?></strong></u></h2>
<hr/>
<form name="print_form" method="POST" action="<?php=$_SERVER['PHP_SELF'];?>">
<input type="hidden" name="id" value="<?php=$id?>">
<?php if (!$_POST['action']) echo "<input type='hidden' name='action' value='data_entry'>\n"; ?>
<?php if ($_POST['action']=="data_entry") echo "<input type='hidden' name='form_type' value='{$_POST['form_type']}'>\n"; ?>
<table>
<tr>
  <td>Select Form : </td><td><?php=$select_form?></td>
</tr>  
</table>
<?php if (!$submit) {echo "</form>"; }?>
<?php
switch (@$_POST['form_type']){
  case "A31": //======================== L E A V E   F O R M  ====================================
  ?>
  <br/>
<input type="hidden" value="print" name="action">
<table width="90%">
<tr>
  <td align="right">From:</td><td><script>DateInput('leave_from', true, 'YYYYMMDD')</script></td><td align="right">To:</td><td><script>DateInput('leave_to', true, 'YYYYMMDD')</script></td><td align="right">Type:</td>
  <td>
  <Select name="lt" onChange="toggle('rcm')"> <!-- lt = Leave Type -->
    <option value="ord">Ordinary</option>
    <option value="eme">Emergency</option>
    <option value="con">Convalescence</option>
    <option value="ptd">Permisive TDY</option>
    <option value="hbl">Holiday Block Leave</option>
    <option value="pat">Paternity</option>
    <option value="pas">Pass</option>
  </Select>
  </td>
</tr>
</table>
<table width="90%">
<tr>
  <td>Accrued : <input type="text" name="acc" size="5"></td><td>Requested : <input type="text" name="req" size="5" onfocus="validateTheDate()"></td><td>Advanced : <input type="text" name="adv" size=5></td><td>Excess : <input type="text" name="exc" size=5></td><td><div id="rcm"  style="visibility: hidden;" title="Red Cross message">RCM : <input type="text" name="rcm" size=8 title="Red Cross Message"></div></td>
</tr>
</table>

<br/>
<table border="1">
<tr>
  <td colspan="2" align="Center">Choose Address</td>
</tr>
<?php
// present to user diferent address from USAP database
$address_query = "SELECT Address_ID, Street1, Street2, City, UPPER(State) AS State, ZIP, Phone1, Phone2
                  FROM ADDRESS
                  WHERE ID='$id'";
$result      = mysql_query($address_query)or die("query error [$address_query]: " . mysql_error());

while($address_row = mysql_fetch_assoc($result)) {
   if (!isset($c)) {$check="CHECKED"; $c=1;} else $check="";
   echo "<tr>\n";
   echo "  <td><input type='radio' name='address' value='{$address_row['Address_ID']}' $check></td>";
   echo "  <td>\n";
   $street2 = ($address_row['Street2']) ? " / " .$address_row['Street2'] : "";
   echo "{$address_row['Street1']}$street2<br/>\n";
   echo "{$address_row['City']}, {$address_row['State']} {$address_row['ZIP']}<br/>\n";
   $phone = str_replace("-","",$address_row['Phone1']);
   $phone = "(" . substr($phone,0,3) .") ". substr($phone,3,3) ."-". substr($phone,-4);
   if ($address_row['Phone2']) {
     $phone2 = str_replace("-","",$address_row['Phone2']);
     $phone2 = "(" . substr($phone2,0,3) .") ". substr($phone2,3,3) ."-". substr($phone2,-4);
	 $phone .= " / $phone2";
   }
   echo "$phone<br/>\n";
   echo "  </td>\n";
   echo "</tr>\n";
 } //Wend
?>

<tr>
  <td colspan="2" align="center">Other Address</td>
</tr>
<tr>
  <td><input type='radio' name='address' value='other'></td>
  <td>
    Street&nbsp;:&nbsp;<input type="text" name="street" size="40"><br/>
	City&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;<input type="text" name="city" size="20"> State : <input type="text" name="st" size="2"> State : <input type="text" name="zip" size="5"><br/>
	Phone&nbsp;:&nbsp;<input type="text" name="phone" size="15">
  </td>
</tr>
</table>
<br/>
<table width="50%" align="center">
<tr>
  <td width="50%" align="center"><img src="images/icons/accept.png"/ title="Accept" onclick="submit();"></td><td width="50%" align="center"><img src="images/icons/remove.png"/ title="Cancel" onClick="window.close()"></td>
</tr>
</table>
</form>
<script>
function validateTheDate() {
  var one_day=1000*60*60*24
  var Today = new Date();
   if(parseInt((leave_from_Object.picked.date - Today)/one_day)<0 || parseInt((leave_to_Object.picked.date - Today)/one_day)<0 )
   {  alert('Dates can not be before today.');
   } else {
     this.print_form.req.value=parseInt(((leave_to_Object.picked.date - leave_from_Object.picked.date)/one_day)+1);
   }
}

function toggle(id1) {

var browserType;

  if (document.layers) {browserType = "nn4"}
  if (document.all) {browserType = "ie"}
  if (window.navigator.userAgent.toLowerCase().match("gecko")) { browserType= "gecko" }

  if (browserType == "gecko" )
     document.poppedLayer = eval('document.getElementById(id1)');
  else if (browserType == "ie")
     document.poppedLayer = eval('document.getElementById(id1)');
  else
     document.poppedLayer = eval('document.layers[id1]');

  if (document.print_form.lt.value=="eme") {
    document.poppedLayer.style.visibility = "visible"; }
  else {
    document.poppedLayer.style.visibility = "hidden";}
}
</script>
</BODY>
</HTML>
    <?php
    break;  //======================== L E A V E   F O R M   E N D S ====================================
  case "A268":  //=============================== F L A G S =============================================
    $c_query = "SELECT counseling_id, c_date, c_reason
	            FROM counseling
				WHERE id='$id'";
	$c_result = mysql_query($c_query) or die("query error [$query]: " . mysql_error());
	
	$flag_counseling = "<select name='flag_counseling' title='Counseling used for this Flag'>\n";
	$flag_counseling .= "  <option></option>\n";
	while ($c_row = mysql_fetch_array($c_result)) {
	  $flag_counseling .= "  <option value='{$c_row['counseling_id']}'>{$c_row['c_date']} - {$c_row['c_reason']}</option>\n";
    } // end while
	$flag_counseling .= "</select>\n";
  ?>
  <br/>

<input type="hidden" value="print" name="action">
<table>
<tr>
  <td>Flag Action:</td>
  <td>
  <select name="flag_type" onChange="toggle('ini')">
    <option value="I">Initiate</option>
    <option value="R">Remove</option>
    <option value="T">Transfer</option>
  </select></td>
  <td>Flag Date:</td><td><script>DateInput('flag_date', true, 'YYYYMMDD')</script></td>
  <td>Reason:</td><td>
  <select name = "flag_reason">
    <option value="A">Adverse Action</option>
    <option value="B">Elimination</option>
    <option value="C">Removal from Selection List</option>
    <option value="D">Reffered OER</option>
    <option value="E">Security Violation</option>
    <option value="J">APFT</option>
    <option value="K">Weight Control Program</option>
  </select>
  </td>
</tr>
<tr>
  <td colspan="2"><div id="ini"  style="visibility: visible;" title="Counseling used for this Flag">Counseling : <?php=$flag_counseling ?></div></td>
</tr>
</table>
<table width="50%" align="center">
<tr>
  <td width="50%" align="center"><img src="images/icons/accept.png"/ title="Accept" onclick="submit();"></td><td width="50%" align="center"><img src="images/icons/remove.png"/ title="Cancel" onClick="window.close()"></td>
</tr>
</table>
</form>
<script>
function toggle(id1) {

var browserType;

  if (document.layers) {browserType = "nn4"}
  if (document.all) {browserType = "ie"}
  if (window.navigator.userAgent.toLowerCase().match("gecko")) { browserType= "gecko" }

  if (browserType == "gecko" )
     document.poppedLayer = eval('document.getElementById(id1)');
  else if (browserType == "ie")
     document.poppedLayer = eval('document.getElementById(id1)');
  else
     document.poppedLayer = eval('document.layers[id1]');

  if (document.print_form.flag_type.value=="I") {
    document.poppedLayer.style.visibility = "visible"; }
  else {
    document.poppedLayer.style.visibility = "hidden";}
}
</script>
</BODY>
</HTML>
  <?php
    break;	//=============================== F L A G S   E N D S =======================================
  } // end of switch

} else {

require_once('include\fpdf16\fpdf.php');   
require_once('include\fpdi\fpdi.php');

$form="forms\\{$_POST['form_type']}.pdf";

// initiate FPDI   
$pdf = new FPDI();   
// add a page   
$pdf->AddPage('P','Letter');   
// set the sourcefile   
$pdf->setSourceFile($form);   
// import page 1   
$tplIdx = $pdf->importPage(1);   
// use the imported page and place it at point 10,10 with a width of 100 mm   
$pdf->useTemplate($tplIdx);

// this information should be the same for all the forms ============================================
$id=$_POST['id'];
$query = "SELECT CONCAT(M.Last_Name,', ',M.First_Name,IF(M.Middle_Initial='','',CONCAT(' ',M.Middle_Initial,'.'))) AS FULL_NAME,
                 M.RANK, CONCAT(LEFT(M.SSN,3),'-',MID(M.SSN,4,2),'-',RIGHT(M.SSN,4)) AS SSN, M.Company, M.Battalion, M.Component
          FROM MAIN M
          WHERE ID='$id'";
$result = mysql_query($query) or die("query error " . mysql_error());
$personal_info = mysql_fetch_assoc($result);

$company_query = "SELECT cmd_sig_block, co_name, bn_name, bde_name, CONCAT(city,', ',st,' ',zip) as co_address, phone
                  FROM company_info
				  WHERE bn='{$personal_info['Battalion']}' and co='{$personal_info['Company']}'";
$result = mysql_query($company_query) or die("query error ". mysql_error());
$company_info = mysql_fetch_assoc($result);
$co_phone = str_replace($elements,"",$company_info['phone']);
$co_phone = "(".substr($co_phone,0,3) .") ".substr($co_phone,3,3) . "-" . substr($co_phone,-4);
// this information should be the same for all the forms ============================================
	
switch (@$_POST['form_type']){
  CASE "A31":  
    $address_id = $_POST['address'];
    $elements = array("-","(",")");
	
	$remarks_query = "SELECT *
	                  FROM leave_remarks
					  WHERE leave_type='{$_POST['lt']}' or leave_type='def' 
					  ORDER BY id DESC LIMIT 1";
	$result= mysql_query($remarks_query) or die("query error :" . mysql_error());
	$leave_block_17 = mysql_fetch_assoc($result);
    $remarks = $leave_block_17['block_17'];
	  
    if (@$_POST['address']=="other") {
	  $address_info['Street1'] = $_POST['street'];
	  $address_info['City']   = $_POST['city'];
	  $address_info['State']  = $_POST['st'];
	  $address_info['ZIP']    = $_POST['zip'];
	  $address_info['Phone1'] = $_POST['phone'];
    } else {
      $address_query = "SELECT Street1, Street2, City, UPPER(State) AS State, ZIP, Phone1, Phone2
                        FROM ADDRESS
                        WHERE Address_ID='$address_id'";
      $result       = mysql_query($address_query)or die("query error [$address_query]: " . mysql_error());
      $address_info = mysql_fetch_assoc($result);
	 }  // end if other address
	 
      $street2 = ($address_row['Street2']) ? " / " .$address_row['Street2'] : "";
      $phone = str_replace($elements,"",$address_info['Phone1']);
      $phone = "(".substr($phone,0,3) .") ".substr($phone,3,3) . "-" . substr($phone,-4);
	  
	  if ($address_info['Phone2']) {
        $phone2 = str_replace($elements,"",$address_info['Phone2']);
        $phone2 = "(" . substr($phone2,0,3) .") ". substr($phone2,3,3) ."-". substr($phone2,-4);
	    $phone .= " / $phone2";
	  } // end if Phone2	
	 
    // now write some text above the imported page
    // ======================================= P E R S O N A L  D A T A ===========================
    $pdf->SetFont('Arial','',10);   
    $pdf->SetTextColor(0,0,0);   
    $pdf->SetXY(15, 36);   
    $pdf->Write(0, $personal_info['FULL_NAME']);   
    $pdf->SetXY(85, 36);   
    $pdf->Write(0, $personal_info['SSN']);
    $pdf->SetXY(135, 36);   
    $pdf->Write(0, $personal_info['RANK']);
    $pdf->SetXY(165, 36);   
    $pdf->Write(0, date("Ymd")); 
    //============================================ A D D R E S S  =================================
    $pdf->SetFont('Arial','',8);
    $pdf->SetXY(13, 47);   
    $pdf->Write(0, $address_info['Street1'] . $street2);
    $pdf->SetXY(13, 50);   
    $pdf->Write(0, $address_info['City'].", ".$address_info['State']." ".$address_info['ZIP']);  
    $pdf->SetXY(13, 53);   
    $pdf->Write(0, $phone);
    //================================= S E L E C T  T Y P E  O F  L E A V E ======================
    $pdf->SetFont('Arial','B',14);
	switch ($_POST['lt']) {
	  case "ord":
        $pdf->SetXY(90.6, 44.5);
		$pdf->Write(0, "X");
	    break;
	  case "eme":
        $pdf->SetXY(116, 44.5);
		$pdf->Write(0, "X");
	    $pdf->SetXY(96,53);
		$pdf->SetFont('Arial','',8);
        $pdf->Write(0, "Red Cross Message # : ".$_POST['rcm']);
	    break;
	  case "con":
        $pdf->SetXY(123.7, 49);
		$pdf->Write(0, "X");
	    $pdf->SetXY(98,53);
		$pdf->SetFont('Arial','',8);
        $pdf->Write(0, "Convalescence Leave");
	    break;
	  case "ptd":
        $pdf->SetXY(90.6, 49);
		$pdf->Write(0, "X");
	    $pdf->SetXY(98,53);
		$pdf->SetFont('Arial','',8);
        $pdf->Write(0, "For House Hunting");
	    break;
	  case "hbl":
        $pdf->SetXY(90.6, 44.5);
		$pdf->Write(0, "X");
	    $pdf->SetXY(98,53);
		$pdf->SetFont('Arial','',8);
        $pdf->Write(0, "HOLIDAY BLOCK LEAVE");
	    break;
	  case "pat":
        $pdf->SetXY(123.7, 49);
		$pdf->Write(0, "X");
	    $pdf->SetXY(98,53);
		$pdf->SetFont('Arial','',8);
        $pdf->Write(0, "PATERNITY LEAVE");
	    break;
	  case "pas":
        $pdf->SetXY(123.7, 49);
		$pdf->Write(0, "X");
	    $pdf->SetXY(98,53);
		$pdf->SetFont('Arial','',8);
        $pdf->Write(0, "PASS");
	    break;
	}
	// ============================================= C O M P A N Y  I N F O =======================
    $pdf->SetFont('Arial','',12);
	$title = strtoupper(substr($company_info['co_name'],0,1)) ." ". ucfirst(substr($company_info['co_name'],strpos($company_info['co_name']," ")+1,2)) .", ". $company_info['bn_name'];
    $pdf->SetXY(147.2, 45);
    $pdf->Write(0, $title);
    $pdf->SetXY(147.2, 49);   
    $pdf->Write(0, $company_info['co_address']);  
    $pdf->SetXY(147.2, 53);   
    $pdf->Write(0, $co_phone);
	$pdf->SetXY(143,78.5);
    $pdf->SetFont('Arial','',10);
	$pdf->Write(0, $company_info['cmd_sig_block']);
	$pdf->SetXY(75.5,74.5);          // block 12, supervisor recomendation
    $pdf->SetFont('Arial','B',14);
	$pdf->Write(0, "X");
	// ============================================= B L O C K  9 & 1 0 ==========================
	$pdf->SetFont('Arial','',12);
	$pdf->SetXY(15, 66);
	$pdf->Write(0, $_POST['acc']);
	$pdf->SetXY(49, 66);
	$pdf->Write(0, $_POST['req']);
	$pdf->SetXY(145, 66);
	$pdf->Write(0, $_POST['leave_from']);
	$pdf->SetXY(175, 66);
	$pdf->Write(0, $_POST['leave_to']);
	// ============================================= B L O C K  1 7 ==============================
    $pdf->SetXY(13,123);
    $pdf->SetFont('Times','I',9);
    $pdf->MultiCell(190,3,$remarks);
	// ============================================= C R E A T E  F O R M =========================
    $pdf->Output('Leave Form.pdf','I'); // create the form
    break;
	
  case "A268": // ============================  F L A G S =========================================
    // change bottom margin limit to 1cm
    $pdf->SetAutoPageBreak(true,1);

     // ======================================= P E R S O N A L  D A T A ===========================
    $pdf->SetFont('Arial','',10);   
    $pdf->SetTextColor(0,0,0);   
    $pdf->SetXY(10, 35.5);   
    $pdf->Write(0, $personal_info['FULL_NAME']);   
    $pdf->SetXY(125, 35.5);   
    $pdf->Write(0, $personal_info['SSN']);
    $pdf->SetXY(185, 35.5);   
    $pdf->Write(0, $personal_info['RANK']);
    //================================= S E L E C T  C O M P O N E N T  ===============================
    $pdf->SetFont('Arial','B',14);
	switch ($personal_info['Component']) {
	  case "Regular Army":
        $pdf->SetXY(20, 42.3);
		$pdf->Write(0, "X");
	    break;
	  case "National Guard":
	  case "Army Reserves":
        $pdf->SetXY(107.3, 42.3);
		$pdf->Write(0, "X");
	    break;
	}
     // ======================================= C O M P A N Y   D A T A ===========================
    $unit = strtoupper($company_info['co_name']) .", ". strtoupper($company_info['bn_name']) .", ". strtoupper($company_info['bde_name']);
    $pdf->SetFont('Arial','',10);
    $pdf->SetXY(8, 57);   
    $pdf->Write(0, $unit);
    $pdf->SetXY(145, 57);
    $pdf->Write(0, strtoupper($company_info['co_address']));
	$pdf->SetXY(8,260);
	$pdf->Write(0, $company_info['cmd_sig_block']);	
    //================================= T Y P E   O F   F L A G ===================================
    $pdf->SetFont('Arial','B',14);
    $fdate = str_replace("-","",$_POST['flag_date']);
	switch ($_POST['flag_type']) {
	  case "I":
        $pdf->SetXY(20, 81);
		$pdf->Write(0, "X");
        $pdf->SetXY(20, 97.6);
		$pdf->Write(0, "X");
		switch ($_POST['flag_reason']){
		  case "A":
		    $pdf->SetXY(28.6, 112.4);
			break;
		  case "B":
		    $pdf->SetXY(28.6, 120.7);
			break;
		  case "C":
		    $pdf->SetXY(28.6, 129.2);
			break;
		  case "D":
		    $pdf->SetXY(28.6, 137.7);
			break;
		  case "E":
		    $pdf->SetXY(28.6, 146.2);
			break;
		  case "J":
		    $pdf->SetXY(145.4, 112.4);
			break;
		  case "K":
		    $pdf->SetXY(145.4, 120.7);
			break;
		} // end switch
  		$pdf->Write(0, "X");
        $pdf->SetFont('Arial','',10);
		$pdf->SetXY(90, 97.6);
		$pdf->Write(0, $fdate);
	    break;
	  case "R":
        $pdf->SetXY(150.6, 81);
		$pdf->Write(0, "X");	  
        $pdf->SetXY(20, 208.3);
		$pdf->Write(0, "X");
		switch ($_POST['flag_reason']){
		  case "A":
		    $msg = "Adverse Action";
			break;
		  case "B":
		    $msg = "ELIM";
			break;
		  case "C":
		    $msg = "Removal Sel. List";
			break;
		  case "D":
		    $msg = "";
			break;
		  case "E":
		    $msg = "Sec. Violation";
			break;
		  case "J":
		    $msg = "APFT";
			break;
		  case "K":
		    $msg = "Weight Control";
			break;
		} // end switch
        $pdf->SetXY(28.5, 217);
  		$pdf->Write(0, "X");
		$pdf->SetFont('Arial','BIU',8);
		$pdf->SetXY(74, 216.5);
		$pdf->Write(0, "[".$msg."]");
        $pdf->SetFont('Arial','',10);
        $pdf->SetXY(90, 207.4);
		$pdf->Write(0, $fdate);
	    break;
	  case "T":
        $pdf->SetXY(84.5, 81);
		$pdf->Write(0, "X");
        $pdf->SetXY(20, 169.8);
		$pdf->Write(0, "X");
	    break;
	}
	// ============================================= C R E A T E  F O R M =========================
    $pdf->Output('Flag.pdf','I'); // create the form	
    break;
  } // end switch ====
} //end if
