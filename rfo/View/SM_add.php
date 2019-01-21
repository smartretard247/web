<p>Welcome to: SM_add</p>
    
    <table>
	<tr>
		<form method="post">
		<td colspan="2"><p align="center">
			<input name="action" type="hidden" value="SM_import"/>
			<input type="submit" value="Import Class From Excel"/></p>
		</td>
		</form>
	</tr>
	<form method="post">
	<tr>
	    <td>Rank :</td><td>
			<select name="Rank">
				<option value="">...</option>
				<option value="PVT">PVT</option>
				<option value="PVT">PV2</option>
				<option value="PVT">PFC</option>
				<option value="PVT">SPC</option>
			</select>
		</td>
	</tr>
	<tr>
	    <td>Name :</td><td><input type="input" name="LastName" maxlength="40"/></td>
	</tr>
	<tr>
	    <td>Class #:</td><td><select name="ClassNumber">
				<option value="">...</option>
				<?php $classes = $db->GetTable('Classes', 'ClassNumber');
				foreach($classes as $tclass) : ?>
					<option value="<?php echo $tclass['ClassNumber']; ?>"><?php echo $tclass['ClassNumber']; ?></option>
				<?php endforeach; ?>
			</select></td>
	</tr>
	<tr>
	    <td>SSN :</td><td><input type="input" name="SSN" maxlength="9"/></td>
	</tr>
	<tr>
	    <td>Component :</td><td><input name="Component" type="radio" value="RA" checked="checked"/>RA
		<input name="Component" type="radio" value="NG"/>NG
		<input name="Component" type="radio" value="ER"/>ER</td>
	</tr>
	<tr>
	    <td colspan="2"><input name="action" type="hidden" value="SM_add"/>
			    <input name="pending_add" type="submit" value="Add"/></td>
	</tr>
    </table>
    </form>
<br/><a href="index.php">Go Back</a>