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
    <div class="gallery grid">
        <div class="grid-sizer"></div>
        <?php foreach ($photos as $i => $photo) : ?>
            <?php if ($photo->photoid) : ?>
                <?php
                $class = "";
                if ($app->isAdmin && $photo->status === 'censored') {
                    $class = "censored";
                }
                if ($app->currentUser->userid != $photo->userid) {
                    $class = "vote";
                } else {
                    $class = "user";
                }
                ?>
                <div class="grid-item <?php print $class; ?>">
                    <?php
                    $photoThumb = $photoPath . $photo->userid . '/' . 'thumbs/' . $photo->filepath;
                    $photoFull = $photoPath . $photo->userid . '/' . $photo->filepath;
                    ?>
                    <img id="<?php echo $photo->photoid ?>" data-layzr="<?php echo $photoThumb ?>" data-thumb="<?php echo $photoThumb ?>" data-full="<?php echo $photoFull ?>">
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <div class="fullPhoto"></div>
<?php else : ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="alert alert-info" role="alert">There are no contributions yet.</div>
            </div>
        </div>
    </div>
<?php endif; ?>