<?php

require "./database/json-db.php";

class App {

    function __construct() {
        session_start();

        $this->db = new JsonDB("./database/");
        $this->isUser = false;
        $this->isAdmin = false;
        $this->isLogged = false;
        $this->currentUser = null;
        $this->successMessage = '';
        $this->errorMessage = '';

        if(isset($_SESSION['user'])){
          $this->currentUser = $_SESSION['user'];
          $this->isLogged = true;
          if ($this->currentUser['email'] === 'admino') {
              $this->isAdmin = true;
          } else {
              $this->isUser = true;
          }

          $this->handleRequest();

        }else{
          $this->handleLogin();
        }
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

    function handleLogin() {

        $email = $password = '';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // var_dump($_POST);
            $email = $this->cleanInput($_POST["email"]);
            $password = $this->cleanInput($_POST["password"]);
            $user = $this->db->select('users', 'email', $email);
            if (count($user) === 1) {
                $user = $user[0];
            } else if (count($user) > 1) {
                $this->errorMessage = 'User ' . $email . ' has multiple instances';
            } else {
                $this->errorMessage = 'User ' . $email . ' does not exists';
            }
            if (strlen($this->errorMessage) === 0 && $user['pass'] === $password) {

              $_SESSION['user'] = $user;
                $this->currentUser = $user;
                $this->isLogged = true;
                $this->successMessage = 'Login succesfull, welcome <b>' . $this->currentUser['name'] . '</b>';
                if ($user['email'] === 'admino') {
                    $this->isAdmin = true;
                } else {
                    $this->isUser = true;
                }
            }
        }
    }

    function handleRequest(){

      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        var_dump($_POST['type']);
      }

    }

}
