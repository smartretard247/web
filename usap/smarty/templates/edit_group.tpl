<p>&nbsp;</p>
<table width="95%" border="1" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td class="heading">Group Edit</td>
  </tr>
  {section name="error" show=$display.error}
  <tr>
    <td class="error">{$display.error}</td>
  </tr>
  {/section}
  {section name="notice" show=$display.notice}
  <tr>
    <td class="notice">{$display.notice}</td>
  </tr>
  {/section}
  <tr>
    <td align="center">
      <br />
      <form method="GET" action="{$conf.html}/edit_group.php">
      <table border="0" cellspacing="0" cellpadding="2" align="center">
        <col class="column_name" />
        <col class="data" />
        <tr>
          <td>Unit: </td>
          <td>{$display.unit_select}</td>
        </tr>
        <tr>
          <td>Field: </td>
          <td>{$display.field_select}</td>
        </tr>
        <tr>
          <td>Personnel Type: </td>
          <td>
            {section name=pt loop=$conf.pers_type}
              <input type="checkbox" name="pers_type[]" value="{$conf.pers_type[pt]}">{$conf.pers_type[pt]}&nbsp;&nbsp;
            {/section}
          </td>
        </tr>
        <tr>
          <td>Platoon: </td>
          <td>
            {section name=plt loop=$conf.platoon}
              <input type="checkbox" name="platoon[]" value="{$conf.platoon[plt]}">{$conf.platoon[plt]}&nbsp;&nbsp;
            {/section}
          </td>
        </tr>
        <tr>
          <td>Shift: </td>
          <td>
            {section name=sh loop=$conf.shift}
              <input type="checkbox" name="shift[]" value="{$conf.shift[sh]}">{$conf.shift[sh]}&nbsp;&nbsp;
            {/section}
          </td>
        </tr>
        <tr>
          <td>Status: </td>
          <td>
            <input type="checkbox" name="status[]" value="active">Active&nbsp;&nbsp;
            <input type="checkbox" name="status[]" value="inactive">Inactive&nbsp;&nbsp;
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>
            <input type="submit" name="initial_submit" value="Go" class="button">
          </td>
        </tr>
      </table>
      </form>
    </td>
  </tr>
  {section name="fields" show=$display.fields}
  <tr>
    <td>
      <form method="POST" action="{$conf.html}/edit_group.php">
        <p><input type="submit" name="edit_submit" value="Submit All Changes" class="button"></p>
        <input type="hidden" name="unit" value="{$display.unit}">
        <input type="hidden" name="edit_group_field" value="{$display.edit_group_field}">
          {$fields_table}
        <p><input type="submit" name="edit_submit" value="Submit All Changes" class="button"></p>
      </form>
    </td>
  </tr>
  {/section} {* END FIELDS SECTION *}
</table>
