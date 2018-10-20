<?php

require($_SERVER['DOCUMENT_ROOT'] . '/init.php');

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
    user='{$user}' AND token='{$token}' AND type = 'remember_me'";

$tokens_sql = sql_query($query, false);

$config = config_load();

$valid_cookie = false;

var_dump($user);
echo '<br /><br />';
var_dump($leerling);
echo '<br /><br />';
var_dump($token);
echo '<br /><br />';
var_dump($mac);
exit;

if ($tokens_sql->num_rows > 0) {
    while ($token_sql = $tokens_sql->fetch_assoc()) {
        $valid_date = false;
        $valid_hmac = false;
        $valid_hash = false;

        if ($tokens_sql['created'] < time()-$tokens_sql['days_valid']*24*60*60) {
            $valid_date = true;
        }

        if (hash_equals($token_sql['token'], $token)) {
            $valid_hash = true;
        }

        if (hash_equals(hash_hmac('sha512', $user . ':' . $leerling . ':' . $tokens_sql['token'], $config['hmac_key']), $mac)) {
            $valid_hmac = true;
        }

        if ($valid_date && $valid_hmac && $valid_hash) {
            $valid_cookie = true;
            break;
        }
    }
}

if (!$valid_cookie) {
    if (!empty($token)) {
        $query =
            "DELETE FROM
                tokens
            WHERE
                user='{$user}' AND token='{$token}' AND type = 'remember_me'";
        sql_query($query, false);
    }

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
    redirect('/?reset', 'Uw account is geblokkeerd door teveel mislukt inlogpogingen, contacteer AUB de administrator.');
}

if (!$user['active']) {
    log_action($user['first_name'] . ' ' . $user['last_name'], 'Account Inactive', 2);
    redirect('/?reset', 'Uw account is niet actief, contacteer AUB de administrator.');
}

sql_query("UPDATE {$table} SET failed_login='0' WHERE id='{$user['id']}' AND class='{$user['class']}'", false);

log_action($user['first_name'] . ' ' . $user['last_name'], 'Login Remember', 0);

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

remember_clear_old();

if (!empty($return_url)) {
    redirect($return_url, 'U bent ingelogd');
} else {
    redirect('/general/home', 'U bent ingelogd');
}
