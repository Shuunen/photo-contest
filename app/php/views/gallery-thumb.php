<?php if ($photo["photoid"]) : ?>

    <?php
    $class = '';
    if ($app->currentUser->userid != $photo["userid"] && $photo["status"] === 'approved') {
        $class .= " vote";
    }
    if ($app->currentUser->userid === $photo["userid"]) {
        $class .= " my-photos";
    }
    ?>

    <?php if ($app->isAdmin || $app->isModerator || $app->isUser && $photo["status"] === 'approved' || $app->isUser && $photo["userid"] === $app->currentUser->userid) : ?>
        <div class="grid-item <?php print $class; ?>" data-photostatus="<?php echo $photo["status"] ?>" data-griditem-photoid="<?php echo $photo["photoid"] ?>">

            <?php if ($app->voteOpened) : ?>
                <?php $rateCount = $app->getRatesCountForPhoto($photo["photoid"]) ?>
                <div class="rate-status <?php echo ($rateCount > 0) ? 'rate-complete' : '' ?>"></div>
            <?php endif; ?>

            <?php $photoThumb = $app->photoPath . $photo["userid"] . '/' . 'thumbs/' . $photo["filepath"] ?>
            <?php $photoFull = $app->photoPath . $photo["userid"] . '/' . $photo["filepath"] ?>
            <img class="grid-item-thumb" event-emitter data-photoid="<?php echo $photo["photoid"] ?>" data-layzr="<?php echo $photoThumb ?>" data-thumb="<?php echo $photoThumb ?>" data-full="<?php echo $photoFull ?>">

        </div>
    <?php endif; ?>

<?php endif; ?>
