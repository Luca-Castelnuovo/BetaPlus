<?php
require($_SERVER['DOCUMENT_ROOT'] . '/init.php');

login_leerling();

$id = clean_data($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!token_val($id, true)) {
        redirect('/ster-opdrachten/view/' . $id, 'U hebt geen toestemming om deze Ster Opdracht aan te passen');
    }

    // Simple validation (max file size 2MB and only two allowed mime types)
    $validator = new FileUpload\Validator\Simple('2M', ['application/pdf', 'application/x-pdf']);

    // Simple path resolver, where uploads will be put
    $pathresolver = new FileUpload\PathResolver\Simple($_SERVER['DOCUMENT_ROOT'] .  '/files/ster-opdrachten');

    // The machine's filesystem
    $filesystem = new FileUpload\FileSystem\Simple();

    // FileUploader itself
    $fileupload = new FileUpload\FileUpload($_FILES['files'], $_SERVER);

    // Adding it all together. Note that you can use multiple validators or none at all
    $fileupload->setPathResolver($pathresolver);
    $fileupload->setFileSystem($filesystem);
    $fileupload->addValidator($validator);

    // Doing the deed
    list($files, $headers) = $fileupload->processAll();

    // Outputting it, for example like this
    foreach ($headers as $header => $value) {
        header($header . ': ' . $value);
    }

    echo json_encode(['files' => $files]);

    foreach ($files as $file) {
        //Remeber to check if the upload was completed
        if ($file->completed) {
            echo $file->getRealPath();

            // Call any method on an SplFileInfo instance
            var_dump($file->isFile());
        }
    }

    $query =
        "INSERT INTO
            files
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

    log_action($_SESSION['first_name'] . ' ' . $_SESSION['last_name'], 'SterOpdrachten new', 0);

    redirect('/leerlingen/home', 'Ster Opdracht toegevoegd');
} else {
    if ($_SESSION['id'] == $steropdracht['leerling_id'] || $_SESSION['id'] == $steropdracht['buddy_id']) {
        token_gen($id);
    } else {
        redirect('/ster-opdrachten/view/' . $id, 'U hebt geen toestemming om deze Ster Opdracht aan te passen');
    }

    $query =
    "SELECT
        project_name
    FROM
        steropdrachten
    WHERE
        id='{$id}'";

    $steropdrachten = sql_query($query, true);
}
head('Bestanden || Ster Opdrachten', 2, 'Bestanden');

?>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col s12">
                <div class="row">
                    <form class="col s12" method="post" action="files.php?id=<?= $id ?>">
                        <div class="row">
                            <div class="input-field col s12">
                                <input type="text" value="<?= $steropdracht['project_name'] ?>" readonly>
                                <label for="project_name">Naam Ster Opdracht</label>
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
