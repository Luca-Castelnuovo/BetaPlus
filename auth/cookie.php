<?php

require($_SERVER['DOCUMENT_ROOT'] . "/init.php");

if (!isset($_COOKIE['REMEMBERME'])) {
    redirect('/?logout');
}

$cookie = isset($_COOKIE['REMEMBERME']) ? $_COOKIE['REMEMBERME'] : '';
if ($cookie) {
    list($user, $token, $mac) = explode(':', $cookie);

    $query =
    "SELECT
        token,
        created,
        days_valid
    FROM
        tokens
    WHERE
        user='{$user}' AND type = 'remember_me'";

    $token_sql = sql_query($query, true);

    $config = config_load();

    if (!hash_equals(hash_hmac('sha512', $user . ':' . $token, $config['hmac_key']), $mac)) {
        redirect('/?logout');
    }
    if (!hash_equals($token_sql['token'], $token)) {
        redirect('/?logout');
    }

    echo 'success';
} else {
    redirect('/?logout');
}
