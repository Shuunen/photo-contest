<?php

require "./app/database/json-db.php";

session_start();

class App {

    function __construct() {

        $this->db = new JsonDB("./app/database/");
        $this->isUser = false;
        $this->isAdmin = false;
        $this->isLogged = false;
        $this->currentUser = null;

        if (isset($_SESSION['user'])) {
            $this->currentUser = $_SESSION['user'];
            $this->isLogged = true;
            if ($this->currentUser['email'] === 'admino') {
                $this->isAdmin = true;
            } else {
                $this->isUser = true;
            }
        }

        $this->handleRequest();

        // var_dump($_SESSION);
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
            $str = str_replace((array) $replace, ' ', $str);
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

    function generateUsersIds() {
        $users = $this->db->selectAll("users");
        foreach ($users as $user) {
            if (!isset($user['id'])) {
                $user['id'] = tokenize($user['name']) . '_' . getGUID();
                $this->db->update('users', 'name', $user['name'], $user);
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
            $this->currentUser = $user;
            $this->isLogged = true;
            if ($user['email'] === 'admino') {
                $this->isAdmin = true;
            } else {
                $this->isUser = true;
            }
        } else {
            $_SESSION['message'] = 'Email or password does not match';
            $_SESSION['messageStatus'] = 'error';
        }
    }

    function handleAddPhoto($request) {

        if (isset($request['photoUrl'])) {
            $this->db->insert("photos", array("id" => $this->getGUID(), "userId" => $this->currentUser['id'], "file" => $request['photoUrl']), true);
            $_SESSION['message'] = 'Image ' . $request['photoUrl'] . ' added to db';
            $_SESSION['messageStatus'] = 'success';
        } else {
            $_SESSION['message'] = 'No photoUrl given';
            $_SESSION['messageStatus'] = 'error';
        }

    }

    function handleRate($request){

      //if($this->db->select("rates"))
      $this->db->selectMultiCond("rates", array("photoId"=>$request['photoId']), array("categoryId"=>$request['categoryId']), array("userId"=>$this->currentUser['id']));

      $this->db->insert("rates", array("photoId" => $request['photoId'], "categoryId" => $request['categoryId'], "rate" =>$request['rate'], "userId" => $this->currentUser['id']), true);
      $_SESSION['message'] = 'Rate ' . $request['photoId'] . ' for the category '. $request['categoryId'] . ' with '.$request['rate'];
      $_SESSION['messageStatus'] = 'success';


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

}
