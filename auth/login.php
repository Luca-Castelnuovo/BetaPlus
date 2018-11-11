<?php

require($_SERVER['DOCUMENT_ROOT'] . '/init.php');

csrf_val($_POST['CSRFtoken']);

is_empty([$_POST['username'], $_POST['password']], '/');

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
    log_action('user.account_blocked', $user['first_name'] . ' ' . $user['last_name']);
    redirect('/?reset', 'Uw account is geblokkeerd door teveel mislukt inlogpogingen, contacteer AUB de administrator.');
}

if (!password_verify($password, $user['password'])) {
    $table = ($user['class'] == 'docenten') ? 'docenten' : 'leerlingen';
    sql_query("UPDATE {$table} SET failed_login = failed_login + 1 WHERE id='{$user['id']}'", false);
    log_action('user.password_auth_failed', $user['first_name'] . ' ' . $user['last_name']);
    redirect('/?reset', 'Het is niet mogelijk om in te loggen met de ingevulde gegevens.');
}

if (!$user['active']) {
    log_action('user.disabled', $user['first_name'] . ' ' . $user['last_name']);
    redirect('/?reset', 'Uw account is niet actief, contacteer AUB de administrator.');
}

sql_query("UPDATE {$table} SET failed_login='0' WHERE id='{$user['id']}' AND class='{$user['class']}'", false);

remember_clear_old();

if (isset($_POST['remember'])) {
    $token = gen(256);
    $date = current_date(true);
    $ip = ip();

    $query =
        "INSERT INTO
        tokens
            (token,
            type,
            created,
            days_valid,
            user,
            ip)
        VALUES
            ('{$token}',
            'remember_me',
            '{$date}',
            '30',
            '{$user['id']}',
            '{$ip}')";

    sql_query($query, false);

    $cookie_user = ($user['class'] == 'class') ? 0 : 1;

    $cookie = $user['id'] . ':' . $cookie_user . ':' . $token;
    $mac = hash_hmac('sha512', $cookie, $GLOBALS['config']->security->hmac);
    $cookie .= ':' . $mac;
    setcookie('REMEMBERME', $cookie, time() + 2592000, "/", $GLOBALS['config']->app->domain, true, true);

    log_action('user.cookie_auth_set', $user['first_name'] . ' ' . $user['last_name']);
} else {
    log_action('user.password_auth_succeeded', $user['first_name'] . ' ' . $user['last_name']);
    log_action('user.login', $user['first_name'] . ' ' . $user['last_name']);
}

$return_url = $_SESSION['return_url'];

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

session_regenerate_id(true);

if (!empty($return_url)) {
    redirect($return_url, 'U bent ingelogd');
} else {
    redirect('/general/home', 'U bent ingelogd');
}
