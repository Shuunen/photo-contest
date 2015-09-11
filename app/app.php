<?php
define('LAZER_DATA_PATH', realpath(dirname(__FILE__)) . '/database/Lazer/'); //Path to folder with tables
use Lazer\Classes\Database as Lazer;

date_default_timezone_set("Europe/Paris");

include './php/ImageResize/ImageResize.php';

session_start();

class App {

    function __construct() {

        $this->isUser = false;
        $this->isAdmin = false;
        $this->isLogged = false;
        $this->currentUser = null;

        // vote are opened after September 25 & until October 25
        $this->voteOpened = new DateTime('2015-09-25') < new DateTime("now") && new DateTime("now") <= new DateTime('2015-10-25');

        // submit are opened until September 25
        $this->submitOpened = new DateTime("now") <= new DateTime('2015-09-25');

        if (isset($_SESSION['user'])) {
            $this->currentUser = $_SESSION['user'];
            $this->isLogged = true;
            if (isset($this->currentUser->role) && $this->currentUser->role === 'admin') {
                $this->isAdmin = true;
            } else {
                $this->isUser = true;
            }
        }

        $this->installDB();

        $this->handleRequest();
    }

    function getGUID() {
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45); // "-"
        $uuid = substr($charid, 0, 8) . $hyphen
            . substr($charid, 8, 4) . $hyphen
            . substr($charid, 12, 4) . $hyphen
            . substr($charid, 16, 4) . $hyphen
            . substr($charid, 20, 12);
        return $uuid;
    }

    function cleanInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    function handleLogin($request) {

        // var_dump($request);

        if (!isset($request["email"]) || !isset($request["password"])) {
            $_SESSION['message'] = 'Missing email or password';
            $_SESSION['messageStatus'] = 'error';
            return false;
        }

        $email = $request["email"];
        $password = $request["password"];

        $user = Lazer::table('users')->where('email', '=', $email)->find();

        if (count($user) === 1) {
            $_SESSION['messageStatus'] = 'success';
        } else if (count($user) > 1) {
            $_SESSION['message'] = 'User ' . $email . ' has multiple instances';
            $_SESSION['messageStatus'] = 'error';
        } else {
            $_SESSION['message'] = 'User ' . $email . ' does not exists';
            $_SESSION['messageStatus'] = 'error';
        }

        if ($_SESSION['messageStatus'] === 'success' && $user->pass === $password) {
            $_SESSION['user'] = $user;
            $_SESSION['message'] = 'Login succesfull, welcome ' . $user->name;
        } else {
            $_SESSION['message'] = 'Email or password does not match';
            $_SESSION['messageStatus'] = 'error';
        }
    }

    function handleAddPhoto($request) {

        if (isset($request['photoUrl'])) {


            try {
                // create thumb
                if (!file_exists('./photos/' . $this->currentUser->userid . '/thumbs')) {
                    mkdir('./photos/' . $this->currentUser->userid . '/thumbs', 0777, TRUE);
                }
                $image = new \Eventviva\ImageResize('./photos/' . $this->currentUser->userid . '/' . $request['photoUrl']);
                $image->resizeToHeight(1080);
                $image->save('./photos/' . $this->currentUser->userid . '/' . $request['photoUrl'],null,90);

                $image->resizeToHeight(200);
                $image->save('./photos/' . $this->currentUser->userid . '/thumbs/' . $request['photoUrl']);
                //end create thumb

                try {
                    $this->storePhotoToDB($request);
                } catch (Exception $e) {
                    //var_dump($e);

                    $_SESSION['message'] = 'Fail to store photo ' . $request['photoUrl'] . ' into the database. : ' . $e->getMessage();
                    $_SESSION['messageStatus'] = 'error';
                    exit();
                }


                $_SESSION['message'] = 'Image ' . $request['photoUrl'] . ' added to db and thumbnail created';
                $_SESSION['messageStatus'] = 'success';

            } catch (Exception $e) {
                // var_dump($e);
                // exit();
                $_SESSION['message'] = 'Fail to create thumbnail for Image ' . $request['photoUrl'];
                $_SESSION['messageStatus'] = 'error';
            }

        } else {
            $_SESSION['message'] = 'No photoUrl given';
            $_SESSION['messageStatus'] = 'error';
        }
    }

    function storePhotoToDB($request) {

        $photo = Lazer::table('photos');

        $photo->photoid = $this->getGUID();
        $photo->userid = $this->currentUser->userid;
        $photo->filepath = $request['photoUrl'];
        $photo->status = 'submitted';
        $photo->save();

    }

    function handleLogout() {

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();

        $_SESSION['message'] = 'User has been disconnected';
        $_SESSION['messageStatus'] = 'success';
    }

    function handleRate($request) {

        $this->storeRateToDB($request);

        $_SESSION['message'] = 'Rate ' . $request['photoId'] . ' for the category ' . $request['categoryId'] . ' with ' . $request['rate'];
        $_SESSION['messageStatus'] = 'success';

    }

    function storeRateToDB($request) {

        $existingRate = Lazer::table('rates')->where('photoid', '=', $request['photoId'])->andWhere('userid', '=', $this->currentUser->userid)->andWhere('categoryid', '=', $request['categoryId'])->find();

        if ($existingRate->count() == 0) {

            $rate = Lazer::table('rates');
            $rate->photoid = $request['photoId'];
            $rate->userid = $this->currentUser->userid;
            $rate->categoryid = $request['categoryId'];
            $rate->rate = $request['rate'];
            $rate->save();

        } else {

            $existingRate->rate = $request['rate'];
            $existingRate->save();
        }

    }

    function handleModeration($request) {

        $request['newStatus'] = $request['action'] === 'approve' ? 'approved' : 'censored';

        $this->storeModerationToDB($request);

        $_SESSION['message'] = 'This photo is now ' . $request['newStatus'];
        $_SESSION['messageStatus'] = 'success';
    }

    function storeModerationToDB($request) {

        $photo = Lazer::table('photos')->where('id', '=', $request['photoId'])->find();
        $photo->status = $request['newStatus'];
        $photo->save();
    }

    function handleRemovePhoto($request) {

        $photo = $this->removePhotoFromDB($request);

        if ($photo) {

            $path = $photo->userid . '/' . $photo->filepath;
            $thumbPath = $photo->userid . '/thumbs/' . $photo->filepath;
            unlink('./photos/' . $path);
            unlink('./photos/' . $thumbPath);
            $_SESSION['message'] = 'Photo ' . $photo->filepath . ' has been deleted';

        } else {

            $_SESSION['message'] = 'Photo has already been deleted';
        }

        $_SESSION['messageStatus'] = 'success';
    }

    function removePhotoFromDB($request) {

        $photo = Lazer::table('photos')->where('id', '=', $request['photoId'])->find();

        if ($photo->count() === 1) {
            $photo->delete();
            return $photo;
        } else {
            return false;
        }


    }

    function handleRequest() {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {

            $request = $_GET;

            if (!isset($request['type'])) {
                // if no particular type
                return;
            }

            $type = $request['type'];
            if ($type === 'login') {
                $this->handleLogin($request);
            } else if ($type === 'logout') {
                $this->handleLogout();
            } else if ($type === 'addPhoto') {
                $this->handleAddPhoto($request);
            } else if ($type === 'removePhoto') {
                $this->handleRemovePhoto($request); // TODO
            } else if ($type === 'rate') {
                $this->handleRate($request);
            } else if ($type === 'moderation') {
                $this->handleModeration($request);
            } else {
                $_SESSION['message'] = 'These request is not allowed';
                $_SESSION['messageStatus'] = 'error';
            }

            if (isset($request['ajax'])) {
                // if ajax, print json and exit
                echo json_encode(array('message' => $_SESSION['message'], 'messageStatus' => $_SESSION['messageStatus']), JSON_FORCE_OBJECT);
                die();
            }
        }
    }

    function getCategories() {
        return $cat = Lazer::table('categories')->findAll();
    }

    function getPhotosToVote() {
        return $photos = Lazer::table('photos')->where('userid', '!=', $this->currentUser->userid)->where('status', '=', 'approved')->findAll();
    }

    function getPhotosToModerate() {
        return $photos = Lazer::table('photos')->where('status', '=', 'submitted')->findAll();
    }

    function getUserPhotos() {
        return $photos = Lazer::table('photos')->where('userid', '=', $this->currentUser->userid)->findAll();
    }


    function getRateForPhotoAndCategory($photoId, $categoryId) {

        $rate = Lazer::table('rates')->where('photoid', '=', $photoId)->where('categoryid', '=', $categoryId)->where('userid', '=', $this->currentUser->userid)->find();
        if ($rate->count() == 0) {
            return 0;
        } else {
            return $rate->rate;
        }

    }

    function randomPassword() {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789&!#";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    function installDB() {

        //install users
        try {
            \Lazer\Classes\Helpers\Validate::table('users')->exists();
        } catch (\Lazer\Classes\LazerException $e) {
            //Database doesn't exist


            Lazer::create('users', array(
                'userid' => 'string',
                'name' => 'string',
                'email' => 'string',
                'pass' => 'string',
                'role' => 'string',
            ));

            $users = json_decode(file_get_contents('../users.json'));

            if($users!= null && count($users)>0){

              $user = Lazer::table('users');

              foreach($users as $jsonUser){


                $user->userid = $this->getGUID();
                $user->name = $jsonUser->name;
                $user->email = $jsonUser->email;
                $user->pass = $this->randomPassword();
                $user->role = isset($jsonUser->role)? $jsonUser->role : 'user';
                $user->save();
              }
            }
        }

        //install categories
        try {
            \Lazer\Classes\Helpers\Validate::table('categories')->exists();
        } catch (\Lazer\Classes\LazerException $e) {
            //Database doesn't exist

            Lazer::create('categories', array(
                'categoryid' => 'string',
                'label' => 'string',
            ));

            $category = Lazer::table('categories');

            $category->categoryid = "travels";
            $category->label = 'Travels';
            $category->save();

            $category->categoryid = "most_creative";
            $category->label = 'Most creative';
            $category->save();

            $category->categoryid = "funniest";
            $category->label = 'Funiest';
            $category->save();

            $category->categoryid = "40";
            $category->label = '40';
            $category->save();

        }

        //install photos
        try {
            \Lazer\Classes\Helpers\Validate::table('photos')->exists();
        } catch (\Lazer\Classes\LazerException $e) {
            //Database doesn't exist

            Lazer::create('photos', array(
                'photoid' => 'string',
                'userid' => 'string',
                'filepath' => 'string',
                'status' => 'string'
            ));


        }

        //install rates
        try {
            \Lazer\Classes\Helpers\Validate::table('rates')->exists();
        } catch (\Lazer\Classes\LazerException $e) {
            //Database doesn't exist

            Lazer::create('rates', array(
                'photoid' => 'string',
                'userid' => 'string',
                'categoryid' => 'string',
                'rate' => 'string',
            ));


        }

    }

}
