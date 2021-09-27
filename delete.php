<?php

include "includes/functions.php";

session_start();




if (isset($_SESSION['userLogged'])) {
    unlink('products/'. $_GET['filename']);
    deleteProductFromDB($_GET['id']);
}

setcookie('recents' . $_GET['id'], null, -3600);

header("Location: index.php");
exit();

