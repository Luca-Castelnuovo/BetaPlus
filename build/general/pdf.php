<?php

require($_SERVER['DOCUMENT_ROOT'] . '/init.php');

$id = clean_data($_GET['random_id']);

$query =
"SELECT
    path,
    public
FROM
    files
WHERE
    random_id='{$id}'";

$file = sql_query($query, true);

if (empty($file['path'])) {
    redirect('/general/error?code=404');
    exit;
}

if (!$file["public"]) {
    login();
}

$path = "{$_SERVER['DOCUMENT_ROOT']}/files/{$file['path']}";

header('Content-type: application/pdf');
header('Content-Disposition: inline; filename="' . $path . '"');
@readfile($path);
