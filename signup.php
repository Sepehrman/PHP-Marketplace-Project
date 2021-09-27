<?php

include "includes/functions.php";


if (hasValidInputsSignup($_POST) == true) {
    session_start();
    registerUser($_POST);
    $_SESSION['userLogged'] = true;
    $username = findFullnameFromEmail(trim($_POST['email']), trim($_POST['password']));
    $_SESSION['sessionUser'] = $username;
    $_SESSION['userEmail'] = $_POST['email'];
    sendConfirmationEmail($_POST);
    header("Location: index.php");
    exit();
} else {
    header("Location: index.php");
    exit();
}


