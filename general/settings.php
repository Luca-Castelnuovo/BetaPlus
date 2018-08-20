<?php

require($_SERVER['DOCUMENT_ROOT'] . '/init.php');

login();

// TODO: rmeove true for produtction
if ($_SESSION['class'] === 'docent' || true) {
    redirect('/docenten/settings');
} else {
    redirect('/leerlingen/settings');
}
