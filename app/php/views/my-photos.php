<h2 class="animated fadeInDown">my photos | upload photos</h2><br>
<?php
$photos = $app->db->select("photos", "userId", $app->currentUser['id']);
$categories = [];
$categories['travels'] = 'Travels ';
$categories['most_creative '] = 'Most creative';
$categories['funniest'] = 'Funiest';
$categories['40'] = '40';
?>
<?php $photoPath = './app/photos/' . $app->currentUser['id'] . '/' ?>
<?php if (count($photos)) : ?>
    <div class="gallery">
        <?php foreach ($photos as $photo) : ?>
            <div class="item">
                <img src="<?php echo $photoPath . $photo['file'] ?>">
                <div class="ratings">
                    <?php foreach ($categories as $id => $category) : ?>
                        <div class="rating">
                            <div class="category"><?php print $category; ?> : </div>
                            <div class="stars"><input name="rating-<?php print $id; ?>" type="hidden" class="rating" data-filled="fa fa-star fa-3x" data-filled-selected="fa fa-star fa-3x" data-empty="fa fa-star-o fa-3x"></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="gallery-nav">
        <?php foreach ($photos as $photo) : ?>
            <img src="<?php echo $photoPath . $photo['file'] ?>">
        <?php endforeach; ?>
    </div>
<?php else : ?>
    <b>You do not have any photos actually, you should add some.</b>
<?php endif; ?>
