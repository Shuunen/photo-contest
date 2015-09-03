<?php
require "./database/json-db.php";
require "./libraries/functions.php";

$db = new JsonDB("./database/");

$isUser = false;
$isAdmin = false;
$isLogged = false;
$currentUser = null;
$email = $password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = cleanInput($_POST["email"]);
    $password = cleanInput($_POST["password"]);
    $user = $db->select('users', 'email', $email);
    if (count($user) === 1) {
        $user = $user[0];
    } else {
        exit('error : multiple users share the same email');
    }
    if ($user['pass'] === $password) {
        $currentUser = $user;
        $isLogged = true;
        if ($user['email'] === 'admino') {
            $isAdmin = true;
        } else {
            $isUser = true;
        }
    }
}


// $db->createTable("users");
// $db->insert("users", array("id" => "d133-4d2053569645-a5d2-f3d68e4d6ee7", "name" => "Romùain, Raçamièr !:p"), true);

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
    <link href="./libraries/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="./libraries/animate.css/animate.min.css" rel="stylesheet">
    <link href="./styles/css/main.css" rel="stylesheet">
</head>

<body>

<div class="container">


    <?php

    /* generate id for new user */
    $users = $db->selectAll("users");
    foreach ($users as $i => $user) {
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
        <?php if (!$isLogged) : ?>
            <div class="col-xs-8 col-xs-offset-2 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 animated fadeInUp">
                <form class="form-horizontal" method="post">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Email</label>

                        <div class="col-sm-10">
                            <input type="text" name="email" class="form-control" id="inputEmail3"
                                   placeholder="Email" value="romain.racamier">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">Password</label>

                        <div class="col-sm-10">
                            <input type="password" name="password" class="form-control" id="inputPassword3"
                                   placeholder="Password" value="mypass">
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
            <div class="col-md-12 animated fadeInUp">
                Welcome <?php echo($currentUser['name']) ?>
            </div>
        <?php endif; ?>
    </div>

</div>
<!-- /container -->

<script src="./libraries/jquery/jquery.min.js"></script>
<script src="./libraries/bootstrap/js/bootstrap.min.js"></script>
<script async src='http://localhost:3000/browser-sync/browser-sync-client.2.9.1.js'></script>

</body>
</html>


