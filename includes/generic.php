<?php

//Load configuration
function config_load($config)
{
    return parse_ini_file('/var/www/betasterren.lucacastelnuovo.nl/config.ini');
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


//Send mails
function mail_send($to, $subject, $body)
{
    $to = clean_data($to);
    $subject = clean_data($subject);
    $body = clean_data($body, false, true);

    $request = ['to' => $to, 'subject' => $subject, 'body' => $body, 'from_name' => 'BetaSterren || HBL'];

    request('POST', 'https://api.lucacastelnuovo.nl/mail/', $request);
}

//Make api request
function request($method, $url, $data = false)
{
    $curl = curl_init();
    switch ($method) {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);
            if ($data) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            }
            break;

        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;

        default:
            if ($data) {
                $url = sprintf("%s?%s", $url, http_build_query($data));
            }
    }

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    curl_close($curl);
    return json_decode($result, true);
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
