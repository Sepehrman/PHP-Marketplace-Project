<?php

include "includes/functions.php";

session_start();

if (!isset($_SESSION['userLogged'])) {
    header("Location: index.php");
    exit();
}
//$prodId =  $_POST['prodID'];
$message = $_POST['message'];



$receiverEmail = findEmailGivenProduct($_POST['ID']);

echo "from " . $_SESSION['userEmail'];
echo "to " . $_POST['ID'];
echo "message " . $message;

echo var_dump(sendEmail($_SESSION['userEmail'], $receiverEmail, $message));

//header("Location: index.php");
//exit();
