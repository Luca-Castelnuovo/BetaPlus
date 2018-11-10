<?php

require($_SERVER['DOCUMENT_ROOT'] . '/init.php');

login_admin();

$id = clean_data($_GET['id']);
$class = clean_data($_GET['class']);

is_empty([$id, $class], '/admin', 'Deze link is niet geldig');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = clean_data($_POST['first_name']);
    $last_name = clean_data($_POST['last_name']);
    $email = clean_data($_POST['email']);

    if ($class === 'docenten') {
        $query =
            "UPDATE
                docenten
            SET
                first_name = '{$first_name}',
                last_name = '{$last_name}',
                email = '{$email}',
            WHERE
                id='{$id}';";
    } else {
        $leerling_nummer = clean_data($_POST['leerling_nummer']);
        $class_post = clean_data($_POST['class']);
        $profile_url = clean_data($_POST['profile_url']);
        $admin = clean_data($_POST['admin']);

        $query =
            "UPDATE
                leerlingen
            SET
                first_name = '{$first_name}',
                last_name = '{$last_name}',
                email = '{$email}',
                class = '{$class_post}',
                profile_url = '{$profile_url}',
                leerling_nummer = '{$leerling_nummer}',
                admin = '{$admin}',
            WHERE
                id='{$id}';";
    }

    sql_query($query, false);

    // log_action($_SESSION['first_name'] . ' ' . $_SESSION['last_name'], 'SterOpdrachten bestand toegevoegd');

    redirect('/admin', 'Gebruiker Aangepast');
}

$query =
    "SELECT
        first_name,
        last_name
    FROM
        {$sql_table}
    WHERE
        id='{$id}'";

$user = sql_query($query, true);

head('Edit || Admin', 5, 'Edit');

?>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col s12">
                <div class="row">
                    <form class="col s12" method="post" action="/admin/message/<?= $id ?>/<?= $class ?>">
                        <div class="row">
                            <h5>Gebruiker:</h5>
                            <p><?= $user['first_name'] ?> <?= $user['last_name'] ?></p>
                        </div>
                        <div class="row">
                            <h5>Bericht:</h5>
                            <textarea name="message" id="simplemde" cols="30" rows="10"></textarea>
                        </div>
                        <button class="btn-large waves-effect waves-light color-primary--background" type="submit" name="action">
                            Verstuur <i class="material-icons right">send</i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php footer('<script src="' . $GLOBALS['config']->cdn->js->simplemde->library . '"></script><script src="' . $GLOBALS['config']->cdn->js->simplemde->init . '"></script>'); ?>
