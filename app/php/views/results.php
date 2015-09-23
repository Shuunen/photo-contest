<?php

use Lazer\Classes\Database as Lazer;

  $results = $app->getResults();

?>
<div class="rate-results">

  <?php foreach($results as $cat => $photos) :?>
    <div class="col-md-3 category-results">
    <?php $category = Lazer::table('categories')->where('categoryid', '=', $cat)->find();?>
    <h2><?php print $category->label;?></h2>
    <?php $count = 0;?>
    <?php foreach($photos as $photoId => $rate):?>

      <?php $photoInfo = Lazer::table('photos')->where('photoid', '=', $photoId)->find();?>


      <?php if($count < 3 ) : ?>
        <div class="col-md-12 text-center">
          <img class="result-<?php print $count;?>" src="<?php print './photos/' . $photoInfo->userid . '/' . 'thumbs/' . $photoInfo->filepath;?>">
          <div class="photo-info">
            <?php $user = Lazer::table('users')->where('userid', '=', $photoInfo->userid)->find();?>
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
            <?php $user = Lazer::table('users')->where('userid', '=', $photoInfo->userid)->find();?>
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
