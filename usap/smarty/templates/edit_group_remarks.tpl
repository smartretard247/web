        <table border="1" cellpadding="1" cellspacing="0" align="center" width="100%">
          <tr class="table_cheading">
            <td>Name</td>
            <td>Rank</td>
            <td>SSN</td>
            <td>PLT</td>
            <td>Subject</td>
            <td>New Remark</td>
          </tr>
          {section name="r" loop=$display.id}
          <tr bgcolor="{$display.bgcolor[r]}">
            <td>{$display.name[r]}</td>
            <td>{$display.rank[r]}</td>
            <td>{$display.ssn[r]}</td>
            <td>{$display.plt[r]}</td>
            <td>{$display.subject_select[r]}</td>
            <td>
              <textarea name="remark[{$display.id[r]}]" rows="3" cols="30"></textarea>
            </td>
          </tr>
          {/section}
        </table>