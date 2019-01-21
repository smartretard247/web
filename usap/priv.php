<?
include("config.php");

function siteheader()
{
    global $_CONF;

    $retval = "";
    $retval .="<html><head><title>privacy and security</title>\n";
    $retval .="<link rel='stylesheet' href='" . $_CONF['html'] . "/css.php'>\n";
    $retval .="</head><body>\n";
    //html body
    $retval .="<table border='0' cellspacing='1' width='100%'>\n";
    $retval .="<tr><td align='center'>\n";
    $retval .="<img src='" . $_CONF["html"] . "/images/signalflags.gif' width='66' height='52' align='absmiddle' border='0'>\n";
    $retval .="</td><td align='center'>\n";
    $retval .="<font size='5'><strong>Unit Soldier Administration Program</strong></font>";
    $retval .="</td></tr>\n";
    $retval .="<tr><td valign='top'>&nbsp;\n";
    $retval .="</td><td valign='top'>\n";
    return $retval;
}

function sitefooter()
{
    global $_CONF;

    $retval = "";
    $retval .="</td>\n";
    $retval .="<tr><td colspan='2'>&nbsp;</td></tr>\n";
    $retval .="<tr><td align='center'>\n";
    $retval .="<img src='" . $_CONF["html"] . "/images/signalflags.gif' width='66' height='52' align='absmiddle'>\n";
    $retval .="</td><td align='left'>\n";
    $retval .="<hr width='100%'>";
    $retval .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&copy; 2001 U.S. Army, All Rights Reserved</td></tr>\n";
    $retval .="</table></body></html>\n";
    return $retval;
}
echo siteheader();
?>
<center>
<font size='+1'><strong>Privacy and Security</strong></font>
</center>
<ol>
<li>Unit Soldier Administration Program (USAP) is provided as a 15th Signal Brigade service by the Brigade Automation Department</li>
<li>Information presented on USAP is considered private information and may not be distributed or copied unless otherwise specified. use of appropriate byline/photo/image credits is requested. </li>
<li>USAP does collect private information. Access to that information is controlled by strict user permissions (normally limited to company operations, first sergeant, and commanders). </li>
<li>USAP is accessible only from the Ft. Gordon intranet. This is not a publicly available internet site.</li>
<li>For site management, information is collected for statistical purposes. This government computer system uses software programs to create summary statistics, which are used for such purposes as assessing what information is of most and least interest, determining technical design specifications, and identifying system performance or problem areas. </li>
<li>For site security purposes and to ensure that this service remains available to all authorized users, this government computer system employs software programs to monitor network traffic, to identify unauthorized attempts to upload or change information, or otherwise cause damage. </li>
<li>Unauthorized attempts to upload information or change information on this service are strictly prohibited and may be punishable under the computer fraud and abuse act of 1986 and the national information infrastructure protection act. </li>
<li>If you have any questions or comments about the information presented here, please forward them to BDE Automation at (706) 791-7373</li>
<li>Cookie disclaimer - USAP does use persistent cookies (persistent tokens that pass information back and forth from the client machine to the server) and session cookies (tokens that remain active only until you close your browser) in order to personalize your site based upon your stated preferences. USAP does not keep a database of information obtained from these cookies. </li>
</ol>
<?
echo sitefooter();
?>