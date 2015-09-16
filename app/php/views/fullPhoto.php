<?php
  $categories = $app->getCategories();
  $photoPath = './photos/';
?>
<div class="item">
  <img id="<?php echo $photo->photoid ?>" src="<?php echo $photoPath . $photo->userid . '/' . $photo->filepath ?>">


  <?php if($app->currentUser->role === 'admin'):?>
    <div class="moderation-controls">
        <button data-action="approve" type="button" class="btn btn-success">
            <span class="fa fa-check-circle" aria-hidden="true"></span> Approve
        </button>
        <button data-action="censor" type="button" class="btn btn-warning">
            <span class="fa fa-minus-circle" aria-hidden="true"></span> Censor
        </button>
    </div>

    <button type="button" title="Delete this photo" class="btn btn-danger delete-photo">
        Delete this photo&nbsp;&nbsp;<span class="fa fa-trash" aria-hidden="true"></span>
    </button>
  <?php elseif ($app->currentUser->userid !== $photo->userid) : ?>
    <?php if ($app->voteOpened) : ?>
        <div class="ratings">
            <?php foreach ($categories as $category) : ?>
                <div class="rating">
                    <div class="category"><?php print $category->label; ?> :</div>
                    <div class="stars rating-category" data-catgerory-id="<?php print $category->categoryid; ?>" data-photo-id="<?php print $photo->photoid ?>">
                        <input name="rating-<?php print $category->categoryid; ?>" type="hidden" class="rating" data-filled="fa fa-star fa-2x" data-filled-selected="fa fa-star fa-2x" data-empty="fa fa-star-o fa-2x" value="<?php print $app->getRateForPhotoAndCategory($photo->photoid, $category->categoryid); ?>"></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <div class="countdown-container">Votes will be opened in&nbsp;
            <div class="countdown voteOpened"></div>
        </div>
    <?php endif; ?>
  <?php else : ?>
    <button type="button" title="Delete this photo" class="btn btn-danger delete-photo">
        Delete this photo&nbsp;&nbsp;<span class="fa fa-trash" aria-hidden="true"></span>
    </button>

    <div class="status">
        <span class="fa fa-<?php echo($photo->status === 'approved' ? 'check-circle' : ($photo->status === 'censored' ? 'ban' : 'hourglass-half')) ?>" aria-hidden="true"></span>
        &nbsp;<span><?php echo ucwords($photo->status) ?></span>
    </div>
  <?php endif; ?>

</div>
