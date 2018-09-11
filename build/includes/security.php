<?php

//Generate random string
function gen($length)
{
    if (!empty($length)) {
        $length = $length / 2;
        return bin2hex(random_bytes($length));
    } else {
        return bin2hex(random_bytes(32));
    }
}


//Clean user submitted data
function clean_data($data, $editor = false, $mail = false)
{
    $conn = sql_connect();
    $data = $conn->escape_string($data);
    sql_disconnect($conn);

    if (!$editor) {
        $data = trim($data);
    }

    if (!$mail) {
        $data = htmlspecialchars($data);
    }

    if (!$editor) {
        $data = stripslashes($data);
    }

    return $data;
}

function csrf_gen()
{
    if (isset($_SESSION['CSRFtoken'])) {
        return $_SESSION['CSRFtoken'];
    } else {
        $_SESSION['CSRFtoken'] = gen(32);
        return $_SESSION['CSRFtoken'];
    }
}

function csrf_val($post_token, $returnbool = false)
{
    if (!isset($_SESSION['CSRFtoken'])) {
        $user = isset($_SESSION) ? $_SESSION['first_name'] . ' ' . $_SESSION['last_name'] : 'UNKNOWN';
        log_action($user, 'CSRF - session tkn not set', 1);
        if ($returnbool) {
            return false;
        } else {
            redirect('/general/home', 'CSRF error!');
        }
    }

    if (!(hash_equals($_SESSION['CSRFtoken'], $post_token))) {
        $user = isset($_SESSION) ? $_SESSION['first_name'] . ' ' . $_SESSION['last_name'] : 'UNKNOWN';
        log_action($user, 'CSRF - Post tkn not equal to session tkn', 2);
        if ($returnbool) {
            return false;
        } else {
            redirect('/general/home', 'CSRF error!');
        }
    } else {
        unset($_SESSION['CSRFtoken']);
        if ($returnbool) {
            return true;
        }
    }
}

function token_gen($identifier)
{
    $token = "token_{$_SESSION['id']}_{$identifier}";

    if (isset($_SESSION[$token])) {
        return $_SESSION[$token];
    } else {
        $_SESSION[$token] = true;
        return $_SESSION[$token];
    }
}

function token_val($identifier, $returnbool = false)
{
    $token = "token_{$_SESSION['id']}_{$identifier}";

    if (!isset($_SESSION[$token]) || !$_SESSION[$token]) {
        if ($returnbool) {
            return false;
        } else {
            redirect('/general/home', 'U hebt geen toegang tot deze pagina!');
        }
    } else {
        unset($_SESSION[$token]);
        if ($returnbool) {
            return true;
        }
    }
}

function login()
{
    if ($_SESSION['logged_in'] != 1) {
        $_SESSION['return_url'] = $_SERVER['REQUEST_URI'];
        redirect('/?reset&preserveremember', 'Deze pagina is alleen zichtbaar als u ingelogd bent!');
    }

    if (!isset($_SESSION['CREATED'])) {
        $_SESSION['CREATED'] = time();
    } elseif (time() - $_SESSION['CREATED'] > 600) {
        session_regenerate_id(true);
        $_SESSION['CREATED'] = time();
    }

    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 600)) {
        log_action($_SESSION['first_name'] . ' ' . $_SESSION['last_name'], 'Session Expired', 0);
        $_SESSION['return_url'] = $_SERVER['REQUEST_URI'];
        redirect('/?reset&preserveremember', 'Uw sessie is verlopen');
    } else {
        $_SESSION['LAST_ACTIVITY'] = time();
    }

    if ($_SESSION['ip'] != ip()) {
        log_action($_SESSION['first_name'] . ' ' . $_SESSION['last_name'], 'Session IP doesnt match client IP', 2);
        $_SESSION['return_url'] = $_SERVER['REQUEST_URI'];
        redirect('/?reset&preserveremember', 'Uw sessie is verlopen');
    }
}

function login_leerling()
{
    login();

    if ($_SESSION['class'] === 'docent') {
        redirect('/general/home', 'Deze pagina is alleen zichtbaar voor leerlingen!');
    }
}

function login_docent()
{
    login();

    if ($_SESSION['class'] !== 'docent') {
        log_action($_SESSION['first_name'] . ' ' . $_SESSION['last_name'], 'User tried to access teacher page', 1);
        redirect('/general/home', 'Deze pagina is alleen zichtbaar voor docenten!');
    }
}

function login_admin()
{
    login();

    if ($_SESSION['admin'] != true) {
        log_action($_SESSION['first_name'] . ' ' . $_SESSION['last_name'], 'User tried to access admin page', 2);
        redirect('/general/home', 'Deze pagina is alleen zichtbaar voor administrators!');
    }
}
