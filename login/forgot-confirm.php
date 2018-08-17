<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/scripts/functions.php");

check_post();
csrf_val($_POST['token']);

if (captcha_validate($_POST['g-recaptcha-response'])) {
    $email = escape_data($_POST['email_reset_password'], 3, '/login/');
    $result = $mysqli->query("SELECT email,first_name,user_number,failed_login,active FROM users WHERE email='$email'");

    if ($result->num_rows == 0) {
        $_SESSION['alert'] = "Controleer uw e-mail voor een bevestigingslink om uw wachtwoordreset te voltooien!";
        header('location: index');
    } else {
        $user = $result->fetch_assoc();
        $email = $user['email'];
        $name = $user['first_name'];
        $user_number = $user['user_number'];
        $blocked = $user['failed_login'];
        $active = $user['active'];

        if ($blocked >= 4) {
            mail_alert($user_number . ' is geblokkeerd en PROBEERDE ZIJN WACHTWOORD TE RESETEN!');
            $_SESSION['alert'] = "Uw account is geblokeerd door te veel mislukte inlogpogingen! Contacteer AUB de administrator om uw account te deblokkeren!";
            header("location: index");
            exit;
        }

        if (!$active) {
            mail_alert($user_number . ' is niet actief en PROBEERDE ZIJN WACHTWOORD TE RESETEN!');
            $_SESSION['alert'] = 'Uw Account is nog niet actief. Bevestig uw e-mailadres door te klikken op de link in uw email!';
            header("location: index");
            exit;
        }

        $_SESSION['alert'] = "Controleer uw e-mail voor een bevestigingslink om uw wachtwoordreset te voltooien!";

        $code = gen(128);
        $currentDate = date("d/m/Y h:i:s");

        $mysqli->query("INSERT INTO code (code,created,valid,type,user) VALUES ('$code','$currentDate','1','pass_reset','$email')");

        $subject = 'reset wachtwoord ( BetaSterren)';
        $message = '<body>Beste ' . $name . ',<br /><br /><a href="https://betasterren.lucacastelnuovo.nl/login/reset?email&#61;' . $email . '&code&#61;' . $code . '" target="_blank" rel="noopener noreferrer">Reset je wachtwoord met deze link.</a><br /><br /><p><b>Deze link is maar 1 dag geldig!</b></p></body>';

        mail_send($email, $subject, $message);

        $ip = ip();

        $date = date('Y-m-d H:i:s');
        $text = $date . '	:	' . $ip . '	:	' . $user['user_number'] . PHP_EOL;
        $file = fopen("ip-forgot.txt", "a+");
        fwrite($file, $text);
        header('location: index');
    }
} else {
    $_SESSION['alert'] = 'Klik AUB op de captcha!';
    header("location: forgot");
    exit;
}
