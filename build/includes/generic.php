<?php

//Load configuration
function config_load()
{
    return parse_ini_file('/var/www/config.ini');
}


//Get clients ip
function ip()
{
    return $_SERVER['REMOTE_ADDR'];
}

//Get current date (and time)
function current_date($andTime = false)
{
    if ($andTime) {
        return date('Y-m-d H:i:s');
    } else {
        return date('Y-m-d');
    }
}


//Log actions
function log_action($user, $action, $priority)
{
    $date = current_date(true);
    $ip = ip();
    $query = "INSERT INTO logs (date, user, action, ip, priority) VALUES ('{$date}', '{$user}', '{$action}', '{$ip}', '{$priority}')";
    sql_query($query, false);
}


//Redirect and set alert if specified
function redirect($to, $alert = null)
{
    !isset($alert) ?: alert_set($alert);
    header('location: ' . $to);
    exit;
}


//Alert system
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
