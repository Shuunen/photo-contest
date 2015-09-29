
<div class="item close-fullscreen-photo" event-emitter>

    <div class="photo-container">
        <i class="fa fa-spinner fa-spin fa-5x"></i>
        <img data-photoid="<?php echo $photo->photoid ?>" data-lazy="<?php echo $app->photoPath . $photo->userid . '/' . $photo->filepath ?>">
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

    <?php if ($app->currentUser->role === 'moderator'): ?>
        <div class="moderation-controls <?php echo(($app->showResults) ? 'on-side' : '') ?>">
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
    <?php endif; ?>

    <?php if ($app->currentUser->userid !== $photo->userid || (!$app->voteOpened && $app->voteEnded && $this->showResults)) : ?>
        <?php $categories = $app->getCategories() ?>
        <?php if ($app->voteOpened) : ?>
            <div class="ratings">
                <?php foreach ($categories as $category) : ?>
                    <div class="rating col-md-6">
                        <div class="category"><?php print $category->label; ?> :</div>
                        <div class="stars rating-category" data-catgerory-id="<?php print $category->categoryid; ?>" data-photo-id="<?php print $photo->photoid ?>">
                            <input name="rating-<?php print $category->categoryid; ?>" type="hidden" class="rating" data-fractions="2" data-start="<?php print $app->lowerVote; ?>" data-stop="<?php print $app->higherVote; ?>" data-filled="fa fa-star fa-2x" data-filled-selected="fa fa-star fa-2x" data-empty="fa fa-star-o fa-2x" value="<?php print $app->getRateForPhotoAndCategory($photo->photoid, $category->categoryid); ?>">
                            <span class="clear-vote fa fa-times" title="Clear vote"></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php elseif ($app->voteEnded && !$this->showResults): ?>
            <div class="countdown-container">Votes are closed, the results will come soon.</div>
        <?php elseif ($this->showResults): ?>
            <div class="results-container center text-left">
                <?php
                  $user = $app->getUserByUserid($photo->userid);
                  $results = $app->getResultsByPhoto($photo->photoid);
                  $resultsArray = $results->asArray()[0];
                  $globalRes = $results->global_results;
                ?>
                <div class="author"><i>by</i>&nbsp;<?php print count($user) === 1 ? $user->name : $photo->userid;; ?>,&nbsp;<i>total stars :</i>&nbsp;<?php echo $globalRes ?>&nbsp;<i class="fa fa-star"></i>
                </div>
                <?php foreach ($categories as $category) : ?>
                    <?php
                      $resultCatIndex = $category->categoryid;

                      if($category->categoryid === "40"){
                        $resultCatIndex = "fourty";
                      };
                    ?>
                    <div class="media col-xs-6">
                        <div class="media-left media-middle">
                            <?php print $category->label; ?>
                        </div>
                        <div class="media-body media-middle">
                            : <strong><?php print $resultsArray[$resultCatIndex]; ?></strong>
                            <i class="fa fa-star"></i>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <div class="countdown-container">Votes will be opened in&nbsp;
                <div class="countdown voteOpened"></div>
            </div>
        <?php endif; ?>
    <?php elseif (!$app->voteOpened && !$app->voteEnded) : ?>
        <button type="button" title="Delete this photo" event-emitter class="btn btn-danger delete-photo">
            Delete my photo&nbsp;&nbsp;<span class="fa fa-trash" aria-hidden="true"></span>
        </button>
        <div class="moderation-controls">
            <div class="status-box">
                <span class="fa fa-<?php echo($photo->status === 'approved' ? 'check-circle' : ($photo->status === 'censored' ? 'ban' : 'hourglass-half')) ?>" aria-hidden="true"></span>
                &nbsp;<span><?php echo ucwords($photo->status) ?></span>
            </div>
        </div>
    <?php elseif ($app->currentUser->userid === $photo->userid && $app->voteOpened) : ?>
        <div class="countdown-container">You can't vote for your own photo.</div>
    <?php elseif ($app->voteEnded && !$this->showResults): ?>
        <div class="countdown-container">Votes are closed, the results will come soon.</div>
    <?php endif; ?>

</div>
