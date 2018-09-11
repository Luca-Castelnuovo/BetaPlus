<?php

require($_SERVER['DOCUMENT_ROOT'] . "/init.php");

login_docent();

head('Beoordeling || Ster Opdrachten', 2, 'Beoordeling');

$id = clean_data($_GET['id']);

$query =
    "SELECT
        project_name,
        status
        FROM
        steropdrachten
    WHERE
        id='{$id}'";

$steropdracht = sql_query($query, true);

if ($steropdracht['status'] != 3) {
    redirect('/general/home/', 'Ster Opdracht niet af en kan dus niet worden beoordeeld');
}

?>

<div class="row">
    <div class="col s12 m8 offset-m2 l4 offset-l4">
        <div class="card login">
            <div class="card-action color-primary--background hover-disable white-text">
                <h3>Beoordeling Ster Opdracht</h3>
            </div>
            <div class="card-content">
                <form action="/ster-opdrachten/process.php" method="get">
                    <div class="row">
                        <div class="input-field col s12">
                            <label for="project_name">Ster Opdracht</label>
                            <input type="text" id="project_name" value="<?= $steropdracht['project_name']; ?>"
                                   readonly/>
                        </div>
                    </div>
                    <h4>Beoordeling</h4>
                    <p>
                        <label>
                            <input name="grade" type="radio" value="A" required/>
                            <span>A</span>
                        </label>
                    </p>
                    <p>
                        <label>
                            <input name="grade" type="radio" value="B" required/>
                            <span>B</span>
                        </label>
                    </p>
                    <p>
                        <label>
                            <input name="grade" type="radio" value="C" required/>
                            <span>C</span>
                        </label>
                    </p>
                    <p>
                        <label>
                            <input name="grade" type="radio" value="D" required/>
                            <span>D</span>
                        </label>
                    </p>
                    <h4>Aantal Sterren</h4>
                    <p>
                        <label>
                            <input name="sterren" type="radio" value="1" required/>
                            <span>1</span>
                        </label>
                    </p>
                    <p>
                        <label>
                            <input name="sterren" type="radio" value="2" required/>
                            <span>2</span>
                        </label>
                    </p>
                    <p>
                        <label>
                            <input name="sterren" type="radio" value="3" required/>
                            <span>3</span>
                        </label>
                    </p>
                    <div class="row">
                        <input type="hidden" name="CSRFtoken" value="<?= csrf_gen(); ?>">
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <input type="hidden" name="type" value="abcd">
                        <button type="submit" class="waves-effect waves-light btn color-primary--background width-full">
                            Verstuur
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php footer(); ?>
