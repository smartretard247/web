<table width="100%" border="0" cellspacing="1" cellpadding="1">
  <col width="25%" align="center" />
  <col width="25%" align="center" />
  <col width="25%" align="center" />
  <col width="25%" align="center" />
  <tr>
    <td colspan="4" class="table_heading">Exodus Information </td>
  </tr>
  <tr>
    <td colspan="4" align="left">
      Status: <?php=conf_select("exodus_status",$exodus_row['exodus_status']);?>
      <a target="_blank" href="<?php=$_CONF['html']?>/exodus_status.html">(Status Explanation)</a>
      <?php if($exodus_row['returned']) { echo "<br />Status Before Return: {$exodus_row['old_exodus_status']}"; } ?>
      <br>
      <span class="example">Disregard departure and return status for Holding Company On/Off Post.</span>
      <br />
      Comment: <input type="text" name="exodus_comment" value="<?php echo $exodus_row['comment']; ?>" size="50" maxlength="254">
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <table width="100%" border="1" cellspacing="0" cellpadding="2">
        <tr>
          <td colspan="2" align="center">Departure</td>
        </tr>
        <tr>
          <td colspan="2" align="center">
            Date: <input type="text" class="text_box" name="dep_date" size="10" maxlength="9" value="<?php=$exodus_row['dep_date']?>">
            Time: <input type="text" class="text_box" name="dep_time" size="6" maxlength="5" value="<?php=($exodus_row['dep_time']>0)?$exodus_row['dep_time']:'';?>">
          </td>
        </tr>
        <tr bgcolor="<?php=$_CONF['up']['row_highlight_color']?>">
          <td>
            <input type="radio" name="dep_mode" value="POV" <?php if(strcasecmp($exodus_row['dep_mode'],"pov")==0) { echo "checked"; }?>>
            POV
          </td>
          <td>
            <input type="radio" name="dep_pov_type" value="Driver" <?php if(strcasecmp($exodus_row['dep_pov_type'],"driver")==0) { echo "checked"; }?>>
            Driver
            <input type="radio" name="dep_pov_type" value="Passenger" <?php if(strcasecmp($exodus_row['dep_pov_type'],"passenger")==0) { echo "checked"; }?>>
            Passenger
          </td>
        </tr>
        <tr>
          <td>
            <input type="radio" name="dep_mode" value="BUS" <?php if(strcasecmp($exodus_row['dep_mode'],"bus")==0) { echo "checked"; }?>>
            Bus
          </td>
          <td>
            Ticket Status: <?php=conf_select("exodus_ticket_status",$exodus_row['dep_ticket_status'],0,0,'dep_ticket_status');?><br>
            Bought Bus Ticket on Ft. Gordon: <?php=conf_select("yn",$exodus_row['bought_dep_bustic_onpost'],0,0,"bought_dep_bustic_onpost")?><br>
            Destination City: <input type="text" name="bus_dest_city" size="15" maxlength="30" value="<?php=$exodus_row['bus_dest_city']?>">
             State: <input type="text" name="bus_dest_state" size="3" maxlength="2" value="<?php=$exodus_row['bus_dest_state']?>"><br>
             Gate Number: <?php=conf_select('gate_numbers',$exodus_row['gate'],0,0,'gate')?>
          </td>
        </tr>
        <tr bgcolor="<?php=$_CONF['up']['row_highlight_color']?>">
          <td>
            <input type="radio" name="dep_mode" value="AIR" <?php if(strcasecmp($exodus_row['dep_mode'],"air")==0) { echo "checked"; }?>>
            Air
          </td>
          <td>
            Airport: <?php=conf_select("exodus_airports",$exodus_row['dep_airport'],0,0,'dep_airport');?><br>
            Airline: <?php=conf_select("exodus_airlines",$exodus_row['dep_airline'],0,0,'dep_airline');?><br>
            Flight Number: <input type="text" class="text_box" name="dep_flight_num" size="6" value="<?php=$exodus_row['dep_flight_num']?>"><br>
            Has Bus Ticket (If Atlanta): <?php=conf_select("yn",$exodus_row['dep_air_bus_ticket'],0,0,'dep_air_bus_ticket');?><br>
            Bought Bus Ticket on Ft. Gordon: <?php=conf_select("yn",$exodus_row['bought_dep_bustic_onpost'],0,0,'bought_dep_bustic_onpost2');?>
          </td>
        </tr>
      </table>
    </td>
    <td colspan="2">
      <table width="100%" border="1" cellspacing="0" cellpadding="2">
        <tr>
          <td colspan="2" align="center">Return</td>
        </tr>
        <tr>
          <td colspan="2" align="center">
            Date: <input type="text" class="text_box" name="ret_date" size="10" maxlength="9" value="<?php=$exodus_row['ret_date']?>">
            Time: <input type="text" class="text_box" name="ret_time" size="6" maxlength="5" value="<?php=($exodus_row['ret_time']>0)?$exodus_row['ret_time']:'';?>">
          </td>
        </tr>
        <tr bgcolor="<?php=$_CONF['up']['row_highlight_color']?>">
          <td>
            <input type="radio" name="ret_mode" value="POV" <?php if(strcasecmp($exodus_row['ret_mode'],"pov")==0) { echo "checked"; }?>>
            POV</td>
          <td>
            <input type="radio" name="ret_pov_type" value="Driver" <?php if(strcasecmp($exodus_row['ret_pov_type'],"driver")==0) { echo "checked"; }?>>
            Driver
            <input type="radio" name="ret_pov_type" value="Passenger" <?php if(strcasecmp($exodus_row['ret_pov_type'],"passenger")==0) { echo "checked"; }?>>
            Passenger
          </td>
        </tr>
        <tr>
          <td>
            <input type="radio" name="ret_mode" value="BUS" <?php if(strcasecmp($exodus_row['ret_mode'],"bus")==0) { echo "checked"; }?>>
            Bus
          </td>
          <td>
            Ticket status: <?php=conf_select("exodus_ticket_status",$exodus_row['ret_ticket_status'],0,0,'ret_ticket_status');?>
          </td>
        </tr>
        <tr bgcolor="<?php=$_CONF['up']['row_highlight_color']?>">
          <td>
            <input type="radio" name="ret_mode" value="AIR" <?php if(strcasecmp($exodus_row['ret_mode'],"air")==0) { echo "checked"; }?>>
            Air
          </td>
          <td>
            Airport: <?php=conf_select("exodus_airports",$exodus_row['ret_airport'],0,0,'ret_airport');?><br>
            Airline: <?php=conf_select("exodus_airlines",$exodus_row['ret_airline'],0,0,'ret_airline');?><br>
            Flight number: <input type="text" class="text_box" name="ret_flight_num" size="6" value="<?php=$exodus_row['ret_flight_num']?>"><br>
            Has Bus Ticket (If Atlanta): <?php=conf_select("yn",$exodus_row['ret_air_bus_ticket'],0,0,'ret_air_bus_ticket');?>          </td>
        </tr>
        <tr>
          <td>
            <input type="radio" name="ret_mode" value="not returning" <?php if($exodus_row['ret_mode'] == "none") { echo "checked"; } ?>>
          </td>
          <td>Not returning</td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td colspan="4">
    <blockquote>NOTICE: You must select an EXODUS Address for this soldier, also, in the Address block above. If an address already listed
    for this soldier is where they are going on EXODUS, then just change the drop down to <strong>Exodus</strong>. If the address they
    are going to is not listed, then use the Add New Address block to enter the new information and choose <strong>Exodus</strong>
    as the type.</blockquote>
</table>
