<?php
require "./app.php";
$app = new App();
function __autoload($class_name){
    $class_name = str_replace("_", "/", $class_name);
    require('./php/Lazer-Database/src/' . $class_name . '.php');
}
// $app->generateUsersIdAndName();
// $db->createTable("photos");
// $db->insert("photos", array("id" => getGUID(), "userId" => "romain-racamier_4D3435B4-F929-5AAE-A7B4-653FD7991950", "file" => "water-801925_1920.jpg"), true);
// $db->insert("photos", array("id" => getGUID(), "userId" => "romain-racamier_4D3435B4-F929-5AAE-A7B4-653FD7991950", "file" => "workstation-405768_1920.jpg"), true);
// $db->insert("users", array("name" => "Michèl Albàn"), true);
// $app->db->insert("category", array("id" => "travels", "label" => "Travels"), true);
// $app->db->insert("category", array("id" => "most_creative", "label" => "Most creative"), true);
// $app->db->insert("category", array("id" => "funniest", "label" => "Funiest"), true);
// $app->db->insert("category", array("id" => "40", "label" => "40"), true);
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
        <?php require '/php/styles.php' ?>
    </head>

    <body>

        <?php require '/php/views/main.php' ?>
        
        <?php require '/php/scripts.php' ?>

    </body>

</html>
