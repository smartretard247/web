<p>Welcome to CL_edit!</p>
         
<?php 
    $tclass = $db->GetByID('classes', $class->GetClassNumber(), 'ClassNumber', 'ClassNumber');
    StartTable();
   
    ?>
    
    <form method="post">
	<input name="ClassNumber" type="hidden" value="<?php echo $tclass['ClassNumber']; ?>"/>
        <td><b>Class Number: </b></td><td><input name="show_ClassNumber" type="input" value="<?php echo $tclass['ClassNumber']; ?>" disabled/></td></tr>
   
	<tr><td><b>Graduation Date: </b></td><td><input name="GradDate" type="input" value="<?php echo $tclass['GradDate']; ?>" maxlength="10"/></td></tr>
        
        <tr><td>
        <input type="hidden" name="action" value="CL_edit"/>
        <input type="hidden" name="pending_cl_update" value="1"/>
        <input type="submit" value="Save"/></td>
    </form>
    <?php EndTable(); ?> 

<br/><a href="index.php">Go Back</a>
