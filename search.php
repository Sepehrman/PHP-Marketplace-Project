<?php

//echo $_POST;

include "includes/functions.php";

var_dump($_POST);
$search = trim(strtolower($_POST['searchable']));


if (isset($_POST['searchable']) && $search != '') {
    $out = preg_replace('/\s+/', '+', $search);
    header('Location: index.php?search='.$out);
    exit();
} else {
    setErrorCookie("Cannot process an empty string to search for!");
    header('Location: index.php');
    exit();
}




