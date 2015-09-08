<?php

if ($app->isLogged) {
    require 'nav.php';
}

?>

    <div class="page-header">
        <h1 id="type">UXD Photoshop Contest 2015</h1>
    </div>

<?php

require 'messages.php';

if (!$app->isLogged) {

    require 'login.php';

} else {

    require 'vote.php';

    if ($app->submitOpened) {
        require 'uploadModal.php';
    }

    require 'myPhotosModal.php';
}
