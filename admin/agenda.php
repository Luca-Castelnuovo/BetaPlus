<?php
require($_SERVER['DOCUMENT_ROOT'] . '/init.php');

login_admin();

head('Agenda Panel', 5);

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

    redirect('/general/agenda', 'Item toegevoegd');
}

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
                    <label for="link">Link</label>
                </div>
            </div>
            <div class="row">
                <input name="CSRFtoken" type="hidden" value="<?= csrf_gen() ?>">
                <button class="btn-large waves-effect waves-light color-primary--background" type="submit" name="action">Verstuur <i class="material-icons right">send</i></button>
            </div>
        </form>
    </div>
</div>

<!-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        var elems = document.querySelectorAll('.datepicker');
        var instances = M.Datepicker.init(elems, {
            format: 'yyyy-mm-dd',
            firstDay: 1
        });
    });
</script> -->

<?php footer('<script>document.addEventListener("DOMContentLoaded",function(){var e=document.querySelectorAll(".datepicker");M.Datepicker.init(e,{format:"yyyy-mm-dd",firstDay:1})});</script>'); ?>
