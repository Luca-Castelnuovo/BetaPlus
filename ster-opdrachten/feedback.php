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
                <!-- Modal Trigger -->
                <button class="waves-effect waves-light btn modal-trigger" data-target="modal1">Modal</button>

                <!-- Modal Structure -->
                <div id="modal1" class="modal">
                <div class="modal-content">
                  <h4>Add an Image</h4>

                  <div class="row">
                    <form class="col s12">
                      <div class="row modal-form-row">
                        <div class="input-field col s12">
                          <input id="image_url" type="text" class="validate">
                          <label for="image_url">Image URL</label>
                        </div>
                      </div>
                      <div class="row">
                        <div class="input-field col s12">
                          <input id="image_title" type="text" class="validate">
                          <label for="image_title">Title</label>
                        </div>
                      </div>
                      <div class="row">
                        <div class="input-field col s12">
                          <textarea id="image_description" type="text" class="materialize-textarea validate"></textarea>
                          <label for="image_description">Description</label>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
                <div class="modal-footer">
                  <a class=" modal-action modal-close waves-effect waves-green btn-flat">Submit</a>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php footer(); ?>
