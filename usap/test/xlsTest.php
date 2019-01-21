<?php

  include("xls.php");

  header("Pragma: public");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Content-Type: application/force-download");
  header("Content-Type: application/octet-stream");
  header("Content-Type: application/download");
  header("Content-Disposition: attachment;filename=test.xls");
  header("Content-Transfer-Encoding: binary");
  
  xlsBOF();
  xlsWriteLabel(0,0,"Name");
  xlsWriteLabel(0,1,"SSN");
  xlsWriteLabel(0,2,"Phone");
  xlsWriteLabel(1,0,"Jose");
  xlsWriteLabel(1,1,"583-61-0945");
  xlsWriteLabel(1,2,"762-333-3771");
  xlsEOF();
?>
