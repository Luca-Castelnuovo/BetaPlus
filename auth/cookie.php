<?php

require($_SERVER['DOCUMENT_ROOT'] . "/init.php");

if (!isset($_COOKIE['rememberme'])) {
    redirect('/?logout');
}

echo $_COOKIE['rememberme'];

// $cookie = isset($_COOKIE['rememberme']) ? $_COOKIE['rememberme'] : '';
// if ($cookie) {
//     list($user, $token, $mac) = explode(':', $cookie);
//
//     $query =
//     "SELECT
//         token,
//         created,
//         days_valid
//     FROM
//         tokens
//     WHERE
//         user='{$user}' AND type = 'remember_me'";
//
//     $token_sql = sql_query($query, true);
//
//     if (!hash_equals(hash_hmac('sha256', $user . ':' . $token, 'SECRET_KEY'), $mac)) {
//         redirect('/?logout');
//     }
//     if (!hash_equals($token_sql['token'], $token)) {
//         redirect('/?logout');
//     }
// } else {
//     redirect('/?logout');
// }
