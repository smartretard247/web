<?php /* Smarty version 2.3.0, created on 2004-01-28 16:46:31
         compiled from load_tda.tpl */ ?>
<?php if (isset($this->_sections["message"])) unset($this->_sections["message"]);
$this->_sections["message"]['name'] = "message";
$this->_sections["message"]['loop'] = is_array("1") ? count("1") : max(0, (int)"1");
$this->_sections["message"]['show'] = (bool)$this->_tpl_vars['info']['message'];
$this->_sections["message"]['max'] = $this->_sections["message"]['loop'];
$this->_sections["message"]['step'] = 1;
$this->_sections["message"]['start'] = $this->_sections["message"]['step'] > 0 ? 0 : $this->_sections["message"]['loop']-1;
if ($this->_sections["message"]['show']) {
    $this->_sections["message"]['total'] = $this->_sections["message"]['loop'];
    if ($this->_sections["message"]['total'] == 0)
        $this->_sections["message"]['show'] = false;
} else
    $this->_sections["message"]['total'] = 0;
if ($this->_sections["message"]['show']):

            for ($this->_sections["message"]['index'] = $this->_sections["message"]['start'], $this->_sections["message"]['iteration'] = 1;
                 $this->_sections["message"]['iteration'] <= $this->_sections["message"]['total'];
                 $this->_sections["message"]['index'] += $this->_sections["message"]['step'], $this->_sections["message"]['iteration']++):
$this->_sections["message"]['rownum'] = $this->_sections["message"]['iteration'];
$this->_sections["message"]['index_prev'] = $this->_sections["message"]['index'] - $this->_sections["message"]['step'];
$this->_sections["message"]['index_next'] = $this->_sections["message"]['index'] + $this->_sections["message"]['step'];
$this->_sections["message"]['first']      = ($this->_sections["message"]['iteration'] == 1);
$this->_sections["message"]['last']       = ($this->_sections["message"]['iteration'] == $this->_sections["message"]['total']);
?>
    NOTICE: <?php echo $this->_tpl_vars['info']['message']; ?>

<?php endfor; endif; ?>
<form enctype="multipart/form-data" action="<?php echo $this->_tpl_vars['info']['current_page']; ?>
" method="post">
<input type="hidden" name="MAX_FILE_SIZE" value="300000" />
Use this form to load new TDA information into the database.

<p>
TDA Year:
  <select name="year" size="1">
    <option value="2004">2004</option>
    <option value="2005">2005</option>
    <option value="2006">2006</option>
  </select>

<p>
CSV File:
  <input type="file" name="file">

<p>
<input type="submit" name="submit" value="Load File" class="button">
</form>