<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/scripts/functions.php");

if ($_SESSION['admin_login'] != true) {
    header("location: /login/");
    exit;
}

check_post();
csrf_val($_POST['token']);

if (captcha_validate($_POST['g-recaptcha-response'])) {
    // TODO: finish 2fa api and implement here
    $secret_key = $config['secret_key'];
    require_once($_SERVER['DOCUMENT_ROOT'] . "/scripts/authenticator.php");
    $Authenticator = new Authenticator();
    $code = escape_data($_POST['code'], 4, '/login/');
    $checkResult = $Authenticator->verifyCode($secret_key, $code, null);
    if ($checkResult) {
        session_unset();
        $_SESSION['admin'] = true;
        $_SESSION['logged_in'] = true;
        $_SESSION['user_type'] = 1;
        $_SESSION['active'] = true;
        unset($_SESSION['admin_login']);
        header("location: /login/admin/");
    } else {
        $_SESSION["alert"] = 'Incorrect 2FA code!';
        header("location: /login/admin/login");
        exit;
    }
} else {
    $_SESSION['alert'] = 'Klik AUB op de captcha!';
    header("location: /login/admin/login");
    exit;
}
