<table width="90%" border="1" align="center">
  <tr>
    <td class="heading">Add New Class</td>
  </tr>
  <tr>
    <td>
      <form method='post' action='<?php=$_SERVER["SCRIPT_NAME"]?>' name="add_class" onload="document.add_class.mos.focus();">
        <table width="100%" border="0" cellspacing="2" cellpadding="2">
          <col width="20%" />
          <col width="80%" />
          <tr>
            <td>MOS:</td>
            <td><input type="text" name="mos" size="5" value="<?php=htmlentities($_POST['mos'])?>"></td>
          </tr>
          <tr>
            <td>Class Number:</td>
            <td><input type="text" name="class_number" size="15" value="<?php=htmlentities($_POST['class_number'])?>"></td>
          </tr>
          <tr>
            <td>Unit</td>
            <td>
              <?php 
                if(isset($_POST['unit']))
                { $unit = explode('-',$_POST['unit']); }
                else
                {
                    $unit[0] = 0;
                    $unit[1] = 0;
                }
                echo unit_select(4,$unit[0],$unit[1]); 
              ?>
            </td>
          </tr>
          <tr>
            <td>Start Date</td>
            <td><input type="text" name="start_date" size="10" maxlength="9" value="<?php=htmlentities($_POST['start_date'])?>"></td>
          </tr>
          <tr>
            <td>EOC Date</td>
            <td><input type="text" name="eoc_date" size="10" maxlength="9" value="<?php=htmlentities($_POST['eoc_date'])?>"></td>
          </tr>
          <tr>
            <td>CTT Date</td>
            <td><input type="text" name="ctt_date" size="10" maxlength="9" value="<?php=htmlentities($_POST['ctt_date'])?>"></td>
          </tr>
          <tr>
            <td>Transition Date</td>
            <td><input type="text" name="trans_date" size="10" maxlength="9" value="<?php=htmlentities($_POST['trans_date'])?>"></td>
          </tr>
          <tr>
            <td>STX Start Date</td>
            <td><input type="text" name="stx_start" size="10" maxlength="9" value="<?php=htmlentities($_POST['stx_start'])?>"></td>
          </tr>
          <tr>
            <td>STX End Date</td>
            <td><input type="text" name="stx_end" size="10" maxlength="9" value="<?php=htmlentities($_POST['stx_end'])?>"></td>
          </tr>
          <tr>
            <td>Graduation Date</td>
            <td><input type="text" name="grad_date" size="10" maxlength="9" value="<?php=htmlentities($_POST['grad_date'])?>"></td>
          </tr>
          <tr>
            <td>PCS Date</td>
            <td><input type="text" name="pcs_date" size="10" maxlength="9" value="<?php=htmlentities($_POST['pcs_date'])?>"></td>
          </tr>
          <tr>
            <td>AOT Type</td>
            <td><?php=conf_select('aot_type',$_POST['aot_type'])?></td>
          </tr>
          <tr>
            <td>Phase</td>
            <td><?php=conf_select('phase',$_POST['phase'])?></td>
          </tr>          
          <tr>
            <td class="column_name">Custom Fields</td>
            <td class="example">These fields will allow you to add custom dates or text areas to
                                your classes. For example, if you want to track a <strong>Drown-proofing</strong>
                                date for each class, you'd give <strong>Drown-proofing</strong> as the Field Name and 
                                the date for the value. </td>
          </tr>
          <?php
            for($x=0;$x<5;$x++)
            {
                ?>
                <tr>
                  <td>Field:<input type="text" name="field[<?php=$x?>]" size="21" maxlength="20" value="<?php=htmlentities($_POST['field'][$x])?>"></td>
                  <td>Value:<input type="text" name="value[<?php=$x?>]" size="40" maxlength="40" value="<?php=htmlentities($_POST['value'][$x])?>"></td>
                </tr>
                <?php
            }
          ?>
          <tr>
            <td>&nbsp;</td>
            <td><input type="submit" name="add_class_submit" value="Add Class" class="button"></td>
          </tr>
        </table>
      </form>
    </td>
  </tr>
</table>