<?php

//Load configuration
function config_load()
{
    return parse_ini_file('/var/www/config.ini');
}

function ip()
{
    return $_SERVER['REMOTE_ADDR'];
}

//Log actions
function log_action($user, $action)
{
    $date = date('Y-m-d H:i:s', time());
    $ip = ip();
    $query = "INSERT INTO logs (date, user, action, ip) VALUES ('{$date}', '{$user}', '{$action}', '{$ip}')";
    sql_query($query, false);
}

//Redirect and set alert if specified
function redirect($to, $alert = null)
{
    !isset($alert) ?: alert_set($alert);
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
