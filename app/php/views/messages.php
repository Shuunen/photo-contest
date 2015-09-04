
<?php global $successMessage, $errorMessage ?>

<?php if (strlen($successMessage) > 0) : ?>           
    <div class="alert alert-success animated fadeIn" role="alert"><?php echo $successMessage ?></div>
<?php endif; ?>

<?php if (strlen($errorMessage) > 0) : ?>
    <div class="alert alert-danger animated fadeIn" role="alert"><?php echo $errorMessage ?></div>
<?php endif; ?>