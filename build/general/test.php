<?php

require($_SERVER['DOCUMENT_ROOT'] . '/init.php');

$_SESSION['toast_set'] = true;
redirect('/general/toast?url=/general/home&alert=test');
