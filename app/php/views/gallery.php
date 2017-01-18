<?php


  if($app->startVoteDate <= new DateTime('now') || $app->isModerator){
    // Phase 1 : Submission only, owned photos only available
    $photos = $app->getAllPhotos()->asArray();
  }
  else {
    // Phase 2 : Voting, all approved photos displayed, only not owned are allowed or voting
    $photos = $app->getUserPhotos()->asArray();
  }

  shuffle($photos);
?>
<?php if (count($photos)) : ?>
    <div class="gallery grid">
        <div class="grid-sizer"></div>
        <?php foreach ($photos as $i => $photo) : ?>
            <?php require 'gallery-thumb.php' ?>
        <?php endforeach; ?>
    </div>
    <div class="fullscreen-photo"></div>
<?php else : ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="alert alert-info" role="alert">There are no contributions yet.</div>
            </div>
        </div>
    </div>
<?php endif; ?>
