<?php

include "includes/functions.php";

session_start();

if (!isset($_SESSION['userLogged'])) {
    header("Location: index.php");
    exit();
}


$jsonArr = json_decode(getAllPinned($_SESSION['userEmail']));
if (!in_array($_GET['id'], $jsonArr)) {
    $jsonArr[] = $_GET['id'];
}

updatePins($_SESSION['userEmail'], json_encode($jsonArr));
header("Location: index.php");
exit();
