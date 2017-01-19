<?php if ($photo["photoid"]) : ?>

    <?php
    $class = '';
    $sortAttrs = ''; // TODO : merge $class & $sortAttrs into $attributes
    if (!$app->showResults) {
        // TODO : act like $sortAttrs and build a $filterAttrs rather than use classes
        if ($app->currentUser->userid != $photo["userid"] && $photo["status"] === 'approved') {
            $class .= " vote";
        }
        if ($app->currentUser->userid === $photo["userid"]) {
            $class .= " my-photos";
        }
    } else {
        $photoResultArray = $app->getResultsByPhoto($photo['photoid']);

        $sortAttrs = 'data-result-global="' . $photoResultArray["totalStars"] . '"';
        $categories = $app->getCategories();
        foreach ($categories as $category) {
          if($photo["status"] === 'approved'){
            $sortAttrs .= ' data-result-' . $category->categoryid . '="' . $photoResultArray["total".$category->categoryid] . '"';
          }else{
            $sortAttrs .= ' data-result-' . $category->categoryid . '="-1"';
          }
        }
    }
    ?>

    <?php if ($app->isAdmin || $app->isModerator || $app->isUser && $photo["status"] === 'approved' || $app->isUser && $photo["userid"] === $app->currentUser->userid) : ?>
        <div class="grid-item <?php print $class; ?>" data-photostatus="<?php echo $photo["status"] ?>" data-griditem-photoid="<?php echo $photo["photoid"] ?>" <?php print $sortAttrs ?> <?php if($photo["userid"] != $app->currentUser->userid):?>draggable="true" ondragstart="drag(event)"<?php endif;?>>

            <?php if ($app->voteOpened && $photo["status"] === 'approved' && $app->votingMode === "stars") : ?>
                <?php $rateCount = $app->getRatesCountForPhoto($photo["photoid"]) ?>
                <div class="rate-status <?php echo ($rateCount > 0) ? 'rate-complete' : '' ?>"></div>
            <?php endif; ?>

            <?php $photoThumb = $app->photoPath . $photo["userid"] . '/' . 'thumbs/' . $photo["filepath"] ?>
            <?php $photoFull = $app->photoPath . $photo["userid"] . '/' . $photo["filepath"] ?>
            <img class="grid-item-thumb" event-emitter data-photoid="<?php echo $photo["photoid"] ?>" data-lazy="<?php echo $photoThumb ?>">

        </div>
    <?php endif; ?>

<?php endif; ?>
