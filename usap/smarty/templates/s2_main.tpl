<table border="1" cellpadding="2" cellspacing="0" width="95%" align="center">
  <tr>
    <td class="table_cheading">S2 - Security</td>
  </tr>

  {* ERROR MESSAGE *}
  {section name="error" loop=1 show=$error}
  <tr>
    <td class="error">{$error}</td>
  </tr>
  {/section}

  {* STATUS MESSAGE *}
  {section name="message" loop=1 show=$message}
  <tr>
    <td class="notice">{$message}</td>
  </tr>
  {/section}

  {* SEARCH *}
  {section name="search" loop=1 show=$show.search}
  <tr>
    <form method="GET" action="{$url}/s2/index.php">
    <td>
      Locate: <input type="text" name="locate_text" size="40">&nbsp;
      <input type="submit" value="Go" class="button">
      &nbsp;&nbsp;
      <input type="submit" value="Issue Detail Report" name="issue_detail_submit" class="button">
    </td>
    </form>
  </tr>
  {/section}

  {* SEARCH RESULTS *}
  {section name="info" loop=1 show=$locate_results}
  <tr>
    <td>{$locate_results}</td>
  </tr>
  {/section}

  {* SOLDIER INFO *}
  {section name="info" loop=1 show=$show.info}
  <tr>
    <td class="table_heading">Soldier Information</td>
  </tr>
  <tr>
    <td>
      <form method="POST" action="{$url}/s2/index.php">
      <table border="0" cellpadding="2" cellspacing="0" width="100%">
        <col width="33%"></col>
        <col width="33%"></col>
        <col width="34%"></col>
        {section name="pcs" loop=1 show=$info.pcs}
        <tr>
          <td colspan="3">NOTICE: This soldier has a PCS/ETS/Deleted status</td>
        </tr>
        {/section}
        <tr>
          <td colspan="3">
            <a href="{$url}/data_sheet.php?id={$info.id}">Data Sheet</a>
            &nbsp;|&nbsp;
            <a href="{$url}/add_remark.php?id={$info.id}">New Global Remark</a>
            &nbsp;|&nbsp;
            <a href="{$url}/reports/s2_history_report.php?id={$info.id}">Security History</a>
          </td>
        </tr>
        <tr>
          <td class="column_name">Name</td>
          <td class="column_name">Rank</td>
          <td class="column_name">SSN</td>
        </tr>
        <tr>
          <td>{$info.last_name}, {$info.first_name} {$info.mi}</td>
          <td>{$info.rank}</td>
          <td>{$info.ssn}</td>
        </tr>
        <tr>
          <td class="column_name">Unit</td>
          <td class="column_name">MOS</td>
          <td class="column_name">Component</td>
        </tr>
        <tr>
          <td>{$info.unit}</td>
          <td>{$info.mos}</td>
          <td>{$info.component}</td>
        </tr>
        <tr>
          <td class="column_name">Arrival Date (days)</td>
          <td class="column_name">Inactive Status</td>
          <td class="column_name">{$meps_header}</td>
        </tr>
        <tr>
          <td>{$info.arrival_date} ({$info.days})</td>
          <td>{$info.inactive_status}</td>
          <td>{$meps_select}</td>
        </tr>
        <tr>
          <td class="column_name">Clearance Status</td>
          <td class="column_name">Derog Issue</td>
          <td class="column_name">Status Date</td>
        </tr>
        <tr>
          <td>{$clearance_status_select}</td>
          <td>{$derog_issue_select}</td>
          <td><input type="text" name="status_date" size="9" maxlength="10" value="{$info.status_date}"></td>
        </tr>
        <tr>
          <td class="column_name" colspan="3">Issue Detail</td>
        </tr>
        <tr>
          <td colspan="3"><input type="text" size="50" name="issue_detail" value="{$info.issue_detail}"></td>
        </tr>
        <tr>
          <td class="column_name" colspan="3" width="100%">Remarks</td>
        </tr>
        <tr>
          <td colspan="3" width="100%">
            <textarea rows="5" cols="70" wrap="physical" name="remark">{$info.remark}</textarea>
          </td>
        </tr>
        <tr>
          <td colspan="3" align="right" width="100%">
            <input type="reset" value="Cancel" class="button">
            &nbsp;
            <input type="hidden" name="id" value="{$info.id}">
            <input type="submit" name="submit" value="Save Changes" class="button">
          </td>
        </tr>
      </table>
      </form>
    </td>
  </tr>
  {/section}

  {* MASS INPUT *}
  {section name="mass_input" loop=1 show=$show.mass_input}
  <tr>
    <td class="table_heading">Mass Input</td>
  </tr>
  <tr>
    <td>
      Copy and Paste the Name, SSN, and Clearance Status columns into this text area. Do not worry about formatting,
      just copy and paste directly from Excel.
      <form method="POST" action="{$url}/s2/index.php">
        <textarea name="data" cols="70" rows="10" wrap="off"></textarea>
        <br>
        <input type="submit" value="Process Data" class="button">
      </form>
    </td>
  </tr>
  {/section}

  {* MASS INPUT RESULTS *}
  {section name="mass_results" loop=1 show=$show.mass_results}
  <tr>
    <td class="table_heading">Results</td>
  </tr>
  <tr>
    <td>
      <table border="1" width="100%">
        <tr valign="top">
        {section name="s" loop=1 show=$report.successful}
          <td><strong>Successful:</strong><br>
            {section name="suc" loop=$report.successful}
              {$report.successful[suc]}<br>
            {/section}
          </td>
        {/section}
        {section name="e" loop=1 show=$report.error}
          <td><strong>Errors:</strong><br>
            {section name="err" loop=$report.error}
              {$report.error[err]}<br>
            {/section}
          </td>
        {/section}
        {section name="n" loop=1 show=$report.not_in_usap}
          <td><strong>Not In USAP:</strong><br>
            {section name="niu" loop=$report.not_in_usap}
              {$report.not_in_usap[niu]}<br>
            {/section}
          </td>
        {/section}
        {section name="b" loop=1 show=$report.bad_status}
          <td><strong>Bad Status:</strong><br>
            {section name="bs" loop=$report.bad_status}
              {$report.bad_status[bs]}<br>
            {/section}
          </td>
        {/section}
      </tr>
    </table>
  {/section}

  {* ISSUE DETAIL REPORT *}
  {section name="issue_detail_report" loop=1 show=$show.issue_detail_report}
  <tr>
    <td>{$export_links}</td>
  </tr>
  <tr>
    <td>{$issue_detail_report}</td>
  </tr>
  {/section}
</table>
