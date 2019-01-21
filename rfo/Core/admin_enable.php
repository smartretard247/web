<?php $root = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT'); //get root folder for relative paths
    session_save_path($root . '/sessions'); session_start();
	
	if(isset($_POST['admin_pass'])) {
		if($_POST['admin_pass'] == 'password') {
			$_SESSION['admin_enabled'] = true;
			$_SESSION['debug'] = true;
		} else {
			$_SESSION['error_message'] = 'Incorrect Password';
		}
	}
	
	if(isset($_POST['guest_mode'])) {
		$_SESSION['admin_enabled'] = false;
		$_SESSION['debug'] = false;
	}
	
	header('Location: ../index.php');
?>
