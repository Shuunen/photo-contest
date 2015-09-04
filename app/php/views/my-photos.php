<h2 class="animated fadeInLeft">My photos</h2>
<?php $photos = $app->db->select("photos", "userId", $app->currentUser['id']); ?>
<?php if (count($photos)) : ?>
    <div class="gallery animated fadeInUp">
        <?php foreach ($photos as $photo) : ?>
            <div class="item">
                <img src="./photos/<?php echo $app->currentUser['id'] . '/' . $photo['file'] ?>">
            </div>
        <?php endforeach; ?>
    </div>
<?php else : ?>
    <b>You do not have any photos actually, you should add some.</b>
<?php endif; ?>