<?php
require($_SERVER['DOCUMENT_ROOT'] . "/init.php");

login_leerling();

head('Nieuw || Ster Opdrachten', 2, 'Nieuw', '<link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_name = clean_data($_POST['project_name']);
    $content = clean_data($_POST['content'], true);
    $date = date('Y-m-d');

    $leerling_id = $_SESSION['leerling_nummer'] ?? '1';

    $query=
    "INSERT INTO
        steropdrachten
            (project_name,
            content,
            created,
            status_date,
            last_edited,
            leerling_id)
    VALUES
        ('{$project_name}',
        '{$content}',
        '{$date}',
        '{$date}',
        '{$date}',
        '{$leerling_id}')";

    sql_query($query, false);

    redirect('/leerlingen/home', 'Ster Opdracht toegevoegd');
}

?>

<div class="section">
        <div class="container">
            <div class="row">
                <div class="col s12">
                    <div class="row">
                        <form class="col s12" method="post" action="new.php">
                            <div class="row">
                                <div class="input-field col s8">
                                    <input class="validate" id="project_name" name="project_name" type="text" required> <label for="project_name">Naam Ster Opdracht</label>
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
                                <textarea name="content" id="simplemde" cols="30" rows="10"></textarea>
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
