<?php

include "includes/functions.php";


session_start();

if ($_SESSION['userLogged'] && isValidFileType($_FILES) && isValidForPosting($_POST)) {
    insertProductOntoDB($_POST, $_FILES);
}
header("Location: index.php");
exit();
