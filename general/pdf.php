<?php

require($_SERVER['DOCUMENT_ROOT'] . '/init.php');

login();

$id = clean_data($_GET['random_id']);

is_empty([$token_get], '/general/home', 'Deze link is niet geldig');

$query =
    "SELECT
        path
    FROM
        files
    WHERE
        random_id='{$id}'";

$file = sql_query($query, true);

if (empty($file['path'])) {
    redirect('/general/error?code=404');
}

$path = "{$_SERVER['DOCUMENT_ROOT']}/files/{$file['path']}";

header('Content-type: application/pdf');
header('Content-Disposition: inline; filename="' . $path . '"');
@readfile($path);
