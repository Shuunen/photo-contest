<?php if ($photo->photoid) : ?>
  <?php
    $class = '';
    if ($app->currentUser->userid != $photo->userid && $photo->status === 'approved') {
      $class .= " vote";
    }
    if ($app->currentUser->userid === $photo->userid) {
      $class .= " my-photos";
    }
    $rateCount = $app->getRatesCountForPhoto($photo->photoid);
    $rateClass = "";
    if ($rateCount > 0){
      $rateClass = " rate-complete";
      if($app->voteOpened){
        $class .= " rate-complete";
      }
    } else if ($rateCount < 4 && $rateCount > 0) {
      $rateClass = " rate-started";
    }
  ?>

  <?php if ($app->isAdmin || $app->isUser && $photo->status === 'approved' || $app->isUser && $photo->userid === $app->currentUser->userid) : ?>
    <div class="grid-item <?php print $class; ?>" data-photostatus="<?php echo $photo->status ?>">
      <?php
      $photoThumb = $app->photoPath . $photo->userid . '/' . 'thumbs/' . $photo->filepath;
      $photoFull = $app->photoPath . $photo->userid . '/' . $photo->filepath;
      ?>
      <?php if(strlen($rateClass) && $app->voteOpened) : ?>
      <div class="rate-status <?php echo $rateClass ?>"></div>
      <?php endif; ?>
      <img class="grid-item-thumb" event-emitter data-photoid="<?php echo $photo->photoid ?>" data-layzr="<?php echo $photoThumb ?>" data-thumb="<?php echo $photoThumb ?>" data-full="<?php echo $photoFull ?>">
    </div>
  <?php endif; ?>
<?php endif; ?>
