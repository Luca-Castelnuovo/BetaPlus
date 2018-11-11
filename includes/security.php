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

//Clear old remember me tokens
function remember_clear_old()
{
    $query =
        "DELETE FROM
            tokens
        WHERE
            created < NOW() - INTERVAL 30 DAY AND type = 'remember_me'";

    sql_query($query, false);
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
        log_action('security.csrf_not_set');
        if ($returnbool) {
            return false;
        } else {
            redirect('/general/home', 'CSRF error!');
        }
    }

    if (!(hash_equals($_SESSION['CSRFtoken'], $post_token))) {
        log_action('security.csrf_mismatch');
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
            log_action('security.token_val_denied');
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
        log_action('security.session_expired');
        $_SESSION['return_url'] = $_SERVER['REQUEST_URI'];
        redirect('/?reset&preserveremember', 'Uw sessie is verlopen');
    } else {
        $_SESSION['LAST_ACTIVITY'] = time();
    }

    if ($_SESSION['ip'] != ip()) {
        log_action('security.session_ip_mismatch');
        $_SESSION['return_url'] = $_SERVER['REQUEST_URI'];
        redirect('/?reset&preserveremember', 'Uw sessie is verlopen');
    }
}

function login_leerling()
{
    login();

    if ($_SESSION['class'] === 'docent') {
        log_action('user.access_student_denied');
        redirect('/general/home', 'Deze pagina is alleen zichtbaar voor leerlingen!');
    }
}

function login_docent()
{
    login();

    if ($_SESSION['class'] !== 'docent') {
        log_action('user.access_teacher_denied');
        redirect('/general/home', 'Deze pagina is alleen zichtbaar voor docenten!');
    }
}

function login_admin()
{
    login();

    if ($_SESSION['admin'] != true) {
        log_action('user.access_admin_denied');
        redirect('/general/home', 'Deze pagina is alleen zichtbaar voor administrators!');
    }
}
