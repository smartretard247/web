    </div><!-- end main -->
        <div id="footer">
            <a href="../rfo/core/logout.php">Logout</a><br/><br/>
            <?php if($_SESSION['admin_enabled']) : ?>
                <b><a href="http://<?php echo $localIP; ?>/">Go Back To Server</a></b><br/>
            <?php endif;
                if($_SESSION['debug']) {
                    echo '<ul>';
                    
                    if($_POST) {
                        foreach($_POST as $key => $value) {
                            echo '<li>$_POST[' . $key . '] => ' . $value . "</li>";
                        }
                        echo '<br/>';
                    }
                    
                    if($_GET) {
                        foreach($_GET as $key => $value) {
                            echo '<li>$_GET[' . $key . '] => ' . $value . "</li>";
                        }
                        echo '<br/>';
                    }
                    
                    if($_SESSION) {
                        foreach($_SESSION as $key => $value) {
                            echo '<li>$_SESSION[' . $key . '] => ' . $value . "</li>";
                        }
                    }
                    
                    echo '</ul>';
                } 
            ?>
            <p class="copyright">
		&copy; <?php echo date("Y"); ?> Jesse Young
            </p>
        </div>
        <?php $_SESSION['error_message'] = ''; $_SESSION['message'] = ''; ?>
    </div><!-- end page -->
    </body>
</html>