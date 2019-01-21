<p>Welcome to rfo_show!</p>
        
<?php
    StartTable();
    TH('The following RFO is for:'); echo "</tr>" ;

    echo "<tr><td>" . $soldier->GetName() . "</td>"; 
    
    EndTable(); ?>

    <br/><br/>
    <form action ="index.php" method="post">
        <table>
            <tr>
                <td>Airborne:</td><td>HRAP:</td><td>APFT</td><td>Clearance:</td><td>UCMJ:</td>
				<?php if($soldier->GetComponent() == 'RA') : ?>
                <td>Leave:</td><td>POV:</td><td>Dependants:</td><td>POR:</td>
				<?php endif; ?>
				<td>Profile:</td><td>Dental:</td><td>PHA:</td>
				<?php if($soldier->GetComponent() == 'NG' || $soldier->GetComponent() == 'ER') : ?>
					<td>Traveling:</td>
				<?php endif; ?>
            </tr>
            <tr>
                <td><?php echo $soldier->GetRFO()->GetAirborne(); ?></td>
                <td><?php echo $soldier->GetRFO()->GetHRAP(); ?></td>
				<td><?php echo $soldier->GetRFO()->GetAPFT(); ?></td>
				<td><?php echo $soldier->GetRFO()->GetSecurityClearance(); ?></td>
				<td><?php echo $soldier->GetRFO()->GetUCMJ(); ?></td>
				<?php if($soldier->GetComponent() == 'RA') : ?>
					<td><?php echo $soldier->GetRFO()->GetLeave(); ?></td>
					<td><?php echo $soldier->GetRFO()->GetPOV(); ?></td>
					<td><?php echo $soldier->GetRFO()->GetFamily(); ?></td>
					<td><?php echo $soldier->GetRFO()->GetPOR(); ?></td>
				<?php endif; ?>
				<td><?php echo $soldier->GetRFO()->GetProfile(); ?></td>
				<td><?php echo $soldier->GetRFO()->GetDentalCategory(); ?></td>
				<td><?php echo $soldier->GetRFO()->GetPHA(); ?></td>
				<?php if($soldier->GetComponent() == 'NG' || $soldier->GetComponent() == 'ER') : ?>
					<td><?php echo $soldier->GetRFO()->GetTravel(); ?></td>
				<?php endif; ?> 
            </tr>
        </table>
    </form>

<br/><a href="index.php">Go Back</a>

