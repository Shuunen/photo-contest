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
        $res = $app->getResultsByPhoto($photo['photoid']);
        if(count($res)>0){
          $photoResultArray = $res->asArray()[0];
          $sortAttrs = 'data-result-global="' . $res->global_results . '"';
          $categories = $app->getCategories();
          foreach ($categories as $category) {
              $resultCatIndex = $category->categoryid;
              if ($category->categoryid === "40") {
                  $resultCatIndex = "fourty";
              }
              $sortAttrs .= ' data-result-' . $category->categoryid . '="' . $photoResultArray[$resultCatIndex] . '"';
          }
        }else{

        }
    }
    ?>

    <?php if ($app->isAdmin || $app->isModerator || $app->isUser && $photo["status"] === 'approved' || $app->isUser && $photo["userid"] === $app->currentUser->userid) : ?>
        <div class="grid-item <?php print $class; ?>" data-photostatus="<?php echo $photo["status"] ?>" data-griditem-photoid="<?php echo $photo["photoid"] ?>" <?php print $sortAttrs ?>>

            <?php if ($app->voteOpened) : ?>
                <?php $rateCount = $app->getRatesCountForPhoto($photo["photoid"]) ?>
                <div class="rate-status <?php echo ($rateCount > 0) ? 'rate-complete' : '' ?>"></div>
            <?php endif; ?>

            <?php $photoThumb = $app->photoPath . $photo["userid"] . '/' . 'thumbs/' . $photo["filepath"] ?>
            <?php $photoFull = $app->photoPath . $photo["userid"] . '/' . $photo["filepath"] ?>
            <img class="grid-item-thumb" event-emitter data-photoid="<?php echo $photo["photoid"] ?>" data-lazy="<?php echo $photoThumb ?>">

        </div>
    <?php endif; ?>

<?php endif; ?>
