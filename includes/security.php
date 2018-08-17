<?php

//Generate random string
function gen($length)
{
    if (!empty($length)) {
        $length = $length / 2;
        return bin2hex(random_bytes($length));
    } else {
        return bin2hex(random_bytes(32));
    }
}


//Clean user submitted data
function clean_data($data)
{
    $conn = sql_connect();
    $data = $conn->escape_string($data);
    sql_disconnect($conn);

    $data = trim($data);

    $data = htmlspecialchars($data);

    $data = stripslashes($data);

    return $data;
}

function login()
{
    //check i user is logged in.
}

function login_leerling()
{
    login();
}

function login_docent()
{
    login();
}
