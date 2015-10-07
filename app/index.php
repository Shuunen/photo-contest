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
        <link rel="shortcut icon" href="favicon.gif" />

        <title>Photo contest 2015 | <?php print $app->version ?></title>

        <!-- build:css styles/styles.css -->
        <link href="../bower_components/bootstrap-rating/bootstrap-rating.css" rel="stylesheet">
        <link href="../bower_components/fineuploader-dist/dist/fine-uploader-gallery.min.css" rel="stylesheet">
        <link href="../bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <link href="../bower_components/smoke/dist/css/smoke.min.css" rel="stylesheet">
        <link href="../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.standalone.min.css" rel="stylesheet">
        <link href="./styles/main.css" rel="stylesheet">
        <!-- endbuild -->
    </head>

    <body class="<?php print ($app->onDesktop ? 'desktop':'mobile'); ?>">

        <?php require './php/views/main.php' ?>

        <?php require './php/views/bokeh.php' ?>

        <script>
          var voteOpenDate = "<?php print $app->startVoteDate->format('Y-m-d H:i:s') ?>";
        </script>

        <!-- build:js scripts/scripts.js -->
        <script type="text/javascript" src="../bower_components/jquery/dist/jquery.min.js"></script>
        <script type="text/javascript" src="../bower_components/fineuploader-dist/dist/fine-uploader.min.js"></script>
        <script type="text/javascript" src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="../bower_components/bootstrap-rating/bootstrap-rating.min.js"></script>
        <script type="text/javascript" src="../bower_components/jquery.countdown/dist/jquery.countdown.min.js"></script>
        <script type="text/javascript" src="../bower_components/isotope/dist/isotope.pkgd.min.js"></script>
        <script type="text/javascript" src="../bower_components/smoke/dist/js/smoke.min.js"></script>
        <script type="text/javascript" src="../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
        <!-- endbuild -->

        <!-- build:js scripts/app.js -->
        <script type="text/javascript" src="./scripts/main.js"></script>
        <!-- endbuild -->

        <?php require './php/views/analytics.php' ?>

    </body>

</html>
