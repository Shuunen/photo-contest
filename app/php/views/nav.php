<nav class="navbar navbar-default">
    <div class="container-fluid">

        <div class="navbar-header">
            <a class="navbar-brand" href="#">Photo contest 2015</a>
        </div>

        <ul class="nav navbar-nav navbar-right">
            <?php if ($app->isAdmin): ?>
                <?php $photos = $app->getPhotosToModerate(); ?>
                <li>
                    <a href="#" data-toggle="modal" data-target="#moderatePhotosModal">Moderate photos
                        <?php if (count($photos)) : ?>
                            <span class="badge"><?php echo count($photos) ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <!--<li><a href="#" data-toggle="modal" data-target="#tablePhotosModal">Table photos</a></li>-->
            <?php endif; ?>
            <?php if ($app->submitOpened) : ?>
                <li><a href="#" data-toggle="modal" data-target="#uploadModal">Submit photos</a></li>
            <?php endif; ?>
            <li><a href="#" id="logoutLink">Logout</a></li>
        </ul>
    </div><!-- /.container-fluid -->
</nav>

<?php
if ($app->isAdmin) {
    require 'moderatePhotosModal.php';
    // require 'tablePhotosModal.php';
}
