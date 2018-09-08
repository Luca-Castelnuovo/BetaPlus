<?php

require($_SERVER['DOCUMENT_ROOT'] . "/init.php");

if (!isset($_COOKIE['REMEMBERME'])) {
    redirect('/?logout');
}

list($user, $token, $mac) = explode(':', $_COOKIE['REMEMBERME']);

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

if (!hash_equals(hash_hmac('sha512', $user . ':' . $token, $config['hmac_key']), $mac)) {
    redirect('/?logout');
}
if (!hash_equals($token_sql['token'], $token)) {
    redirect('/?logout');
}

echo 'user  ' . $user . '<br />';
echo 'token  ' . $token . '<br />';
echo 'sql  ' . $token_sql['token'] . '<br />';
echo 'config  ' . $config['hmac_key'] . '<br />';
echo 'mac  ' . $mac;

echo 'success';
exit;
