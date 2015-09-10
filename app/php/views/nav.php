<nav class="navbar navbar-default">
    <div class="container-fluid">

        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span>
                </a>
                <ul class="dropdown-menu">
                    <?php if ($app->isAdmin): ?>
                        <?php $photos = $app->getPhotosToModerate(); ?>
                        <li>
                            <a href="#" data-toggle="modal" data-target="#moderatePhotosModal">Moderate photos
                                <?php if (count($photos)) : ?>
                                    <span class="badge"><?php echo count($photos) ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li><a href="#">See results <span class="badge">14</span></a></li>
                    <?php endif; ?>
                    <?php $photos = $app->getUserPhotos(); ?>
                    <li>
                        <a href="#" data-toggle="modal" class="<?php echo count($photos) < 1 ? 'disabled' : '' ?>" data-target="#myPhotosModal">See my photos
                            <?php if (count($photos)) : ?>
                                <span class="badge"><?php echo count($photos) ?></span>
                            <?php endif; ?>
                        </a></li>
                    <?php if ($app->submitOpened) : ?>
                        <li><a href="#" data-toggle="modal" data-target="#uploadModal">Submit photos</a></li>
                    <?php endif; ?>
                    <li role="separator" class="divider"></li>
                    <li><a href="#" id="logoutLink">Logout</a></li>
                </ul>
            </li>
        </ul>

    </div>
    <!-- /.container-fluid -->
</nav>

<?php
if ($app->isAdmin) {
    require 'moderatePhotosModal.php';
}
