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
function clean_data($data)
{
    $conn = sql_connect();
    $data = $conn->escape_string($data);
    sql_disconnect($conn);

    $data = trim($data);

    $data = htmlspecialchars($data);

    $data = stripslashes($data);

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

function csrf_val($post_token)
{
    if (!isset($_SESSION['CSRFtoken'])) {
        redirect('/', 'CSRF error!');
    }

    if (!(hash_equals($_SESSION['CSRFtoken'], $post_token))) {
        redirect('/', 'CSRF error!');
    } else {
        unset($_SESSION['CSRFtoken']);
    }
}

function login()
{
    // //check if user is loggedin
    // if ($_SESSION['logged_in'] != 1) {
    //     $_SESSION['return_url'] = $_SERVER['REQUEST_URI'];
    //     redirect('/', 'Deze pagina is alleen zichtbaar als u ingelogd bent!');
    // }
    //
    // //check if account is active
    // if ($_SESSION['active'] != 1) {
    //     redirect('/', 'Uw Account is nog niet actief. Bevestig uw e-mailadres door te klikken op de link in uw email!');
    // }
    //
    // //auto logout after 10min no activity
    // if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 600)) {
    //     redirect('/', 'Uw sessie is verlopen');
    // } else {
    //     $_SESSION['LAST_ACTIVITY'] = time();
    // }
    //
    // //regenerate session id (sec against session stealing)
    // if (!isset($_SESSION['CREATED'])) {
    //     $_SESSION['CREATED'] = time();
    // } elseif (time() - $_SESSION['CREATED'] > 600) {
    //     session_regenerate_id(true);
    //     $_SESSION['CREATED'] = time();
    // }
    //
    // //check if session is stolen
    // if ($_SESSION['ip'] != ip_rem()) {
    //     mail_alert('Deze sessie is gestolen door: ' . ip_rem() . '<br><br>' . var_export($_SESSION, true));
    //     redirect();
    // }
}

function login_leerling()
{
    login();

    // if ($_SESSION['class'] === 'docent') {
    //     redirect('/general/home', 'Deze pagina is alleen zichtbaar voor leerlingen!');
    // }
}

function login_docent()
{
    login();

    // if ($_SESSION['class'] !== 'docent') {
    //     redirect('/general/home', 'Deze pagina is alleen zichtbaar voor leerlingen!');
    // }
}

function login_admin()
{
    login();

    // if ($_SESSION['admin'] !== true && $_SESSION['class'] === 'docent') {
    //     redirect('/general/home', 'Deze pagina is alleen zichtbaar voor administrators!');
    // }
}
