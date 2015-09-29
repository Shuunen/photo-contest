<?php

define('LAZER_DATA_PATH', realpath(dirname(__FILE__)) . '/database/Lazer/'); //Path to folder with tables

use Lazer\Classes\Database as Lazer;

use Lazer\Classes\Relation as Relation;

date_default_timezone_set("Europe/Paris");

include './php/ImageResize/ImageResize.php';

session_start();

class App {

    function __construct() {

        $this->version = 5.1;
        $this->photoPath = './photos/';
        $this->isUser = false;
        $this->isVisitor = false;
        $this->isModerator = false;
        $this->isAdmin = false;
        $this->isLogged = false;
        $this->currentUser = null;

        $this->lowerVote = "0";
        $this->higherVote = "5";

        $now = new DateTime('now');
        $this->startVoteDate = new DateTime('2015-09-26', new DateTimeZone('Pacific/Niue'));
        $this->startVoteDate->setTimezone($now->getTimezone());

        $this->endVoteDate = new DateTime('2015-10-08', new DateTimeZone('Pacific/Niue'));
        $this->endVoteDate->setTimezone($now->getTimezone());

        $this->resultsDate = new DateTime('2015-10-09');

        // vote are opened after September 25 & until October 07
        $this->voteOpened = $this->startVoteDate < $now && $now <= $this->endVoteDate;
        $this->voteEnded = $now > $this->endVoteDate;
        $this->showResults = $now > $this->resultsDate;

        // submit are opened until September 25
        $this->submitOpened = $now <= $this->startVoteDate;

        if (isset($_SESSION['user'])) {
            $this->currentUser = $_SESSION['user'];
            $this->isLogged = true;
            if (isset($this->currentUser->role)) {
                switch ($this->currentUser->role) {
                    case "admin":
                        $this->isAdmin = true;
                        break;
                    case "moderator":
                        $this->isModerator = true;
                        break;
                    case "visitor":
                        $this->isVisitor = true;
                        break;
                    default:
                        $this->isUser = true;
                }
            } else {
                $this->isUser = true;
            }
        }

        $this->onDesktop = true;
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/android|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
            $this->onDesktop = false;
        }

        $this->installDB();

