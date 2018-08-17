<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/scripts/functions.php");

login_admin();
check_post();
csrf_val($_POST['token']);

$user_number = escape_data($_GET['user'], 4);
$editfirstname = escape_data($_POST['edit-first-name'], 100, null);
$editlastname = escape_data($_POST['edit-last-name'], 100, null);
$editemail = escape_data($_POST['edit-email'], 100, null);
$editphonenumber = escape_data($_POST['edit-phone-number'], 100, null);
$editovermij = escape_data($_POST['edit-over-mij'], 100, null);
$editopleiding = escape_data($_POST['edit-opleiding'], 100, null);
$editwerkervaring = escape_data($_POST['edit-werk-ervaring'], 100, null);
$editintereses = escape_data($_POST['edit-interesses'], 100, null);
$editalert = escape_data($_POST['edit-alert'], 100, null);
$editclass = escape_data($_POST['edit-class'], 100, null);
$edittype = escape_data($_POST['edit-type'], 100, null);
$editavatar = escape_data($_FILES['edit-avatar']['tmp_name'], 100, null);
$editdelete = escape_data($_POST['edit-delete'], 100, null);
$editunblock = escape_data($_POST['edit-unblock'], 100, null);
$editmentorbeta = escape_data($_POST['edit-mentor'], 100, null);
$editmentorelse = escape_data($_POST['edit-mentor-else'], 100, null);
$editactive = escape_data($_POST['edit-active'], 100, null);
$editpasswordactive = escape_data($_POST['edit-password-active'], 100, null);
$editutalent = escape_data($_POST['edit-utalent'], 100, null);

switch ($editmentorbeta) {
    case 'else':
        $editmentor = $editmentorelse;
        break;

    default:
        $editmentor = $editmentorbeta;
}

switch ($edittype) {
    case 'admin':
        $edittypetext = 1;
        break;

    case 'user':
        $edittypetext = 0;
        break;

    default:
        $edittypetext = 0;
}

switch ($editactive) {
    case 'active':
        $editactivesql = 1;
        break;

    case 'inactive':
        $editactivesql = 0;
        break;

    default:
        $editactivesql = 0;
}

if (!empty($editavatar)) {
    $verifyimg = getimagesize($_FILES['edit-avatar']['tmp_name']);
    if ($verifyimg['mime'] != 'image/png') {
        $_SESSION['alert'] = 'Alleen foto\'s met het PNG formaat zijn toegestaan';
        header("location: edit-account?user=$user_number");
        exit;
    }

    $uploaddir = '../../files/leerlingen/';
    $uploadfile = $uploaddir . $user_number . '.png';
    if (move_uploaded_file($_FILES['edit-avatar']['tmp_name'], $uploadfile)) {
        $_SESSION['alert'] = 'Foto succesvol veranderd!';
        header("location: edit-account?user=$user_number");
    } else {
        $_SESSION['alert'] = 'Foto niet succesvol veranderd!';
        header("location: edit-account?user=$user_number");
        exit;
    }
}

if (!empty($editfirstname)) {
    $sql = "UPDATE users SET first_name='$editfirstname' WHERE user_number='$user_number'";
}

if (!empty($editlastname)) {
    $sql = "UPDATE users SET last_name='$editlastname' WHERE user_number='$user_number'";
}

if (!empty($editemail)) {
    $sql = "UPDATE users SET email='$editemail' WHERE user_number='$user_number'";
}

if (!empty($editphonenumber)) {
    $sql = "UPDATE users SET phone_number='$editphonenumber' WHERE user_number='$user_number'";
}

if (!empty($editovermij)) {
    $sql = "UPDATE users SET over_mij='$editovermij' WHERE user_number='$user_number'";
}

if (!empty($editopleiding)) {
    $sql = "UPDATE users SET opleiding='$editopleiding' WHERE user_number='$user_number'";
}

if (!empty($editwerkervaring)) {
    $sql = "UPDATE users SET werk_ervaring='$editwerkervaring' WHERE user_number='$user_number'";
}

if (!empty($editintereses)) {
    $sql = "UPDATE users SET interesses='$editintereses' WHERE user_number='$user_number'";
}

if (!empty($editclass)) {
    $sql = "UPDATE users SET class='$editclass' WHERE user_number='$user_number'";
}

if (!empty($editmentor)) {
    $sql = "UPDATE users SET mentor='$editmentor' WHERE user_number='$user_number'";
}

if (!empty($edittype)) {
    $sql = "UPDATE users SET user_type='$edittypetext' WHERE user_number='$user_number'";
}

if (!empty($editactive)) {
    $sql = "UPDATE users SET active='$editactivesql' WHERE user_number='$user_number'";
}

if (!empty($editutalent)) {
    $sql = "UPDATE users SET utalent='$editutalent' WHERE user_number='$user_number'";
}

if ($editutalent == 'remove') {
    $sql = "UPDATE users SET utalent='' WHERE user_number='$user_number'";
}

if (!empty($editdelete)) {
    $sql = "DELETE FROM users WHERE user_number='$user_number';";
}

if (!empty($editpasswordactive)) {
    $new_password = password_hash($_POST['edit-password'], PASSWORD_BCRYPT);
    $sql = "UPDATE users SET password='$new_password' WHERE user_number='$user_number'";
}

if (!empty($editunblock)) {
    $sql = "UPDATE users SET failed_login='0' WHERE user_number='$user_number'";
}

if (!empty($editalert)) {
    $sql = "UPDATE users SET alert='$editalert' WHERE user_number='$user_number'";
}

if ($mysqli->query($sql)) {
    $_SESSION['alert'] = 'Success';
    header("location: edit-account?user=$user_number");
} else {
    $_SESSION['alert'] = 'Error';
    header("location: select-account");
}
