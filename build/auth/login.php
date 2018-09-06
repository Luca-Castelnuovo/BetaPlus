<?php

require($_SERVER['DOCUMENT_ROOT'] . "/init.php");

if (isset($_COOKIE['rememberme'])) {
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

    $cookie = isset($_COOKIE['rememberme']) ? $_COOKIE['rememberme'] : '';
    if ($cookie) {
        list($user, $token, $mac) = explode(':', $cookie);
        if (!hash_equals(hash_hmac('sha256', $user . ':' . $token, 'SECRET_KEY'), $mac)) {
            redirect('/?reset', 'Het is niet mogelijk om in te loggen met de ingevulde gegevens.1');
        }
        if (!hash_equals($token_sql['token'], $token)) {
            var_dump($query);
            var_dump($token_sql);
            var_dump($token);
            exit;
            // redirect('/?reset', 'Het is niet mogelijk om in te loggen met de ingevulde gegevens.');
        }
    } else {
        redirect('/?reset', 'Het is niet mogelijk om in te loggen met de ingevulde gegevens.3');
    }
} else {
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

    if (!password_verify($password, $user['password'])) {
        $table = ($user['class'] == 'docenten') ? 'docenten' : 'leerlingen';
        sql_query("UPDATE {$table} SET failed_login = failed_login + 1 WHERE id='{$user['id']}'", false);
        redirect('/?reset', 'Het is niet mogelijk om in te loggen met de ingevulde gegevens.');
    }

    sql_query("UPDATE leerlingen SET failed_login='0' WHERE id='{$user['id']}'", false);
    sql_query("UPDATE docenten SET failed_login='0' WHERE id='{$user['id']}'", false);

    if (!$user['active']) {
        log_action($user['first_name'] . ' ' . $user['last_name'], 'Account Inactive', 2);
        redirect('/?reset', 'Uw account is niet actief, contacteer AUB de administrator');
    }

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
                gen_ip)
            VALUES
                ('{$token}',
                'remember_me',
                '{$date}',
                '30',
                '{$user['id']}',
                '{$ip}')";

        sql_query($query, false);

        $cookie = $user['id'] . ':' . $token;
        $mac = hash_hmac('sha256', $cookie, 'SECRET_KEY');
        $cookie .= ':' . $mac;
        setcookie('rememberme', $cookie, time()+2592000);

        log_action($user['first_name'] . ' ' . $user['last_name'], 'Login Remember Me', 2);
    } else {
        log_action($user['first_name'] . ' ' . $user['last_name'], 'Login', 0);
    }
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
