<?php
require($_SERVER['DOCUMENT_ROOT'] . "/init.php");

login();

$id = clean_data($_GET['id']);

$query =
"SELECT
    leerling_id,
    project_name
FROM
    steropdrachten
WHERE
    id='{$id}'";

$steropdracht = sql_query($query, true);


if ($_GET['type'] === 'request') {
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
}

login_docent();

head('Feedback || Ster Opdrachten', 2, 'Feedback');

?>

<div class="section">
        <div class="container">
            <div class="row">
                <div class="col s12">
                    <h3>Feedback</h3>
                    <a class="waves-effect waves-light btn modal-trigger " href="#feedback">Feedback</a>
                    <div class="modal" id="feedback">
                        <div class="modal-content">
                            <h4>Feedback</h4>
                            <div class="row">
                                <form class="col s12">
                                    <div class="row"></div>
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <input type="hidden" name="CSRFtoken" value="<?= csrf_gen() ?>">
                                            <textarea class="materialize-textarea" id="feedback_content" type="text"></textarea> <label for="feedback_content">Zet uw feedback hier</label>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a class="modal-close waves-effect waves-green btn-flat" href="#!" id="feedback_content_submit">Verstuur</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.modal');
    var instances = M.Modal.init(elems, {});
    });

    document.querySelector('#feedback_content_submit').addEventListener('click', function() {
        $.ajax({
            type: "POST",
            url: '/ster-opdrachten/process.php',
            data: {CSRFtoken: document.querySelector('input[name="CSRFtoken"]').value, id: <?= $id ?>, feedback:document.querySelector('#feedback_content').value},
            cache: !1,
            dataType: "JSON",
            success: function (response) {
                location.replace(response.url);
            }
        });
    });
    </script>
<?php footer('<script src="https://cdn.lucacastelnuovo.nl/js/ajax.js"></script>'); ?>
