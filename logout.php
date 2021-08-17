<?php

include "includes/functions.php";

session_start();


$recents = findAllRecentlyViewed($_COOKIE);


var_dump($recents);
    setcookie($recents[0] , null, -3600);

session_destroy();

header("Location: index.php");


