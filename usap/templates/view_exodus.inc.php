<table width="100%" border="0" cellspacing="1" cellpadding="1">
  <col width="25%" align="center" />
  <col width="25%" align="center" />
  <col width="25%" align="center" />
  <col width="25%" align="center" />
  <tr>
    <td colspan="4" class="table_heading" align="left">HBL Information</td>
  </tr>
  <tr>
    <td colspan="4" align="left">
      <span class="column_name">Status:</span> <?php=$exodus_row['exodus_status']?>
      <?php if($exodus_row['returned']) { echo "<br /><span class=\"column_name\">Status Before Returning:</span> {$exodus_row['old_exodus_status']}"; } ?>
      <br />
      <span class="column_name">Comment:</span><?php echo $exodus_row['comment']; ?>
    </td>
  </tr>
  <tr>
<?php
$blank_exodus = array("Unconfirmed Travel Plans","Holding Company - On Post","Holding Company - Off Post","Planned Air Atlanta","Planned Air Augusta","Planned Bus","Planned POV","Planned Three Kings","Planned Holding Company");
if(!in_array($exodus_row['exodus_status'],$blank_exodus))
{
    ?>
    <td colspan="2">
      <table width="100%" border="1" cellspacing="0" cellpadding="2">
        <tr>
          <td colspan="2" align="center">Departure</td>
        </tr>
        <tr>
          <td colspan="2" align="center">
            <span class="column_name">Date:</span> <?php=$exodus_row['dep_date']?>
            <span class="column_name">Time:</span> <?php=$exodus_row['dep_time']?>
          </td>
        </tr>
        <tr>
          <td>
            <span class="column_name">Mode:</span> <?php=$exodus_row['dep_mode']?>
          </td>
          <td>
          <?php
            switch(strtolower($exodus_row['dep_mode']))
            {
                case "pov":
                    echo "<span class='column_name'>Type:</span> {$exodus_row['dep_pov_type']}";
                break;
                case "bus":
                    echo "<span class='column_name'>Bus ticket status:</span> {$exodus_row['dep_ticket_status']}<br>\n";
                    echo "<span class='column_name'>Bought ticket on post:</span> {$exodus_row['bought_dep_bustic_onpost']}<br>\n";
                    echo "<span class='column_name'>Desination:</span> {$exodus_row['bus_dest_city']} {$exodus_row['bus_dest_state']}<br>\n";
                    echo "<span class='column_name'>Gate Number:</span> {$exodus_row['gate']}\n";
                break;
                case "air":
                    echo "<span class='column_name'>Airport:</span> {$exodus_row['dep_airport']}<br>\n";
                    echo "<span class='column_name'>Airline:</span> {$exodus_row['dep_airline']}<br>\n";
                    echo "<span class='column_name'>Flight number:</span> {$exodus_row['dep_flight_num']}<br>\n";
                    if(strcasecmp($exodus_row['dep_airport'],"atlanta")==0)
                    {
                        echo "<span class='column_name'>Has bus ticket:</span> {$exodus_row['dep_air_bus_ticket']}<br>\n";
                        echo "<span class='column_name'>Bought Bus Ticket On Post:</span> {$exodus_row['bought_dep_bustic_onpost']}\n";
                    }
                break;
                default:
                    echo "&nbsp;";
            }
            ?>
          </td>
        </tr>
      </table>
    </td>
    <td colspan="2">
      <table width="100%" border="1" cellspacing="0" cellpadding="2">
        <tr>
          <td colspan="2" align="center">Return</td>
        </tr>
        <?php
        if(strstr($exodus_row['exodus_status'],'pcs'))
        { echo "<tr><td colspan='2' align='center' class='column_name'>Not returning</td></tr>"; }
        else
        {
            ?>
        <tr>
          <td colspan="2" align="center">
            <span class="column_name">Date:</span> <?php=$exodus_row['ret_date']?>
            <span class="column_name">Time:</span> <?php=$exodus_row['ret_time']?>
          </td>
        </tr>
        <tr>
          <td>
            <span class="column_name">Mode:</span> <?php=$exodus_row['ret_mode']?>
          </td>
          <td>
          <?php
            switch(strtolower($exodus_row['ret_mode']))
            {
                case "pov":
                    echo "<span class='column_name'>Type:</span> {$exodus_row['ret_pov_type']}";
                break;
                case "bus":
                    echo "<span class='column_name'>Bus ticket status:</span> {$exodus_row['ret_ticket_status']}";
                break;
                case "air":
                    echo "<span class='column_name'>Airport:</span> {$exodus_row['ret_airport']}<br>\n";
                    echo "<span class='column_name'>Airline:</span> {$exodus_row['ret_airline']}<br>\n";
                    echo "<span class='column_name'>Flight number:</span> {$exodus_row['ret_flight_num']}<br>\n";
                    if(strcasecmp($exodus_row['ret_airport'],"atlanta") == 0)
                    { echo "<span class='column_name'>Has bus ticket:</span> {$exodus_row['ret_air_bus_ticket']}\n"; }
                break;
                default:
                    echo "&nbsp;";
            }
            ?>
          </td>
        </tr>
        <?php } ?>
      </table>
    </td>
    <?php } ?>
  </tr>
</table>
