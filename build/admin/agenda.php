<?php
require($_SERVER['DOCUMENT_ROOT'] . '/init.php');

login_docent();

if (!empty($_GET['id'])) {
    $id = clean_data($_GET['id']);

    $query =
        "DELETE FROM
            agenda
        WHERE
            id='{$id}'";

    sql_query($query, false);
    redirect('/general/agenda', 'Item verwijderd');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_val($_POST['CSRFtoken']);

    $title = clean_data($_POST['title']);
    $link = clean_data($_POST['link']);
    $date = clean_data($_POST['date']);

    is_empty([$title, $date], '/admin/agenda');

    $query =
        "INSERT INTO
            agenda
                (title,
                link,
                date)
        VALUES
            ('{$title}',
            '{$link}',
            '$date')";

    sql_query($query, false);

    log_action($_SESSION['first_name'] . ' ' . $_SESSION['last_name'], 'Agenda item toegevoed', 0);

    redirect('/general/agenda', 'Item toegevoegd');
}

head('Agenda Panel', 5);

?>

<div class="section">
    <div class="container">
        <form method="post" action="agenda.php">
            <h3>Nieuw Item</h3>
            <div class="row">
                <div class="input-field col s12">
                    <input class="validate" id="title" name="title" type="text" required>
                    <label for="title">Titel item</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <input class="datepicker" name="date" id="date" type="text">
                    <label for="date">Datum</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <input id="link" name="link" type="text">
                    <label for="link">Link (optioneel)</label>
                </div>
            </div>
            <div class="row">
                <input name="CSRFtoken" type="hidden" value="<?= csrf_gen() ?>">
                <button class="btn-large waves-effect waves-light color-primary--background" type="submit" name="action">Verstuur <i class="material-icons right">send</i></button>
            </div>
        </form>
    </div>
</div>

<?php footer('<script>document.addEventListener("DOMContentLoaded",function(){var e=document.querySelectorAll(".datepicker");M.Datepicker.init(e,{format:"yyyy-mm-dd",firstDay:1})});</script>'); ?>
