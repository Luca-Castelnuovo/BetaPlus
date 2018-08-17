<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/scripts/functions.php");
login_admin();
$_SESSION['allow_register'] = 1;
header("location: /login/new-user");
exit;
