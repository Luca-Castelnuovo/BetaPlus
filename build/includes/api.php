<?php

//Send mails
function api_mail($to, $subject, $body)
{
    return api_request('POST', $GLOBALS['config']->api->mail->url, ['api_key' => $GLOBALS['config']->api->mail->key, 'to' => $to, 'subject' => $subject, 'body' => $body, 'from_name' => 'BetaSterren || HBL']);
}

//Check captcha field
function api_captcha($response_token, $redirect)
{
    $request = api_request('POST', $GLOBALS['config']->api->recaptcha->url, ['api_key' => $GLOBALS['config']->api->recaptcha->key, 'g-recaptcha-response' => $response_token]);
    if (!$request['status']) {
        log_action('api.captcha_invalid');
        redirect($redirect, 'Klik AUB op de captcha');
    } else {
        log_action('api.captcha_valid');
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
    return json_decode($result, true);
}
