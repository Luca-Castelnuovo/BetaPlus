<?php

require($_SERVER['DOCUMENT_ROOT'] . "/init.php");

if (!isset($_COOKIE['REMEMBERME'])) {
    echo '2';
    exit;
    // redirect('/?logout');
}

list($user, $leerling, $token, $mac) = explode(':', $_COOKIE['REMEMBERME']);

$user = clean_data($user);

$query =
"SELECT
    token,
    created,
    days_valid
FROM
    tokens
WHERE
    user='{$user}' AND type = 'remember_me'
LIMIT
    1";

$token_sql = sql_query($query, true);

//check if token within 30 days

$config = config_load();

if (!hash_equals(hash_hmac('sha512', $user . ':' . $leerling . ':'. $token, $config['hmac_key']), $mac)) {
    echo '3';
    exit;
    // redirect('/?logout');
}
if (!hash_equals($token_sql['token'], $token)) {
    echo '4';
    exit;
    // redirect('/?logout');
}

$table = ($leerling == 1) ? 'leerlingen' : 'docenten';

$query =
    "SELECT
        id,
        class,
        active,
        password,
        first_name,
        last_name,
        failed_login
    FROM
        {$table}
    WHERE
        id='{$user}'";

$user = sql_query($query, true);

if ($user['failed_login'] > 4) {
    log_action($user['first_name'] . ' ' . $user['last_name'], 'Too many failed login attempts', 2);
    echo '5';
    exit;
    // redirect('/?reset', 'Uw account is geblokkeerd door teveel mislukt inlogpogingen, contacteer AUB de administrator');
}

if (!$user['active']) {
    log_action($user['first_name'] . ' ' . $user['last_name'], 'Account Inactive', 2);
    echo '6';
    exit;
    // redirect('/?reset', 'Uw account is niet actief, contacteer AUB de administrator');
}

sql_query("UPDATE leerlingen SET failed_login='0' WHERE id='{$user['id']}' AND class='{$user['class']}'", false);
sql_query("UPDATE docenten SET failed_login='0' WHERE id='{$user['id']}' AND class='{$user['class']}'", false);

log_action($user['first_name'] . ' ' . $user['last_name'], 'Login use Remember', 2);

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
    echo '7';
    exit;
// redirect($return_url, 'U bent ingelogd');
} else {
    echo '8';
    exit;
    // redirect('/general/home', 'U bent ingelogd');
}
