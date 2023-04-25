<?php

define('SALT', 'salting_for_the_sake_of_hash');
define('FILE_SIZE_LIMIT', 4000000);

$cleardbUrl = parse_url(getenv("CLEARDB_DATABASE_URL"));

define('DB_HOST',     'containers-us-west-193.railway.app');
define('DB_PORT',     '6171');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '4vsqglUNrazr8S7DF2XJ');
define('DB_DATABASE', 'railway');


use Socketlabs\SocketLabsClient;
use Socketlabs\Message\BasicMessage;
use Socketlabs\Message\EmailAddress;
include_once ("./vendor/autoload.php");


function establishDBConnection() {
    $connected = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
    if (!$connected) {
        echo mysqli_connect_error();
        exit;
    }
    return $connected;
}


function setItemInaccessible($id) {
    $connect = establishDBConnection();
    $sql = mysqli_prepare($connect, 'UPDATE products SET is_accessible = 0 WHERE id = (?)');
    mysqli_stmt_bind_param($sql, 'd', $id);
    $result = mysqli_stmt_execute($sql);
    mysqli_close($connect);
    return $result;
}


function sendEmail($emailFrom, $emailTo, $contentMessage) {

    // If user is not verified
    $serverId = 40434;
    $injectionApiKey = "Df64Ege8M2Qbk5HZw39L";

    $client = new SocketLabsClient($serverId, $injectionApiKey);
    $message = new BasicMessage();
    $message->subject = "Marketplace Messenger";
    $message->htmlBody = "<html>$contentMessage<br><br><b>Marketplace Inc.</b></html>";
    $message->plainTextBody = "This is to confirm that";
    $message->from = new EmailAddress($emailFrom);
    $message->addToAddress($emailTo);

    $response = $client->send($message);
    return $message;
}





function sendConfirmationEmail($userData) {

    // If user is not verified
    $serverId = 40434;
    $injectionApiKey = "Df64Ege8M2Qbk5HZw39L";

    $client = new SocketLabsClient($serverId, $injectionApiKey);

    $message = new BasicMessage();

    $username = findFullnameFromEmail(trim($_POST['email']), trim($_POST['password']));

    $message->subject = "Marketplace Registration Confirmation";
    $name = $_POST['first_name'];

    $message->htmlBody = "<html>Thank you for registering to Marketplace $name. This is to confirm that you have successfully registered for Marketplace and can now use what we have to offer</html>";
    $message->plainTextBody = "This is to confirm that";

    $message->from = new EmailAddress("NoReply@Marketplace.com");
    $message->addToAddress($userData['email']);

    $response = $client->send($message);
}


function searchForExistingProducts($string) {
    $link = establishDBConnection();
    $query = 'SELECT * FROM products';
    $results = mysqli_query($link, $query);
    mysqli_close($link);
    $arrResult = mysqli_fetch_array($results);

    return $arrResult;
}




function updatePins($email, $json) {
    $connect = establishDBConnection();
    $sql = mysqli_prepare($connect, "UPDATE user SET pinned = (?) WHERE email = (?)");
    mysqli_stmt_bind_param($sql, 'ss', $json, $email);
    $result = mysqli_stmt_execute($sql);
    mysqli_close($connect);
    return $result;
}



function incrementDownvoteCount($id) {
    $connect = establishDBConnection();
    $sql = mysqli_prepare($connect, 'UPDATE products SET downvotes_count = downvotes_count + 1 WHERE id = (?)');
    mysqli_stmt_bind_param($sql, 's', $id);
    $result = mysqli_stmt_execute($sql);
    mysqli_close($connect);
    return $result;
}

function decrementDownvoteCount($id) {
    $connect = establishDBConnection();
    $sql = mysqli_prepare($connect, 'UPDATE products SET downvotes_count = downvotes_count - 1 WHERE id = (?)');
    mysqli_stmt_bind_param($sql, 's', $id);
    $result = mysqli_stmt_execute($sql);
    mysqli_close($connect);
    return $result;
}


