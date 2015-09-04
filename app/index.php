<?php

var_dump($_POST);

require "./php/app.php";
$app = new App();
// $db->createTable("photos");
// $db->insert("photos", array("id" => getGUID(), "userId" => "romain-racamier_4D3435B4-F929-5AAE-A7B4-653FD7991950", "file" => "water-801925_1920.jpg"), true);
// $db->insert("photos", array("id" => getGUID(), "userId" => "romain-racamier_4D3435B4-F929-5AAE-A7B4-653FD7991950", "file" => "workstation-405768_1920.jpg"), true);
// $db->insert("users", array("name" => "Michèl Albàn"), true);
?>
<!DOCTYPE html>
<html lang="en" class="<?php echo($app->isLogged ? '' : 'login') ?>">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="./favicon.ico">
        <title>UXD Photoshop contest 2015</title>
        <link href="/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="../bower_components/bootstrap-rating/bootstrap-rating.css" rel="stylesheet">
        <link href="../bower_components/animate-css/animate.min.css" rel="stylesheet">
        <link href="../bower_components/slick.js/slick/slick.css" rel="stylesheet">
        <link href="../bower_components/slick.js/slick/slick-theme.css" rel="stylesheet">
        <link href="./styles/css/main.css" rel="stylesheet">
    </head>

    <body>
            
        <div class="container">

            <h1 class="animated fadeInDown">UXD Photoshop Contest 2017</h1>

            <?php require './php/views/messages.php'; ?>

            <?php if (!$app->isLogged) : ?>
                <?php require './php/views/login.php'; ?>
            <?php else : ?>
                <?php require './php/views/my-photos.php'; ?>
            <?php endif; ?>

        </div>
        <!-- /container -->

        <script type="text/javascript" src="../bower_components/jquery/dist/jquery.min.js"></script>
        <script type="text/javascript" src="../bower_components/slick.js/slick/slick.min.js"></script>
        <script type="text/javascript" src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="../bower_components/bootstrap-rating/bootstrap-rating.min.js"></script>
        <script type="text/javascript" src="./scripts/main.js"></script>

    </body>
</html>


