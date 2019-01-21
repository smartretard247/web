<p>Welcome to SM_remove!</p>
         
 <?php 
    $alpha = $db->GetTable('alpha', 'LastName');
    StartTable();
    TH('Last');
    TH('First');
    TH('Class #');
    TH('Remove');
    echo    "</tr>" ; ?>
    <form method="post">
    <?php if($alpha) { foreach ($alpha as $talpha) : ?>
        <?php echo "<tr><td>" . $talpha['LastName'] . "</td>"; ?>
        <?php echo "<td>" . $talpha['ClassNumber'] . "</td><td>"; ?>
			<?php if($select_all) : ?>
				<input name="remove<?php echo $talpha['SSN']; ?>" type="checkbox" checked="checked"/>
			<?php else : ?>
				<input name="remove<?php echo $talpha['SSN']; ?>" type="checkbox"/>
			<?php endif; ?>
            <input name="<?php echo $talpha['SSN']; ?>" value="<?php echo $talpha['SSN']; ?>" type="hidden"/>
        </td>
    <?php endforeach; } ?>
    </tr>
    <?php NoDataRow($talpha, 3) ?>
    <tr>
        <td colspan="4">
			<p align="right">
				<input type="hidden" name="action" value="SM_remove"/>
				<input name="select_all" type="submit" value="Select All"/>
				<input name="select_none" type="submit" value="Select None"/>
				<input name="pending_removal" type="submit" value="Remove Selected"/>
			</p>
        </td>
    </tr>
    </form>
    
    <?php EndTable(); ?> 

<br/><a href="index.php">Go Back</a>