function getAllDownvotes($email) {
    $link = establishDBConnection();
    $query = 'SELECT downvotes FROM user where email = "' . $email . '"';
    $result = mysqli_query($link, $query);
    mysqli_close($link);
    return mysqli_fetch_array($result)['downvotes'];
}

function updateDownvotes($email, $json) {
    $connect = establishDBConnection();
    $sql = mysqli_prepare($connect, "UPDATE user SET downvotes = (?) WHERE email = (?)");
    mysqli_stmt_bind_param($sql, 'ss', $json, $email);
    $result = mysqli_stmt_execute($sql);
    mysqli_close($connect);
    return $result;
}



function registerUser($data) {
    $firstname = trim(ucfirst(strtolower($data['first_name'])));
    $lastname = trim(ucfirst(strtolower($data['last_name'])));
    $email = trim(ucfirst(strtolower($data['email'])));
    $password = trim($data['password']);
    $hash = md5($password . SALT);

    $verify_pass = trim($data['verify_pass']);

    $connection = establishDBConnection();
    $query = 'INSERT INTO user(firstname, lastname, email, password, pinned, downvotes) VALUES("'. $firstname . '","' . $lastname. '","' . $email . '","' . $hash. '","' . json_encode([]) . '","' . json_encode([]) . '")';
    return mysqli_query($connection, $query);
}

function setErrorCookie($message) {
    setcookie('error_message', $message, time() + (60 * 20));
}


function getAllProducts() {
    $link = establishDBConnection();
    $query = "SELECT * FROM products";
    $products = mysqli_query($link, $query);
    mysqli_close($link);
    return $products;
}




function findProductGivenID($id) {
    $link = establishDBConnection();
    $query = 'SELECT * FROM products WHERE id = "'. $id .'"';

    $results = mysqli_query($link, $query);
    return (mysqli_fetch_array($results));
}



function isValidForPosting($data) {
    if (!preg_match('/^[a-z !$.,&]+$/i', $data['description'])) {
        setErrorCookie("Description should only include '[a-z !$.,&]'");
        return false;
    }
    if (trim($data['title']) == '') {
        setErrorCookie("Your item must have a title!");
        return false;
    }
    if (trim($data['price']) == '') {
        setErrorCookie("Make sure you have a price set for your item!");
        return false;
    }
        return true;
}



function isValidFileType($file) {
    $maxUploadSize = 4 * 1024 * 1024;
    if (($file['picture']['type'] === 'image/jpeg' || $file['picture']['type'] === 'image/png') && $file['picture']['type'] < $maxUploadSize) {
        return true;
    }
    setErrorCookie("Can only have .png or .jpg files!");
    return false;
}



function insertProductOntoDB($data, $file) {
    $title = trim(ucwords($data['title']));
    $price = trim(($data['price']));
    $description = trim(ucfirst($data['description']));
    $author = $_SESSION['sessionUser'];
    $authorEmail = $_SESSION['userEmail'];

    $names = preg_split("/ /", $_SESSION['sessionUser']);
    $uniqueFileName = $names[0] . "_" . md5(microtime()) . ".png";
    $filepath = "products/" . $uniqueFileName;
    move_uploaded_file($file['picture']['tmp_name'], $filepath);


    $connection = establishDBConnection();
    $query = 'INSERT INTO products (title, price, description, picture, author, author_email, downvotes_count, time_added) VALUES("'. $title . '","' . $price. '","' . $description . '","' . $uniqueFileName . '","' . $author . '","' . $authorEmail . '","' . 0 . '","' . date("M j Y") . '")';
    return mysqli_query($connection, $query);
}



