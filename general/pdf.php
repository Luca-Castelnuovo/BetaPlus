<?php

require($_SERVER['DOCUMENT_ROOT'] . '/init.php');

if (isset($_GET['id'])) {
    $_SESSION["id"] = escape_data($_GET['id'], 13);
    header('Location: pdf');
} else {
    $rid = $_SESSION["id"];
    $result = $mysqli->query("SELECT * FROM files WHERE rid='$rid'");
    $row = $result->fetch_assoc();
    $file = '../files/' . $row["path"];
    $name = $row["name"];
    $login = $row["login"];
    if ($login) {
        if ($_SESSION['logged_in'] != 1) {
            $_SESSION['return_url'] = "/ster-opdrachten/view?id=" . $rid;
            $_SESSION['alert'] = 'Deze pagina is alleen zichtbaar als u ingelogd bent!';
            header("location: /login/");
            exit;
        }
    }
    if (file_exists($file)) {
        header('Content-type: application/pdf');
        header('Content-Disposition: inline; filename="' . $name . '.pdf"');
        @readfile($file);
    } else {
        header('Location: error?code=404');
    }
}
