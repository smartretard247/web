<?php /* Smarty version 2.3.0, created on 2004-08-02 12:07:02
         compiled from Default/available_surveys.tpl */ ?>
<table width="70%" align="center" cellpadding="0" cellspacing="0">
  <tr class="grayboxheader">
    <td width="14"><img src="<?php echo $this->_tpl_vars['conf']['images_html']; ?>
/box_left.gif" border="0" width="14"></td>
    <td background="<?php echo $this->_tpl_vars['conf']['images_html']; ?>
/box_bg.gif">Survey System</td>
    <td width="14"><img src="<?php echo $this->_tpl_vars['conf']['images_html']; ?>
/box_right.gif" border="0" width="14"></td>
  </tr>
</table>
<table width="70%" align="center" class="bordered_table">
  <tr>
    <td class="whitebox">Public Surveys</td>
  </tr>
  <tr>
    <td>
      <div style="font-weight:bold;text-align:center">
        The following surveys are publicly available. Click on a link to begin taking the survey.
      </div>

      <div class="indented_cell">
      <?php if (isset($this->_sections["s"])) unset($this->_sections["s"]);
$this->_sections["s"]['name'] = "s";
$this->_sections["s"]['loop'] = is_array($this->_tpl_vars['survey']) ? count($this->_tpl_vars['survey']) : max(0, (int)$this->_tpl_vars['survey']);
$this->_sections["s"]['show'] = (bool)"TRUE";
$this->_sections["s"]['max'] = $this->_sections["s"]['loop'];
$this->_sections["s"]['step'] = 1;
$this->_sections["s"]['start'] = $this->_sections["s"]['step'] > 0 ? 0 : $this->_sections["s"]['loop']-1;
if ($this->_sections["s"]['show']) {
    $this->_sections["s"]['total'] = $this->_sections["s"]['loop'];
    if ($this->_sections["s"]['total'] == 0)
        $this->_sections["s"]['show'] = false;
} else
    $this->_sections["s"]['total'] = 0;
if ($this->_sections["s"]['show']):

            for ($this->_sections["s"]['index'] = $this->_sections["s"]['start'], $this->_sections["s"]['iteration'] = 1;
                 $this->_sections["s"]['iteration'] <= $this->_sections["s"]['total'];
                 $this->_sections["s"]['index'] += $this->_sections["s"]['step'], $this->_sections["s"]['iteration']++):
$this->_sections["s"]['rownum'] = $this->_sections["s"]['iteration'];
$this->_sections["s"]['index_prev'] = $this->_sections["s"]['index'] - $this->_sections["s"]['step'];
$this->_sections["s"]['index_next'] = $this->_sections["s"]['index'] + $this->_sections["s"]['step'];
$this->_sections["s"]['first']      = ($this->_sections["s"]['iteration'] == 1);
$this->_sections["s"]['last']       = ($this->_sections["s"]['iteration'] == $this->_sections["s"]['total']);
?>
        <?php echo $this->_sections['s']['iteration']; ?>
. <a href="<?php echo $this->_tpl_vars['conf']['html']; ?>
/survey.php?sid=<?php echo $this->_tpl_vars['survey'][$this->_sections['s']['index']]['sid']; ?>
"><?php echo $this->_tpl_vars['survey'][$this->_sections['s']['index']]['display']; ?>
</a>
        <?php if (isset($this->_sections["r"])) unset($this->_sections["r"]);
$this->_sections["r"]['name'] = "r";
$this->_sections["r"]['loop'] = is_array("1") ? count("1") : max(0, (int)"1");
$this->_sections["r"]['show'] = (bool)$this->_tpl_vars['results'][$this->_sections['s']['index']];
$this->_sections["r"]['max'] = $this->_sections["r"]['loop'];
$this->_sections["r"]['step'] = 1;
$this->_sections["r"]['start'] = $this->_sections["r"]['step'] > 0 ? 0 : $this->_sections["r"]['loop']-1;
if ($this->_sections["r"]['show']) {
    $this->_sections["r"]['total'] = $this->_sections["r"]['loop'];
    if ($this->_sections["r"]['total'] == 0)
        $this->_sections["r"]['show'] = false;
} else
    $this->_sections["r"]['total'] = 0;
