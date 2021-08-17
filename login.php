<?php

include "includes/functions.php";

if (hasValidInputsLogin($_POST) && findUserFromDB(trim($_POST['email_login']), trim($_POST['password_login']))) {
    echo "Logged in";
    session_start();
    $username = findFullnameFromEmail(trim($_POST['email_login']), trim($_POST['password_login']));
    $_SESSION['sessionUser'] = $username;
    $_SESSION['userLogged'] = true;
    $_SESSION['userEmail'] = $_POST['email_login'];
    echo $_SESSION['userLogged'];
    header("Location: index.php");
    exit();
} else {
    setErrorCookie("Could not find the user in the Database!");
    header("Location: index.php");
    exit();
}