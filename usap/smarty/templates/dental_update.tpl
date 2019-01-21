<table border="1" cellpadding="2" cellspacing="0" align="center" width="90%">
  <tr>
    <td class="table_heading">Dental Update</td>
  </tr>
  <tr>
    <td>
      <h3>Click <a href="dental_update_directions.html">here</a> for directions.</h3>
      <br>
      <span class="example">Cut and paste the Dental information from the web page into the following text area. Do not worry about formatting</span>
      <form method="POST" action="{$url.action}">
        Unit: {$unit_select}<br>
        <textarea name="data" rows="10" cols="50"></textarea>
        <br>
        <input type="submit" name="submit" value="Enter Data">
      </form>
    </td>
  </tr>
  {section name="r" loop=1 show=$result}
  <tr>
    <td class="table_heading">Results</td>
  </tr>
  <tr>
    <td>
      <table border="1" cellpadding="2" cellspacing="0" width="100%">
        <tr>
          <th>Successful Updates</th>
          {section name="bad_names1" loop=1 show=$result.bad}
            <th>Failed Updates</th>
          {/section}
          <th>On Report, Not in USAP</th>
          <th>In USAP, Not on Report</th>
        </tr>
        <tr>
          <td valign="top">
            {section name="g" loop=$result.good}
              {$result.good[g]}<br>
            {/section}
            &nbsp;
          </td>
          {section name="bad_names2" loop=1 show=$result.bad}
          <td valign="top">
            {section name="b" loop=$result.bad}
              {$result.bad[b]}<br>
            {/section}
            &nbsp;
          </td>
          {/section}
          <td valign="top">
            {section name="nfu" loop=$result.nfu}
              {$result.nfu[nfu]}<br>
            {/section}
            &nbsp;
          </td>
          <td valign="top">
            {section name="nfr" loop=$result.nfr}
              {$result.nfr[nfr]}<br>
            {/section}
            &nbsp;
          </td>
        </tr>
      </table>
    </td>
  </tr>
  {/section}
</table>
