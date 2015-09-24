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
          <img class="result-<?php print $count;?>" src="<?php print './photos/' . $photoInfo->userid . '/' . 'thumbs/' . $photoInfo->filepath;?>">
          <div class="photo-info">
            <div class="author"><?php print count($user) === 1 ? $user->name : $photoInfo->userid;?></div>
            <div class="rate">Rate : <?php print $rate;?></div>
          </div>
        </div>
      <?php else :?>
        <div class="col-md-12">
          <div class="col-md-6">
            <img class="result-<?php print $count;?>" src="<?php print './photos/' . $photoInfo->userid . '/' . 'thumbs/' . $photoInfo->filepath;?>">
          </div>
          <div class="col-md-6">
            <div class="author"><?php print count($user) === 1 ? $user->name : $photoInfo->userid;?></div>
            <div class="rate">Rate : <?php print $rate;?></div>
          </div>
        </div>
      <?php endif;?>

      <?php $count++; ?>
    <?php endforeach;?>
    </div>

  <?php endforeach;?>

</div>
