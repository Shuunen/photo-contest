<?php
require "/app.php";
$app = new App();
$viewsDir = __DIR__ . '/php/views/';
// $app->db->createTable("photos");
// $app->db->insert("photos", array("id" => $app->getGUID(), "userId" => "romain-racamier_4D3435B4-F929-5AAE-A7B4-653FD7991950", "file" => "water-801925_1920.jpg"), true);
// $app->db->insert("photos", array("id" => $app->getGUID(), "userId" => "romain-racamier_4D3435B4-F929-5AAE-A7B4-653FD7991950", "file" => "workstation-405768_1920.jpg"), true);
// $app->db->insert("users", array("name" => "Michèl Albàn"), true);

//$app->db->insert("category", array("id" => "travels", "label" => "Travels"), true);
//$app->db->insert("category", array("id" => "most_creative", "label" => "Most creative"), true);
//$app->db->insert("category", array("id" => "funniest", "label" => "Funiest"), true);
//$app->db->insert("category", array("id" => "40", "label" => "40"), true);
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
        <link href="./crappy_bower_component/fine-uploader/fine-uploader-gallery.min.css" rel="stylesheet">
<!--        <link href="../bower_components/fine-uploader/_build/fine-uploader-gallery.min.css" rel="stylesheet">-->
        <link href="../bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="../bower_components/zoomwall/zoomwall.css" />
        <link href="/styles/css/main.css" rel="stylesheet">
    </head>

    <body>
        <div class="container">

            <h1 class="animated fadeInDown">UXD Photoshop Contest 2015</h1>

            <?php require $viewsDir . 'messages.php'; ?>

            <?php if (!$app->isLogged) : ?>
                <?php require $viewsDir . 'login.php'; ?>
            <?php else : ?>
                <?php require $viewsDir . 'my-photos.php'; ?>
                <?php require $viewsDir . 'upload.php'; ?>
            <?php endif; ?>

        </div>
        <!-- /container -->

        <script type="text/javascript" src="../bower_components/jquery/dist/jquery.min.js"></script>
        <script type="text/javascript" src="../bower_components/slick.js/slick/slick.min.js"></script>
        <script type="text/javascript" src="./crappy_bower_component/fine-uploader/fine-uploader.min.js"></script>
<!--        <script type="text/javascript" src="../bower_components/fine-uploader/_build/fine-uploader.min.js"></script>-->
        <script type="text/javascript" src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="../bower_components/bootstrap-rating/bootstrap-rating.min.js"></script>
        <script type="text/javascript" src="../bower_components/zoomwall/zoomwall.js"></script>
        <script type="text/javascript" src="./scripts/main.js"></script>

    </body>
</html>


