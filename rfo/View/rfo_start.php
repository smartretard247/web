<p>Read the following questions and answer to the best of your knowledge.  If you have any questions ask an Operations NCO.</p>
        
<?php
    StartTable();
    TH('The following RFO is for:'); echo "</tr>" ;

    echo "<tr><td>" . $soldier->GetName() . "</td>"; 

    EndTable(); ?>

    <br/><br/>
    <form action ="index.php" method="post">
        <table>
            <tr>
                <td>Is Airborne School in your contract?</td>
                <td><input name="Airborne" type="radio" value="Yes"/>Yes</td>
                <td><input name="Airborne" type="radio" value="No" checked="checked"/>No</td>
                <td><input name="Airborne" type="radio" value="I don't know"/>I don't know</td>
            </tr>
            <tr>
                <td>Are you interested in Hometown Recruiting?</td>
                <td><input name="HRAP" type="radio" value="Wants"/>Yes</td>
                <td><input name="HRAP" type="radio" value="No" checked="checked"/>No</td>
                <td><input name="HRAP" type="radio" value="I don't know"/>I don't know</td>
            </tr>
            <tr>
                <td>Have you passed your most recent EOC APFT?</td>
                <td><input name="APFT" type="radio" value="Yes" checked="checked"/>Yes</td>
                <td><input name="APFT" type="radio" value="No"/>No</td>
                <td><input name="APFT" type="radio" value="Not taken"/>I don't know</td>
            </tr>
            <tr>
                <td>Are you aware of any current security clearance issues?</td>
                <td><input name="SecurityClearance" type="radio" value="Yes"/>Yes</td>
                <td><input name="SecurityClearance" type="radio" value="No" checked="checked"/>No</td>
                <td><input name="SecurityClearance" type="radio" value="I don't know"/>I don't know</td>
            </tr>
            <tr>
                <td>Are you currently pending UCMJ?</td>
                <td><input name="UCMJ" type="radio" value="Yes"/>Yes</td>
                <td><input name="UCMJ" type="radio" value="No" checked="checked"/>No</td>
                <td><input name="UCMJ" type="radio" value="I don't know"/>I don't know</td>
            </tr>
            <?php if($soldier->GetComponent() == 'RA') : ?>
            <?php if(0) : ?>
			<tr>
                <td>Do you want to take leave between duty stations?</td>
                <td><input name="TakingLeave" type="radio" value="7 Days"/>7 Days</td>
                <td><input name="TakingLeave" type="radio" value="10 Days"/>10 Days</td>
                <td><input name="TakingLeave" type="radio" value="None" checked="checked"/>None</td>
            </tr>
			<?php else : ?>
				<input name="TakingLeave" type="hidden" value="None"/>
			<?php endif; ?>
            <tr>
                <td>Will you be traveling by POV?</td>
                <td><input name="POV" type="radio" value="Yes"/>Yes</td>
                <td><input name="POV" type="radio" value="No" checked="checked"/>No</td>
                <td><input name="POV" type="radio" value="I don't know"/>I don't know</td>
            </tr>
            <tr>
                <td>Do you have any dependants?</td>
                <td><input name="Family" type="radio" value="Yes"/>Yes</td>
                <td><input name="Family" type="radio" value="No" checked="checked"/>No</td>
                <td><input name="Family" type="radio" value="I don't know"/>I don't know</td>
            </tr>
            <tr>
                <td>If you are going overseas, have you been to the POR briefing?</td>
                <td><input name="POR" type="radio" value="Yes"/>Yes</td>
                <td><input name="POR" type="radio" value="No"/>No</td>
                <td><input name="POR" type="radio" value="N/A" checked="checked"/>N/A</td>
            </tr>
            <?php endif; ?>
            <tr>
                <td>Do you have a medical profile?</td>
                <td><input name="Profile" type="radio" value="Yes"/>Yes</td>
                <td><input name="Profile" type="radio" value="No" checked="checked"/>No</td>
                <td><input name="Profile" type="radio" value="Permanent"/>I don't know</td>
            </tr>
            <tr>
                <td>What dental category are you in?</td>
                <td><input name="DentalCategory" type="radio" value="CLS2" checked="checked"/>1 or 2</td>
                <td><input name="DentalCategory" type="radio" value="CLS3"/>3</td>
                <td><input name="DentalCategory" type="radio" value="CLS4"/>4</td>
            </tr>
			<?php if($soldier->GetComponent() == 'RA') : ?>
            <tr>
                <td>Have you completed parts I, II, and III of your PHA?</td>
                <td><input name="PHA" type="radio" value="Yes" checked="checked"/>Yes</td>
                <td><input name="PHA" type="radio" value="No"/>No</td>
                <td><input name="PHA" type="radio" value="I don't know"/>I don't know</td>
            </tr>
			<?php endif; ?>
			<?php if($soldier->GetComponent() == 'NG' || $soldier->GetComponent() == 'ER') : ?>
            <tr>
                <td>Have you completed at least part I and II of your PHA?</td>
                <td><input name="PHA" type="radio" value="Yes" checked="checked"/>Yes</td>
                <td><input name="PHA" type="radio" value="No"/>No</td>
                <td><input name="PHA" type="radio" value="I don't know"/>I don't know</td>
            </tr>
            <tr>
                <td>How would you prefer to travel back home?</td>
                <td><input name="Travel" type="radio" value="POV"/>POV</td>
                <td><input name="Travel" type="radio" value="Flight"/>Fly</td>
                <td><input name="Travel" type="radio" value="Flight" checked="checked"/>No preference</td>
            </tr>
            <?php endif; ?>
            <tr align="center">
                <td><input type="hidden" name="action" value="rfo_add"/></td>
                <td><input type="hidden" name="SSN" value="<?php echo $soldier->GetSSN(); ?>"/></td>
                <td><input type="hidden" name="Component" value="<?php echo $soldier->GetComponent(); ?>"/></td>
                <td colspan="3"><input type="submit" value="Submit"/></td>
            </tr>
        </table>
    </form>

<br/><a href="index.php">Go Back</a>