if ($this->_sections["r"]['show']):

            for ($this->_sections["r"]['index'] = $this->_sections["r"]['start'], $this->_sections["r"]['iteration'] = 1;
                 $this->_sections["r"]['iteration'] <= $this->_sections["r"]['total'];
                 $this->_sections["r"]['index'] += $this->_sections["r"]['step'], $this->_sections["r"]['iteration']++):
$this->_sections["r"]['rownum'] = $this->_sections["r"]['iteration'];
$this->_sections["r"]['index_prev'] = $this->_sections["r"]['index'] - $this->_sections["r"]['step'];
$this->_sections["r"]['index_next'] = $this->_sections["r"]['index'] + $this->_sections["r"]['step'];
$this->_sections["r"]['first']      = ($this->_sections["r"]['iteration'] == 1);
$this->_sections["r"]['last']       = ($this->_sections["r"]['iteration'] == $this->_sections["r"]['total']);
?>
          &nbsp;&nbsp;<a href="<?php echo $this->_tpl_vars['conf']['html']; ?>
/results.php?sid=<?php echo $this->_tpl_vars['survey'][$this->_sections['s']['index']]['sid']; ?>
">[ View Results ]</a>
        <?php endfor; endif; ?>
        <br />
      <?php endfor; else: ?>
        There are no surveys available at this time.
      <?php endif; ?>
      </div>
    </td>
  </tr>
  <?php if (isset($this->_sections["priv_surveys"])) unset($this->_sections["priv_surveys"]);
$this->_sections["priv_surveys"]['name'] = "priv_surveys";
$this->_sections["priv_surveys"]['loop'] = is_array("1") ? count("1") : max(0, (int)"1");
$this->_sections["priv_surveys"]['show'] = (bool)$this->_tpl_vars['priv_survey'];
$this->_sections["priv_surveys"]['max'] = $this->_sections["priv_surveys"]['loop'];
$this->_sections["priv_surveys"]['step'] = 1;
$this->_sections["priv_surveys"]['start'] = $this->_sections["priv_surveys"]['step'] > 0 ? 0 : $this->_sections["priv_surveys"]['loop']-1;
if ($this->_sections["priv_surveys"]['show']) {
    $this->_sections["priv_surveys"]['total'] = $this->_sections["priv_surveys"]['loop'];
    if ($this->_sections["priv_surveys"]['total'] == 0)
        $this->_sections["priv_surveys"]['show'] = false;
} else
    $this->_sections["priv_surveys"]['total'] = 0;
if ($this->_sections["priv_surveys"]['show']):

            for ($this->_sections["priv_surveys"]['index'] = $this->_sections["priv_surveys"]['start'], $this->_sections["priv_surveys"]['iteration'] = 1;
                 $this->_sections["priv_surveys"]['iteration'] <= $this->_sections["priv_surveys"]['total'];
                 $this->_sections["priv_surveys"]['index'] += $this->_sections["priv_surveys"]['step'], $this->_sections["priv_surveys"]['iteration']++):
$this->_sections["priv_surveys"]['rownum'] = $this->_sections["priv_surveys"]['iteration'];
$this->_sections["priv_surveys"]['index_prev'] = $this->_sections["priv_surveys"]['index'] - $this->_sections["priv_surveys"]['step'];
$this->_sections["priv_surveys"]['index_next'] = $this->_sections["priv_surveys"]['index'] + $this->_sections["priv_surveys"]['step'];
$this->_sections["priv_surveys"]['first']      = ($this->_sections["priv_surveys"]['iteration'] == 1);
$this->_sections["priv_surveys"]['last']       = ($this->_sections["priv_surveys"]['iteration'] == $this->_sections["priv_surveys"]['total']);
?>
    <tr>
      <td class="whitebox">Private Surveys</td>
    </tr>
    <tr>
      <td>
        <form class="indented_cell" method="POST" action="<?php echo $this->_tpl_vars['conf']['html']; ?>
/survey.php">
          Survey:&nbsp;
          <select name="sid" size="1">
            <?php if (isset($this->_sections["ps"])) unset($this->_sections["ps"]);
