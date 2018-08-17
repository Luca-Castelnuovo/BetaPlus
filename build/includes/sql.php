<?php

//Connect to database
function sql_connect()
{
    // $config = config_load();
    $config = ['db_host' => '192.168.1.7', 'db_user' => 'root', 'db_password' => 'eMMSrx8BcDmOAoivdGTiHa6tzDVjpany', 'db_name' => 'betasterren_db'];
    $conn = new mysqli($config['db_host'], $config['db_user'], $config['db_password'], $config['db_name']);

    if ($conn->connect_error) {
        action_log('SERVER', 'sql_connect_failure');
        echo '<h1>DataBase connection error.</h1><h2>Please contact administrator</h2>';
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
