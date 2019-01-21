<p>Welcome to SM_edit!</p>
         
<?php 
    $talpha = $db->GetByID('alpha', $soldier->GetSSN(), 'LastName', 'SSN');
    StartTable();
   
    ?>
    
    <form method="post">
	<input name="SSN" type="hidden" value="<?php echo $talpha['SSN']; ?>"/>
        <td><b>SSN: </b></td><td><input name="show_SSN" type="input" value="<?php echo $talpha['SSN']; ?>" disabled/></td></tr>
        <tr><td><b>Rank: </b></td><td>
			<select name="Rank">
				<option value="<?php echo $talpha['Rank']; ?>"><?php echo $talpha['Rank']; ?></option>
				<option value="PVT">PVT</option>
				<option value="PVT">PV2</option>
				<option value="PVT">PFC</option>
				<option value="PVT">SPC</option>
			</select>
			<?php /*<input name="Rank" type="input" value="<?php echo $talpha['Rank']; ?>" maxlength="3"/>*/ ?>
		</td></tr>
		<tr><td><b>Name: </b></td><td><input name="LastName" type="input" value="<?php echo $talpha['LastName']; ?>" maxlength="20"/></td></tr>
		<tr><td><b>Class Number: </b></td><td><input name="ClassNumber" type="input" value="<?php echo $talpha['ClassNumber']; ?>" maxlength="6"/></td></tr>
        <tr>
            <td><b>Component: </b></td>
            <td>
                <?php        if($talpha['Component'] == "RA") : ?>
                    <input name="Component" type="radio" value="RA" checked="checked"/>RA
                    <input name="Component" type="radio" value="NG"/>NG
                    <input name="Component" type="radio" value="ER"/>ER
                <?php endif; if($talpha['Component'] == "NG") : ?>
                    <input name="Component" type="radio" value="RA"/>RA
                    <input name="Component" type="radio" value="NG" checked="checked"/>NG
                    <input name="Component" type="radio" value="ER"/>ER
                <?php endif; if($talpha['Component'] == "ER") : ?>
                    <input name="Component" type="radio" value="RA"/>RA
                    <input name="Component" type="radio" value="NG"/>NG
                    <input name="Component" type="radio" value="ER" checked="checked"/>ER
                <?php endif; ?>
            </td>
        </tr>
        <tr><td><b>RFO Completed: </b></td><td>
                <?php if($talpha['RFO'] == 1) { ?>
                    <input name="RFO" type="checkbox" value="true" checked="checked" disabled/>
                <?php ;} else { ?>
                    <input name="RFO" type="checkbox" value="false" disabled/>
                <?php ;} ?>
            </td></tr>
        <tr><td>
        <input type="hidden" name="action" value="SM_edit"/>
        <input type="hidden" name="pending_update" value="1"/>
        <input type="submit" value="Save"/></td>
    </form>
    <?php EndTable(); ?> 

<br/><a href="index.php">Go Back</a>
