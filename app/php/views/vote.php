<?php
$photos = $app->db->selectNot("photos", "userId", $app->currentUser['id']);
$categories = $app->db->selectAll("category");
$photoPath = './app/photos/';

$rates = $app->db->select("rates", "userId", $app->currentUser['id']);

//var_dump($rates);

function getRateForPhotoAndCategory($rates, $photoId, $categoryId) {
    foreach ($rates as $rate) {
        if ((isset($rate['photoId']) && $rate['photoId'] === $photoId) && (isset($rate['categoryId']) && $rate['categoryId'] === $categoryId)) {
            return $rate['rate'];
        }
    }
    return 0;
}
?>

<?php if (count($photos)) : ?>
    <h2>Contributions gallery &nbsp;<span class="badge"><?php echo count($photos) ?></span></h2>
    <div class="gallery">
        <?php foreach ($photos as $i => $photo) : ?>
            <img data-toggle="modal" data-target="#voteModal" data-index="<?php echo $i ?>" src="<?php echo $photoPath . $photo['userId'] . '/' . 'thumbs/' . $photo['file'] ?>">
        <?php endforeach; ?>
    </div>
<?php else : ?>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="alert alert-info" role="alert">There is no contributions actually.</div>
        </div>
    </div>
<?php endif; ?>

<?php if (count($photos)) : ?>
    <div id="voteModal" tabindex="-1" role="dialog" class="modal fullscreen fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="gallery-slider">
                        <?php foreach ($photos as $photo) : ?>
                            <div class="item">

                                <img src="<?php echo $photoPath . $photo['userId'] . '/' . $photo['file'] ?>">

                                <div class="ratings">
                                    <?php foreach ($categories as $id => $category) : ?>
                                        <div class="rating">
                                            <div class="category" ><?php print $category['label']; ?> :</div>
                                            <div class="stars rating-category" data-catgerory-id="<?php print $category["id"]; ?>" data-photo-id="<?php print $photo['id'] ?>">
                                                <input name="rating-<?php print $category["id"]; ?>" type="hidden" class="rating" data-filled="fa fa-star fa-2x" data-filled-selected="fa fa-star fa-2x" data-empty="fa fa-star-o fa-2x" value="<?php print getRateForPhotoAndCategory($rates, $photo['id'], $category["id"]); ?>"></span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>                    
            </div>
        </div>
    </div>
<?php endif; ?>

