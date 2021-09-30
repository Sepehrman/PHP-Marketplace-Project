<?php

include "includes/functions.php";

session_start();

if (!isset($_SESSION['userLogged'])) {
    header("Location: index.php");
    exit();
}
$message = $_POST['message'];

$receiverEmail = findEmailGivenProduct($_POST['ID']);

sendEmail($_SESSION['userEmail'], $receiverEmail, $message);
header("Location: index.php");
exit();
