<?

include("lib-common.php");

if(isset($_SESSION['debug_mode']))
{ unset($_SESSION['debug_mode']); }
else
{ $_SESSION['debug_mode'] = 1; }

header("Location: " . $_SESSION['redirect_to']);
exit();

?>