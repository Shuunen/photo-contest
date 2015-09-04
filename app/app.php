<?php

require "./database/json-db.php";

session_start();

class App {

    function __construct() {
        
        $this->db = new JsonDB("./database/");
        $this->isUser = false;
        $this->isAdmin = false;
        $this->isLogged = false;
        $this->currentUser = null;
        $this->message = '';
        $this->status = '';

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
            $this->message = 'Missing email or password';
            $this->status = 'error';
            return false;
        }

        $email = $this->cleanInput($request["email"]);
        $password = $this->cleanInput($request["password"]);

        $user = $this->db->select('users', 'email', $email);

        if (count($user) === 1) {
            $user = $user[0];
            $this->status = 'success';
        } else if (count($user) > 1) {
            $this->message = 'User ' . $email . ' has multiple instances';
            $this->status = 'error';
        } else {
            $this->message = 'User ' . $email . ' does not exists';
            $this->status = 'error';
        }

        if ($this->status === 'success' && $user['pass'] === $password) {
            $_SESSION['user'] = $user;
            $this->currentUser = $user;
            $this->isLogged = true;
            $this->message = 'Login succesfull, welcome ' . $this->currentUser['name'];
            if ($user['email'] === 'admino') {
                $this->isAdmin = true;
            } else {
                $this->isUser = true;
            }
        } else {
            $this->message = 'Password does not match';
            $this->status = 'error';
        }
    }

    function handleRequest() {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $request = $_GET;
            if (!isset($request['type'])) {
                // if no type, no ajax call
                return;
            }
            $type = $request['type'];
            if ($type === 'login') {
                $this->handleLogin($request);
            } else if ($type === 'vote') {
                $this->handleVote($request);
            } else if ($type === 'approval') {
                $this->handleApproval($request);
            } else {
                $this->message = 'These request is not allowed';
                $this->status = 'error';
            }
            echo json_encode(array('message' => $this->message, 'status' => $this->status), JSON_FORCE_OBJECT);
            exit();
        }
    }

}
