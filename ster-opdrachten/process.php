<?php

require($_SERVER['DOCUMENT_ROOT'] . "/init.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    login_docent();
    csrf_val($_POST['CSRFtoken']);
    $id = clean_data($_POST['id']);
    $feedback = clean_data($_POST['feedback'], true);
    $datetime = current_date(false);

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
    csrf_val($_GET['CSRFtoken']);
    $id = clean_data($_GET['id']);
    $type = clean_data($_GET['type']);

    if ($type == 'request_feedback') {
        login_leerling();
    } else {
        login_docent();
    }

    switch ($type) {
        case 'go':
            $query =
            "SELECT
                leerling_id
            FROM
                steropdrachten
            WHERE
                id='{$id}'";

            $steropdracht = sql_query($query, true);

            $query =
            "SELECT
                email
            FROM
                leerlingen
            WHERE
                id='{$steropdracht['leerling_id']}'";

            $leerling = sql_query($query, true);

            $query =
            "UPDATE
                steropdrachten
            SET
                status = '2'
            WHERE
                id='{$id}' AND (status = '0' OR status = '1')";

            sql_query($query, false);


            $body = <<<END

END;

            api_mail($leerling['email'], 'Go voor een Ster Opdracht ||  BetaSterren', $body);

            redirect('/general/home/', 'Go verstuurd');
            break;
        case 'nogo':
            $query =
            "SELECT
                leerling_id
            FROM
                steropdrachten
            WHERE
                id='{$id}'";

            $steropdracht = sql_query($query, true);

            $query =
            "SELECT
                email
            FROM
                leerlingen
            WHERE
                id='{$steropdracht['leerling_id']}'";

            $leerling = sql_query($query, true);


            $query =
            "UPDATE
                steropdrachten
            SET
                status = '1'
            WHERE
                id='{$id}' AND status = '0'";

            sql_query($query, false);

            $body = <<<END

END;

            api_mail($leerling['email'], 'No Go voor een Ster Opdracht ||  BetaSterren', $body);

            redirect('/general/home/', 'No Go verstuurd');
            break;
        case 'abcd':
            api_mail($email, 'Nieuw wachtwoord verzoek ||  BetaSterren', $body);

            redirect('/general/home/', 'Beoordeling verstuurd');
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
