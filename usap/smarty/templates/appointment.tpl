{section name="message" loop=1 show=$values.message}
<p style="text-align:center;" class="{$values.message_class}">{$values.message}</p>
{/section}

<table border="0" width="100%"><tr><td>

{section name="view_appointments" loop=1 show=$values.show.current_appointments}
<table border="1" width="100%" align="center" cellpadding="2" cellspacing="0">
  <tr>
    <td class="table_cheading">Appointments for {$values.name}</td>
  </tr>
  <tr>
    <td align="center">
      [<a href="{$values.url}/appointment.php?id={$values.id}&export2=excel&all_apt=1">Export to Excel</a>]&nbsp;&nbsp;
      [<a href="{$values.url}/appointment.php?id={$values.id}&export2=word&all_apt=1">Export to Word</a>]&nbsp;&nbsp;
      [<a href="{$values.url}/data_sheet.php?id={$values.id}">Data Sheet</a>]
    </td>
  </tr>
</table>
<br />
<form method="GET" action="{$values.url}/appointment.php">
{/section}

{section name="export_heading" loop=1 show=$values.show.export_heading}
<div align="center" class="table_cheading">Appointments for {$values.name}</div>
{/section}

{$values.roster}

</td></tr><tr><td>

{section name="all_apt" loop=1 show=$values.show.all_apt_link}
Click <a href="{$values.url}/events.php?id={$values.id}">here</a> to schedule an event.<br/>
Click <a href="{$values.url}/appointment.php?id={$values.id}&all_apt=1">here</a> to view any past appointments<br />
<br />
{/section}

{section name="view_appointments2" loop=1 show=$values.show.current_appointments}
<input type="submit" value="Delete Checked Appointments" class="button">
<input type="hidden" name="id" value="{$values.id}">
</form>
{/section}

</td></tr><tr><td><br>

{section name="add_appointment" loop=1 show=$values.show.new_appointment}
<form method="POST" action="{$values.url}/appointment.php" name="appointment">
<input type="hidden" name="id" value="{$values.id}">
<table border="1" cellpadding="2" cellspacing="0" width="95%" align="center">
  <tr>
    <td colspan="2" class="table_cheading">New Appointment</td>
  </tr>
  <tr>
    <td>
      <table border="0" cellpadding="2" width="100%">
        <col class="column_name" width="15%"></col>
        <col width="85%"></col>
        <tr>
          <td>Name</td>
          <td>
            {$values.name}
            &nbsp;&nbsp;
            [<a href="{$values.url}/data_sheet.php?id={$values.id}">Data Sheet</a>]
          </td>
        </tr>
        <tr>
          <td>Description</td>
          <td><input type="text" name="description" size="40" maxlength="255" value="{$values.description}"></td>
        </tr>
        <tr>
          <td>Location</td>
          <td><input type="text" name="location" size="40" maxlength="255" value="{$values.location}"></td>
        </tr>
        <tr>
          <td>Start</td>
          <td>
            Date: <input type="text" name="start_date" size="10" maxlength="9" value="{$values.start_date}" onchange="document.appointment.end_date.value=document.appointment.start_date.value;">
            Time: <select name="start_time" onchange="document.appointment.end_time.selectedIndex=document.appointment.start_time.selectedIndex+1;">
              {html_options options=$values.start_times.options selected=$values.start_times.selected}
            </select>
          </td>
        </tr>
        <tr>
          <td>End</td>
          <td>
            Date: <input type="text" name="end_date" size="10" maxlength="9" value="{$values.end_date}">
            Time: <select name="end_time">
              {html_options options=$values.end_times.options selected=$values.end_times.selected}
            </select>
          </td>
        </tr>
        <tr>
          <td>Private</td>
          <td>
            <input type="checkbox" name="private" {$values.private} value="1">
            <span class="example">Viewable only by users who have View Restricted Remarks permissions for {$values.name}.</span>
        <tr>
          <td>Notes</td>
          <td><textarea rows="5" cols="40" name="notes">{$values.notes}</textarea></td>
        </tr>
      </table>
    </td>
  <tr>
    <td align="center" colspan="2">
      <input class="button" type="reset" value="Clear">
      &nbsp;&nbsp;
      <input class="button" type="submit" value="Submit" name="new_appointment_submit">
    </td>
  </tr>
</table>
</form>
{/section}

</td></tr></table>