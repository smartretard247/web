<?php $debug = true;
	include_once 'header.php';
    
    include '../core/include.php';   
	include '../core/import.php';

    if(isset($error_message)) { echo '<br/><b>Error: ' . $error_message . '</b><br/>'; }

    if(isset($_POST['action'])) {
        $action = $_POST['action'];
    } else { $action = 'default'; }
	
	if(isset($_POST['to_import'])) {
		$filename = $_POST['to_import'];
	}

	$i = 1;
	//load values from file here...
	$alpharoster = ImportClassRoster($filename, "447th D Co");
	
    //$alpharoster = GetTable('alpha');
    
    ?>
	
	<p>Welcome to SM_import2!</p>

	<table>
	<tr>
    <form action="../index.php" method="post">
	
	<td colspan="6">
	    <p align="center"><b>Step 2:</b> Please verify all information, then select the class number before importing.  If class number is not listed you must add it first.
			</p>
	</td>
    </tr>
    <tr>
	<td><b>SSN</b></td>
	<td><b>Rank</b></td>
	<td><b>Name</b></td>
	<td><b>Component</b></td>
    </tr>
    
	<?php foreach($alpharoster as $talpha) : ?>
    <tr>
        <td><input name="SSN<?php echo $i; ?>" type="input" value="<?php echo $talpha['SSN']; ?>" maxlength="9"/></td>
        <td>
	    <select name="Rank<?php echo $i; ?>">
		<option value="<?php echo $talpha['Rank']; ?>"><?php echo $talpha['Rank']; ?></option>
		<option value="PVT">PVT</option>
		<option value="PVT">PV2</option>
		<option value="PVT">PFC</option>
		<option value="PVT">SPC</option>
	    </select>
	</td>
	<td>
	    <input name="LastName<?php echo $i; ?>" type="input" value="<?php echo $talpha['LastName']; ?>" maxlength="40"/>
	</td>
	<td>
	    <?php if($talpha['Component'] == "RA") : ?>
				<input name="Component<?php echo $i; ?>" type="radio" value="RA" checked="checked"/>RA
                <input name="Component<?php echo $i; ?>" type="radio" value="NG"/>NG
                <input name="Component<?php echo $i; ?>" type="radio" value="ER"/>ER
            <?php elseif($talpha['Component'] == "NG") : ?>
                <input name="Component<?php echo $i; ?>" type="radio" value="RA"/>RA
                <input name="Component<?php echo $i; ?>" type="radio" value="NG" checked="checked"/>NG
                <input name="Component<?php echo $i; ?>" type="radio" value="ER"/>ER
            <?php elseif($talpha['Component'] == "ER") : ?>
                <input name="Component<?php echo $i; ?>" type="radio" value="RA"/>RA
                <input name="Component<?php echo $i; ?>" type="radio" value="NG"/>NG
                <input name="Component<?php echo $i; ?>" type="radio" value="ER" checked="checked"/>ER
	    <?php else : ?>
                <input name="Component<?php echo $i; ?>" type="radio" value="RA"/>RA
                <input name="Component<?php echo $i; ?>" type="radio" value="NG"/>NG
                <input name="Component<?php echo $i; ?>" type="radio" value="ER"/>ER	
            <?php endif; ?>
        </td>
    </tr>
	<?php ++$i; endforeach; ?>
	
    
    <tr>
        <td colspan="6">
	    <p align="right">
		<b>Class Number: </b><select name="ClassNumber">
				<option value="">...</option>
				<?php $classes = GetTable('Classes', 'ClassNumber');
				foreach($classes as $tclass) : ?>
					<option value="<?php echo $tclass['ClassNumber']; ?>"><?php echo $tclass['ClassNumber']; ?></option>
				<?php endforeach; ?>
			</select>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<b>Total Personnel to Add: </b><input name="TotalPersonnel" type="input" value="<?php echo $i-1; ?>"/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="hidden" name="action" value="SM_import"/>
		<input type="hidden" name="pending_update" value="1"/>
		<input type="submit" value="Import All"/>
	    </p>
	</td>
    </form>
    <?php EndTable(); ?> 

<br/><a href="../index.php">Go Back</a>

<?php include 'footer.php'; ?>
