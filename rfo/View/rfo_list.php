<p>Click the button labelled "Begin RFO" on the row with your name.  If your name is not listed please inform Operations.</p>
		<?php if($admin_enabled) : ?>
		<form method="post">
			<input type="hidden" name="action" value="rfo_show_all"/>
			Admin mode is ON:&nbsp;&nbsp;&nbsp;
			<input type="submit" value="View All"/>
		</form><br/>
		<?php endif; ?>

<?php 
    $alpha = $db->GetTable('alpha', 'LastName');
    StartTable();
    TH('Name');
    TH('Class #');
    TH('Comp');
    TH('RFO');
    echo    "</tr>" ;
    if($alpha) { foreach ($alpha as $talpha) : ?>
        <?php echo "<tr><td>" . $talpha['LastName'] . "</td>"; ?>
        <?php echo "<td>" . $talpha['ClassNumber'] . "</td>"; ?>
        <?php echo "<td>" . $talpha['Component'] . "</td>"; ?>
        <td><form method="post">
            <input type="hidden" name="Component" value="<?php echo $talpha['Component']; ?>"/>
            <input type="hidden" name="SSN" value="<?php echo $talpha['SSN']; ?>"/>
            <?php if(!$talpha['RFO']) { ?>
                <input type="hidden" name="action" value="rfo_terms"/>
                <input type="submit" value="Begin RFO"/>  
            <?php ;} else { ?>
				<?php if($admin_enabled) : ?>
					<input type="hidden" name="action" value="rfo_show"/>
					<input type="submit" value="View RFO"/>
				<?php endif; ?>
            <?php ;} ?>
        </form></td>
    <?php endforeach; } NoDataRow($talpha, 4); EndTable(); ?>

<br/><a href="index.php">Go Back</a>

