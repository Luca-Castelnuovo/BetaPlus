<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/scripts/functions.php");
check_post();
csrf_val($_POST['token']);

if ($_SESSION['allow_register_confirm'] != 1) {
    $_SESSION['alert'] = "Deze link is reeds gebruikt of de URL is ongeldig!";
    header("location: /login/");
    exit;
} else {
    $_SESSION['allow_register_confirm'] = 0;
}

$first_name = escape_data($_POST['first_name'], 1, '/login/');
$last_name = escape_data($_POST['last_name'], 2, '/login/');
$email = escape_data($_POST['email'], 3, '/login/');
$mentorbeta = escape_data($_POST['mentor-beta'], 8, null);
$mentorelse = escape_data($_POST['mentor-else'], 8, null);
$phone_number = escape_data($_POST['phone_number'], 5, null);
$class = escape_data($_POST['klas'], 6, '/login/');
$user_number = escape_data($_POST['user_number'], 4, '/login/');
$opleiding = escape_data($_POST['opleiding'], 7, null);
$over_mij = escape_data($_POST['over_mij'], 7, null);
$werk_ervaring = escape_data($_POST['werk_ervaring'], 7, null);
$interesses = escape_data($_POST['interesses'], 7, null);
$password = escape_data(password_hash($_POST['password'], PASSWORD_BCRYPT), 100, '/login/');
$password2 = escape_data(password_hash($_POST['password2'], PASSWORD_BCRYPT), 100, '/login/');

if ($password != $password2) {
    $_SESSION['alert'] = 'De 2 wachtwoorden komen niet overheen!';
    $_SESSION['allow_register'] = 1;
    header("location: new-user");
}

switch ($mentorbeta) {
    case 'else':
        $mentorsql = $mentorelse;
        break;

    default:
        $mentorsql = $mentorbeta;
}

$result = $mysqli->query("SELECT * FROM users WHERE email='$email'");
if ($result->num_rows > 0) {
    $_SESSION['alert'] = 'Het opgegeven e-mail adres is al geregistreerd!';
    $_SESSION['allow_register'] = 1;
    header("location: new-user");
} else {
    $result = $mysqli->query("SELECT * FROM users WHERE user_number='$user_number'");
    if ($result->num_rows > 0) {
        $_SESSION['alert'] = 'Het opgegeven leerling nummer is al geregistreerd!';
        $_SESSION['allow_register'] = 1;
        header("location: new-user");
    } else {
        $sql = "INSERT INTO users (first_name, last_name, email, password, user_number, phone_number, class, opleiding, over_mij, werk_ervaring, interesses, mentor) VALUES ('$first_name','$last_name','$email','$password', '$user_number', '$phone_number', '$class', '$opleiding', '$over_mij', '$werk_ervaring', '$interesses', '$mentorsql')";
        if ($mysqli->query($sql)) {
            $code = gen(128);
            $currentDate = date("d/m/Y h:i:s");
            $sql = "INSERT INTO code (code,created,valid,type,user) VALUES ('$code','$currentDate','7','verify-register','$email')";
            $mysqli->query($sql);

            $subject = 'Account Verificatie ( BetaSterren )';
            $message = '<body>Beste BetaSterren leerling,<br><br><a href="https://betasterren.hetbaarnschlyceum.nl/login/verify?email&#61;' . $email . '&code&#61;' . $code . '" target="_blank" rel="noopener noreferrer">Verifieer je account door op deze link te klikken.</a><br><br><p><b>Deze link is 7 dagen geldig.</b></p></body>';
            mail_send($email, $subject, $message);
            mail_alert('Er is een nieuwe gebruiker geregistreerd.' . $user_number);

            $ip = ip();
            $date = date('Y-m-d H:i:s');
            $text = $date . '	:	' . $ip . '	:	' . $user_number . PHP_EOL;
            $file = fopen("ip-register.txt", "a+");
            fwrite($file, $text);

            $_SESSION['alert'] = "Uw account is nog niet geactiveerd, bevestig alstublieft uw e-mail door op de link in uw email te klikken! Als u de mail niet kunt vinden, kunt u in uw spam kijken of contact opnemen met de beheerder (<a href=\"mailto:lucacastelnuovo@hetbaarnschlyceum.nl?SUBJECT=BetaSterren%20-%20Account%20Activation\">lucacastelnuovo@hetbaarnschlyceum.nl</a>)";
            header("location: /login/");
        } else {
            $_SESSION['alert'] = 'Registration failed!';
            header("location: /login/");
        }
    }
}
