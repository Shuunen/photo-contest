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

        <!-- build:css styles/styles.css -->
        <link href="../bower_components/bootstrap-rating/bootstrap-rating.css" rel="stylesheet">
        <link href="../bower_components/animate-css/animate.min.css" rel="stylesheet">
        <link href="../bower_components/slick.js/slick/slick.css" rel="stylesheet">
        <link href="../bower_components/slick.js/slick/slick-theme.css" rel="stylesheet">
        <link href="../bower_components/fineuploader-dist/dist/fine-uploader-gallery.min.css" rel="stylesheet">
        <link href="../bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <link href="./styles/css/main.css" rel="stylesheet">
        <!-- endbuild -->
    </head>

    <body>

        <?php require './php/views/main.php' ?>

        <!-- build:js scripts/scripts.js -->
        <script type="text/javascript" src="../bower_components/jquery/dist/jquery.min.js"></script>
        <script type="text/javascript" src="../bower_components/slick.js/slick/slick.min.js"></script>
        <script type="text/javascript" src="../bower_components/fineuploader-dist/dist/fine-uploader.min.js"></script>
        <script type="text/javascript" src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="../bower_components/bootstrap-rating/bootstrap-rating.min.js"></script>
        <script type="text/javascript" src="../bower_components/jquery.countdown/dist/jquery.countdown.min.js"></script>
        <script type="text/javascript" src="../bower_components/layzr.js/dist/layzr.min.js"></script>
        <script type="text/javascript" src="../bower_components/smoothScroll/smoothscroll.min.js"></script>
        <script type="text/javascript" src="./scripts/main.js"></script>
        <script type="text/javascript" src="./scripts/ratings.js"></script>
        <script type="text/javascript" src="./scripts/upload.js"></script>
        <script type="text/javascript" src="./scripts/moderate.js"></script>
        <!-- endbuild -->

        <?php require './php/views/analytics.php' ?>

    </body>

</html>
