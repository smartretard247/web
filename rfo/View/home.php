<p>Welcome home!</p>

<table><tr><td>
<table><?php if($admin_enabled) : ?>
    <tr>
		
        <td>Add a Soldier:</td>
        <td><form method="post"><input type="hidden" name="action" value="SM_add"/><input value="Go" type="submit"/></form></td> 
    </tr>
    <tr>
        <td>Remove a Soldier:</td>
        <td><form method="post"><input type="hidden" name="action" value="SM_remove"/><input value="Go" type="submit"/></form></td> 
    </tr>
    <tr>
        <td>View the Roster:</td>
        <td><form method="post"><input type="hidden" name="action" value="SM_show"/><input value="Go" type="submit"/></form></td> 
    </tr>
	<?php endif; ?>
    <tr>
        <td>View or Start an RFO:</td>
        <td><form method="post"><input type="hidden" name="action" value="rfo_list"/><input value="Go" type="submit"/></form></td> 
    </tr>
</table></td>

<?php if($admin_enabled) : ?><td cellvalign="middle"><table>
    <tr>
		
        <td>Add a Class:</td>
        <td><form method="post"><input type="hidden" name="action" value="CL_add"/><input value="Go" type="submit"/></form></td> 
    </tr>
    <tr>
        <td>Remove a Class:</td>
        <td><form method="post"><input type="hidden" name="action" value="CL_remove"/><input value="Go" type="submit"/></form></td> 
    </tr>
    <tr>
        <td>View Classes:</td>
        <td><form method="post"><input type="hidden" name="action" value="CL_show"/><input value="Go" type="submit"/></form></td> 
    </tr>
</table></td>
<?php endif; ?>
</tr></table>

<br/><?php if($admin_enabled) : ?><a href="http://localhost:4001">Go Back To Server2Go</a><?php endif; ?>