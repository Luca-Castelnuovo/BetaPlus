<?php
require($_SERVER['DOCUMENT_ROOT'] . "/init.php");
login();

$id = clean_data($_GET['id']);

$query =
    "SELECT
        leerling_id,
        buddy_id,
        project_name,
        subject,
        content,
        status,
        status_docent,
        status_date,
        feedback,
        feedback_docent,
        feedback_date,
        grade,
        grade_docent,
        grade_date,
        sterren,
        created,
        last_edited,
        image_url
    FROM
        steropdrachten
    WHERE
        id = '{$id}'";

$steropdracht = sql_query($query, true);
$steropdracht ?: redirect('/ster-opdrachten/', 'Deze Ster Opdracht bestaat niet');

head($steropdracht['project_name'] . ' || Ster Opdrachten', 2, $steropdracht['project_name']);

$query =
    "SELECT
        first_name,
        last_name,
        leerling_nummer
    FROM
        leerlingen
    WHERE
        id = '{$steropdracht['leerling_id']}'";

$leerling = sql_query($query, true);

$query =
    "SELECT
        first_name,
        last_name,
        leerling_nummer
    FROM
        leerlingen
    WHERE
        id = '{$steropdracht['buddy_id']}'";

$buddy = sql_query($query, true);

switch ($steropdracht['status']) {
    case 0:
        $status = 'Niet Beoordeeld';
        break;
    case 1:
        $status = 'No Go';
        break;
    case 2:
        $status = 'Go';
        break;
    case 3:
        $status = 'Wachtend op Cijfer';
        break;
    case 4:
        $status = 'Becijferd';
        break;
    default:
        $status = 'Unknown';
        break;
}

require($_SERVER['DOCUMENT_ROOT'] . "/libs/Parsedown.php");
$parsedown = new Parsedown();
$parsedown->setSafeMode(true);

$CSRFtoken = csrf_gen();

if ($steropdracht['leerling_id'] == $_SESSION['id'] || $steropdracht['buddy_id'] == $_SESSION['id']) {
    $show = true;
    $show_leerling = true;
} elseif ($_SESSION['class'] == 'docent') {
    $show = true;
    $show_leerling = false;
} else {
    $show = false;
    $show_leerling = false;
}


?>

