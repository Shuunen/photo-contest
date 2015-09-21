<?php
$categories = $app->getCategories();
$photoPath = './photos/';
?>
<div class="item close-fullscreen-photo" event-emitter title="Close photo">
    <div class="photo-container">
        <img data-photoid="<?php echo $photo->photoid ?>" src="<?php echo $photoPath . $photo->userid . '/' . $photo->filepath ?>">
    </div>
    
    <button type="button" event-emitter class="slide-control prev btn" title="Previous photo">
        <i class="fa fa-chevron-left fa-3x"></i>
    </button>
    
    <button type="button" event-emitter class="slide-control next btn" title="Next photo">
        <i class="fa fa-chevron-right fa-3x"></i>
    </button>
    
     <button type="button" event-emitter class="close-fullscreen-photo btn" title="Close photo">
        <i class="fa fa-times fa-3x"></i>
    </button>
    
    <?php if ($app->currentUser->role === 'admin'): ?>
        <div class="moderation-controls">
            <button data-action="approve" type="button" event-emitter class="moderation-control btn btn-success" <?php print $photo->status === "approved" ? "disabled" : ""; ?>>
                <span class="fa fa-check-circle" aria-hidden="true"></span> Approve<?php print $photo->status === "approved" ? "d" : ""; ?>
            </button>
            <button data-action="censor" type="button" event-emitter class="moderation-control btn btn-warning" <?php print $photo->status === "censored" ? "disabled" : ""; ?>>
                <span class="fa fa-minus-circle" aria-hidden="true"></span> Censor<?php print $photo->status === "censored" ? "ed" : ""; ?>
            </button>
        </div>

        <button type="button" title="Delete this photo" event-emitter class="btn btn-danger delete-photo">
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
        <button type="button" title="Delete this photo" event-emitter class="btn btn-danger delete-photo">
            Delete this photo&nbsp;&nbsp;<span class="fa fa-trash" aria-hidden="true"></span>
        </button>
        <div class="moderation-controls">
            <div class="status-box">
                <span class="fa fa-<?php echo($photo->status === 'approved' ? 'check-circle' : ($photo->status === 'censored' ? 'ban' : 'hourglass-half')) ?>" aria-hidden="true"></span>
                &nbsp;<span><?php echo ucwords($photo->status) ?></span>
            </div>
        </div>
    <?php endif; ?>

</div>