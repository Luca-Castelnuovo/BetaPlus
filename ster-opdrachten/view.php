<?php
require($_SERVER['DOCUMENT_ROOT'] . "/init.php");

login();

$steropdracht['project_name'] = 'test';

head('Bekijk || Ster Opdrachten', 2, $steropdracht['project_name']);
