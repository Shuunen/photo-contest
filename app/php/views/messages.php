<?php if (isset($_SESSION['message']) && strlen($_SESSION['message']) > 0) : ?>

    <?php $alertClass = 'alert-' . ($_SESSION['messageStatus'] === 'success' ? 'success' : 'danger') ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="alert <?php echo $alertClass ?> animated fadeIn" role="alert"><?php echo $_SESSION['message'] ?></div>
            </div>
        </div>
    </div>
    
    <?php $_SESSION['message'] = '' ?>
    <?php $_SESSION['messageStatus'] = '' ?>

<?php endif; ?>
