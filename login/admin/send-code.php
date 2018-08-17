<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/scripts/functions.php");

login_admin();
check_post();
csrf_val($_POST['token']);

$email = escape_data($_POST['emailcode'], 3, '/login/admin/');
$code = gen(128);
$currentDate = date("d/m/Y h:i:s");
$sql = "INSERT INTO code (code,created,valid,type,user) VALUES ('$code','$currentDate','7','register','$email')";

if ($mysqli->query($sql)) {
    $subject = 'Account Registratie ( BetaSterren )';
    $message = '<body>Beste BetaSterren leerling,<br /><br /><a href="https://betasterren.lucacastelnuovo.nl/login/verify-register?email&#61;' . $email . '&code&#61;' . $code . '" target="_blank" rel="noopener noreferrer">Je kan registreren met deze link.</a><br><p><b>Deze link is 7 dagen geldig!</b></p></body>';
    mail_send($email, $subject, $message);
    $_SESSION['alert'] = 'Verificatie link is verstuurd!';
    header("location: /login/admin/");
} else {
    $_SESSION['alert'] = 'MySql error!';
    header("location: /login/admin/");
}
