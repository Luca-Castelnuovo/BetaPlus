<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/scripts/functions.php");

check_post();
csrf_val($_POST['token']);
login();

$user_number = escape_data($_SESSION["user_number"], 4);

if ($_POST['newpassword'] == $_POST['confirmpassword']) {
    $result = $mysqli->query("SELECT password FROM users WHERE user_number='$user_number'");
    $row = $result->fetch_assoc();
    if (password_verify($_POST['oldpassword'], $row["password"])) {
        $new_password = password_hash($_POST['newpassword'], PASSWORD_BCRYPT);
        $mysqli->query("UPDATE users SET password='$new_password' WHERE user_number='$user_number'");
        $_SESSION['alert'] = 'Uw wachtwoord is succesvol opnieuw ingesteld!';
        header("location: /leerlingen/profile");
        exit;
    } else {
        $_SESSION['alert'] = 'Voer AUB uw correcte oude wachtwoord in!';
        header("location: /leerlingen/edit-profile");
        exit;
    }
} else {
    $_SESSION['alert'] = "De Twee ingevoerde wachtwoorden komen niet overeen, probeer het opnieuw!";
    header("location: /leerlingen/edit-profile");
    exit;
}
