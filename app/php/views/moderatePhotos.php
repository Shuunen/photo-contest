<?php
$photos = $app->getPhotosToModerate();
$photoPath = './photos/';
?>
<?php if (count($photos)) : ?>
    <div class="gallery-slider">
        <?php foreach ($photos as $photo) : ?>
            <div class="item">

                <img id="<?php echo $photo->id ?>" class="moderation-photo" src="<?php echo $photoPath . $photo->userid . '/' . $photo->filepath ?>">

                <div class="moderation-controls">
                    <button data-action="approve" type="button" class="btn btn-success">
                        <span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span> Approve
                    </button>
                    <button data-action="censor" type="button" class="btn btn-warning">
                        <span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span> Censor
                    </button>
                </div>

                <button type="button" title="Delete this photo" class="btn btn-danger delete-photo">
                    <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                </button>

            </div>
        <?php endforeach; ?>
    </div>
<?php else : ?>
    <div class="alert alert-info" role="alert">There is no photos to moderate actually.</div>
<?php endif; ?>