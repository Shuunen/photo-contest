<h2 class="animated fadeInLeft">My photos</h2>
<?php $photos = $app->db->select("photos", "userId", $app->currentUser['id']);
$categories = [];

                      $categories['travels'] = 'Travels ';
                      $categories['most_creative '] = 'Most creative';
                      $categories['funniest'] = 'Funiest';
                      $categories['40'] = '40';?>
<?php if (count($photos)) : ?>
    <!--div class="gallery animated fadeInUp">
        <?php foreach ($photos as $photo) : ?>
            <div class="item">
                <img src="./photos/<?php echo $app->currentUser['id'] . '/' . $photo['file'] ?>">
                <?php foreach ($categories as $id => $category) : ?>
                  <span><?php print $category;?> : </span>
                  <span><input name="rating-<?php print $id;?>" type="hidden" class="rating" data-filled="fa fa-star fa-3x" data-filled-selected="fa fa-star fa-3x" data-empty="fa fa-star-o fa-3x"></span><br>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div-->
<div id="gallery" class="zoomwall">
  <?php foreach ($photos as $photo) : ?>
  <?php endforeach; ?>
    <img src="./photos/romain-racamier_4D3435B4-F929-5AAE-A7B4-653FD7991950/water-801925_480.jpg" data-highres="http://localhost:9000/photos/romain-racamier_4D3435B4-F929-5AAE-A7B4-653FD7991950/water-801925_1920.jpg" />
    <img src="http://localhost:9000/photos/romain-racamier_4D3435B4-F929-5AAE-A7B4-653FD7991950/workstation-405768_480.jpg" data-highres="http://localhost:9000/photos/romain-racamier_4D3435B4-F929-5AAE-A7B4-653FD7991950/workstation-405768_1920.jpg" />


</div>
<?php else : ?>
    <b>You do not have any photos actually, you should add some.</b>
<?php endif; ?>
