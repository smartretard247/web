    
        <div id="footer">
            <?php
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