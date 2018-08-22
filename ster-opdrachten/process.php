<?php

require($_SERVER['DOCUMENT_ROOT'] . "/init.php");

//go
//nogo
//abcd

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    login_docent();
    csrf_val($_POST['CSRFtoken']);
    $id = clean_data($_POST['id']);
    $feedback = clean_data($_POST['feedback'], true);
    $datetime = date('Y-m-d');

    $docent = sql_query("SELECT last_name FROM docenten WHERE id='{$_SESSION['id']}'", true);

    $query =
    "UPDATE
        steropdrachten
    SET
        feedback = '{$feedback}',
        feedback_docent = '{$docent['last_name']}',
        feedback_date = '{$datetime}',
        feedback_requested = '0'
    WHERE
        id = '{$id}'";

    sql_query($query, false);
    $_SESSION['toast_set'] = true;
    echo json_encode(['url' => '/general/toast?url=/ster-opdrachten/view/' . $id . '&alert=Feedback verstuurd']);
    exit;
} else {
    //// TODO: Setup CSRF
    // csrf_val($_GET['CSRFtoken']);
    $id = clean_data($_GET['id']);
    $type = clean_data($_GET['type']);

    if ($type == 'request_feedback') {
        login_leerling();
    } else {
        login_docent();
    }

    switch ($type) {
        case 'go':
            // code...
            break;
        case 'nogo':
            // code...
            break;
        case 'abcd':
            // code...
            break;
        case 'request_feedback':
            $query =
            "SELECT
                leerling_id
            FROM
                steropdrachten
            WHERE
                id='{$id}'";

            $steropdracht = sql_query($query, true);

            login_leerling();
            if ($steropdracht['leerling_id'] == $_SESSION['id']) {
                $query =
                "UPDATE
                    steropdrachten
                SET
                    feedback_requested = '1'
                WHERE
                    id='{$id}'";
                sql_query($query, false);
                redirect('/ster-opdrachten/view/' . $id . '/', 'Feedback aangevraagd');
            } else {
                redirect('/ster-opdrachten/view/' . $id . '/', 'U hebt geen toestemming om deze Ster Opdracht aan te passen');
            }
            break;

        default:
            redirect('/general/home', 'Oeps er ging iets fout');
            break;
    }
}
