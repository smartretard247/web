<p>Welcome to rfo_show_all!</p>
        
<?php StartTable(); ?>
    
    <th colspan="3">The following RFO is for:</th></tr>
    <tr>
		<td>All Available RFO's</td>
	    <td colspan="2"><p align="center">
			<form method="post">
				<input name="action" type="hidden" value="RFO_export"/>
				<input type="submit" value="Export To Excel"/></p>
			</form>
		</td>
    
    <?php EndTable(); ?>

    <br/><br/>
    <form action ="index.php" method="post">
        <table>
            <tr>
				<td>NAME</td><td>COMP</td><td>SSN</td><td>RANK</td>
                <td>AIRB</td><td>HRAP</td><td>APFT</td><td>SEC</td><td>UCMJ</td>
                <td>LV</td><td>POV</td><td>FMLY</td><td>POR</td>
				<td>PROF</td><td>DRC</td><td>PHA</td>
				<td>TRVL</td>
				<td>CLASS</td>
            </tr>
			<?php 
				$alpha = $db->GetTable('alpha', 'LastName');
				if($alpha) { foreach ($alpha as $talpha) {
					$soldier->SetFromDB($talpha['SSN']);
					$soldier->GetRFO()->SetFromDB($talpha['SSN']);
					
					if($soldier->GetRFO()->GetCompletion()) { ?> 
						<tr>
							<td><?php echo $soldier->GetName(); ?></td>
							<td><?php echo $soldier->GetComponent(); ?></td>
							<td><?php echo $soldier->GetSSN(); ?></td>
							<td><?php echo $soldier->GetRank(); ?></td>
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
							<?php else : ?>
								<td><?php echo "N/A"; ?></td>
								<td><?php echo "N/A"; ?></td>
								<td><?php echo "N/A"; ?></td>
								<td><?php echo "N/A"; ?></td>
							<?php endif; ?>
							<td><?php echo $soldier->GetRFO()->GetProfile(); ?></td>
							<td><?php echo $soldier->GetRFO()->GetDentalCategory(); ?></td>
							<td><?php echo $soldier->GetRFO()->GetPHA(); ?></td>
							<?php if($soldier->GetComponent() == 'NG' || $soldier->GetComponent() == 'ER') : ?>
								<td><?php echo $soldier->GetRFO()->GetTravel(); ?></td>
							<?php else : ?>
								<td><?php echo "N/A"; ?></td>
							<?php endif; ?>
							<td><?php echo $soldier->GetClassNumber(); ?></td>
						</tr>
			<?php ;} ;} NoDataRow($talpha, 6); } ?>
        </table>
    </form>

<br/><a href="index.php">Go Back</a>

