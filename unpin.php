<?php

include "includes/functions.php";


session_start();

if (!isset($_SESSION['userLogged'])) {
    header("Location: index.php");
    exit();
}


$jsonArr = json_decode(getAllPinned($_SESSION['userEmail']));


$newArray = [];

foreach ($jsonArr as $jsonObj) {
    if ($jsonObj != $_GET['id']) {
        $newArray[] = $jsonObj;
    }
}

var_dump($newArray);


var_dump($jsonArr);


echo updatePins($_SESSION['userEmail'], json_encode($newArray)) ? "yes" : "no";

header("Location: index.php");
exit();
