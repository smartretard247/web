<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <!-- the head section -->
    <head>
        <title>D447 - RFO</title>
        <link rel="stylesheet" type="text/css"
              href="/rfo/main.css" />
    </head>

    <!-- the body section -->
    <body>
    <div id="page">
        <div id="header">
            <h1>D447 - RFO</h1>
			<p align="right">
				<?php session_save_path('../sessions');
                                    ini_set('session.gc_probability', 1);
        
                                    if(!$_SESSION['admin_enabled']) : ?>
					<form method="post" action="core/admin_enable.php">
						<input type="password" name="admin_pass"/>
						<input type="submit" value="Admin Mode"/>
					</form>
				<?php else : ?>
					<form method="post" action="core/admin_enable.php">
						<input type="submit" value="Guest Mode" name="guest_mode"/>
					</form>
				<?php endif; ?>
			</p>
        </div>
        <div id="main"><br/>