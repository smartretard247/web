<?php /* Smarty version 2.3.0, created on 2003-06-23 17:19:56
         compiled from edit_group_remarks.tpl */ ?>
        <table border="1" cellpadding="1" cellspacing="0" align="center" width="100%">
          <tr class="table_cheading">
            <td>Name</td>
            <td>Rank</td>
            <td>SSN</td>
            <td>PLT</td>
            <td>Subject</td>
            <td>New Remark</td>
          </tr>
          <?php if (isset($this->_sections["r"])) unset($this->_sections["r"]);
$this->_sections["r"]['name'] = "r";
$this->_sections["r"]['loop'] = is_array($this->_tpl_vars['display']['id']) ? count($this->_tpl_vars['display']['id']) : max(0, (int)$this->_tpl_vars['display']['id']);
$this->_sections["r"]['show'] = true;
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
          <tr bgcolor="<?php echo $this->_tpl_vars['display']['bgcolor'][$this->_sections['r']['index']]; ?>
">
            <td><?php echo $this->_tpl_vars['display']['name'][$this->_sections['r']['index']]; ?>
</td>
            <td><?php echo $this->_tpl_vars['display']['rank'][$this->_sections['r']['index']]; ?>
</td>
            <td><?php echo $this->_tpl_vars['display']['ssn'][$this->_sections['r']['index']]; ?>
</td>
            <td><?php echo $this->_tpl_vars['display']['plt'][$this->_sections['r']['index']]; ?>
</td>
            <td><?php echo $this->_tpl_vars['display']['subject_select'][$this->_sections['r']['index']]; ?>
</td>
            <td>
              <textarea name="remark[<?php echo $this->_tpl_vars['display']['id'][$this->_sections['r']['index']]; ?>
]" rows="3" cols="30"></textarea>
            </td>
          </tr>
          <?php endfor; endif; ?>
        </table>