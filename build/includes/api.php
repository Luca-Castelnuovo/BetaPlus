<?php

//Send mails
function api_mail($to, $subject, $body)
{
    $request = ['to' => $to, 'subject' => $subject, 'body' => $body, 'from_name' => 'BetaSterren || HBL'];
    api_request('POST', 'https://api.lucacastelnuovo.nl/mail/', $request);
}

//Send mails
function api_captcha($response_token)
{
    if (!api_request('POST', 'https://api.lucacastelnuovo.nl/recaptch/', ['g-recaptcha-response' => $response_token])['status']) {
        log_action('unknown', 'captcha_invalid');
        redirect('', 'Please complete captcha');
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
                $config = config_load();
                $data['api_key'] = $config['api_key'];
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
