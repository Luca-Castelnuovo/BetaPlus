<?php

//Load configuration
function config_load()
{
    // TODO: enable function for production
    //return parse_ini_file('/var/www/betasterren.lucacastelnuovo.nl/config.ini');
    return ['api_client_id' => 'rqc4o57337jp9d9ilueflk6rwl5s48ra', 'db_host' => '192.168.1.7', 'db_user' => 'root', 'db_password' => 'test', 'db_name' => 'betasterren_db'];
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
