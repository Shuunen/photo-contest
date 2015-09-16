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

}
?>
</div>