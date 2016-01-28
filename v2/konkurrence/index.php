<?php

session_start();
if($_GET['facebook'] == 'true'){

    $_SESSION['facebook'] = 'true';

    header("Location: konkurrence.php");
    die;

}

header("Location: http://www.bundgaardsgarn.dk");






?>