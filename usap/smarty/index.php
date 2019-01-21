<?php
require("Smarty.class.php");
$smarty = new Smarty;
$smarty->assign("Name","Ned");
$smarty->display("index.tpl");
?>