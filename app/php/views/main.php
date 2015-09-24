<div class="main fade">
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

        if($app->showResults){
          require 'results.php';
        }
    }
    ?>
</div>
