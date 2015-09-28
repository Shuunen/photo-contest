<nav class="navbar navbar-default">
    <div class="container-fluid">

        <div class="navbar-header">
            <a class="navbar-brand refresh-button" event-emitter href="#">Photo contest 2015</a>
        </div>

        <ul class="nav navbar-nav navbar-right">

            <?php if (!$app->showResults): ?>

                <!-- ***************************
                **** If not showing results ****
                **************************** -->

                <?php $photos = $app->getAllPhotos(); ?>
                <?php $photos_vote = $app->getPhotosToVote(); ?>
                <?php $photos_user = $app->getUserPhotos(); ?>
                <?php $photos_moderate = $app->getPhotosToModerate(); ?>

                <li>
                    <a href="#" class="grid-filter btn btn-info" event-emitter data-filter="*">
                        All photos
                        <?php $count = ($app->isAdmin || $app->isModerator) ? count($photos) : count($photos_user) + count($photos_vote) ?>
                        <?php if ($count) : ?>
                            <span class="badge"><?php echo $count ?></span>
                        <?php endif; ?>
                    </a>
                </li>

                <li>
                    <a href="#my-photos" class="grid-filter btn btn-info" event-emitter data-filter=".my-photos">
                        My photos
                        <?php if (count($photos_user)) : ?>
                            <span class="badge"><?php echo count($photos_user) ?></span>
                        <?php endif; ?>
                    </a>
                </li>

                <?php if ($app->isModerator) : ?>
                    <li>
                        <a href="#submitted" class="grid-filter btn btn-info" event-emitter data-filter="[data-photostatus='submitted']">
                            To moderate
                            <?php if (count($photos_moderate)) : ?>
                                <span class="badge"><?php echo count($photos_moderate) ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if ($app->isAdmin || $app->isModerator) : ?>
                    <?php $photos = $app->getPhotosCensored(); ?>
                    <li>
                        <a href="#censored" class="grid-filter btn btn-info" event-emitter data-filter="[data-photostatus='censored']">
                            Censored
                            <?php if (count($photos)) : ?>
                                <span class="badge"><?php echo count($photos) ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endif; ?>

                <li>
                    <a href="#vote" class="grid-filter btn btn-info" event-emitter data-filter=".vote">
                        Opened to vote
                        <?php if (count($photos_vote)) : ?>
                            <span class="badge"><?php echo count($photos_vote) ?></span>
                        <?php endif; ?>
                    </a>
                </li>

            <?php else : ?>

                <!-- ***************************
                **** If showing results !:) ****
                **************************** -->

                <li>
                    <a href="#global" class="grid-filter btn btn-info" event-emitter data-sort="global">Global</a>
                </li>

                <?php $categories = $app->getCategories(); ?>
                <?php foreach ($categories as $category): ?>
                    <li>
                        <a href="#<?php print $category->categoryid; ?>" class="grid-filter btn btn-info" event-emitter data-sort="<?php print $category->categoryid; ?>">
                            <?php print $category->label; ?>
                        </a>
                    </li>
                <?php endforeach; ?>

            <?php endif; ?>

            <?php if (($app->isAdmin || $app->isModerator) && $app->showResults): ?>
                <li><a href="#" data-toggle="modal" data-target="#resultsModal">Results Table</a></li>
            <?php endif; ?>

            <?php if ($app->isAdmin): ?>
                <li><a href="#" data-toggle="modal" event-emitter data-target="#addUserModal">Add User</a></li>
            <?php endif; ?>

            <?php if ($app->submitOpened) : ?>
                <li><a href="#" data-toggle="modal" event-emitter data-target="#uploadModal">Submit photos</a></li>
            <?php endif; ?>

            <li><a href="#" event-emitter title="Logged as <?php echo $app->currentUser->name ?>" class="logout-link">Logout</a></li>
        </ul>
    </div>
    <!-- /.container-fluid -->
</nav>
