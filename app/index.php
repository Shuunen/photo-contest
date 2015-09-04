<?php
require "./database/json-db.php";
require "./php/functions.php";

$db = new JsonDB("./database/");

$isUser = false;
$isAdmin = false;
$isLogged = false;
$currentUser = null;
$errorMessage = '';
$successMessage = '';
$email = $password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = cleanInput($_POST["email"]);
    $password = cleanInput($_POST["password"]);
    $user = $db->select('users', 'email', $email);
    if (count($user) === 1) {
        $user = $user[0];
    } else if (count($user) > 1) {
        $errorMessage = 'User ' . $email . ' has multiple instances';
    } else {
        $errorMessage = 'User ' . $email . ' does not exists';
    }
    if (strlen($errorMessage) === 0 && $user['pass'] === $password) {
        $currentUser = $user;
        $isLogged = true;
        $successMessage = 'Login succesfull, welcome <b>' . $currentUser['name'] . '</b>';
        if ($user['email'] === 'admino') {
            $isAdmin = true;
        } else {
            $isUser = true;
        }
    }
}


// $db->createTable("photos");
// $db->insert("photos", array("id" => getGUID(), "userId" => "romain-racamier_4D3435B4-F929-5AAE-A7B4-653FD7991950", "file" => "water-801925_1920.jpg"), true);
// $db->insert("photos", array("id" => getGUID(), "userId" => "romain-racamier_4D3435B4-F929-5AAE-A7B4-653FD7991950", "file" => "workstation-405768_1920.jpg"), true);

// $db->insert("users", array("name" => "Michèl Albàn"), true);

?>
<!DOCTYPE html>
<html lang="en" class="<?php echo($isLogged ? '' : 'login') ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="./favicon.ico">
    <title>UXD Photoshop contest 2015</title>
    <link href="../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../bower_components/bootstrap-rating/bootstrap-rating.css" rel="stylesheet">
    <link href="../bower_components/animate-css/animate.min.css" rel="stylesheet">
    <link href="../bower_components/slick.js/slick/slick.css" rel="stylesheet">
    <link href="../bower_components/slick.js/slick/slick-theme.css" rel="stylesheet">
    <link href="./styles/css/main.css" rel="stylesheet">
</head>

<body>

<div class="container">


    <?php

    /* generate id for new user */
    $users = $db->selectAll("users");
    foreach ($users as $user) {
        if (!isset($user['id'])) {
            $user['id'] = tokenize($user['name']) . '_' . getGUID();
            $db->update('users', 'name', $user['name'], $user);
        }
    }

    ?>


    <div class="row hero">

        <div class="col-md-12">
            <h1 class="animated fadeInDown">UXD Photoshop Contest 2015</h1>
        </div>

        <?php if (strlen($successMessage) > 0) : ?>
            <div class="col-xs-8 col-xs-offset-2 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 animated fadeIn">
                <div class="alert alert-success" role="alert"><?php echo $successMessage ?></div>
            </div>
        <?php endif; ?>

        <?php if (strlen($errorMessage) > 0) : ?>
            <div class="col-xs-8 col-xs-offset-2 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 animated fadeIn">
                <div class="alert alert-danger" role="alert"><?php echo $errorMessage ?></div>
            </div>
        <?php endif; ?>

        <?php if (!$isLogged) : ?>
            <div class="col-xs-8 col-xs-offset-2 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 animated fadeInUp">
                <form class="form-horizontal" method="post">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Email</label>

                        <div class="col-sm-10">
                            <input type="text" name="email" class="form-control" id="inputEmail3"
                                   placeholder="Email" value="michel.alban@amdocs.com">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">Password</label>

                        <div class="col-sm-10">
                            <input type="password" name="password" class="form-control" id="inputPassword3"
                                   placeholder="Password" value="albanPass">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-default">Sign in</button>
                        </div>
                    </div>
                </form>
            </div>
        <?php else : ?>
            <div class="col-xs-10 col-xs-offset-1 col-md-8 col-md-offset-2 animated fadeInLeft">
                <h2>My gallery</h2>
            </div>
            <div class="col-xs-10 col-xs-offset-1 col-md-8 col-md-offset-2 animated fadeInUp">
                <?php $photos = $db->select("photos", "userId", $currentUser['id']); ?>
                <?php if (count($photos)) : ?>
                    <div class="gallery">
                        <?php foreach ($photos as $photo) : ?>
                            <div class="item">
                                <img src="./photos/<?php echo $currentUser['id'] . '/' . $photo['file'] ?>">
                                <div>
                                  <input type="hidden" class="rating-tooltip-manual" data-filled="glyphicon glyphicon-star" data-filled-selected="glyphicon glyphicon-star" data-empty="glyphicon glyphicon-star-empty">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <b>You do not have any photos actually, you should add some.</b>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

</div>
<!-- /container -->

<script type="text/javascript" src="../bower_components/jquery/dist/jquery.min.js"></script>
<script type="text/javascript" src="../bower_components/slick.js/slick/slick.min.js"></script>
<script type="text/javascript" src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="../bower_components/bootstrap-rating/bootstrap-rating.min.js"></script>
<!--<script type="text/javascript" async src='http://localhost:3000/browser-sync/browser-sync-client.2.9.1.js'></script>-->
<script type="text/javascript" src="./scripts/main.js"></script>

</body>
</html>


