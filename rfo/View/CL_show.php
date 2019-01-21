<p>Welcome to CL_show!</p>
         
 <?php 
    $class = $db->GetTable('classes', 'ClassNumber');
    StartTable();
    TH('Class Number');
    TH('Graduation Date');
    TH('Edit');
    echo    "</tr>" ;
    
    if($class) { foreach ($class as $tclass) : ?>
        <?php echo "<tr><td>" . $tclass['ClassNumber'] . "</td>"; ?>
	<?php echo "<td>" . $tclass['GradDate'] . "</td>"; ?>
        <td><form method="post">
            <input type="hidden" name="action" value="CL_edit"/>
            <input type="hidden" name="ClassNumber" value="<?php echo $tclass['ClassNumber']; ?>"/>
            <input type="submit" value="Edit"/>
        </form></td>
    <?php endforeach; NoDataRow($tclass, 3); } EndTable(); ?> 

    <br/><a href="index.php">Go Back</a>
