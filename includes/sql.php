<?php

//Connect to database
function sql_connect()
{
    $conn = new mysqli($GLOBALS['config']->security->database->host, $GLOBALS['config']->security->database->user, $GLOBALS['config']->security->database->password, $GLOBALS['config']->security->database->database);

    if ($conn->connect_error) {
        echo '<h1>DataBase connection error.</h1><h2>Please contact the administrator</h2>';
        exit;
    } else {
        return $conn;
    }
}


//Close database connection
function sql_disconnect($conn)
{
    mysqli_close($conn);
}


//Execute sql query's
function sql_query($query, $assoc = true)
{
    $conn = sql_connect();

    $result = $conn->query($query);

    sql_disconnect($conn);

    if ($assoc) {
        return $result->fetch_assoc();
    } else {
        return $result;
    }
}
