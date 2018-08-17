<?php
session_start();
$return_to = $_SESSION['return_url'];
unset($_SESSION['return_url']);
unset($_SESSION['alert']);
session_regenerate_id(true);
if (!empty($return_to)) {
    header("Location: $return_to");
} elseif ($_SESSION['class'] == 'admin') {
    // TODO: activate 2fa security

    //$_SESSION['admin_login'] = true;
    //header("Location: admin/login");

    $_SESSION['admin'] = true;
    $_SESSION['logged_in'] = true;
    $_SESSION['user_type'] = 1;
    $_SESSION['active'] = true;
    header("Location: admin/");
} else {
    header("Location: /leerlingen/profile");
}
