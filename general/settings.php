<?php

require($_SERVER['DOCUMENT_ROOT'] . '/init.php');

login();

if ($_SESSION['class'] === 'docent') {
    redirect('/docenten/settings');
} else {
    redirect('/leerlingen/settings');
}