function hasValidInputsLogin($data)
{
    $email = trim(ucfirst(strtolower($data['email_login'])));
    $password = trim($data['password_login']);
    if (!preg_match("/^[a-z][a-z0-9.+]{3,}([a-z]|[0-9])+@{1}[-0-9A-Z.+_]+.[a-zA-Z]{2,4}$/i", $email)) {
        return false;
    } elseif (!preg_match('/((?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[,.\/?*!])){8}/', $password)) {
        return false;
    }
    return true;
}

    function hasValidInputsSignup($data)
    {
        $firstname = trim(ucfirst(strtolower($data['first_name'])));
        $lastname = trim(ucfirst(strtolower($data['last_name'])));
        $email = trim(ucfirst(strtolower($data['email'])));
        $password = trim($data['password']);
        $verify = trim($data['verify_pass']);

        if (!preg_match("/^[A-Z]+$/i", $firstname)) {
            setErrorCookie("Firstname should only include Letters");
            return false;
        }
        elseif (!preg_match("/^[A-Z]+$/i", $lastname)) {
            setErrorCookie("Lastname should only include Letters");
            return false;
        } elseif (!preg_match("/^[a-z][a-z0-9.+]{3,}([a-z]|[0-9])+@{1}[-0-9A-Z.+_]+.[a-zA-Z]{2,4}$/i", $email)) {
            setErrorCookie("You should use a valid Email Address!");
            return false;
        } elseif (!preg_match('/((?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[,.\/?*_!])){8}/', $password) || $password != $verify) {
            setErrorCookie("Passwords must match and have at least on of lowercase, uppercase, digit, and special characters ',.\?*!_' with a minimum length of 8");
            return false;
    }
        return true;
    }



function getAllPinned($email) {
    $link = establishDBConnection();
    $query = 'SELECT pinned FROM user where email = "' . $email . '"';
    $result = mysqli_query($link, $query);
    mysqli_close($link);
    return mysqli_fetch_array($result)['pinned'];
}


function findFullnameFromEmail($email, $pass) {
    $email = trim(ucfirst(strtolower($email)));
    $link = establishDBConnection();
    $hash = md5($pass . SALT);
    $query   = 'select (CONCAT(firstname  ,\' \',  lastname)) from user where email = "'.$email.'" and password = "'.$hash.'"';
    $results = mysqli_query($link, $query);

    return (mysqli_fetch_array($results))[0];
}

function findEmailGivenProduct($id) {
    $link = establishDBConnection();
    $query   = 'select author_email from products where id = "'.$id.'"';
    $results = mysqli_query($link, $query);
    return (mysqli_fetch_array($results))[0];
}







function deleteProductFromDB($id) {
    $connect = establishDBConnection();
    $sql = mysqli_prepare($connect, "DELETE FROM products WHERE id=(?)");
    mysqli_stmt_bind_param($sql, 'i', $id);
    $result = mysqli_stmt_execute($sql);
    mysqli_close($connect);
    return $result;
}





function findAllRecentlyViewed($cookies) {
    $recents = [];
    for ($i = 0; $i < count($cookies) ; ++$i)

        if (preg_match("/recents.*/", array_keys($_COOKIE)[$i])) {
            $recents[] = array_values($_COOKIE)[$i];
    }
    return $recents;
}


function removeAllRecentlyViewed($cookies) {
    for ($i = 0; $i < count($cookies) ; ++$i) {

        if (preg_match("/recents.*/", array_keys($_COOKIE)[$i])) {
            setcookie('recents'  . $i, null, -3600);
        }
        }

}



//function deleteProductFromDB($id, $user) {
//    $link = establishDBConnection();
////    '" AND author = "' . $user .
//    $query = 'DELETE * FROM products WHERE id="'. $id .  '")';
//    $result = mysqli_query($link, $query);
//    mysqli_close($link);
//    return $result;
//}





function findUserFromDB($email, $pass) {
    $found = false;

    $email = trim(ucfirst(strtolower($email)));
    $password = trim($pass);
    $hash = md5($pass . SALT);

    $link = establishDBConnection();

    $query   = 'select * from user where email = "'.$email.'" and password = "'.$hash.'"';
    $results = mysqli_query($link, $query);

    if (mysqli_fetch_array($results))
    {
        $found = true;
    }

    mysqli_close($link);
    return $found;
}









