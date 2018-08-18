<?php
require($_SERVER['DOCUMENT_ROOT'] . "/init.php");

login_leerling();

head('Edit || Ster Opdrachten', 2, 'Edit', '<link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>');

$id = clean_data($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_SESSION['steropdrachten_edit_confirm_id'] !== $id) {
        redirect('/ster-opdrachten/view/'.$id, 'U hebt geen toestemming om deze Ster Opdracht aan te passen');
    } else {
        unset($_SESSION['steropdrachten_edit_confirm_id']);
    }

    $project_name = clean_data($_POST['project_name']);
    $content = clean_data($_POST['content'], true);
    $datetime = date('Y-m-d H:i:s');

    $query=
    "UPDATE
        steropdrachten
    SET
        project_name = '{$project_name}',
        content = '{$content}',
        last_edited = '{$datetime}'
    WHERE
        id = {$id}";

    sql_query($query, false);

    redirect('/ster-opdrachten/view/'.$id, 'Ster Opdracht aangepast');
} else {
    $query=
    "SELECT
        project_name,
        content,
        leerling_id
    FROM
        steropdrachten
    WHERE
        id = {$id}";

    $steropdracht = sql_query($query, true);

    // TODO: remove true for production
    if (($_SESSION['leerling_id'] === $steropdracht['leerling_id']) || true) {
        unset($_SESSION['steropdrachten_edit_confirm_id']);
        $_SESSION['steropdrachten_edit_confirm_id'] = $id;
    } else {
        redirect('/ster-opdrachten/view/'.$id, 'U hebt geen toestemming om deze Ster Opdracht aan te passen');
    }
}

?>

<div class="section">
        <div class="container">
            <div class="row">
                <div class="col s12">
                    <div class="row">
                        <form class="col s12" method="post" action="/ster-opdrachten/edit/<?= $id ?>">
                            <div class="row">
                                <div class="input-field col s8">
                                    <input class="validate" id="project_name" name="project_name" type="text" required value="<?= $steropdracht['project_name'] ?>"> <label for="project_name">Naam Ster Opdracht</label>
                                </div>
                                <div class="file-field input-field col s4">
                                    <div class="btn">
                                        <span>Omslagfoto</span>
                                        <input type="file">
                                    </div>
                                    <div class="file-path-wrapper">
                                        <input class="file-path validate" type="text">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <h5>Content Ster Opdracht</h5>
                                <textarea name="content" id="simplemde" cols="30" rows="10"><?= $steropdracht['content'] ?></textarea>
                                <script>var simplemde = new SimpleMDE({ element: document.querySelector("#simplemde") });</script>
                            </div>
                            <button class="btn-large waves-effect waves-light color-primary--background" type="submit" name="action">Verstuur
                                <i class="material-icons right">send</i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php footer(); ?>
