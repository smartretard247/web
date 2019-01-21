<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<style type="text/css">@import "../JS/datepick/jquery.datepick.css";</style>
<script type="text/javascript" src="../JS/datepick/jquery.plugin.js"></script>
<script type="text/javascript" src="../JS/datepick/jquery.datepick.js"></script>
<script type="text/javascript" src="../JS/datePickers.js"></script>

<?php //include_once $_SESSION['rootDir'] . '../database.php'; $db = new Database('server2go');
    $showDetails = $dbServer->SafeFetch("SELECT * FROM shows WHERE ID = :0", array($idOfShow));
?>

<table><tr>
    <th colspan="2"><?php echo $showDetails['Name']; ?></th>
    </tr>
    <tr>
      <form method="post">
        <td><b>Next Episode: </b></td>
        <td><input size="11" type="input" name="nextEpisode" value="<?php echo $showDetails['CurrentEpisode']; ?>"/></td>
    </tr>
    <tr>
        <td><b>Airs On: </b></td>
        <td>
          <select name="airsOn" style="width: 100px;">
            <?php $days = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"); ?>
            <?php for($i = 0; $i < 7; $i++) {
              if($showDetails['Airs'] == $i) {
                echo '<option selected value="' . $i . '">' . $days[$i] . '</option>';
              } else {
                echo '<option value="' . $i . '">' . $days[$i] . '</option>';
              }
            } ?>
          </select>
        </td>
    </tr>
    <tr>
        <td><b>New Season Starts On: </b></td>
        <td><input id="popupDatepicker1" class="datePickers" size="11" type="input" name="startson" value="<?php echo $showDetails['SeasonStart']; ?>"/></td>
    </tr>
    <tr>
        <td><b>New Season Ends On: </b></td>
        <td><input id="popupDatepicker2" class="datePickers" size="11" type="input" name="endson" value="<?php echo $showDetails['SeasonEnd']; ?>"/></td>
    </tr>
    <tr>
        <td><b>Preferred Quality: </b></td>
        <td><input size="11" type="input" name="quality" value="<?php echo $showDetails['Quality']; ?>"/></td>
    </tr>
    <tr>
      <td colspan="2" style="text-align: right;">
          <input type="hidden" name="showid" value="<?php echo $idOfShow; ?>"/>
          <input type="hidden" name="action" value="newseason"/>
          <input type="submit" value="Save"/>
      </td>
            
    </form>
    </tr>
</table>

<br/><a href="index.php">Go Back</a>
