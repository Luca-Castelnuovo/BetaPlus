<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/scripts/functions.php");
login_admin();
$_SESSION['allow_register_docent'] = 1;
header("location: new-docent");
exit;
