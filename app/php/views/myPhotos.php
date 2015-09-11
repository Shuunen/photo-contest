<?php
$photos = $app->getUserPhotos();
$photoPath = './photos/';
?>
<?php if (count($photos)) : ?>
    <div class="gallery-slider">
        <?php foreach ($photos as $photo) : ?>
            <div class="item">

                <img id="<?php echo $photo->photoid ?>" src="<?php echo $photoPath . $photo->userid . '/' . $photo->filepath ?>">

                <button type="button" title="Delete this photo" class="btn btn-danger delete-photo">
                    <span class="fa fa-trash" aria-hidden="true"></span>
                </button>

                <div class="status">
                    <span class="fa fa-<?php echo($photo->status === 'approved' ? 'check-circle' : ($photo->status === 'censored' ? 'ban' : 'hourglass-half')) ?>" aria-hidden="true"></span>
                    &nbsp;<span><?php echo ucwords($photo->status) ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else : ?>
    <div class="alert alert-info" role="alert">You did not submit any photos<?php if ($app->submitOpened) : ?> yet, you should upload some<?php endif; ?>.
    </div>
<?php endif; ?>
