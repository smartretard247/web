<script type="text/javascript">
    var popup = document.getElementById('popup1');
    
    $(function() {
        if(popup) {
            popup.datepick({dateFormat: 'yyyy-mm-dd'});
        }
    });
</script>

<?php //include_once $_SESSION['rootDir'] . '../database.php'; $db = new Database('server2go');
    $eventDetails = $dbServer->SafeFetch("SELECT * FROM calendar WHERE ID = :0", array($idOfEvent));
?>

<table>
    <tr>
        <th colspan="3">Event Information</th>
        <th>Details</th>
    </tr>
<tr>
    <form action="core/addevent.php" method="post">
        <td><b>Date: </b></td>
        <td><input id="popup1" size="11" type="input" name="DATE" value="<?php echo $eventDetails['TheDate']; ?>"/></td>
        <td>
            <?php if($eventDetails['Complete']) : ?>
              <input type="checkbox" name="COMPLETE" value="1" onclick="this.value = (this.value == 1) ? 0 : 1;" checked />
            <?php else : ?>
              <input type="checkbox" name="COMPLETE" value="0" onclick="this.value = (this.value == 1) ? 0 : 1;"/>
            <?php endif; ?>
        </td>
        <td rowspan="3">
          <textarea rows="3" cols="20" name="DETAILS"><?php echo $eventDetails['Details']; ?></textarea>
        </td>
    </tr>
    <tr>
        <td><b>Event: </b></td><td colspan="2"><input type="input" name="EVENT" value="<?php echo $eventDetails['Event']; ?>"/></td>
    </tr>
    
        <tr>
            <td style="text-align: left;">
                <b>Action:</b>
            </td>
            <td colspan="2" style="text-align: right;">
                <?php $windowLocation = "index.php?action=delete&event=" . $eventDetails['ID']; ?>
                <input type="button" value="Delete" onclick="window.location='<?php echo $windowLocation; ?>'"/>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="hidden" name="ID" value="<?php echo $idOfEvent; ?>"/>
                <input type="submit" value="Save"/>
            </td>
            
    </form>
    </tr>
</table>

<br/><a href="index.php">Go Back</a>