<div class="container">
    <div class="section">
        <div class="card-panel center">
            <div class="card-image">
                <img class="responsive-img" src="<?= $steropdracht['image_url'] ?>"
                     onerror="this.src='https://cdn.lucacastelnuovo.nl/images/betasterren/logo.png'"
                     alt="Ster Opdracht Banner">
            </div>
            <h1 class="center"><?= $steropdracht['project_name'] ?></h1>
            <h6 class="center flow-text">
                Door: <?= $leerling['first_name'] ?> <?= $leerling['last_name'] ?><?php if (isset($steropdracht['buddy_id'])) {
                    echo ' ' . $leerling['first_name'] . ' ' . $leerling['last_name'];
                } ?>
            </h6>
        </div>
    </div>

    <?php if ($show) {
        ?>
        <div class="section">
            <div class="card-panel center">
                <h3 class="center">Details</h3>
                <?php if ($steropdracht['status'] <= 2 && $_SESSION['class'] != 'docent') {
                    ?>
                    <div class="row">
                        <div class="col s12 m12 l6">
                            <a href="/ster-opdrachten/edit/<?= $id ?>/"
                               class="waves-effect waves-light btn color-primary--background"><i
                                        class="material-icons left">edit</i>Edit Ster Opdracht</a>
                        </div>
                        <div class="col s12 m12 l6">
                            <a href="/ster-opdrachten/process/<?= $id ?>/request_feedback/<?= $CSRFtoken ?>"
                               class="waves-effect waves-light btn color-primary--background"><i
                                        class="material-icons left">feedback</i>Feedback Aanvragen</a>
                        </div>
                    </div>
                    <?php
                } elseif ($steropdracht['status'] <= 2 && $_SESSION['class'] == 'docent') {
                    ?>
                    <div class="row">
                        <?php
                        if ($steropdracht['status'] == 0) {
                            echo <<<END
                                <div class="col s12 m12 l6">
                                    <a href="/ster-opdrachten/process/{$id}/go/{$CSRFtoken}" class="waves-effect waves-light btn color-primary--background"><i class="material-icons left">check</i>Go</a>
                                </div>
                                <div class="col s12 m12 l6">
                                    <a href="/ster-opdrachten/process/{$id}/nogo/{$CSRFtoken}" class="waves-effect waves-light btn color-primary--background"><i class="material-icons left">close</i>No Go</a>
                                </div>
END;
                        } elseif ($steropdracht['status'] == 1) {
                            echo <<<END
                                <div class="col s12">
                                    <a href="/ster-opdrachten/process/{$id}/go/{$CSRFtoken}" class="waves-effect waves-light btn color-primary--background"><i class="material-icons left">check</i>Go</a>
                                </div>
END;
                        } elseif ($steropdracht['status'] == 3) {
                            echo <<<END
                                <div class="col s12">
                                    <a href="/ster-opdrachten/abcd/{$id}" class="waves-effect waves-light btn color-primary--background"><i class="material-icons left">check</i>Beoordeel</a>
                                </div>
END;
                        } ?>
                    </div>
                    <div class="row">
                        <div class="col s12">
                            <a href="#feedback"
                               class="waves-effect waves-light btn color-primary--background modal-trigger"><i
                                        class="material-icons left">feedback</i>Geef Feedback</a>
                            <div class="modal" id="feedback">
                                <div class="modal-content">
                                    <h4>Feedback</h4>
                                    <div class="row">
                                        <form class="col s12">
                                            <div class="row"></div>
                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <input type="hidden" name="CSRFtoken" value="<?= $CSRFtoken ?>">
                                                    <textarea class="materialize-textarea" id="feedback_content"
                                                              type="text"><?= $steropdracht['feedback'] ?></textarea>
                                                    <label for="feedback_content">Zet uw feedback hier</label>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a class="modal-close waves-effect waves-green btn-flat" href="#!"
                                       id="feedback_content_submit">Verstuur</a>
                                </div>
                            </div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    var elems = document.querySelectorAll('.modal');
                                    var instances = M.Modal.init(elems, {});
                                });
                                <?php if (isset($_GET['feedback'])) {
                                ?>
                                document.addEventListener('DOMContentLoaded', function () {
                                    setTimeout(function () {
                                        M.Modal.getInstance(document.querySelector('#feedback')).open();
                                    }, 100);
                                });
                                <?php
                                } ?>
                                document.querySelector('#feedback_content_submit').addEventListener('click', function () {
                                    $.ajax({
                                        type: "POST",
                                        url: '/ster-opdrachten/process.php',
                                        data: {
                                            CSRFtoken: document.querySelector('input[name="CSRFtoken"]').value,
                                            id: <?= $id ?>,
                                            feedback: document.querySelector('#feedback_content').value
                                        },
                                        cache: !1,
                                        dataType: "JSON",
                                        success: function (response) {
                                            location.replace(response.url);
                                        }
                                    });
                                });
                            </script>
                        </div>
                    </div>
                    <?php
                } ?>
                <table class="striped centered responsive-table">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Status</th>
                        <th>Docent</th>
                        <th>Datum</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Project</td>
                        <td>
                            <?= $status ?>
                        </td>
                        <td>
                            <?= $steropdracht['status_docent'] ?>
                        </td>
                        <td>
                            <?= $steropdracht['status_date'] ?>
                        </td>
                    </tr>
                    <?php if ($steropdracht['status'] == 4) {
                        ?>
                        <tr>
                            <td>Cijfer</td>
                            <td><span class="transform-uppercase bold"><?= $steropdracht['grade'] ?></span>
                            </td>
                            <td>
                                <?= $steropdracht['grade_docent'] ?>
                            </td>
                            <td>
                                <?= $steropdracht['grade_date'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Aantal Sterren</td>
                            <td>
                                <?= $steropdracht['sterren'] ?>
                            </td>
                            <td>
                                <?= $steropdracht['grade_docent'] ?>
                            </td>
                            <td>
                                <?= $steropdracht['grade_date'] ?>
                            </td>
                        </tr>
                        <?php
                    } ?>
                    <?php if (!empty($steropdracht['feedback'])) {
                        ?>
                        <tr>
                            <td>Feedback</td>
                            <td>
                                <a class="waves-effect waves-light btn color-secondary--background modal-trigger"
                                   href="#feedback_view">Klik Hier</a></td>
                            <td>
                                <?= $steropdracht['feedback_docent'] ?>
                            </td>
                            <td>
                                <?= $steropdracht['feedback_date'] ?>
                            </td>
                            <div id="feedback_view" class="modal ">
                                <div class="modal-content">
                                    <h4>Feedback</h4>
                                    <p><?= nl2br($steropdracht['feedback']) ?></p>
                                </div>
                                <div class="modal-footer">
                                    <a href="#!"
                                       class="modal-close waves-effect waves-light color-secondary--background btn">Close</a>
                                </div>
                            </div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    var elems = document.querySelectorAll('.modal');
                                    var instances = M.Modal.init(elems, {});
                                });
                            </script>
                        </tr>
                    <?php if (isset($_GET['feedback_view'])) {
                    ?>
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                setTimeout(function () {
                                    M.Modal.getInstance(document.querySelector('#feedback_view')).open();
                                }, 100);
                            });
                        </script>
                        <?php
                    } ?>
                        <?php
                    } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    } ?>
    <div class="section">
        <div class="card-panel center">
            <h3 class="center">Bestanden</h3>
            <?php if ($show_leerling) {
                ?>
                <div class="row">
                    <div class="col s12">
                        <a href="/ster-opdrachten/files/add/<?= $id ?>"
                           class="waves-effect waves-light btn color-primary--background"><i
                                    class="material-icons left">attach_file</i>Bestand Toevoegen</a>
                    </div>
                </div>
                <?php
            } ?>
            <div class="row">
                <table class="striped centered responsive-table">
                    <thead>
                    <tr>
                        <th>Bestandsnaam</th>
                        <th>Bekijk</th>
                        <th>Datum</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>ABCD.pdf</td>
                        <td><a class="waves-effect waves-light btn color-secondary--background modal-trigger"
                               target="_blank" href="/general/pdf/file_id">Open Bestand</a></td>
                        <td>datum zonder tijd</td>
                    </tr>
                    <tr>
                        <td>Hypothese.pdf</td>
                        <td><a class="waves-effect waves-light btn color-secondary--background modal-trigger"
                               target="_blank" href="/general/pdf/file_id2">Open Bestand</a></td>
                        <td>datum zonder tijd</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="section">
        <?php $content = str_replace('&gt; ', '> ', $steropdracht['content']);
        echo $parsedown->text($content); ?>
    </div>
</div>

<?php footer("<script src=\"https://cdn.lucacastelnuovo.nl/js/ajax.js\"></script><script>document.querySelector('p').classList.add('flow-text');document.querySelector('.container ul').classList.add('browser-default');document.querySelector('.container ul li').classList.add('browser-default');</script>"); ?>
