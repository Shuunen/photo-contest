<h2 class="animated fadeInLeft">My photos</h2>
<?php
$photos = $app->db->selectNot("photos", "userId", $app->currentUser['id']);
$categories = $app->db->selectAll("category");
$rates = $app->db->select("rates", "userId", $app->currentUser['id']);
//var_dump($rates);

function getRateForPhotoAndCategory($rates, $photoId, $categoryId){

  foreach($rates as $rate){
    if((isset ($rate['photoId']) && $rate['photoId'] === $photoId) && (isset($rate['categoryId']) && $rate['categoryId']  === $categoryId)){
      return $rate['rate'];
    }
  }

  return 0;
}

?>
<?php if (count($photos)) : ?>
    <div class="gallery animated fadeInUp">
        <?php foreach ($photos as $photo) : ?>
            <div class="item">
                <img src="./photos/<?php echo $photo['userId'] . '/' . $photo['file'] ?>">
                <?php foreach ($categories as $category) : ?>
                  <div class="rating-category" data-catgerory-id="<?php print $category["id"]; ?>" data-photo-id="<?php print $photo['id']?>">
                    <span><?php print $category["label"]; ?> : </span>
                    <span><input name="rating-<?php print $category["id"]; ?>" type="hidden" class="rating" data-filled="fa fa-star fa-3x" data-filled-selected="fa fa-star fa-3x" data-empty="fa fa-star-o fa-3x" value="<?php print getRateForPhotoAndCategory($rates,$photo['id'],$category["id"]);?>"></span>
                    </div>
              <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
<!--div class="row">
  <?php foreach ($categories as $category) : ?>
        <div class="rating-category" data-catgerory-id="<?php print $category["id"]; ?>" data-photo-id="">
          <span><?php print $category["label"]; ?> : </span>
          <span><input name="rating-<?php print $category["id"]; ?>" type="hidden" class="rating" data-filled="fa fa-star fa-3x" data-filled-selected="fa fa-star fa-3x" data-empty="fa fa-star-o fa-3x"></span>
          </div>
  <?php endforeach; ?>
  <div id="gallery" class="zoomwall">
      <?php foreach ($photos as $photo) : ?>
      <?php endforeach; ?>

      <img src="./photos/romain-racamier_4D3435B4-F929-5AAE-A7B4-653FD7991950/water-801925_480.jpg" data-highres="./photos/romain-racamier_4D3435B4-F929-5AAE-A7B4-653FD7991950/water-801925_1920.jpg" />
      <img src="./photos/romain-racamier_4D3435B4-F929-5AAE-A7B4-653FD7991950/workstation-405768_480.jpg" data-highres="./photos/romain-racamier_4D3435B4-F929-5AAE-A7B4-653FD7991950/workstation-405768_1920.jpg" />

  </div>
</div-->
<?php else : ?>
    <b>You do not have any photos actually, you should add some.</b>
<?php endif; ?>
