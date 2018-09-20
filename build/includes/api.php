<?php

//Send mails
function api_mail($to, $subject, $body)
{
    $config = config_load();
    return api_request('POST', 'https://api.lucacastelnuovo.nl/mail/', ['api_key' => $config['api_key_mail'], 'to' => $to, 'subject' => $subject, 'body' => $body, 'from_name' => 'BetaSterren || HBL']);
}

//Send mails
function api_captcha($response_token, $redirect)
{
    $config = config_load();
    $request = api_request('POST', 'https://api.lucacastelnuovo.nl/recaptcha/', ['api_key' => $config['api_key_recaptcha'], 'g-recaptcha-response' => $response_token]);
    if (!$request['status']) {
        $user = isset($_SESSION) ? $_SESSION['first_name'] . ' ' . $_SESSION['last_name'] : 'UNKNOWN';
        log_action($user, 'Captcha Invalid', 0);
        redirect($redirect, 'Klik AUB op de captcha');
    }
}

//Make api request
function api_request($method, $url, $data = false)
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
    echo '1';
    var_dump(json_decode($result, true));exit;
    //return json_decode($result, true);
}
