<p>Read the paragraph below, click "Agree", and enter your full SSN.</p>
        
<?php
    StartTable();
    TH('This RFO will be for:'); echo "</tr>" ;

    echo "<tr><td>" . $soldier->GetName() . "</td>"; 

    EndTable(); ?>

    <br/><br/>
    <form action ="index.php" method="post">
        <table>
			<tr>
                <td colspan="4"><center>
				<b>Attention!!! Read Before Continuing!!!</b> 
				<p>Acknowledgement of this message indicates all answers were entered by you 
				and are true to the best of your knowledge. All data entered is sent to the orders requesting authority and shall 
				not be changed after today. It is your responsibility to report to Company Operations if your current class 
				number changes with the effective date of change. You will become a holdover if your class number changes and 
				Company operations is not made aware, if your MEDPROS status is not Green or your Dental Category becomes 3 or 4. 
				You are responsible for ensuring your Medical Readiness Status is 100% at all times. You will be responsible for 
				making all medical and dental appointments throughout your time in training. All Active Duty will indicate at this 
				time whether or not you will take leave enroute to your final destination. You may not use more leave than 
				accumulated on your LES as of the 1st in the graduating month. You are responsible for your own transportation to
				and from your leave destination; you will return to the military port of call at least 24 hours prior to your 
				overseas flight. Fort Gordon Transportation office schedules all overseas military flights and your leave will be
				based on their schedule. You may not receive all requested leave dates because of their scheduling.</p>
				</center></td>
            </tr>
			
            <tr>
                <td colspan="4" height="50"><p align="center">Do you agree to these terms?
                <input name="Agree" type="radio" value="Yes"/>Agree
                <input name="Agree" type="radio" value="No" checked="checked"/>Disagree</p></td>
            </tr>
			
			<tr>
                <td colspan="4" height="50"><p align="center">Please enter your full SSN to confirm your identity (no dashes):
				<input name="Password" type="password" maxlength="9"/></p></td>
            </tr>
            
            <tr align="center">
                <td colspan="4">
					<input type="hidden" name="action" value="rfo_start"/>
					<input type="hidden" name="SSN" value="<?php echo $soldier->GetSSN(); ?>"/>
					<input type="hidden" name="Component" value="<?php echo $soldier->GetComponent(); ?>"/>
					<p align="right"><input type="submit" value="Submit"/></p>
				</td>
            </tr>
        </table>
    </form>

<br/><a href="index.php">Go Back</a>

