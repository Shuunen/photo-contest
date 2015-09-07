<h2 class="animated fadeInLeft">My photos</h2>
<?php
$photos = $app->db->select("photos", "userId", $app->currentUser['id']);
$categories = [];
$categories['travels'] = 'Travels ';
$categories['most_creative '] = 'Most creative';
$categories['funniest'] = 'Funiest';
$categories['40'] = '40';
?>
<?php if (count($photos)) : ?>
    <div class="gallery animated fadeInUp">
        <?php foreach ($photos as $photo) : ?>
            <div class="item">
                <img src="./app/photos/<?php echo $app->currentUser['id'] . '/' . $photo['file'] ?>">
                <?php foreach ($categories as $id => $category) : ?>
                    <span><?php print $category; ?> : </span>
                    <span><input name="rating-<?php print $id; ?>" type="hidden" class="rating" data-filled="fa fa-star fa-3x" data-filled-selected="fa fa-star fa-3x" data-empty="fa fa-star-o fa-3x"></span><br>
                    <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php else : ?>
    <b>You do not have any photos actually, you should add some.</b>
<?php endif; ?>
