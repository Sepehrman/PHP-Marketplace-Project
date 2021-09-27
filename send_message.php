<?php

include "includes/functions.php";

session_start();

if (!isset($_SESSION['userLogged'])) {
    header("Location: index.php");
    exit();
}

sendEmail("Sepehrman@icloud.com", $_POST['message']);

header("Location: index.php");
exit();