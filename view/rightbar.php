<div id="rightbar" style="float: right;">
    <div class="barcrumb">
<form name="login" action="../core/login.php" method="post">
    <h3>Log In&nbsp;&nbsp;<?php if($_SESSION['admin_enabled']) : ?><a href="index.php?action=create_account">(Create Account)</a><?php endif; ?></h3><br/>
    Username: <input name="Username" type="text"/><br/>
    Password: <input name="ThePassword" type="password"/><br/>
    <p align="right"><input type="submit" value="Login"/></p>
</form>
</div>
    <?php if($_SESSION['valid_user']) : ?>
	<div class="barcrumb">
            <a href="../core/logout.php">Logout</a>
	</div>
    <?php endif; ?>
</div>