$this->_sections["ps"]['name'] = "ps";
$this->_sections["ps"]['loop'] = is_array($this->_tpl_vars['priv_survey']['sid']) ? count($this->_tpl_vars['priv_survey']['sid']) : max(0, (int)$this->_tpl_vars['priv_survey']['sid']);
$this->_sections["ps"]['show'] = true;
$this->_sections["ps"]['max'] = $this->_sections["ps"]['loop'];
$this->_sections["ps"]['step'] = 1;
$this->_sections["ps"]['start'] = $this->_sections["ps"]['step'] > 0 ? 0 : $this->_sections["ps"]['loop']-1;
if ($this->_sections["ps"]['show']) {
    $this->_sections["ps"]['total'] = $this->_sections["ps"]['loop'];
    if ($this->_sections["ps"]['total'] == 0)
        $this->_sections["ps"]['show'] = false;
} else
    $this->_sections["ps"]['total'] = 0;
if ($this->_sections["ps"]['show']):

            for ($this->_sections["ps"]['index'] = $this->_sections["ps"]['start'], $this->_sections["ps"]['iteration'] = 1;
                 $this->_sections["ps"]['iteration'] <= $this->_sections["ps"]['total'];
                 $this->_sections["ps"]['index'] += $this->_sections["ps"]['step'], $this->_sections["ps"]['iteration']++):
$this->_sections["ps"]['rownum'] = $this->_sections["ps"]['iteration'];
$this->_sections["ps"]['index_prev'] = $this->_sections["ps"]['index'] - $this->_sections["ps"]['step'];
$this->_sections["ps"]['index_next'] = $this->_sections["ps"]['index'] + $this->_sections["ps"]['step'];
$this->_sections["ps"]['first']      = ($this->_sections["ps"]['iteration'] == 1);
$this->_sections["ps"]['last']       = ($this->_sections["ps"]['iteration'] == $this->_sections["ps"]['total']);
?>
              <option value="<?php echo $this->_tpl_vars['priv_survey']['sid'][$this->_sections['ps']['index']]; ?>
"><?php echo $this->_tpl_vars['priv_survey']['display'][$this->_sections['ps']['index']]; ?>
</option>
            <?php endfor; endif; ?>
          </select><br>
          Password:&nbsp;
          <input type="password" value="" name="password">
          &nbsp;<input type="submit" name="submit" value="Take Survey">
        </form>
      </td>
    </tr>
  <?php endfor; endif; ?>
  <?php if (isset($this->_sections["priv_results"])) unset($this->_sections["priv_results"]);
$this->_sections["priv_results"]['name'] = "priv_results";
$this->_sections["priv_results"]['loop'] = is_array("1") ? count("1") : max(0, (int)"1");
$this->_sections["priv_results"]['show'] = (bool)$this->_tpl_vars['priv_results'];
$this->_sections["priv_results"]['max'] = $this->_sections["priv_results"]['loop'];
$this->_sections["priv_results"]['step'] = 1;
$this->_sections["priv_results"]['start'] = $this->_sections["priv_results"]['step'] > 0 ? 0 : $this->_sections["priv_results"]['loop']-1;
if ($this->_sections["priv_results"]['show']) {
    $this->_sections["priv_results"]['total'] = $this->_sections["priv_results"]['loop'];
    if ($this->_sections["priv_results"]['total'] == 0)
        $this->_sections["priv_results"]['show'] = false;
} else
    $this->_sections["priv_results"]['total'] = 0;
if ($this->_sections["priv_results"]['show']):

            for ($this->_sections["priv_results"]['index'] = $this->_sections["priv_results"]['start'], $this->_sections["priv_results"]['iteration'] = 1;
                 $this->_sections["priv_results"]['iteration'] <= $this->_sections["priv_results"]['total'];
                 $this->_sections["priv_results"]['index'] += $this->_sections["priv_results"]['step'], $this->_sections["priv_results"]['iteration']++):
