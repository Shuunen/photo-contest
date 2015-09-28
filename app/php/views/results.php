<?php

  $results = $app->getResults();

?>
<div class="rate-results">

  <?php foreach($results as $catId => $photos) :?>
    <div class="col-md-3 category-results">
    <?php $category = $app->getCategoryInfo($catId);?>
    <h2><?php print $category->label;?></h2>
    <?php $count = 0;?>
    <?php foreach($photos as $photoId => $rate):?>

      <?php
        $photoInfo = $app->getPhotoInfo($photoId);
        $user = $app->getUserByUserid($photoInfo->userid);?>

      <?php if($count < 3 ) : ?>
        <div class="col-md-12 text-center">
          <div class="photo-infos">
            <div class="author"><?php print $user->name;?></div>
            <div class="rate">Rate : <?php print $rate;?> stars</div>
          </div>
        </div>
      <?php endif;?>

      <?php $count++; ?>
    <?php endforeach;?>
    </div>

  <?php endforeach;?>

</div>
