<?php
require($_SERVER['DOCUMENT_ROOT'] . "/init.php");

login();

$id = clean_data($_GET['id']);

$query = "";

$steropdracht = sql_query($query, true);


switch ($_GET['type']) {
    case 'request':
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
        // code...
        break;
}

login_docent();



head('Feedback || Ster Opdrachten', 2, 'Feedback');

?>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h3>Feedback</h3>
            </div>
        </div>
    </div>
</div>

<?php footer(); ?>