$this->_sections["priv_results"]['rownum'] = $this->_sections["priv_results"]['iteration'];
$this->_sections["priv_results"]['index_prev'] = $this->_sections["priv_results"]['index'] - $this->_sections["priv_results"]['step'];
$this->_sections["priv_results"]['index_next'] = $this->_sections["priv_results"]['index'] + $this->_sections["priv_results"]['step'];
$this->_sections["priv_results"]['first']      = ($this->_sections["priv_results"]['iteration'] == 1);
$this->_sections["priv_results"]['last']       = ($this->_sections["priv_results"]['iteration'] == $this->_sections["priv_results"]['total']);
?>
    <tr>
      <td class="whitebox">Private Results</td>
    </tr>
    <tr>
      <td>
        <form class="indented_cell" method="POST" action="<?php echo $this->_tpl_vars['conf']['html']; ?>
/results.php">
          Survey:&nbsp;
          <select name="sid" size="1">
            <?php if (isset($this->_sections["pr"])) unset($this->_sections["pr"]);
$this->_sections["pr"]['name'] = "pr";
$this->_sections["pr"]['loop'] = is_array($this->_tpl_vars['priv_results']['sid']) ? count($this->_tpl_vars['priv_results']['sid']) : max(0, (int)$this->_tpl_vars['priv_results']['sid']);
$this->_sections["pr"]['show'] = true;
$this->_sections["pr"]['max'] = $this->_sections["pr"]['loop'];
$this->_sections["pr"]['step'] = 1;
$this->_sections["pr"]['start'] = $this->_sections["pr"]['step'] > 0 ? 0 : $this->_sections["pr"]['loop']-1;
if ($this->_sections["pr"]['show']) {
    $this->_sections["pr"]['total'] = $this->_sections["pr"]['loop'];
    if ($this->_sections["pr"]['total'] == 0)
        $this->_sections["pr"]['show'] = false;
} else
    $this->_sections["pr"]['total'] = 0;
if ($this->_sections["pr"]['show']):

            for ($this->_sections["pr"]['index'] = $this->_sections["pr"]['start'], $this->_sections["pr"]['iteration'] = 1;
                 $this->_sections["pr"]['iteration'] <= $this->_sections["pr"]['total'];
                 $this->_sections["pr"]['index'] += $this->_sections["pr"]['step'], $this->_sections["pr"]['iteration']++):
$this->_sections["pr"]['rownum'] = $this->_sections["pr"]['iteration'];
$this->_sections["pr"]['index_prev'] = $this->_sections["pr"]['index'] - $this->_sections["pr"]['step'];
$this->_sections["pr"]['index_next'] = $this->_sections["pr"]['index'] + $this->_sections["pr"]['step'];
$this->_sections["pr"]['first']      = ($this->_sections["pr"]['iteration'] == 1);
$this->_sections["pr"]['last']       = ($this->_sections["pr"]['iteration'] == $this->_sections["pr"]['total']);
?>
              <option value="<?php echo $this->_tpl_vars['priv_results']['sid'][$this->_sections['pr']['index']]; ?>
"><?php echo $this->_tpl_vars['priv_results']['display'][$this->_sections['pr']['index']]; ?>
</option>
            <?php endfor; endif; ?>
          </select><br>
          Password:&nbsp;
          <input type="password" value="" name="password">
          &nbsp;<input type="submit" name="submit" value="View Results">
        </form>
      </td>
    </tr>
  <?php endfor; endif; ?>
  <tr>
    <td class="whitebox">Edit Surveys</td>
  </tr>
  <tr>
    <td>
      <form class="indented_cell" method="POST" action="<?php echo $this->_tpl_vars['conf']['html']; ?>
/edit_survey.php">
        Survey:&nbsp;
        <select name="sid" size="1">
          <?php if (isset($this->_sections["as"])) unset($this->_sections["as"]);
$this->_sections["as"]['name'] = "as";
$this->_sections["as"]['loop'] = is_array($this->_tpl_vars['all_surveys']['sid']) ? count($this->_tpl_vars['all_surveys']['sid']) : max(0, (int)$this->_tpl_vars['all_surveys']['sid']);
$this->_sections["as"]['show'] = true;
$this->_sections["as"]['max'] = $this->_sections["as"]['loop'];
$this->_sections["as"]['step'] = 1;
$this->_sections["as"]['start'] = $this->_sections["as"]['step'] > 0 ? 0 : $this->_sections["as"]['loop']-1;
if ($this->_sections["as"]['show']) {
    $this->_sections["as"]['total'] = $this->_sections["as"]['loop'];
    if ($this->_sections["as"]['total'] == 0)
        $this->_sections["as"]['show'] = false;
} else
    $this->_sections["as"]['total'] = 0;
