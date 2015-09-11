<?php
$photos = $app->getAllPhotos();
$photoPath = './photos/';
?>
<table class="table users table-hover">
    <thead>
        <tr>
            <th>Id</th>
            <th>Uploaded by</th>
            <th>Filename</th>
            <th>Status</th>
            <th>Preview</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($photos as $photo) : ?>
            <tr>
                <th scope="row"><?php echo $photo->id ?></th>
                <td><?php echo $photo->userid ?></td>
                <td><?php echo $photo->filepath ?></td>
                <td><?php echo $photo->status ?></td>
                <td>
                    <img id="<?php echo $photo->id ?>" class="moderation-photo" src="<?php echo $photoPath . $photo->userid . '/thumbs/' . $photo->filepath ?>">
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

