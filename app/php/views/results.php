<?php

  $results = $app->getResults($app->votingMode === "podium" ? 3 : 10);

//var_dump($results);

?>
<div class="rate-results row">
  <!--div class="col-md-12 text-center">
    <h2>Results</h2>
  </div-->
  <?php foreach($results as $catId => $photos) :?>

    <div class="col-md-3 category-results">
    <?php $category = $app->getCategoryInfo($catId);?>
    <h3 class="text-center"><?php print $category->label;?></h3>

      <?php foreach($photos as $index => $photoResult):?>

        <?php
          $photoInfo = $app->getPhotoInfo($photoResult["photoid"]);
          $user = $app->getUserByUserid($photoInfo->userid);
        ?>

        <?php if($photoInfo->status === "approved" && $app->votingMode === "stars"):?>
          <div class="media">
            <div class="media-left media-middle">
              <a href="<?php print $app->photoPath . $photoInfo->userid . '/' . $photoInfo->filepath;?>" target="_blank">
                <img class="media-object" src="<?php print $app->photoPath . $photoInfo->userid . '/thumbs/' . $photoInfo->filepath;?>">
              </a>
            </div>
            <div class="media-body">
              <div class="photo-infos">
                <div class="author"><a href="<?php print $app->photoPath . $photoInfo->userid . '/' . $photoInfo->filepath;?>" target="_blank"><?php print $user->name;?></a></div>
                <div class="rate" title="Average stars for this user : <?php print round($photoResult['avgStars'],4);?>">
                  <span class="lead"><i class="fa fa-star"></i>&nbsp;<?php print $photoResult["totalCat"];?></span>&nbsp;&nbsp;<em class="small"><?php print $photoResult["totalStars"];?></em>
                </div>
              </div>
            </div>
          </div>
        <?php else :?>
          <div class="media">
            <div class="media-left media-middle">
              <img class="media-object" src="../../images/cup<?php print $index +1;?>.png">
            </div>
            <div class="media-body media-middle">
              <a href="<?php print $app->photoPath . $photoInfo->userid . '/' . $photoInfo->filepath;?>" target="_blank">
                <img class="media-object" src="<?php print $app->photoPath . $photoInfo->userid . '/thumbs/' . $photoInfo->filepath;?>">
              </a>
              <div class="author text-center">
                <a href="<?php print $app->photoPath . $photoInfo->userid . '/' . $photoInfo->filepath;?>" target="_blank" title="Global note : <?php print $photoResult["totalStars"];?>"><?php print $user->name;?></a>
              </div>
            </div>
          </div>

        <?php endif;?>

      <?php endforeach;?>
    </div>

  <?php endforeach;?>
</div>
