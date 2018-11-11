<?php
require($_SERVER['DOCUMENT_ROOT'] . '/init.php');

login_leerling();

head('Nieuw || Ster Opdrachten', 2, 'Nieuw', '<link rel="stylesheet" href="' . $GLOBALS['config']->cdn->css->simplemde . '">
<script src="' . $GLOBALS['config']->cdn->js->simplemde->library . '"></script>');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_name = clean_data($_POST['project_name']);
    $content = clean_data($_POST['content'], true);
    $date = current_date(false);
    $datetime = current_date(true);
    $subject = clean_data($_POST['subject']);

    is_empty([$project_name, $subject], '/leerlingen/home');

    $leerling_id = $_SESSION['id'];

    $query =
        "INSERT INTO
            steropdrachten
                (project_name,
                content,
                created,
                status_date,
                last_edited,
                leerling_id,
                subject)
        VALUES
            ('{$project_name}',
            '{$content}',
            '{$datetime}',
            '{$date}',
            '{$datetime}',
            '{$leerling_id}',
            '{$subject}')";

    sql_query($query, false);

    log_action('steropdracht.create');

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
                            <div class="input-field col s12">
                                <input class="validate" id="project_name" name="project_name" type="text" required>
                                <label for="project_name">Naam Ster Opdracht</label>
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
                            <textarea name="content" id="simplemde" cols="30" rows="10"></textarea>
                        </div>
                        <button class="btn-large waves-effect waves-light color-primary--background" type="submit" name="action">Verstuur <i class="material-icons right">send</i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php footer('<script src="' . $GLOBALS['config']->cdn->js->simplemde->init . '"></script>'); ?>
