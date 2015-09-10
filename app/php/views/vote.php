<?php
$photos = $app->getPhotosToVote();
// $photosArray = $photos->asArray();
// $photoRandom = $photosArray[array_rand($photosArray)];
$categories = $app->getCategories();
$photoPath = './photos/';
?>

<?php if (count($photos)) : ?>
    <h2>Contributions &nbsp;<span class="badge"><?php echo count($photos) ?></span></h2>
    <?php if ($app->voteOpened) : ?>
        <h3>You can vote for each of them.</h3>
    <?php else : ?>
        <h3>You can see each of them, but votes are not opened yet.</h3>
    <?php endif; ?>
    <div class="gallery">
        <?php foreach ($photos as $i => $photo) : ?>
            <img data-toggle="modal" data-target="#voteModal" data-index="<?php echo $i ?>" src="<?php echo $photoPath . $photo->userid . '/' . 'thumbs/' . $photo->filepath ?>">
        <?php endforeach; ?>
    </div>
<?php else : ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="alert alert-info" role="alert">There are no contributions yet.</div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if (count($photos)) : ?>
    <div id="voteModal" tabindex="-1" role="dialog" class="modal fullscreen fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="gallery-slider">
                        <?php foreach ($photos as $photo) : ?>
                            <div class="item">

                                <img src="<?php echo $photoPath . $photo->userid . '/' . $photo->filepath ?>">

                                <?php if ($app->voteOpened) : ?>
                                    <div class="ratings">
                                        <?php foreach ($categories as $category) : ?>
                                            <div class="rating">
                                                <div class="category"><?php print $category->label; ?> :</div>
                                                <div class="stars rating-category" data-catgerory-id="<?php print $category->categoryid; ?>" data-photo-id="<?php print $photo->photoid ?>">
                                                    <input name="rating-<?php print $category->categoryid; ?>" type="hidden" class="rating" data-filled="fa fa-star fa-2x" data-filled-selected="fa fa-star fa-2x" data-empty="fa fa-star-o fa-2x" value="<?php print $app->getRateForPhotoAndCategory($photo->photoid, $category->categoryid); ?>"></span>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else : ?>
                                    <div class="countdown-container">Votes will be opened in&nbsp;
                                        <div class="countdown voteOpened"></div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
<?php endif; ?>

