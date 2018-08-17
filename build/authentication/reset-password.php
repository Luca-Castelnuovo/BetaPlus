<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/scripts/functions.php");

check_post();
csrf_val($_POST['token']);

    if ($_POST['newpassword'] == $_POST['confirmpassword']) {
        $email = $_GET['email'];
        $password = $_GET['password'];
        $new_password = password_hash($_POST['newpassword'], PASSWORD_BCRYPT);
        $email = escape_data($_POST['email'], 3, 'reset?email=' . $email . '&code=' . $code);
        $code = escape_data($_POST['code'], 13, 'reset?email=' . $email . '&code=' . $code);
        $result = $mysqli->query("SELECT created,valid FROM code WHERE code='$code' AND used='0' AND type='pass_reset'");
        $out = $result->fetch_assoc();
        $created = $out["created"];
        $valid = $out["valid"];
        if (!($created >= $valid)) {
            $mysqli->query("UPDATE code SET type='password_reset - expired',ip='$ip' WHERE code='$code'");
            $_SESSION['alert'] = "U heeft een ongeldige URL ingevoerd of de link is niet meer geldig!";
            header("location: /login/");
            exit;
        }

        $ip = ip_rem();
        $mysqli->query("UPDATE users SET password='$new_password' WHERE email='$email'");
        $mysqli->query("UPDATE code SET used='1',ip='$ip' WHERE code='$code'");
        $_SESSION['alert'] = "Uw wachtwoord is opnieuw ingesteld!";
        header("location: /login/");
        exit;
    } else {
        $_SESSION['alert'] = "De Twee ingevoerde wachtwoorden komen niet overeen, probeer het opnieuw!";
        header("location: reset?email=$email&code=$code");
        exit;
    }
