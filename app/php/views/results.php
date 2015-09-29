<?php

  $results = $app->getResults();

//var_dump($results);

?>
<div class="rate-results row">
  <h2>Results</h2>
  <?php if(count($results) === 0):?>
    <div class="col-md-12 text-center">
      <button id="computeResults" event-emitter data-action="computeResults" class="btn btn-primary btn-lg">Compute results</button>
    </div>
  <?php else:?>
    <div class="col-md-12">
      <button id="computeResults" event-emitter data-action="computeResults" class="btn btn-primary btn-xs">Clear and compute results</button>
    </div>
    <?php foreach($results as $catId => $photos) :?>
      <div class="col-md-3 category-results">
      <?php $category = $app->getCategoryInfo($catId);?>
      <h3 class="text-center"><?php print $category->label;?></h3>
      <?php $count = 0;?>
        <?php foreach($photos as $photoResult):?>

          <?php
            $resultCatIndex = $catId;

            $photoResultArray = get_object_vars($photoResult);
            if($catId == "40"){
              $resultCatIndex = "fourty";
            }
            $photoInfo = $photoResult->Photos;//$app->getPhotoInfo($photoResult->photoid);
            $user = $app->getUserByUserid($photoInfo->userid);
          ?>

          <?php if($count < 10) :?>
            <div class="col-md-12">
              <div class="photo-infos text-center">
                <div class="author"><a href="<?php print $app->photoPath . $photoInfo->userid . '/' . $photoInfo->filepath;?>" target="_blank"><?php print $user->name;?></a></div>
                <div class="rate">
                  <span class="lead"><?php print $photoResultArray[$resultCatIndex];?>&nbsp;<i class="fa fa-star"></i></span>&nbsp;/&nbsp;<em class="small"><?php print $photoResult->global_results;?>&nbsp;<i class="fa fa-star"></i></em>
                </div>
              </div>
            </div>
          <?php endif;?>
          <?php $count++;?>
        <?php endforeach;?>
      </div>

    <?php endforeach;?>
  <?php endif;?>

</div>
