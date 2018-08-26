<?php

require($_SERVER['DOCUMENT_ROOT'] . "/init.php");

csrf_val($_POST['CSRFtoken']);

if (empty($_POST['username']) || empty($_POST['password'])) {
    redirect('https://sd.keepcalm-o-matic.co.uk/i-w600/keep-calm-and-don-t-hack-me.jpg');
}

$username = clean_data($_POST['username']);
$password = clean_data($_POST['password']);

$queryDocent =
    "SELECT
        id,
        class,
        active,
        password,
        failed_login
    FROM
        docenten
    WHERE
        email='{$username}'";

$queryLeerling =
    "SELECT
        id,
        class,
        active,
        password,
        failed_login,
        admin
    FROM
        leerlingen
    WHERE
        email='{$username}' OR leerling_nummer='{$username}'";

$userDocent = sql_query($queryDocent, false);
$userLeerling = sql_query($queryLeerling, false);

if ($userDocent->num_rows > 0) {
    $user = $userDocent->fetch_assoc();
} elseif ($userLeerling->num_rows > 0) {
    $user = $userLeerling->fetch_assoc();
} else {
    redirect('/?reset', 'Het is niet mogelijk om in te loggen met de ingevulde gegevens.');
}

if (password_verify($password, $user['password'])) {
    if ($user['failed_login'] > 4) {
        log_action($user['id'] . ' ' . $user['class'], 'Too many failed login attempts', 2);
        redirect('/?reset', 'Uw account is geblokkeerd door teveel mislukt inlogpogingen, contacteer AUB de administrator');
    } else {
        sql_query("UPDATE leerlingen SET failed_login='0' WHERE id='{$user['id']}'", false);
        sql_query("UPDATE docenten SET failed_login='0' WHERE id='{$user['id']}'", false);
    }

    if (!$user['active']) {
        log_action($user['id'] . ' ' . $user['class'], 'Account Inactive', 2);
        redirect('/?reset', 'Uw account is gedactiveerd, contacteer AUB de administrator');
    }

    session_destroy();
    session_start();

    $_SESSION['logged_in'] = true;
    $_SESSION['ip'] = ip();
    $_SESSION['id'] = $user['id'];
    $_SESSION['class'] = $user['class'];

    if (isset($user['admin'])) {
        $_SESSION['admin'] = $user['admin'];
    }

    session_regenerate_id(true);

    $return_to = $_SESSION['return_url'];
    unset($_SESSION['return_url']);

    log_action($user['id'] . ' ' . $user['class'], 'Login', 0);

    if (!empty($return_to)) {
        redirect($return_to, 'U bent ingelogd');
    } else {
        redirect('/general/home', 'U bent ingelogd');
    }
} else {
    $table = ($user['class'] == 'docenten') ? 'docenten' : 'leerlingen';
    sql_query("UPDATE {$table} SET failed_login = failed_login + 1 WHERE id='{$user['id']}'", false);
    redirect('/?reset', 'Het is niet mogelijk om in te loggen met de ingevulde gegevens.');
}
