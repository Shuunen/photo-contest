<?php
$photos = $app->getAllPhotos();
// $photosArray = $photos->asArray();
// $photoRandom = $photosArray[array_rand($photosArray)];
$categories = $app->getCategories();
$photoPath = './photos/';
?>

<?php if (count($photos)) : ?>
    <div class="gallery-filters">
        <button class="btn btn-primary all">all</button>
        <button class="btn btn-primary user">User</button>
        <button class="btn btn-primary censored">Censored</button>
        <button class="btn btn-primary vote">Vote</button>
    </div>
    <div class="gallery">
        <?php foreach ($photos as $i => $photo) : ?>
            <?php if ($photo->photoid) : ?>
                <?php $photoThumb = $photoPath . $photo->userid . '/' . 'thumbs/' . $photo->filepath ?>
                <?php $photoFull = $photoPath . $photo->userid . '/' . $photo->filepath ?>
                <img id="<?php echo $photo->photoid ?>" data-index="<?php echo $i ?>" data-layzr="<?php echo $photoThumb ?>" data-thumb="<?php echo $photoThumb ?>" data-full="<?php echo $photoFull ?>">
            <?php endif; ?>
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

<?php if (count($photos) && false) : ?>
    <div id="voteModal" tabindex="-1" role="dialog" class="modal fullscreen fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="gallery-slider">
                        <?php foreach ($photos as $photo) : ?>
                            <?php if ($photo->photoid) : ?>
                                <div class="item">

                                    <img id="<?php echo $photo->photoid ?>" src="<?php echo $photoPath . $photo->userid . '/' . $photo->filepath ?>">

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
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
<?php endif; ?>