        $this->handleRequest();
    }

    function getGUID() {
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45); // "-"
        $uuid = substr($charid, 0, 8) . $hyphen . substr($charid, 8, 4) . $hyphen . substr($charid, 12, 4) . $hyphen . substr($charid, 16, 4) . $hyphen . substr($charid, 20, 12);
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

        $user = Lazer::table('users')->with('rights')->where('email', '=', $email)->find();

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

    function regenThumbnails() {
        $photos = $this->getAllPhotos();

        foreach ($photos as $photo) {
            if ($photo->userid) {
                $fullPath = './photos/' . $photo->userid . '/' . $photo->filepath;
                $thumbPath = './photos/' . $photo->userid . '/thumbs/' . $photo->filepath;
                $image = new \Eventviva\ImageResize($fullPath);
                $image->crop(250, 175);
                $image->quality_jpg = 75;
                $image->save($thumbPath);
            }
        }

        $_SESSION['message'] = 'Thumbs have been regen.';
        $_SESSION['messageStatus'] = 'success';
    }

    function handleAddPhoto($request) {

        if (isset($request['photoUrl'])) {


            try {
                // create thumb
                if (!file_exists('./photos/' . $this->currentUser->userid . '/thumbs')) {
                    mkdir('./photos/' . $this->currentUser->userid . '/thumbs', 0777, TRUE);
                }

                $fullPathIn = './photos/' . $this->currentUser->userid . '/' . $request['photoUrl'];
                $thumbPathIn = './photos/' . $this->currentUser->userid . '/thumbs/' . $request['photoUrl'];

                // remove png extensions
                $request['photoUrl'] = str_replace('.png', '.jpg', $request['photoUrl']);
                $fullPathOut = str_replace('.png', '.jpg', $fullPathIn);
                $thumbPathOut = str_replace('.png', '.jpg', $thumbPathIn);

                $image = new \Eventviva\ImageResize($fullPathIn);
                $image->resizeToHeight(1080);
                $image->quality_jpg = 90;
                $image->save($fullPathOut, IMAGETYPE_JPEG);

                $image->crop(250, 175);
                $image->quality_jpg = 75;
                $image->save($thumbPathOut, IMAGETYPE_JPEG);
                //end create thumb

                // if original image is a png, delete it
                if (strpos($fullPathIn, '.png') !== false) {
                    unlink($fullPathIn);
                }

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
            setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]
            );
        }

        session_destroy();

        $_SESSION['message'] = 'User has been disconnected';
        $_SESSION['messageStatus'] = 'success';
    }

    function handleRate($request) {

        $rate = $this->storeRateToDB($request);

        $_SESSION['message'] = 'Rate ' . $request['photoId'] . ' for the category ' . $request['categoryId'] . ' with ' . $rate;
        $_SESSION['messageStatus'] = 'success';

        return array('photoid' => $request['photoId']);
    }

    function getResults() {

        $categories = $this->getCategories();

        $results = [];
        foreach ($categories as $category) {

          $catId = $category->categoryid;
          if($category->categoryid === "40"){
            $catId = "fourty";
          }
          $res = Lazer::table('results')->with('photos')->orderBy($catId, 'DESC')->findAll();
          if($res->count()>0){
            $results[$category->categoryid] = $res;
          }
        }

        return $results;
    }

    function getResultsByCategory($categoryId) {
        $photos = Lazer::table('photos')->where('status', '=', 'approved')->findAll();

        $results = [];
        foreach ($photos as $photo) {
            $photoRates = Lazer::table('rates')->where('photoid', '=', $photo->photoid)->andWhere('categoryid', '=', $categoryId)->findAll();
            $results[$photo->photoid] = 0;
            if (count($photoRates) > 0) {
                foreach ($photoRates as $photoRate) {
                    $results[$photo->photoid] += $photoRate->rate;
                }
            }
        }
        arsort($results);

        return $results;
    }

    function generateResults(){

        Lazer::table('results')->delete();

        $status = "Success";
        $_SESSION['message'] = 'Results generated';
        $_SESSION['messageStatus'] = 'success';

        try{
          $photos = Lazer::table('photos')->where('status', '=', 'approved')->findAll();

          $results = Lazer::table('results');

          foreach ($photos as $photo) {
            $this->generateResultsByPhoto($photo->photoid);
          }
        }catch (Exception $e) {
          $status = "Error : ".$e->getMessage();
          $_SESSION['messageStatus'] = 'error';
          $_SESSION['message'] = 'Results not generated';
        }

        return $status;

    }

    function generateResultsByPhoto($photoId){

        $categories = $this->getCategories();
        $results = Lazer::table('results');

        $globalResult = 0;
        $results->photoid = $photoId;
        foreach ($categories as $category) {
          $photoRates = Lazer::table('rates')->where('photoid', '=', $photoId)->andWhere('categoryid', '=', $category->categoryid)->findAll();
          $result = 0;
          if (count($photoRates) > 0) {
            foreach ($photoRates as $photoRate) {
              $result += $photoRate->rate;
            }
          }
          if($category->categoryid === "40"){
            $results->fourty = floatval($result);
          }elseif($category->categoryid === "travels"){
            $results->travels = floatval($result);
          }elseif($category->categoryid === "funniest"){
            $results->funniest = floatval($result);
          }elseif($category->categoryid === "most_creative"){
            $results->most_creative = floatval($result);
          }

          $globalResult += $result;
        }
        $results->global_results = floatval($globalResult);
        $results->save();

    }

    function getResultsByPhoto($photoId) {


        // $time_start = microtime(true);

        $res = Lazer::table('results')->where("photoid", "=", $photoId)->find();

        // die('results : '.  (microtime(true) - $time_start)*100 .' secondes<br/>'); // 17 secondes

        return $res;
    }

    function storeRateToDB($request) {

        $existingRate = Lazer::table('rates')->where('photoid', '=', $request['photoId'])->andWhere('userid', '=', $this->currentUser->userid)->andWhere('categoryid', '=', $request['categoryId'])->find();

        $newRate = $request['rate'];

        if (floatval($newRate) > floatval($this->higherVote)) {
            $newRate = $this->higherVote;
        } elseif (floatval($newRate) < floatval($this->lowerVote)) {
            $newRate = $this->lowerVote;
        }

        if ($existingRate->count() == 0) {

            $rate = Lazer::table('rates');
            $rate->photoid = $request['photoId'];
            $rate->userid = $this->currentUser->userid;
            $rate->categoryid = $request['categoryId'];
            $rate->rate = $newRate;
            $rate->save();
        } else {

            $existingRate->rate = $newRate;
            $existingRate->save();
        }

        return $newRate;
    }

    function handleModeration($request) {

        $request['newStatus'] = $request['action'] === 'approve' ? 'approved' : 'censored';

        $photo = $this->storeModerationToDB($request);

        if ($photo) {
            $_SESSION['message'] = 'This photo is now ' . $photo->status;
            $_SESSION['messageStatus'] = 'success';
            $nbPhotosToModerate = $this->getPhotosToModerate()->count();
            return array('photoid' => $photo->photoid, 'photostatus' => $photo->status, 'nbPhotosToModerate' => $nbPhotosToModerate);
        } else {
            $_SESSION['message'] = 'This photo has not been moderated';
            $_SESSION['messageStatus'] = 'danger';
        }
    }

    function storeModerationToDB($request) {

        $photo = Lazer::table('photos')->where('photoid', '=', $request['photoId'])->find();
        if (count($photo) === 1 && $request['photoId'] != "undefined") {
            $photo->status = $request['newStatus'];
            $photo->save();
            return $photo;
        } else {
            return false;
        }
    }

    function handleRemovePhoto($request) {

        $photo = $this->removePhotoFromDB($request);

        $_SESSION['messageStatus'] = 'success';

        if ($photo) {
            $path = $photo->userid . '/' . $photo->filepath;
            $thumbPath = $photo->userid . '/thumbs/' . $photo->filepath;
            unlink('./photos/' . $path);
            unlink('./photos/' . $thumbPath);
            $_SESSION['message'] = 'Photo ' . $photo->filepath . ' has been deleted';
            return array('photoid' => $photo->photoid, 'photostatus' => 'deleted');
        } else {
            $_SESSION['message'] = 'Photo has already been deleted';
        }
    }

    function removePhotoFromDB($request) {

        $photo = Lazer::table('photos')->where('photoid', '=', $request['photoId'])->find();

        if ($photo->count() === 1) {
            $photo->delete();
            return $photo;
        } else {
            return false;
        }
    }

    function handleTemplate($request) {
        if ($request['template'] === 'fullscreenPhoto') {
            $this->getFullPhotoHtmlcontent($request['photoId']);
        } else if ($request['template'] === 'main') {
            $this->getMainContent();
        } else if ($request['template'] === 'nav') {
            $this->getNavContent();
        } else if ($request['template'] === 'thumb') {
            $this->getThumbContent($request['photoid']);
        }else if ($request['template'] === 'resultsModal') {
            $this->getResultsModalContent();
        } else {
            die('This template is not handled');
        }
    }

    function handleCreateUser($request) {
        if (!isset($request['name']) || $request['name'] === "") {
            $_SESSION['message'] = 'No name set';
            $_SESSION['messageStatus'] = 'error';
        }

        if (!isset($request['email']) || $request['email'] === "") {
            $_SESSION['message'] = 'no email set';
            $_SESSION['messageStatus'] = 'error';
        }

        if (isset($request['name']) && $request['name'] != "" && isset($request['email']) && $request['email'] != "") {
            $existingUser = Lazer::table('users')->where('email', '=', $request['email'])->find();
            if (count($existingUser) == 0) {

                $user = Lazer::table('users');

                $user->userid = $this->getGUID();
                $user->name = $request['name'];
                $user->email = $request['email'];
                $user->pass = $this->randomPassword();
                $user->role = isset($request['role']) ? $request['role'] : 'user';
                $user->save();

                $_SESSION['message'] = 'New user ' . $user->name . ' with the email ' . $user->email . ' and the password : ' . $user->pass . ' has been created.';
                $_SESSION['messageStatus'] = 'success';
            } else {
                $_SESSION['message'] = 'User ' . $request['name'] . ' with the email ' . $request['email'] . ' already exists';
                $_SESSION['messageStatus'] = 'error';
            }
        }
    }

    function handleRequest() {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {

            $request = $_GET;

            if (!isset($request['type'])) {
                // if no particular type
                return;
            }

            $_SESSION['messageStatus'] = 'error';

            $data = null;

            $type = $request['type'];

            /*
             * Anonymous or logged user
             */
            if ($type === 'template') {
                $data = $this->handleTemplate($request);
            } else if (!$this->isLogged) {
                /*
                 * Anonymous user
                 */
                if ($type === 'login') {
                    $this->handleLogin($request);
                } else {
                    $_SESSION['message'] = 'This method is not handled or for logged in users only.';
                }
            } else if ($this->isLogged) {
                /*
                 * Logged user or admin
                 */
                if ($type === 'logout') {
                    $data = $this->handleLogout();
                } else if ($type === 'addPhoto') {
                    $data = $this->handleAddPhoto($request);
                } else if ($type === 'removePhoto') {
                    $data = $this->handleRemovePhoto($request);
                } else if ($this->voteOpened && $type === 'rate') {
                    $data = $this->handleRate($request);
                } else if ($this->isModerator) {
                    /*
                     * Logged moderator
                     */
                    if ($type === 'moderation') {
                        $data = $this->handleModeration($request);
                    } else if ($type === 'computeResultsForPhoto' && isset($request['photoid'])) {
                        $data = $this->generateResultsByPhoto($request['photoid']);
                    } else {
                        $_SESSION['message'] = 'This method is not handled for moderators.';
                    }

                } else if ($this->isAdmin) {
                    /*
                     * Logged admin
                     */
                     if ($type === 'createUser') {
                        $data = $this->handleCreateUser($request);
                    } else if ($type === 'computeResultsForPhoto' && isset($request['photoid'])) {
                        $data = $this->generateResultsByPhoto($request['photoid']);
                    } else if ($type === 'getAllPhotos') {
                        $photos = $this->getAllPhotos();
                        if(count($photos)>0){
                          $data = $photos->asArray();
                          $_SESSION['messageStatus'] = 'success';
                        }
                    } else if ($type === 'regenThumbnails') {
                        $data = $this->regenThumbnails();
                    } else {
                        $_SESSION['message'] = 'This method is not handled for admins.';
                    }
                } else {
                    $_SESSION['message'] = 'This method is not handled or for higher privileges users only.';
                }
            }

            if (isset($request['ajax'])) {
                // if ajax, print json and exit
                echo json_encode(array('message' => $_SESSION['message'], 'messageStatus' => $_SESSION['messageStatus'], 'data' => $data));
                $_SESSION['message'] = '';
                $_SESSION['messageStatus'] = '';
                die();
            }
        }
    }

    function getCategories() {
        return $cat = Lazer::table('categories')->findAll();
    }

    function getCategoryInfo($categoryId) {
        return $category = Lazer::table('categories')->where('categoryid', '=', $categoryId)->find();
    }

    function getAllPhotos() {
        return $photos = Lazer::table('photos')->findAll();
    }

    function getPhotosToVote() {
        return $photos = Lazer::table('photos')->where('userid', '!=', $this->currentUser->userid)->andWhere('status', '=', 'approved')->andWhere('userid', '!=', 'null')->findAll();
    }

    function getPhotosToModerate() {
        return $photos = Lazer::table('photos')->where('status', '=', 'submitted')->findAll();
    }

    function getPhotosCensored() {
        return $photos = Lazer::table('photos')->where('status', '=', 'censored')->findAll();
    }

    function getPhotoInfo($photoId) {
        return $photoInfo = Lazer::table('photos')->where('photoid', '=', $photoId)->find();
    }

    function getUserPhotos() {
        return $photos = Lazer::table('photos')->where('userid', '=', $this->currentUser->userid)->findAll();
    }

    function getUserByUserid($userid) {
        return $user = Lazer::table('users')->with('rights')->where('userid', '=', $userid)->find();
    }

    function getFullPhotoHtmlcontent($photoId) {
        $photo = Lazer::table('photos')->where('photoid', '=', $photoId)->find();
        $app = $this;
        if (count($photo) === 1) {
            require('./php/views/fullscreenPhoto.php');
            die();
        } else {
            die('No photo found with this id.');
        }
    }

    function getMainContent() {
        $app = $this;
        require('./php/views/main.php');
        die();
    }

    function getNavContent() {
        $app = $this;
        require('./php/views/nav.php');
        die();
    }

    function getResultsModalContent() {
        $app = $this;
        require('./php/views/results.php');
        die();
    }

    function getThumbContent($photoid) {
        $photos = Lazer::table('photos')->where('photoid', '=', $photoid)->findAll()->asArray();
        $app = $this;
        if (count($photos) === 1) {
            $photo = $photos[0];
            require('./php/views/gallery-thumb.php');
            die();
        } else {
            die('No photo found with this id.');
        }
    }

    function getRateForPhotoAndCategory($photoId, $categoryId) {

        $rate = Lazer::table('rates')->where('photoid', '=', $photoId)->andWhere('categoryid', '=', $categoryId)->andWhere('userid', '=', $this->currentUser->userid)->find();
        if ($rate->count() == 0) {
            return 0;
        } else {
            return $rate->rate;
        }
    }

    function getRatesCountForPhoto($photoId) {

        $rate = Lazer::table('rates')->where('photoid', '=', $photoId)->andWhere('userid', '=', $this->currentUser->userid)->findAll();
        return $rate->count();
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

            if ($users != null && count($users) > 0) {

                $user = Lazer::table('users');

                foreach ($users as $jsonUser) {


                    $user->userid = $this->getGUID();
                    $user->name = $jsonUser->name;
                    $user->email = $jsonUser->email;
                    $user->pass = $this->randomPassword();
                    $user->role = isset($jsonUser->role) ? $jsonUser->role : 'user';
                    $user->save();
                }
            }
        }

        //install rights
        try {
            \Lazer\Classes\Helpers\Validate::table('rights')->exists();
        } catch (\Lazer\Classes\LazerException $e) {
            //Database doesn't exist


            Lazer::create('rights', array(
                'role' => 'string',
                'see_login' => 'boolean',
                'see_nav' => 'boolean',
                'see_approved_photos' => 'boolean',
                'see_results' => 'boolean',
                'vote' => 'boolean',
                'see_censored' => 'boolean',
                'moderate' => 'boolean',
                'add_users' => 'boolean',
                'see_pre_results' => 'boolean',
                'delete_photo' => 'boolean',
            ));

            Relation::table('users')->belongsTo('rights')->localKey('role')->foreignKey('role')->setRelation();

            $role = Lazer::table('rights');

            $role->role = "superadmin";
            $role->see_login = true;
            $role->see_nav = true;
            $role->see_approved_photos = true;
            $role->see_results = true;
            $role->vote = true;
            $role->see_censored = true;
            $role->moderate = true;
            $role->add_users = true;
            $role->see_pre_results = true;
            $role->delete_photo = true;
            $role->save();

            $role->role = "admin";
            $role->see_login = true;
            $role->see_nav = true;
            $role->see_approved_photos = true;
            $role->see_results = true;
            $role->vote = true;
            $role->see_censored = true;
            $role->moderate = false;
            $role->add_users = true;
            $role->see_pre_results = false;
            $role->delete_photo = false;
            $role->save();

            $role->role = "moderator";
            $role->see_login = true;
            $role->see_nav = true;
            $role->see_approved_photos = true;
            $role->see_results = true;
            $role->vote = true;
            $role->see_censored = true;
            $role->moderate = true;
            $role->add_users = false;
            $role->see_pre_results = true;
            $role->delete_photo = true;
            $role->save();

            $role->role = "user";
            $role->see_login = true;
            $role->see_nav = true;
            $role->see_approved_photos = true;
            $role->see_results = true;
            $role->vote = true;
            $role->see_censored = false;
            $role->moderate = false;
            $role->add_users = false;
            $role->see_pre_results = false;
            $role->delete_photo = true;
            $role->save();

            $role->role = "visitor";
            $role->see_login = true;
            $role->see_nav = true;
            $role->see_approved_photos = true;
            $role->see_results = true;
            $role->vote = false;
            $role->see_censored = false;
            $role->moderate = false;
            $role->add_users = false;
            $role->see_pre_results = false;
            $role->delete_photo = false;
            $role->save();


            $role->role = "anonymous";
            $role->see_login = true;
            $role->see_nav = false;
            $role->see_approved_photos = false;
            $role->see_results = false;
            $role->vote = false;
            $role->see_censored = false;
            $role->moderate = false;
            $role->add_users = false;
            $role->see_pre_results = false;
            $role->delete_photo = false;
            $role->save();

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
            $category->label = 'Funniest';
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

        //install results
        try {
            \Lazer\Classes\Helpers\Validate::table('results')->exists();
        } catch (\Lazer\Classes\LazerException $e) {
            //Database doesn't exist

            Lazer::create('results', array(
                'photoid' => 'string',
                'global_results' => 'double',
                'travels' => 'double',
                'most_creative' => 'double',
                'funniest' => 'double',
                'fourty' => 'double'
            ));

            Relation::table('results')->belongsTo('photos')->localKey('photoid')->foreignKey('photoid')->setRelation();
        }
    }

}
