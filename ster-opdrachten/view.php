<?php
require($_SERVER['DOCUMENT_ROOT'] . "/init.php");

login();

$id = clean_data($_GET['id']);

$query =
"SELECT
    id,
    leerling_id,
    project_name,
    status,
    status_date,
    feedback,
    feedback_date,
    grade,
    grade_date,
    created,
    last_edited
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
//Niet Beoordeeld/Go/No Go/Done/Becijferd

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

?>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col s12">
                <div class="container">
                    <div class="card-panel center">
                        <div class="card-image">
                            <img src="/files/steropdrachten/<?= $steropdracht['id'] ?>.png" onerror="this.src='https://cdn.lucacastelnuovo.nl/images/betasterren/default_profile.png'">
                        </div>
                        <h1 class="center"><?= $steropdracht['project_name'] ?></h1>
                        <h6 class="center flow-text">
                            <?php // TODO: implement rewriterule for leerling search query and add support for buddys?>
                            Door: <a href="/leerlingen/<?= $leerling['leerling_nummer'] ?>"><?= $leerling['first_name'] ?> <?= $leerling['last_name'] ?></a>.
                        </h6>
                    </div>
                </div>
                <div class="container">
                    <div class="card-panel center">
                        <h3 class="center">Details</h3>
                        <table class="striped centered highlight responsive-table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Status</th>
                                    <th>Laatste Update</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td>Project</td>
                                    <td><?= $status ?></td>
                                    <td><?= $steropdracht['status_date'] ?></td>
                                </tr>
                                <?php if (!empty($steropdracht['feedback'])) {
    ?>
                                <tr>
                                    <td>Feedback</td>
                                    <td><a class="waves-effect waves-light btn color-secondary--background modal-trigger" href="#feedback">Klik Hier</a></td>
                                    <td><?= $steropdracht['feedback_date'] ?></td>
                                    <div id="feedback" class="modal ">
                                        <div class="modal-content">
                                            <h4>Feedback</h4>
                                            <p><?= nl2br($steropdracht['feedback']) ?></p>
                                        </div>
                                        <div class="modal-footer">
                                            <a href="#!" class="modal-close waves-effect waves-light color-secondary--background btn">Close</a>
                                        </div>
                                    </div>
                                </tr>
                                <?php
} ?>

                                <?php if ($steropdracht['status'] === 4) {
        ?>
                                <tr>
                                    <td>Cijfer</td>
                                    <td><span class="transform-uppercase bold"><?= $steropdracht['grade'] ?></span></td>
                                    <td><?= $steropdracht['grade_date'] ?></td>
                                </tr>
                                <?php
    } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- PARAGRAPH -->
                <div class="container">
                    <h2 class="truncate">Intro</h2>
                    <p class="flow-text">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                        in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                    </p>
                </div>
                <!-- TABLE -->
                <div class="container">
                    <table class="responsive-table striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Item Name</th>
                                <th>Item Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Alvin</td>
                                <td>Eclair</td>
                                <td>$0.87</td>
                            </tr>
                            <tr>
                                <td>Alan</td>
                                <td>Jellybean</td>
                                <td>$3.76</td>
                            </tr>
                            <tr>
                                <td>Jonathan</td>
                                <td>Lollipop</td>
                                <td>$7.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="container">
                    <h2 class="truncate">Middenstuk</h2>
                    <p class="flow-text">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                        in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                    </p>
                </div>
                <!-- IMAGE -->
                <div class="container center">
                    <img src="http://via.placeholder.com/400x300?text=placeholder">
                    <h6 class="grey-text">Image Description</h6>
                </div>
                <div class="container">
                    <h2 class="truncate">Conclusie</h2>
                    <p class="flow-text">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                        in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php footer("<script>document.addEventListener('DOMContentLoaded', function() {var elems = document.querySelectorAll('.modal');var instances = M.Modal.init(elems, {});});</script>"); ?>
