<?php

  $results = $app->getResults();

?>
<div class="rate-results row">

  <?php foreach($results as $catId => $photos) :?>
    <div class="col-md-3 category-results">
    <?php $category = $app->getCategoryInfo($catId);?>
    <h3 class="text-center"><?php print $category->label;?></h3>
    <?php $count = 0;?>
    <?php foreach($photos as $photoId => $rate):?>

      <?php
        $photoInfo = $app->getPhotoInfo($photoId);
        $user = $app->getUserByUserid($photoInfo->userid);?>

      <?php if($count < 10) :?>
        <div class="col-md-12">
          <div class="photo-infos">
            <div class="author"><a href="<?php print $app->photoPath . $photoInfo->userid . '/' . $photoInfo->filepath ?>" target="_blank"><?php print $user->name;?></a></div>
            <div class="rate">Results : <?php print $rate;?> <i class="fa fa-star"></i></div>
          </div>
        </div>
      <?php endif;?>


      <?php $count++; ?>
    <?php endforeach;?>
    </div>

  <?php endforeach;?>

</div>
