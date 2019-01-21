<p>Welcome to SM_show!</p>
         
 <?php 
    $alpha = $db->GetTable('alpha', 'LastName');
    StartTable();
	TH('Rank');
    TH('Name');
    TH('Class #');
    TH('Comp');
    TH('RFO');
    TH('Edit');
    echo    "</tr>" ;
    
    if($alpha) { foreach ($alpha as $talpha) : ?>
        <?php echo "<tr><td>" . $talpha['Rank'] . "</td>"; ?>
		<?php echo "<td>" . $talpha['LastName'] . "</td>"; ?>
        <?php echo "<td>" . $talpha['ClassNumber'] . "</td>"; ?>
        <?php echo "<td>" . $talpha['Component'] . "</td>"; ?>
        <?php 
            if($talpha['RFO']) { $value = 'Yes'; }
            else { $value = 'No'; }
            echo "<td>" . $value . "</td>"; 
        ?>
        <td><form method="post">
            <input type="hidden" name="action" value="SM_edit"/>
            <input type="hidden" name="SSN" value="<?php echo $talpha['SSN']; ?>"/>
            <input type="submit" value="Edit"/>
        </form></td>
    <?php endforeach; NoDataRow($talpha, 5); } EndTable(); ?> 

    <br/><a href="index.php">Go Back</a>
