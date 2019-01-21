<p>Welcome to SM_import!</p>
         
<?php 
    $talpha = GetBySSN($soldier->GetSSN(), 'alpha', 'LastName');
    
    StartTable();
    ?>


    <form action="view/SM_import2.php" method="post">
	
	<th colspan="6">Step 1</th>
    
    <tr>
		<td colspan="3">
	    <p align="right">
			&nbsp;Select the file to import from:&nbsp;&nbsp;<input type="file" name="to_import"/><br/>
	    </p>
		</td>
		
        <td colspan="3">
	    <p align="right">
		<input type="hidden" name="action" value="SM_import2"/>
		<input type="hidden" name="pending_update" value="1"/>
		<input type="submit" value="Next"/>
	    </p>
		</td>
    </form>
    <?php EndTable(); ?> 

<br/><a href="index.php">Go Back</a>
