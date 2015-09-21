<?php

  $results = $app->getResults();

  foreach($results as $cat => $photos){
    print $cat."<br>";

    foreach($photos as $photoId => $rate){
      $photoInfo = Lazer::table('photos')->where('photoid', '=', $photoId)->find();
      print '<img src="'.'./photos/' . $photoInfo->userid . '/' . 'thumbs/' . $photoInfo->filepath.'">'." : ".$rate."<br>";
    }
    print "<br><br>";
  }

?>
<div class="rateResults">

  <?php foreach($results as $cat => $photos) :?>
    <div class="col-md-6">
    <?php $category = Lazer::table('categories')->where('categoryid', '=', $cat)->find();?>
    <h2><?php print $category->label;?></h2>
    <?php $count = 0;?>
    <?php foreach($photos as $photoId => $rate):?>

      <?php $photoInfo = Lazer::table('photos')->where('photoid', '=', $photoId)->find();?>


      <?php if($count < 3 ) : ?>
        <div class="col-md-<?php print 5-$count;?>">
          <img class="result-<?php print $count;?>" src="<?php print './photos/' . $photoInfo->userid . '/' . 'thumbs/' . $photoInfo->filepath;?>">
          <div class="photoInfo">
            <?php $user = Lazer::table('users')->where('userid', '=', $photoInfo->userid)->find();?>
            <span class="author"><?php print $user->name;?></span><span class="rate">$rate</span>
          </div>
        </div>
      <?php else :?>
        <?php break;?>
      <?php endif;?>

      <?php $count++; ?>
    <?php endforeach;?>
    </div>

  <?php endforeach;?>

</div>
