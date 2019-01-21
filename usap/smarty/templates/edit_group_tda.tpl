        <table border="1" cellpadding="1" cellspacing="0" align="center" width="100%">
          <tr class="table_cheading">
            <td>Name</td>
            <td>Rank</td>
            <td>SSN</td>
            <td>PLT</td>
            <td>Assigned TDA</td>
            <td>Working TDA</td>
            <td>Comment</td>
          </tr>
          {section name="r" loop=$display.id}
          <tr bgcolor="{$display.bgcolor[r]}">
            <td>{$display.name[r]}</td>
            <td>{$display.rank[r]}</td>
            <td>{$display.ssn[r]}</td>
            <td>{$display.plt[r]}</td>
            <td align="center">{$display.tda_assigned_select[r]}</td>
            <td align="center">{$display.tda_working_select[r]}</td>
            <td align="center">{$display.comment[r]}</td>
          </tr>
          {/section}
        </table>