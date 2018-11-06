<?php

$config = require $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';

require $_SERVER['DOCUMENT_ROOT'] . '/includes/template.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/generic.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/security.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/sql.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/api.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/leerlingen.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/steropdrachten.php';

!isset($admin_require) ?: require $_SERVER['DOCUMENT_ROOT'] . '/includes/admin.php';

session_start();
