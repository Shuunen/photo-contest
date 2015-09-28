<?php $photos = $app->getAllPhotos() ?>
<?php if (count($photos)) : ?>
    <div class="gallery grid">
        <div class="grid-sizer"></div>
        <?php foreach ($photos as $i => $photo) : ?>
            <?php if ($photo->photoid) : ?>
                <?php
                  $class = '';
                  $sortAttrs = '';
                  $res = $app->getResultsByPhoto($photo->photoid);
                  $globalRes = 0;
                  foreach($res as $catId => $result){
                    $globalRes += $result;
                    $sortAttrs .= ' data-result-'.$catId.'="'.$result.'"';
                  }
                ?>

                <?php if ($photo->status === 'approved') : ?>
                  <div class="grid-item <?php print $class; ?>" data-result-gobal="<?php print $globalRes;?>" <?php print $sortAttrs;?> >
                      <?php
                      $photoThumb = $app->photoPath . $photo->userid . '/' . 'thumbs/' . $photo->filepath;
                      $photoFull = $app->photoPath . $photo->userid . '/' . $photo->filepath;
                      ?>
                        <img class="grid-item-thumb" event-emitter data-photoid="<?php echo $photo->photoid ?>" data-layzr="<?php echo $photoThumb ?>" data-thumb="<?php echo $photoThumb ?>" data-full="<?php echo $photoFull ?>">
                  </div>
                <?php endif; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <div class="fullscreen-photo auto-next"></div>
<?php else : ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="alert alert-info" role="alert">There are no contributions yet.</div>
            </div>
        </div>
    </div>
<?php endif; ?>
