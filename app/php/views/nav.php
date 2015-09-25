<nav class="navbar navbar-default">
    <div class="container-fluid">

        <div class="navbar-header">
            <a class="navbar-brand refresh-button" event-emitter href="#">Photo contest 2015</a>
        </div>

        <ul class="nav navbar-nav navbar-right">
          <?php if(!$app->showResults):?>
            <?php $photos = $app->getAllPhotos(); ?>
            <?php $photos_vote = $app->getPhotosToVote(); ?>
            <?php $photos_user = $app->getUserPhotos(); ?>
            <li>
                <a href="#" class="grid-filter btn btn-info" event-emitter data-filter="*">
                    All photos
                    <?php if (count($photos)) : ?>
                        <span class="badge"><?php echo count($photos_user)+count($photos_vote) ?></span>
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
            <?php if ($app->isAdmin) : ?>
                <?php $photos = $app->getPhotosToModerate(); ?>
                <li>
                    <a href="#submitted" class="grid-filter btn btn-info" event-emitter data-filter="[data-photostatus='submitted']">
                        To moderate
                        <?php if (count($photos)) : ?>
                            <span class="badge"><?php echo count($photos) ?></span>
                        <?php endif; ?>
                    </a>
                </li>
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
          <?php else :?>
            <li>
                <a href="#global" class="grid-filter btn btn-info" event-emitter data-sort="global">Global</a>
            </li>
            <?php $categories = $app->getCategories();?>
            <?php foreach($categories as $category):?>
              <li>
                  <a href="#<?php print $category->categoryid;?>" class="grid-filter btn btn-info" event-emitter data-sort="<?php print $category->categoryid;?>">
                      <?php print $category->label;?>
                  </a>
              </li>
            <?php endforeach;?>
          <?php endif;?>
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
