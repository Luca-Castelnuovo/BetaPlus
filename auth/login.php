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
        first_name,
        last_name,
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
        first_name,
        last_name,
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

if ($user['failed_login'] > 4) {
    log_action($user['first_name'] . ' ' . $user['last_name'], 'Too many failed login attempts', 2);
    redirect('/?reset', 'Uw account is geblokkeerd door teveel mislukt inlogpogingen, contacteer AUB de administrator');
}

if (password_verify($password, $user['password'])) {
    sql_query("UPDATE leerlingen SET failed_login='0' WHERE id='{$user['id']}'", false);
    sql_query("UPDATE docenten SET failed_login='0' WHERE id='{$user['id']}'", false);

    if (!$user['active']) {
        log_action($user['first_name'] . ' ' . $user['last_name'], 'Account Inactive', 2);
        redirect('/?reset', 'Uw account is niet actief, contacteer AUB de administrator');
    }

    session_destroy();
    session_start();

    $_SESSION['logged_in'] = true;
    $_SESSION['ip'] = ip();
    $_SESSION['id'] = $user['id'];
    $_SESSION['class'] = $user['class'];
    $_SESSION['first_name'] = $user['first_name'];
    $_SESSION['last_name'] = $user['last_name'];

    if (isset($user['admin'])) {
        $_SESSION['admin'] = $user['admin'];
    }

    if (isset($_POST['remember'])) {
        $_SESSION['remember'] = true;
    }

    session_regenerate_id(true);

    log_action($user['first_name'] . ' ' . $user['last_name'], 'Login', 0);

    $return_url = $_SESSION['return_url'];
    unset($_SESSION['return_url']);

    if (!empty($return_url)) {
        redirect($return_url, 'U bent ingelogd');
    } else {
        redirect('/general/home', 'U bent ingelogd');
    }
} else {
    $table = ($user['class'] == 'docenten') ? 'docenten' : 'leerlingen';
    sql_query("UPDATE {$table} SET failed_login = failed_login + 1 WHERE id='{$user['id']}'", false);
    redirect('/?reset', 'Het is niet mogelijk om in te loggen met de ingevulde gegevens.');
}
