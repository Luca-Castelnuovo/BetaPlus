<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/scripts/functions.php");
check_post();
csrf_val($_POST['token']);

$user_number = escape_data($_POST['user_number'], 100, '/login/');

$result = $mysqli->query("SELECT * FROM users WHERE user_number='$user_number' OR email='$user_number'");

if ($result->num_rows == 0) {
    $_SESSION['alert'] = 'Deze leerling/docent bestaat niet of je hebt het verkeerde wachtwoord ingevuld!';
    header("location: /login/");
} else {
    $user = $result->fetch_assoc();
    $failed_login = $user['failed_login'];
    if ($failed_login <= 4) {
        if (password_verify($_POST['password'], $user['password'])) {
            $_SESSION['email'] = $user['email'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['active'] = $user['active'];
            $_SESSION['user_number'] = $user['user_number'];
            $_SESSION['user_type'] = $user['user_type'];
            $_SESSION['logged_in'] = true;
            $_SESSION['class'] = $user['class'];
            $_SESSION['ip'] = ip_rem();

            $ip = ip();
            $date = date('Y-m-d H:i:s');
            $text = $date . '	:	' . $ip . '	:	' . $user['user_number'] . PHP_EOL;
            $file = fopen("ip-login.txt", "a+");
            fwrite($file, $text);

            $mysqli->query("UPDATE users SET failed_login='0' WHERE user_number='$user_number'");
            header("location: loggedin.php");
        } else {
            $failed_login_new = ++$failed_login;
            $mysqli->query("UPDATE users SET failed_login='$failed_login_new' WHERE user_number='$user_number'");
            $_SESSION['alert'] = "Deze leerling/docent bestaat niet of je hebt het verkeerde wachtwoord ingevuld!";
            header("location: /login/");
        }
    } else {
        mail_alert($user_number . ' is geblokkeerd!');
        $_SESSION['alert'] = "Uw account is geblokeerd door te veel mislukte inlogpogingen! Contacteer AUB de administrator om uw account te deblokkeren!";
        header("location: /login/");
    }
}
