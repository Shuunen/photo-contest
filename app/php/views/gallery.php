<?php $photos = $app->getAllPhotos() ?>
<?php if (count($photos)) : ?>
    <div class="gallery grid">
        <div class="grid-sizer"></div>
        <?php foreach ($photos as $i => $photo) : ?>
            <?php require 'gallery-thumb.php' ?>
        <?php endforeach; ?>
    </div>
    <div class="fullscreen-photo <?php echo ($app->voteOpened ? 'voteOpened' : '') ?>"></div>
<?php else : ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="alert alert-info" role="alert">There are no contributions yet.</div>
            </div>
        </div>
    </div>
<?php endif; ?>
