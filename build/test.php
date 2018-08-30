<?php

require($_SERVER['DOCUMENT_ROOT'] . "/init.php");

api_mail($_GET['email'], 'test sub', 'test');
