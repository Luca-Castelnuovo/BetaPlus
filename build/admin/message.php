<?php

require($_SERVER['DOCUMENT_ROOT'] . '/init.php');

login_admin();

$id = clean_data($_GET['id']);
$class = clean_data($_GET['class']);

$sql_table = ($class === 'docenten') ? 'docenten' : 'leerlingen';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_val($_POST['CSRFtoken']);

    $message = clean_data($_POST['message'], true);

    $query =
        "UPDATE
            {$sql_table}
        SET
            message = '{$message}'
        WHERE
            id='{$id}';";

    sql_query($query, false);

    // log_action($_SESSION['first_name'] . ' ' . $_SESSION['last_name'], 'SterOpdrachten bestand toegevoegd');

    redirect('/admin', 'Bericht Verstuurd');
}

is_empty([$id, $class], '/admin', 'Deze link is niet geldig');

$query =
    "SELECT
        first_name,
        last_name
    FROM
        {$sql_table}
    WHERE
        id='{$id}'";

$user = sql_query($query, true);

head('Message || Admin', 5, 'Message', '<link rel="stylesheet" href="' . $GLOBALS['config']->cdn->css->simplemde . '">');

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
                        <input type="hidden" name="CSRFtoken" value="<?= csrf_gen() ?>"/>
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
