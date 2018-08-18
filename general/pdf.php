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

if (!$file["public"]) {
    login();
}

$path = "/files/{$file['path']}";

if (file_exists($path)) {
    header('Content-type: application/pdf');
    header('Content-Disposition: inline; filename="' . $path . '"');
    @readfile($path);
} else {
    redirect('/general/error?code=404');
    // http_response_code(404);exit;
}
