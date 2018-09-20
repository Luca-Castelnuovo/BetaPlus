<?php
require($_SERVER['DOCUMENT_ROOT'] . '/init.php');

login();

if ($_SESSION['toast_set']) {
    redirect(clean_data($_GET['url']), clean_data($_GET['alert']));
} else {
    redirect('/general/home');
}
