<?php

csrf_val($CSRFtoken);


$username = clean_data($_POST['username']);
$password = clean_data($_POST['password']);

$query =
    "SELECT
        id,
        class
    FROM
        docenten
    WHERE
        email='{$username}'";

$query =
    "SELECT
        id,
        class,
        admin
    FROM
        leerlingen
    WHERE
        email='{$username}' OR leerling_nummer='{$username}'";

$user = sql_query($query, true);

//check password

//check if user is active


$_SESSION['logged_in'] = true;
$_SESSION['id'] = $user['id'];
$_SESSION['class'] = $user[''];

//leerlingen
$_SESSION['admin'] = $user[''];
