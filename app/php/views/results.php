<?php

  $results = $app->getResults(10);

//var_dump($results);

?>
<div class="rate-results row">
  <div class="col-md-12 text-center">
    <h2>Results</h2>
  </div>
  <?php foreach($results as $catId => $photos) :?>

    <div class="col-md-3 category-results">
    <?php $category = $app->getCategoryInfo($catId);?>
    <h3 class="text-center"><?php print $category->label;?></h3>

      <?php foreach($photos as $photoResult):?>

        <?php
          $photoInfo = $app->getPhotoInfo($photoResult["photoid"]);
          $user = $app->getUserByUserid($photoInfo->userid);
        ?>


      <div class="media">
          <div class="media-left media-middle">
            <a href="<?php print $app->photoPath . $photoInfo->userid . '/' . $photoInfo->filepath;?>" target="_blank">
              <img class="media-object" src="<?php print $app->photoPath . $photoInfo->userid . '/thumbs/' . $photoInfo->filepath;?>">
            </a>
          </div>
          <div class="media-body">
            <div class="photo-infos">
              <div class="author"><a href="<?php print $app->photoPath . $photoInfo->userid . '/' . $photoInfo->filepath;?>" target="_blank"><?php print $user->name;?></a></div>
              <div class="rate">
                <span class="lead"><i class="fa fa-star"></i>&nbsp;<?php print $photoResult["totalCat"];?></span>&nbsp;&nbsp;<em class="small"><?php print $photoResult["totalStars"];?></em>
              </div>
            </div>
          </div>
        </div>

      <?php endforeach;?>
    </div>

  <?php endforeach;?>
</div>
