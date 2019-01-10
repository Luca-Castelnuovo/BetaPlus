<?php

//Send mails
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function api_mail($to, $subject, $body)
{
    require($_SERVER['DOCUMENT_ROOT'] . '/libs/PHPMailer/Exception.php');
    require($_SERVER['DOCUMENT_ROOT'] . '/libs/PHPMailer/PHPMailer.php');
    require($_SERVER['DOCUMENT_ROOT'] . '/libs/PHPMailer/SMTP.php');

    //Server configuration
    $mail = new PHPMailer(true);
    $mail->SMTPDebug = 2;
    $mail->isSMTP();
    $mail->CharSet = 'UTF-8';

    $mail->Host = $GLOBALS['config']->api->mail->host;

    $mail->SMTPSecure = $GLOBALS['config']->api->mail->smtpsecure;
    $mail->Port = $GLOBALS['config']->api->mail->port;

    $mail->SMTPAuth = $GLOBALS['config']->api->mail->smtpauth;
    $mail->Username = $GLOBALS['config']->api->mail->username;
    $mail->Password = $GLOBALS['config']->api->mail->password;
    $mail->msgHTML(true);
    // $mail->SMTPDebug = 2; //for debugging purposes

    //From
    $mail->setFrom($GLOBALS['config']->api->mail->from, $GLOBALS['config']->api->mail->from_name);
    $mail->addReplyTo($GLOBALS['config']->api->mail->from, $GLOBALS['config']->api->mail->from_name);

    //To
    $mail->addAddress($to);

    //Subject and Body
    $mail->Subject = $subject;
    $mail->Body = $body;

    //Execute mail send
    if (!$mail->send()) {
        log_action('api.mail_error');
    } else {
        echo $mail->ErrorInfo;
        exit;
        log_action('api.mail_sent');
    }
}

//Check captcha field
function api_captcha($response_token, $redirect)
{
    $url = "https://www.google.com/recaptcha/api/siteverify?secret={$GLOBALS['config']->api->recaptcha->private}&response={$response_token}";
    $response = json_decode(file_get_contents($url));

    if ($response->success) {
        log_action('api.captcha_valid');
    } else {
        log_action('api.captcha_invalid');
        redirect($redirect, 'Klik AUB op de captcha');
    }
}
