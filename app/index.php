<?php
require "/app.php";
$app = new App();
$viewsDir = __DIR__ . '/php/views/';
// $db->createTable("photos");
// $db->insert("photos", array("id" => getGUID(), "userId" => "romain-racamier_4D3435B4-F929-5AAE-A7B4-653FD7991950", "file" => "water-801925_1920.jpg"), true);
// $db->insert("photos", array("id" => getGUID(), "userId" => "romain-racamier_4D3435B4-F929-5AAE-A7B4-653FD7991950", "file" => "workstation-405768_1920.jpg"), true);
// $db->insert("users", array("name" => "Michèl Albàn"), true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="./favicon.ico">
    <title>UXD Photoshop contest 2015</title>
    <link href="./bower_components/bootstrap-rating/bootstrap-rating.css" rel="stylesheet">
    <link href="./bower_components/animate-css/animate.min.css" rel="stylesheet">
    <link href="./bower_components/slick.js/slick/slick.css" rel="stylesheet">
    <link href="./bower_components/slick.js/slick/slick-theme.css" rel="stylesheet">
    <link href="./crappy_bower_component/fine-uploader/fine-uploader-gallery.min.css" rel="stylesheet">
    <link href="./bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="./app/styles/css/main.css" rel="stylesheet">
</head>

<body>

<div class="container">

    <?php if ($app->isLogged) : ?>
        <?php require $viewsDir . 'nav.php'; ?>
    <?php endif; ?>

    <div class="page-header">
        <h1 id="type">UXD Photoshop Contest 2015</h1>
    </div>

    <?php require $viewsDir . 'messages.php'; ?>

    <?php if (!$app->isLogged) : ?>
        <?php require $viewsDir . 'login.php'; ?>
    <?php else : ?>                
        <?php require $viewsDir . 'user.php'; ?>
    <?php endif; ?>

</div>

<!-- /container -->

<script type="text/javascript" src="./bower_components/jquery/dist/jquery.min.js"></script>
<script type="text/javascript" src="./bower_components/slick.js/slick/slick.min.js"></script>
<script type="text/javascript" src="./crappy_bower_component/fine-uploader/fine-uploader.min.js"></script>
<script type="text/javascript" src="./bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="./bower_components/bootstrap-rating/bootstrap-rating.min.js"></script>
<script type="text/javascript" src="./app/scripts/main.js"></script>

</body>
</html>


