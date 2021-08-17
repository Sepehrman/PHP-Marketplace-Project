<?php

include "includes/functions.php";

session_start();

if (!isset($_SESSION['userLogged'])) {
    header("Location: index.php");
    exit();
}


$jsonDownvotes = json_decode(getAllDownvotes($_SESSION['userEmail']));
if (!in_array($_GET['id'], $jsonDownvotes)) {
    $jsonDownvotes[] = $_GET['id'];
    incrementDownvoteCount($_GET['id']);
} else {
    $newArray = [];
    foreach ($jsonDownvotes as $jsonObj) {
        if ($jsonObj != $_GET['id']) {
            $newArray[] = $jsonObj;
        }
    }
    $jsonDownvotes = $newArray;
    decrementDownvoteCount($_GET['id']);
}


echo updateDownvotes($_SESSION['userEmail'], json_encode($jsonDownvotes));
//
//
header("Location: index.php");
exit();