if ($this->_sections["as"]['show']):

            for ($this->_sections["as"]['index'] = $this->_sections["as"]['start'], $this->_sections["as"]['iteration'] = 1;
                 $this->_sections["as"]['iteration'] <= $this->_sections["as"]['total'];
                 $this->_sections["as"]['index'] += $this->_sections["as"]['step'], $this->_sections["as"]['iteration']++):
$this->_sections["as"]['rownum'] = $this->_sections["as"]['iteration'];
$this->_sections["as"]['index_prev'] = $this->_sections["as"]['index'] - $this->_sections["as"]['step'];
$this->_sections["as"]['index_next'] = $this->_sections["as"]['index'] + $this->_sections["as"]['step'];
$this->_sections["as"]['first']      = ($this->_sections["as"]['iteration'] == 1);
$this->_sections["as"]['last']       = ($this->_sections["as"]['iteration'] == $this->_sections["as"]['total']);
?>
            <option value="<?php echo $this->_tpl_vars['all_surveys']['sid'][$this->_sections['as']['index']]; ?>
"><?php echo $this->_tpl_vars['all_surveys']['name'][$this->_sections['as']['index']]; ?>
</option>
          <?php endfor; endif; ?>
        </select><br>
        Password:&nbsp;
        <input type="password" value="" name="edit_survey_password">
        &nbsp;<input type="submit" name="submit" value="Edit Survey">
      </form>
    </td>
  </tr>
  <tr>
    <td style="text-align:center">
      <br />
      [ <a href="<?php echo $this->_tpl_vars['conf']['html']; ?>
/new_survey.php">Create New Survey</a>
      &nbsp;|&nbsp;
      <a href="<?php echo $this->_tpl_vars['conf']['html']; ?>
/admin.php">Admin</a>
      &nbsp;|&nbsp;
      <?php if (isset($this->_sections["logout"])) unset($this->_sections["logout"]);
$this->_sections["logout"]['name'] = "logout";
$this->_sections["logout"]['loop'] = is_array("1") ? count("1") : max(0, (int)"1");
$this->_sections["logout"]['show'] = (bool)$this->_tpl_vars['show']['logout'];
$this->_sections["logout"]['max'] = $this->_sections["logout"]['loop'];
$this->_sections["logout"]['step'] = 1;
$this->_sections["logout"]['start'] = $this->_sections["logout"]['step'] > 0 ? 0 : $this->_sections["logout"]['loop']-1;
if ($this->_sections["logout"]['show']) {
    $this->_sections["logout"]['total'] = $this->_sections["logout"]['loop'];
    if ($this->_sections["logout"]['total'] == 0)
        $this->_sections["logout"]['show'] = false;
} else
    $this->_sections["logout"]['total'] = 0;
if ($this->_sections["logout"]['show']):

            for ($this->_sections["logout"]['index'] = $this->_sections["logout"]['start'], $this->_sections["logout"]['iteration'] = 1;
                 $this->_sections["logout"]['iteration'] <= $this->_sections["logout"]['total'];
                 $this->_sections["logout"]['index'] += $this->_sections["logout"]['step'], $this->_sections["logout"]['iteration']++):
$this->_sections["logout"]['rownum'] = $this->_sections["logout"]['iteration'];
$this->_sections["logout"]['index_prev'] = $this->_sections["logout"]['index'] - $this->_sections["logout"]['step'];
$this->_sections["logout"]['index_next'] = $this->_sections["logout"]['index'] + $this->_sections["logout"]['step'];
$this->_sections["logout"]['first']      = ($this->_sections["logout"]['iteration'] == 1);
$this->_sections["logout"]['last']       = ($this->_sections["logout"]['iteration'] == $this->_sections["logout"]['total']);
?>
        <a href="<?php echo $this->_tpl_vars['conf']['html']; ?>
/index.php?action=logout">Logout</a>
        &nbsp;|&nbsp;
      <?php endfor; endif; ?>
      <a href="<?php echo $this->_tpl_vars['conf']['html']; ?>
/docs/index.html">Documentation</a> ]
    </td>
  </tr>
</table>