<?php

require "./database/json-db.php";

define('LAZER_DATA_PATH', realpath(dirname(__FILE__)).'/database/Lazer/'); //Path to folder with tables

use Lazer\Classes\Database as Lazer;

include './php/ImageResize/ImageResize.php';

session_start();

class App {

    function __construct() {

        $this->db = new JsonDB("./database/");
        $this->isUser = false;
        $this->isAdmin = false;
        $this->isLogged = false;
        $this->currentUser = null;

        if (isset($_SESSION['user'])) {
            $this->currentUser = $_SESSION['user'];
            $this->isLogged = true;
            if (isset($this->currentUser['status']) && $this->currentUser['status'] === 'admin') {
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

    function tokenize($str, $replace = array(), $delimiter = '-') {
        if (!empty($replace)) {
            $str = str_replace((array)$replace, ' ', $str);
        }

        $clean = strtr($str, '� áâãäçèéêëìíîïñòóôõöùúûüýÿÀ�?ÂÃÄÇÈÉÊËÌ�?Î�?ÑÒÓÔÕÖÙÚÛÜ�?', 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

        return $clean;
    }

    function cleanInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    function generateUsersIdAndName() {
        $users = $this->db->selectAll("users");
        foreach ($users as $user) {
            if (!isset($user['id'])) {
                $user['id'] = $this->getGUID();
                $user['name'] = ucwords(trim(preg_replace("/\W/", ' ', $user['email'])));
                $this->db->update('users', 'email', $user['email'], $user);
            }
        }
    }

    function handleLogin($request) {

        // var_dump($request);

        if (!isset($request["email"]) || !isset($request["password"])) {
            $_SESSION['message'] = 'Missing email or password';
            $_SESSION['messageStatus'] = 'error';
            return false;
        }

        $email = $this->cleanInput($request["email"]);
        $password = $this->cleanInput($request["password"]);

        $user = $this->db->select('users', 'email', $email);

        if (count($user) === 1) {
            $user = $user[0];
            $_SESSION['messageStatus'] = 'success';
        } else if (count($user) > 1) {
            $_SESSION['message'] = 'User ' . $email . ' has multiple instances';
            $_SESSION['messageStatus'] = 'error';
        } else {
            $_SESSION['message'] = 'User ' . $email . ' does not exists';
            $_SESSION['messageStatus'] = 'error';
        }

        if ($_SESSION['messageStatus'] === 'success' && $user['pass'] === $password) {
            $_SESSION['user'] = $user;
            $_SESSION['message'] = 'Login succesfull, welcome ' . $this->currentUser['name'];
        } else {
            $_SESSION['message'] = 'Email or password does not match';
            $_SESSION['messageStatus'] = 'error';
        }
    }

    function handleAddPhoto($request) {

        if (isset($request['photoUrl'])) {


            try {
                // create thumb
                if (!file_exists('./photos/' . $this->currentUser['id'] . '/thumbs')) {
                    mkdir('./photos/' . $this->currentUser['id'] . '/thumbs', 0777, TRUE);
                }
                $image = new \Eventviva\ImageResize('./photos/' . $this->currentUser['id'] . '/' . $request['photoUrl']);
                $image->resizeToHeight(200);
                $image->save('./photos/' . $this->currentUser['id'] . '/thumbs/' . $request['photoUrl']);
                //end create thumb

                $this->storePhotoToDB($request);


                $_SESSION['message'] = 'Image ' . $request['photoUrl'] . ' added to db and thumbnail created';
                $_SESSION['messageStatus'] = 'success';

            } catch (Exception $e) {
                var_dump($e);
                exit();
                $_SESSION['message'] = 'Fail to create thumbnail for Image ' . $request['photoUrl'];
                $_SESSION['messageStatus'] = 'error';
            }

        } else {
            $_SESSION['message'] = 'No photoUrl given';
            $_SESSION['messageStatus'] = 'error';
        }
    }

    function storePhotoToDB($request){

      //json-db
      $this->db->insert("photos", array("id" => $this->getGUID(), "userId" => $this->currentUser['id'], "file" => $request['photoUrl']), true);

      //Lazer
      $photo = Lazer::table('photos');

      $photo->photoid = $this->getGUID();
      $photo->userid = $this->currentUser['id'];
      $photo->filepath = $request['photoUrl'];
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

    function handleRate($request){

      $this->storeRateToDB($request);

      $_SESSION['message'] = 'Rate ' . $request['photoId'] . ' for the category '. $request['categoryId'] . ' with '.$request['rate'];
      $_SESSION['messageStatus'] = 'success';

    }

    function storeRateToDB($request){

      //json-db
      $this->db->insert("rates", array("photoId" => $request['photoId'], "categoryId" => $request['categoryId'], "rate" =>$request['rate'], "userId" => $this->currentUser['id']), true);

      //Lazer
      $existingRate = Lazer::table('rates')->where('photoid', '=', $request['photoId'])->andWhere('userid', '=', $this->currentUser['id'])->andWhere('categoryid', '=', $request['categoryId'])->find();

      if($existingRate->count() == 0){
        $rate = Lazer::table('rates');

        $rate->photoid = $request['photoId'];
        $rate->userid = $this->currentUser['id'];
        $rate->categoryid = $request['categoryId'];
        $rate->rate = $request['rate'];
        $rate->save();
      }else{

        $existingRate->rate = $request['rate'];
        $existingRate->save();
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
            } else if ($type === 'rate') {
                $this->handleRate($request);
            } else if ($type === 'approval') {
                $this->handleApproval($request);
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

    function installDB(){

      //install users
        try{
            \Lazer\Classes\Helpers\Validate::table('users')->exists();
        } catch(\Lazer\Classes\LazerException $e){
            //Database doesn't exist

            Lazer::create('users', array(
                'userid' => 'string',
                'name' => 'string',
                'email' => 'string',
                'pass' => 'string',
                'role' => 'string',
            ));

            $user = Lazer::table('users');

            $user->userid = $this->getGUID();
            $user->name = 'Romain Racamier';
            $user->email = 'romain.racamier';
            $user->pass = 'mypass';
            $user->role = 'admin';
            $user->save();

            $user->userid = $this->getGUID();
            $user->name = 'Romain Racamier';
            $user->email = 'michel.alban';
            $user->pass = 'albanPass';
            $user->role = 'user';
            $user->save();
        }

      //install categories
        try{
            \Lazer\Classes\Helpers\Validate::table('categories')->exists();
        } catch(\Lazer\Classes\LazerException $e){
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
        try{
            \Lazer\Classes\Helpers\Validate::table('photos')->exists();
        } catch(\Lazer\Classes\LazerException $e){
            //Database doesn't exist

            Lazer::create('photos', array(
                'photoid' => 'string',
                'userid' => 'string',
                'filepath' => 'string',
            ));


        }

      //install rates
        try{
            \Lazer\Classes\Helpers\Validate::table('rates')->exists();
        } catch(\Lazer\Classes\LazerException $e){
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
