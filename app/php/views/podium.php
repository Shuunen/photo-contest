<div class="podium-banner expanded row">
  <div class="expander fa fa-angle-double-down" event-emitter aria-hidden="true"></div>
  <?php $categories = $app->getCategories() ?>

  <?php foreach ($categories as $category) : ?>
    <div class="col-md-3">
      <h5><?php print $category->label; ?></h5>
      <div class="steps" data-category-id="<?php print $category->categoryid; ?>">
        <?php
            $rates = $app->getRatesCategory($category->categoryid);
            //var_dump(sizeof($rates));
        ?>
        <?php for($i = 0; $i < $app->podiumSize ; $i++) : ?>
          <?php $photoInfo = null;?>

          <?php foreach($rates as $rate) : ?>
            <?php if($rate["rate"] == $app->podiumSize-$i): ?>
                <?php $photoInfo = $app->getPhotoInfo($rate["photoid"]);?>
            <?php endif;?>
          <?php endforeach; ?>

          <div class="step step-<?php print $i+1;?>" data-position="<?php print $i+1;?>" ondrop="drop(event)" ondragover="allowDrop(event)" <?php if(isset($photoInfo)) : ?> style="background-image:url('<?php echo $app->photoPath . $photoInfo->userid . '/' . 'thumbs/' . $photoInfo->filepath ?>');" data-photoid="<?php print $photoInfo->photoid;?>"<?php endif;?> ></div>

        <?php endfor; ?>
      </div>
    </div>
  <?php endforeach; ?>

</div>
