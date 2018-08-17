<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/scripts/functions.php");

check_post();

if ($_SESSION['allow_register_docent_confirm'] != 1) {
    $_SESSION['alert'] = "U mag niet registreren!";
    header("location: /login/");
    exit;
} else {
    $_SESSION['allow_register_docent_confirm'] = 0;
}

$first_name = escape_data($_POST['first_name'], 1);
$last_name = escape_data($_POST['last_name'], 2);
$email = escape_data($_POST['email_register'], 3);
$phone_number = escape_data($_POST['phone_number'], 5, null);
$class = escape_data($_POST['class'], 8);
$opleiding = escape_data($_POST['opleiding'], 7, null);
$over_mij = escape_data($_POST['over_mij'], 7, null);
$werk_ervaring = escape_data($_POST['werk_ervaring'], 7, null);
$interesses = escape_data($_POST['interesses'], 7, null);
$password = escape_data(password_hash($_POST['password'], PASSWORD_BCRYPT), 100);
$result = $mysqli->query("SELECT * FROM users WHERE email='$email'");

if ($result->num_rows > 0) {
    $_SESSION['alert'] = 'Het opgegeven e-mail adres bestaat al!';
    $_SESSION['allow_register_docent'] = 1;
    header('location: new-docent');
} else {
    if ($mysqli->query("INSERT INTO users (first_name, last_name, email, password, user_number, phone_number, class, opleiding, over_mij, werk_ervaring, interesses, user_type) VALUES ('$first_name','$last_name','$email','$password', '$email', '$phone_number', '$class', '$opleiding', '$over_mij', '$werk_ervaring', '$interesses', '1')")) {
        $subject = 'Account Verificatie ( BetaSterren )';
        $message = '<body>Beste BetaSterren docent,<br><br><a href="https://betasterren.lucacastelnuovo.nl/login/verify?email&#61;' . $email . '&hash&#61;' . $hash . '" target="_blank" rel="noopener noreferrer">Verifieer je account door op deze link te klikken.</a></body>';
        mail_send($email, $subject, $message);
        $_SESSION['alert'] = 'Registratie succesvol!';
        header("location: index");
    } else {
        $_SESSION['alert'] = 'Registratie mislukt!';
        header("location: index");
    }
}
