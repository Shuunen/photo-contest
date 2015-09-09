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
                    <button data-action="delete" type="button" class="btn btn-danger">
                        <span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span> Delete
                    </button>
                </div>

            </div>
        <?php endforeach; ?>
    </div>
<?php else : ?>
    <div class="alert alert-info" role="alert">There is no photos to moderate actually.</div>
<?php endif; ?>