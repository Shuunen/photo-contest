<?php
$photos = $app->getAllPhotos();
// $photosArray = $photos->asArray();
// $photoRandom = $photosArray[array_rand($photosArray)];
$categories = $app->getCategories();
$photoPath = './photos/';
?>

<?php if (count($photos)) : ?>
    <div class="gallery grid">
        <div class="grid-sizer"></div>
        <?php foreach ($photos as $i => $photo) : ?>
            <?php if ($photo->photoid) : ?>
                <?php
                  $class = '';
                  if ($app->currentUser->userid != $photo->userid && $photo->status === 'approved') {
                      $class .= " vote";
                  }
                  if ($app->currentUser->userid === $photo->userid) {
                      $class .= " my-photos";
                  }
                ?>

                <?php if ($app->isAdmin || $app->isUser && $photo->status === 'approved' || $app->isUser && $photo->userid === $app->currentUser->userid) : ?>
                  <div class="grid-item <?php print $class; ?>" data-photostatus="<?php echo $photo->status ?>">
                      <?php
                      $photoThumb = $photoPath . $photo->userid . '/' . 'thumbs/' . $photo->filepath;
                      $photoFull = $photoPath . $photo->userid . '/' . $photo->filepath;
                      ?>
                        <img data-photoid="<?php echo $photo->photoid ?>" data-layzr="<?php echo $photoThumb ?>" data-thumb="<?php echo $photoThumb ?>" data-full="<?php echo $photoFull ?>">
                  </div>
                <?php endif; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <div class="fullscreen-photo"></div>
<?php else : ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="alert alert-info" role="alert">There are no contributions yet.</div>
            </div>
        </div>
    </div>
<?php endif; ?>
