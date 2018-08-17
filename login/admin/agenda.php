<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/scripts/functions.php");

if ($_SESSION['user_type'] != 1) {
    $_SESSION["alert"] = 'Deze pagina is alleen zichtbaar voor docenten!';
    header("location: /leerlingen/profile");
    exit;
}

check_post();
csrf_val($_POST['token']);
$agendaname = escape_data($_POST['agendaname'], 8, '/login/admin/');
$agendalink = escape_data($_POST['agendalink'], 100, '/login/admin/');
$agendadate = escape_data($_POST['agendadate'], 100, '/login/admin/');
$agendatype = escape_data($_POST['agendatype'], 8, '/login/admin/');

$rid = gen(32);
switch ($agendatype) {
    case 'pdf':
        switch ($_FILES['file']['error']) {
            case UPLOAD_ERR_INI_SIZE:
                $_SESSION['alert'] = "Het bestand is te groot!";
                header("location:  /login/admin/docent");
                exit;
                break;

            case UPLOAD_ERR_FORM_SIZE:
                $_SESSION['alert'] = "Het bestand is te groot!";
                header("location:  /login/admin/docent");
                exit;
                break;

            case UPLOAD_ERR_PARTIAL:
                $_SESSION['alert'] = "Het bestand was niet volledig geupload!";
                header("location:  /login/admin/docent");
                exit;
                break;

            case UPLOAD_ERR_NO_FILE:
                $_SESSION['alert'] = "Er was geen bestand geupload!";
                header("location:  /login/admin/docent");
                exit;
                break;

            case UPLOAD_ERR_NO_TMP_DIR:
                $_SESSION['alert'] = "Error agenda:1!"; //er is geen tmp dir
                header("location:  /login/admin/docent");
                exit;
                break;

            case UPLOAD_ERR_CANT_WRITE:
                $_SESSION['alert'] = "Error agenda:2!"; //geen toegang tot schijf (chmod -R 777)
                header("location:  /login/admin/docent");
                exit;
                break;

            case UPLOAD_ERR_EXTENSION:
                $_SESSION['alert'] = "Error agenda:3!"; //upload gestopt door extensie
                header("location:  /login/admin/docent");
                exit;
                break;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['file']['tmp_name']);
        switch ($mime) {
            case 'application/pdf':
                break;

            default:
                $_SESSION['alert'] = 'Alleen pdf bestanden zijn toegestaan!';
                header("location: /login/admin/docent");
                exit;
                break;
        }

        move_uploaded_file($_FILES["file"]["tmp_name"], $_SERVER["DOCUMENT_ROOT"] . "/files/agenda-item/" . $agendadate . $agendaname . ".pdf");
        $path = "agenda-item/{$agendadate}{$agendaname}.pdf";
        $agendabestand = "/scripts/pdf?id=" . $rid;
        break;

    case 'link':
        $agendabestand = $agendalink;
        break;
}

$sql = "INSERT INTO agenda (date, name, link) VALUES ('$agendadate','$agendaname','$agendabestand')";
$mysqli->query($sql);
$sql = "INSERT INTO files (path, rid, login) VALUES ('$path','$rid', '0')";

if ($mysqli->query($sql)) {
    $_SESSION['alert'] = 'Item succesvol toegevoegd aan de agenda!';
    header("location: /login/admin/docent");
} else {
    $_SESSION['alert'] = 'ERROR item niet succesvol toegevoegd aan de agenda!';
    header("location: /login/admin/docent");
}
