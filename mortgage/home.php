<center>
    <div id="home">
        <?php if(!$_SESSION['valid_user']) : ?>
        <table id="barcrumb" style="position: relative; top: 200px; right: 320px;">
            <tr>
                <th colspan="2">Login Information</th>
            </tr>
            <form action="../core/login.php?return=mortgage" method="post">
            <tr>
                <td  colspan="2" style="text-align: right;">
                    Username: <input name="Username" type="text"><br/>
                    Password: <input name="ThePassword" type="password"><br/>
                </td>
            </tr>
            <tr>
                <td><input type="submit" value="Login"/></td> 
            </tr>
            </form>
        </table>
        <?php else : ?>
        <br/>   
        <table>
            <tr>
                <th colspan="7">Data for <?php echo $data->GetAddress(); ?></th>
            </tr>
            <tr>
                <td>
                    Original Loan Amount<br/><?php echo number_format($data->GetOriginalLoan(),2); ?>
                </td>
                <td>
                    Down Payment<br/><?php echo number_format($data->GetDownPayment(),2); ?>
                </td>
                <td>
                    Interest Rate<br/><?php echo $data->GetInterestRate()*100 . "%"; ?>
                </td>
                <td>
                    Start Date<br/><?php echo $data->GetStartDate()->format('d-M-y'); ?>
                </td>
                <td>
                    Current Mortgage<br/>
                    <form action="index.php?action=set_mortgage" method="post">
                        <input type="text" size="7" value="<?php echo number_format($data->GetCurrentMortgage(),2); ?>" name="new_mortgage" />
                        <input value="Set" type="submit"/>
                    </form>
                </td>
                <td>
                    Current Escrow<br/>
                    <form action="index.php?action=set_escrow" method="post">
                        <input type="text" size="7" value="<?php echo number_format($data->GetCurrentEscrow(),2); ?>" name="new_escrow" />
                        <input value="Set" type="submit"/>
                    </form>
                </td>
                <td>
                    Allotment<br/>
                    <form action="index.php?action=set_allotment" method="post">
                        <input type="text" size="7" value="<?php echo number_format($data->GetAllotment(),2); ?>" name="new_allotment" />
                        <input value="Set" type="submit"/>
                    </form>
                </td>
            </tr>
        </table>
        <br/>
        
        <table>
            <tr>
                <th colspan="5">Actual</th>
            </tr>
            <tr>
                <th>As of Date</th>
                <th>Balance</th>
                <th>Principle</th>
                <th>Interest</th>
                <th>Escrow</th>
            </tr>
            
            <?php for($i = 0, $workingDate = $data->GetStartDate(), $remainingBalance = $data->GetBalance(); $i < 30*12; $i++) : ?>
                <?php
                    if($remainingBalance <= 0.0) { break; }
                
                    $escrow = $data->GetEscrow($workingDate->format('Y'));
                    $mortgage = $data->GetMortgage($workingDate->format('Y'));
                    $interest = $data->GetInterestPortion($remainingBalance);
                    $principle = $data->GetPrinciplePortion($remainingBalance,$escrow,$mortgage);
                ?>
                
                <tr>
                    <td><?php echo $workingDate->format($dateFormat); ?></td>
                    <td><?php echo number_format($remainingBalance,2); ?></td>
                    <td><?php echo number_format($principle,2); ?></td>
                    <td><?php echo number_format($interest,2); ?></td>
                    <td><?php echo number_format($escrow,2); ?></td>
                </tr>
                
                <?php
                    $remainingBalance -= $data->GetAllotment()-$escrow-$interest;
                    date_add($workingDate, $oneMonth);
                ?>
            <?php endfor; ?>
        </table>
        
        <br/>
        <?php if($_SESSION['admin_enabled']) : ?>
            <?php endif; ?> 
        <?php endif; ?>
        
        <?php ShowAlert(); ?>
    </div>
</center>