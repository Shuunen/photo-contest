<nav class="navbar navbar-default">
    <div class="container-fluid">

        <p class="navbar-text">Available actions</p>

        <ul class="nav navbar-nav">
            <?php if ($app->isAdmin): ?>
                <?php $newPhotos = $app->getPhotosToModerate(); ?>
                <li><a href="#" data-toggle="modal" data-target="#moderatePhotosModal">Moderate photos <span class="badge"><?php echo count($newPhotos) ?></span></a></li>
                <li><a href="#">See results <span class="badge">14</span></a></li>
            <?php endif; ?>
            <?php $userPhotos = $app->getUserPhotos(); ?>
            <li><a href="#" data-toggle="modal" data-target="#myPhotosModal">My photos
                    <span class="badge"><?php echo count($userPhotos) ?></span></a></li>
            <?php if ($app->submitOpened) : ?>
                <li><a href="#" data-toggle="modal" data-target="#uploadModal">Submit photos</a></li>
            <?php endif; ?>
        </ul>

        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $app->currentUser->name ?>
                    <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="#" id="logoutLink">Logout</a></li>
                </ul>
            </li>
        </ul>

    </div>
    <!-- /.container-fluid -->
</nav>

<?php if ($app->isAdmin) {
    require 'moderatePhotosModal.php';
} ?>
