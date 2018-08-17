<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/scripts/functions.php");

if ($_SESSION['user_type'] != 1) {
    $_SESSION["alert"] = 'Deze pagina is alleen zichtbaar voor docenten!';
    header("location: /leerlingen/profile");
    exit;
}

$id = escape_data($_POST['id'], 9, '/login/admin/');
$sql = "DELETE FROM agenda WHERE id='$id'";

if ($mysqli->query($sql)) {
    $_SESSION['alert'] = 'Item succesvol verwijderd uit de agenda!';
    header("location: /login/admin/");
} else {
    $_SESSION['alert'] = 'ERROR item niet succesvol verwijderd!';
    header("location: /login/admin/");
}
