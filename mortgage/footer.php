<?php if($action != 'view_home') { echo '<br/>'; } ?>
    </div><!-- end main -->
        <div id="footer">
            <?php ShowAlert(); ?>
            
            <?php if($_SESSION['admin_enabled']) : ?>
                <b><a href="http://<?php echo $localIP; ?>/">Go Back To Server</a></b><br/>
            <?php endif;
                if($_SESSION['debug']) {
                    echo '<ul>';
                    
                    if($_POST) {
                        foreach($_POST as $key => $value) {
                            if(is_array($value)) {
                                echo '<ul>';
                                foreach($value as $ikey => $ivalue) {
                                    echo '<li>$_POST[' . $key . '][' . $ikey . '] => ' . $ivalue . "</li>";
                                }
                                echo '</ul>';
                            } else {
                                echo '<li>$_POST[' . $key . '] => ' . $value . "</li>";
                            }
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
                            if(is_array($value)) {
                                echo '<ul>';
                                foreach($value as $ikey => $ivalue) {
                                    echo '<li>$_SESSION[' . $key . '][' . $ikey . '] => ' . $ivalue . "</li>";
                                }
                                echo '</ul>';
                            } else {
                                echo '<li>$_SESSION[' . $key . '] => ' . $value . "</li>";
                            }
                        }
                    }
                    
                    echo '</ul>';
                } 
            ?>
            <p class="copyright">
		&copy; <?php echo date("Y"); ?> Jeezy
            </p>
            <br/>
        </div>
        <?php $_SESSION['error_message'] = ''; $_SESSION['message'] = ''; ?>
    </div><!-- end page -->
    
    <!-- perform destructions -->
    <?php
        unset($oneMonth);
        unset($data);
        unset($db);
    ?>
    </body>
</html>