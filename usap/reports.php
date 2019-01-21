<?
//configuration files
include("lib-common.php");

echo com_siteheader("reports");
include($_CONF["path"] . "/templates/reports.inc.php");

echo com_sitefooter();
?>