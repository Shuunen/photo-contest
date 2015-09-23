<nav class="navbar navbar-default">
    <div class="container-fluid">

        <div class="navbar-header">
            <a class="navbar-brand refresh-button" event-emitter href="#">Photo contest 2015</a>
        </div>

        <ul class="nav navbar-nav navbar-right">

            <li><a href="#" class="grid-filter btn btn-info" event-emitter data-filter="*">All photos</a></li>
            <li><a href="#my-photos" class="grid-filter btn btn-info" event-emitter data-filter=".my-photos">My photos</a></li>
            <?php if ($app->isAdmin) : ?>
                <?php $photosToModerate = $app->getPhotosToModerate(); ?>
                <li>
                    <a href="#submitted" class="grid-filter btn btn-info" event-emitter data-filter="[data-photostatus='submitted']">To moderate
                        <?php if (count($photosToModerate)) : ?>
                            <span class="badge nbPhotosToModerate"><?php echo count($photosToModerate) ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li><a href="#censored" class="grid-filter btn btn-info" event-emitter data-filter="[data-photostatus='censored']">Censored</a></li>
            <?php endif; ?>
            <li><a href="#vote" class="grid-filter btn btn-info" event-emitter data-filter=".vote">Vote</a></li>

            <?php if ($app->isAdmin): ?>
                <!--<li><a href="#" data-toggle="modal" data-target="#tablePhotosModal">Table photos</a></li>-->
                <li><a href="#" data-toggle="modal" event-emitter data-target="#addUserModal">Add User</a></li>
            <?php endif; ?>
            <?php if ($app->submitOpened) : ?>
                <li><a href="#" data-toggle="modal" event-emitter data-target="#uploadModal">Submit photos</a></li>
            <?php endif; ?>
            <li><a href="#" event-emitter class="logout-link">Logout</a></li>
        </ul>
    </div><!-- /.container-fluid -->
</nav>
