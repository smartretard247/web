        <table border="1" cellpadding="1" cellspacing="0" align="center" width="100%">
          <tr class="table_cheading">
            <td>Name</td>
            <td>Rank</td>
            <td>SSN</td>
            <td>PLT</td>
            <td>Daily Status</td>
            <td>Inactive Status</td>
            <td>Remark</td>
          </tr>
          {section name="r" loop=$display.id}
          <tr bgcolor="{$display.bgcolor[r]}">
            <td>{$display.name[r]}</td>
            <td>{$display.rank[r]}</td>
            <td>{$display.ssn[r]}</td>
            <td>{$display.plt[r]}</td>
            <td>{$display.daily_status_select[r]}</td>
            <td>{$display.inact_status_select[r]}</td>
            <td>{$display.status_remark[r]}</td>
          </tr>
          {/section}
        </table>