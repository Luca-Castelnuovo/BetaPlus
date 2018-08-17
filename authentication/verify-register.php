<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/scripts/functions.php");

$code = escape_data($_GET['code'], 100, '/login/');
$email = escape_data($_GET['email'], 3, '/login/');
$result = $mysqli->query("SELECT created,valid FROM code WHERE code='$code' AND used='0' AND type='register' AND user='$email'");

if ($result->num_rows == 0) {
    $_SESSION['alert'] = "U heeft een ongeldige URL ingevoerd of de link is niet meer geldig!";
    header("location: /login/");
} elseif ($result->num_rows == 1) {
    $out = $result->fetch_assoc();
    $created = $out["created"];
    $valid = $out["valid"];
    if (!($created >= $valid)) {
        $ip = ip_rem();
        $mysqli->query("UPDATE code SET used='1',ip='$ip' WHERE code='$code'");
        $_SESSION['allow_register'] = 1;
        $email = $_GET["email"];
        header("location: new-user?email=$email");
    } else {
        $_SESSION['alert'] = "U heeft een ongeldige URL ingevoerd of de link is niet meer geldig!";
        header("location: /login/");
    }
} else {
    $_SESSION['alert'] = "U heeft een ongeldige URL ingevoerd of de link is niet meer geldig!";
    header("location: /login/");
}
