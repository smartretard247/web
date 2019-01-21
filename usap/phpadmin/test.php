<?php
 # initialize sessions
 session_start();
 
 # set session variable to auto-increment
 $_SESSION['VAR'] += 1;
 echo "<p>If the following number increases everytime you hit the Refresh button, session
 variables are working:",$_SESSION['VAR'],"</p>";
 
 if (isset($_ENV["_FCGI_MUTEX_"])) echo "<p>FastCGI Enabled</p>";
 phpinfo();
 
?>