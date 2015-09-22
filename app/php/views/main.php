<div class="main">
    <?php
    if ($app->isLogged) {
        require 'nav.php';
    }

    require 'messages.php';

    if (!$app->isLogged) {

        require 'login.php';
        
    } else {

        require 'gallery.php';

        if ($app->submitOpened) {
            require 'uploadModal.php';
        }

        if ($app->isAdmin) {
            require 'addUserModal.php';
        }
    }        
    ?>
</div>
<?php
if($app->isDesktop) {
    require 'bokeh.php';
}
//echo '<h3>isDesktop : ' . ($app->isDesktop ? 'YES' : 'no') . '</h3>';
//echo '<h3>HTTP_USER_AGENT : ' . $_SERVER['HTTP_USER_AGENT'] . '</h3>';
