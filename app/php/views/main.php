<div class="main">
<?php

if ($app->isLogged) {
    require 'nav.php';
}

require 'messages.php';

if (!$app->isLogged) {

    require 'login.php';

} else {

    //require 'vote.php';

    if ($app->submitOpened) {
        require 'uploadModal.php';
    }

    //require 'myPhotosModal.php';

    require 'gallery.php';
}
?>
</div>
