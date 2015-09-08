<?php
$photos = $app->db->select("photos", "userId", $app->currentUser['id']);
$categories = [];
$categories['travels'] = 'Travels ';
$categories['most_creative '] = 'Most creative';
$categories['funniest'] = 'Funiest';
$categories['40'] = '40';
$photoPath = './app/photos/' . $app->currentUser['id'] . '/';
?>
<?php if (count($photos)) : ?>
    <h2>My photos &nbsp;<span class="badge"><?php echo count($photos) ?></span></h2>
    <div class="gallery">
        <?php foreach ($photos as $photo) : ?>
            <div class="item">
                <img src="<?php echo $photoPath . $photo['file'] ?>">

                <div class="ratings">
                    <?php foreach ($categories as $id => $category) : ?>
                        <div class="rating">
                            <div class="category"><?php print $category; ?> :</div>
                            <div class="stars">
                                <input name="rating-<?php print $id; ?>" type="hidden" class="rating" data-filled="fa fa-star fa-3x" data-filled-selected="fa fa-star fa-3x" data-empty="fa fa-star-o fa-3x">
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="gallery-nav">
        <?php foreach ($photos as $photo) : ?>
            <img src="<?php echo $photoPath . 'thumbs/' . $photo['file'] ?>">
        <?php endforeach; ?>
    </div>
<?php else : ?>
    <div class="alert alert-info" role="alert">You did not submit any photos yet, you should
        <strong><a href="#" data-toggle="modal" data-target="#uploadModal">upload some</a></strong>.
    </div>
<?php endif; ?>
