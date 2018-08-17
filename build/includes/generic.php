<?php

//Load configuration
function config_load($config)
{
    return parse_ini_file('/var/www/betasterren.lucacastelnuovo.nl/config.ini');
}


//Log actions
function log_action($user, $action)
{
    $date = date('Y-m-d H:i:s', time());
    $remote_ip = $_SERVER['REMOTE_ADDR'];
    //TODO: make log table in db
    $query = "INSERT INTO logs (date, user, action, remote_ip) VALUES ('{$date}', '{$user}', '{$action}', '{$remote_ip}')";
    sql_query($query, false);
}

//Redirect and set alert if specified
function redirect($to, $alert = null)
{
    alert_set($alert);
    header('location: ' . $to);
    exit;
}


//ALERT SYSTEM

function alert_set($alert)
{
    $_SESSION['alert'] = $alert;
}

function alert_display()
{
    if (isset($_SESSION['alert']) && !empty($_SESSION['alert'])) {
        echo "<script>M.toast({html: '{$_SESSION['alert']}'})</script>";
        unset($_SESSION['alert']);
    }
}
