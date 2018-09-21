<?php
require($_SERVER['DOCUMENT_ROOT'] . '/init.php');

login_leerling();

$id = clean_data($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!token_val($id, true)) {
        redirect('/ster-opdrachten/view/' . $id, 'U hebt geen toestemming om deze Ster Opdracht aan te passen');
    }


    if (empty($_FILES['file'])) {
        redirect('/ster-opdrachten/files/' . $id);
    }

    //Load Upload lib
    require($_SERVER['DOCUMENT_ROOT'] . '/libs/Upload.php');

    //Upload file
    $upload = Upload::factory($_SERVER['DOCUMENT_ROOT'] . '/files/steropdrachten');
    $upload->file($_FILES['file']);

    //set max. file size (in mb)
    $upload->set_max_file_size(2);

    //set allowed mime types
    $upload->set_allowed_mime_types(array('application/pdf'));

    $results = $upload->upload();

    var_dump($results);

// $name = clean_data($_POST['name']);
    // $path = 'steropdrachten/' .  gen(64);
    // $random_id = gen(64);
    // $created = current_date(true);
    //
    // $query =
    //     "INSERT INTO
    //         files
    //             (steropdracht_id,
    //             name,
    //             path,
    //             random_id,
    //             created)
    //     VALUES
    //         ('{$id}',
    //         '{$name}',
    //         '{$path}',
    //         '{$random_id}',
    //         '{$created}')";
    //
    // sql_query($query, false);
    //
    // redirect('/ster-opdrachten/view/' . $id, 'Bestand toegevoegd');
} else {
    $query =
        "SELECT
            leerling_id,
            buddy_id
        FROM
            steropdrachten
        WHERE
            id='{$id}'";

    $steropdracht = sql_query($query, true);

    if ($_SESSION['id'] == $steropdracht['leerling_id'] || $_SESSION['id'] == $steropdracht['buddy_id']) {
        token_gen($id);
    } else {
        redirect('/ster-opdrachten/view/' . $id, 'U hebt geen toestemming om deze Ster Opdracht aan te passen');
    }

    if (isset($_GET['delete'])) {
        if (!token_val($id, true)) {
            redirect('/ster-opdrachten/view/' . $id, 'U hebt geen toestemming om deze Ster Opdracht aan te passen');
        }

        $file_id = clean_data($_GET['file_id']);

        $query =
        "SELECT
            path
        FROM
            files
        WHERE
            id='{$file_id}'";

        $file = sql_query($query, true);

        unlink($_SERVER['DOCUMENT_ROOT'] . '/files/' . $file['path']);

        $query =
        "DELETE FROM
            files
        WHERE
            id='{$file_id}'";

        sql_query($query, false);

        redirect('/ster-opdrachten/view/' . $id, 'Bestand verwijderd');
    }
}
head('Bestanden || Ster Opdrachten', 2, 'Bestanden');

?>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col s12">
                <div class="row">
                    <form class="col s12" method="post" action="/ster-opdrachten/files/<?= $id ?>">
                        <div class="row">
                            <div class="input-field col s12">
                                <input class="validate" id="name" name="name" type="text" required>
                                <label for="name">Bestandsnaam</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="file-field input-field">
                                <div class="btn">
                                    <span>File</span>
                                    <input type="file" name="file" accept=".pdf" required>
                                </div>
                                <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text">
                                </div>
                            </div>
                        </div>
                        <button class="btn-large waves-effect waves-light color-primary--background" type="submit" name="action">Verstuur <i class="material-icons right">send</i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php footer(); ?>
