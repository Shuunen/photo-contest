<?php
$photos = $app->getUserPhotos();
$photoPath = './photos/';
?>
<?php if (count($photos)) : ?>
    <div class="gallery-slider">
        <?php foreach ($photos as $photo) : ?>
            <div class="item">

                <img id="<?php echo $photo->id ?>" src="<?php echo $photoPath . $photo->userid . '/' . $photo->filepath ?>">

                <button type="button" title="Delete this photo" class="btn btn-danger delete-photo">
                    <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                </button>

                <div class="status">
                    <span class="glyphicon glyphicon-<?php echo($photo->status === 'approved' ? 'ok' : ($photo->status === 'censored' ? 'ban-circle' : 'hourglass')) ?>" aria-hidden="true"></span>
                    &nbsp;<?php echo ucwords($photo->status) ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else : ?>
    <div class="alert alert-info" role="alert">You did not submit any photos
        <?php if ($app->submitOpened) : ?>
            yet, you should upload some
        <?php endif; ?>.
    </div>
<?php endif; ?>