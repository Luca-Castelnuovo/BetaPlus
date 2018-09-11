<?php

require($_SERVER['DOCUMENT_ROOT'] . "/init.php");

if (!isset($_COOKIE['REMEMBERME'])) {
    redirect('/?logout');
}

list($user, $leerling, $token, $mac) = explode(':', $_COOKIE['REMEMBERME']);

$user = clean_data($user);

$query =
"SELECT
    id,
    token,
    created,
    days_valid
FROM
    tokens
WHERE
    user='{$user}' AND type = 'remember_me'";

$tokens_sql = sql_query($query, false);

if ($tokens_sql->num_rows > 10) {
    $query =
        "DELETE FROM
            tokens
        WHERE
            user='{$user}' AND type = 'remember_me'";

    sql_query($query, false);

    redirect('/?reset', 'U hebt uw account op teveel apparaten onthouden');
}

$valid_date = false;
$valid_hmac = false;
$valid_hash = false;

$config = config_load();

if ($tokens_sql->num_rows > 0) {
    while ($token_sql = $tokens_sql->fetch_assoc() && ($valid_date != $valid_hmac) && ($valid_hmac != $valid_hash)) {
        if ($token['created'] >= $token['valid']) {
            $valid_date = $token_sql['id'];
        }

        if (hash_equals(hash_hmac('sha512', $user . ':' . $leerling . ':'. $token, $config['hmac_key']), $mac)) {
            $valid_hmac = $token_sql['id'];
        }

        if (hash_equals($token_sql['token'], $token)) {
            $valid_hash = $token_sql['id'];
        }
    }
}

if ($valid_date != $valid_hmac || $valid_hmac != $valid_hash) {
    redirect('/?logout');
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
        failed_login,
        admin
    FROM
        {$table}
    WHERE
        id='{$user}'";

$user = sql_query($query, true);

if ($user['failed_login'] > 4) {
    log_action($user['first_name'] . ' ' . $user['last_name'], 'Too many failed login attempts', 2);
    redirect('/?reset', 'Uw account is geblokkeerd door teveel mislukt inlogpogingen, contacteer AUB de administrator');
}

if (!$user['active']) {
    log_action($user['first_name'] . ' ' . $user['last_name'], 'Account Inactive', 2);
    redirect('/?reset', 'Uw account is niet actief, contacteer AUB de administrator');
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
    redirect($return_url, 'U bent ingelogd');
} else {
    redirect('/general/home', 'U bent ingelogd');
}
