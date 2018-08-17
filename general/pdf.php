<?php

require($_SERVER['DOCUMENT_ROOT'] . '/init.php');

$id = escape_data($_GET['id']);

$query =
"SELECT
    name,
    public
FROM
    files
WHERE
    random_id='{$id}'";

$file = sql_query($query, true);

if (!$file["public"]) {
    login();
}

if (file_exists('../files/' . $file["path"])) {
    header('Content-type: application/pdf');
    header('Content-Disposition: inline; filename="' . $file["name"] . '.pdf"');
    @readfile('../files/' . $file["path"]);
} else {
    redirect('/general/error?code=404');
}
