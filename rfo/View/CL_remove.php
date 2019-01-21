<p>Welcome to CL_remove!</p>
         
 <?php 
    $class = $db->GetTable('classes', 'ClassNumber');
    StartTable();
    TH('Class Number');
    TH('Graduation Date');
    TH('Remove');
    echo    "</tr>" ; ?>
    <form method="post">
    <?php if($class) { foreach ($class as $tclass) : ?>
        <?php echo "<tr><td>" . $tclass['ClassNumber'] . "</td>"; ?>
        <?php echo "<td>" . $tclass['GradDate'] . "</td><td>"; ?>
            <input name="remove<?php echo $tclass['ClassNumber']; ?>" type="checkbox"/>
            <input name="<?php echo $tclass['ClassNumber']; ?>" value="<?php echo $tclass['ClassNumber']; ?>" type="hidden"/>
        </td>
    <?php endforeach; } ?>
    </tr>
    <?php NoDataRow($tclass, 3) ?>
    <tr>
        <td colspan="4">
            <input type="hidden" name="action" value="CL_remove"/>
            <input name="pending_cl_removal" type="submit" value="Remove Selected"/>
        </td>
    </tr>
    </form>
    
    <?php EndTable(); ?> 

<br/><a href="index.php">Go Back</a>
