<?php
require($_SERVER['DOCUMENT_ROOT'] . "/init.php");

login_leerling();

head('Edit || Ster Opdrachten', 2, 'Edit', '<link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>');

$id = clean_data($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!token_val($id, true)) {
        redirect('/ster-opdrachten/view/' . $id, 'U hebt geen toestemming om deze Ster Opdracht aan te passen');
    }

    log_action($_SESSION['first_name'] . ' ' . $_SESSION['last_name'], 'SterOpdrachten edit', 0);

    $project_name = clean_data($_POST['project_name']);
    $subject = clean_data($_POST['subject']);
    $content = clean_data($_POST['content'], true);
    $datetime = current_date(true);

    $query =
        "UPDATE
            steropdrachten
        SET
            project_name = '{$project_name}',
            content = '{$content}',
            last_edited = '{$datetime}',
            subject = '{$subject}'
        WHERE
            id = {$id}";

    sql_query($query, false);

    redirect('/ster-opdrachten/view/' . $id, 'Ster Opdracht aangepast');
} else {
    $query =
        "SELECT
            project_name,
            content,
            status,
            leerling_id
        FROM
            steropdrachten
        WHERE
            id = {$id}";

    $steropdracht = sql_query($query, true);

    if ($_SESSION['id'] == $steropdracht['leerling_id']) {
        token_gen($id);
    } else {
        log_action($_SESSION['first_name'] . ' ' . $_SESSION['last_name'], 'SterOpdrachten edit access denied', 1);
        redirect('/ster-opdrachten/view/' . $id, 'U hebt geen toestemming om deze Ster Opdracht aan te passen');
    }

    if ($steropdracht['status'] >= 3) {
        redirect('/ster-opdrachten/view/' . $id, 'Deze Ster Opdracht is klaar, u kunt hem niet meer aanpassen');
    }

    switch ($_GET['type']) {
        case 'done':

            if ($steropdracht['status'] < 2) {
                redirect('/ster-opdrachten/view/' . $id, 'Ster Opdracht heeft geen Go en kan dus niet klaar zijn');
            }

            $query =
                "UPDATE
                    steropdrachten
                SET
                    status = '3'
                WHERE
                    id='{$id}' AND status='2'";

            sql_query($query, false);

            log_action($_SESSION['first_name'] . ' ' . $_SESSION['last_name'], 'SterOpdrachten done', 1);
            redirect('/ster-opdrachten/view/' . $id, 'Ster Opdracht klaar');
            break;

        case 'delete':
            $query =
                "DELETE FROM
                    steropdrachten
                WHERE
                    id='{$id}'";

            sql_query($query, false);

            log_action($_SESSION['first_name'] . ' ' . $_SESSION['last_name'], 'SterOpdrachten delete', 1);
            redirect('/ster-opdrachten/', 'Ster Opdracht verwijderd');
            break;
    }
}

?>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col s12">
                <div class="row">
                    <form class="col s12" method="post" action="/ster-opdrachten/edit/<?= $id ?>/">
                        <div class="row">
                            <div class="input-field col s12 m8">
                                <input class="validate" id="project_name" name="project_name" type="text" required
                                       value="<?= $steropdracht['project_name'] ?>"> <label for="project_name">Naam Ster
                                    Opdracht</label>
                            </div>
                            <div class="col s12 m4">
                                <a href="/general/upload/steropdrachten_cover/<?= $id ?>/<?php token_gen($id); ?>"
                                   class="waves-effect waves-light btn-large color-primary--background">Upload Cover
                                    Foto</a>
                            </div>
                        </div>
                        <h5>Vak</h5>
                        <p>
                            <label>
                                <input name="subject" type="radio" value="Biologie" required checked/>
                                <span>Biologie</span>
                            </label>
                        </p>
                        <p>
                            <label>
                                <input name="subject" type="radio" value="Informatica" required/>
                                <span>Informatica</span>
                            </label>
                        </p>
                        <p>
                            <label>
                                <input name="subject" type="radio" value="Natuurkunde" required/>
                                <span>Natuurkunde</span>
                            </label>
                        </p>
                        <p>
                            <label>
                                <input name="subject" type="radio" value="Scheikunde" required/>
                                <span>Scheikunde</span>
                            </label>
                        </p>
                        <p>
                            <label>
                                <input name="subject" type="radio" value="Wiskunde" required/>
                                <span>Wiskunde</span>
                            </label>
                        </p>
                        <p>
                            <label>
                                <input name="subject" type="radio" value="Overig" required/>
                                <span>Overig</span>
                            </label>
                        </p>
                        <div class="row">
                            <h5>Content Ster Opdracht</h5>
                            <textarea name="content" id="simplemde" cols="30"
                                      rows="10"><?= $steropdracht['content'] ?></textarea>
                            <script>var simplemde = new SimpleMDE({element: document.querySelector("#simplemde")});</script>
                        </div>
                        <div class="row">
                            <?php if (!($steropdracht['status'] < 2)) {
                                ?>
                                <div class="col s12 m4">
                                    <a href="/ster-opdrachten/edit/<?= $id ?>/done"
                                       class="waves-effect waves-light btn-small color-secondary--background"
                                       onclick="return confirm('Weet je het zeker?')"><i class="material-icons left">done</i>Ster
                                        Opdracht klaar</a>
                                </div>
                                <?php
                            } ?>
                            <div class="col s12 m4">
                                <a href="/ster-opdrachten/edit/<?= $id ?>/delete"
                                   class="waves-effect waves-light btn-small color-secondary--background"
                                   onclick="return confirm('Weet je het zeker?')"><i
                                            class="material-icons left">delete</i>Verwijder Ster Opdracht</a>
                            </div>
                        </div>
                        <button class="btn-large waves-effect waves-light color-primary--background" type="submit"
                                name="action">Verstuur
                            <i class="material-icons right">send</i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php footer(); ?>
