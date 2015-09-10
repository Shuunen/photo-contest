<?php
require "./app.php";
$app = new App();
function __autoload($class_name) {
    $class_name = str_replace("_", "/", $class_name);
    $class_name = str_replace("\\", "/", $class_name);
    $class_path = './php/' . $class_name . '.php';
    // var_dump('loading : ' . $class_path);
    require($class_path);
}
$path = './';
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Photo contest 2015">
        <meta name="author" content="UXD">
        <link rel="shortcut icon" type="image/png" href="favicon.png" />
        <title>Photo contest 2015</title>
        
        <!-- build:css styles/combined.css -->
        <link href="<?php echo $path ?>bower_components/bootstrap-rating/bootstrap-rating.css" rel="stylesheet">
        <link href="<?php echo $path ?>bower_components/animate-css/animate.min.css" rel="stylesheet">
        <link href="<?php echo $path ?>bower_components/slick.js/slick/slick.css" rel="stylesheet">
        <link href="<?php echo $path ?>bower_components/slick.js/slick/slick-theme.css" rel="stylesheet">
        <link href="<?php echo $path ?>crappy_bower_component/fine-uploader/fine-uploader-gallery.min.css" rel="stylesheet">
        <!-- <link href="<?php echo $path ?>bower_components/fine-uploader/_build/fine-uploader-gallery.min.css" rel="stylesheet">-->
        <link href="<?php echo $path ?>bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <!-- endbuild -->
        <!-- build:css styles/main.css -->
        <link href="<?php echo $path ?>styles/css/main.css" rel="stylesheet">
        <!-- endbuild -->
    </head>

    <body>

        <?php require './php/views/main.php' ?>

        <!-- build:js scripts/combined.js -->
        <script type="text/javascript" src="<?php echo $path ?>bower_components/jquery/dist/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo $path ?>bower_components/slick.js/slick/slick.min.js"></script>
        <script type="text/javascript" src="<?php echo $path ?>crappy_bower_component/fine-uploader/fine-uploader.min.js"></script>
         <!-- <script type="text/javascript" src="<?php echo $path ?>bower_components/fine-uploader/_build/fine-uploader.min.js"></script>-->
        <script type="text/javascript" src="<?php echo $path ?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?php echo $path ?>bower_components/bootstrap-rating/bootstrap-rating.min.js"></script>
        <script type="text/javascript" src="<?php echo $path ?>bower_components/jquery.countdown/dist/jquery.countdown.min.js"></script>
        <!-- endbuild -->

        <script type="text/javascript" src="<?php echo $path ?>scripts/main.js"></script>
        <?php if ($app->voteOpened): ?>
            <script type="text/javascript" src="<?php echo $path ?>scripts/ratings.js"></script>
        <?php endif; ?>
        <?php if ($app->submitOpened): ?>
            <script type="text/javascript" src="<?php echo $path ?>scripts/upload.js"></script>
        <?php endif; ?>
        <?php if ($app->isAdmin): ?>
            <script type="text/javascript" src="<?php echo $path ?>scripts/moderate.js"></script>
        <?php endif; ?>
    </body>

</html>
