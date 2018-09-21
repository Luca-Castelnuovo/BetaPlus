<?php
require($_SERVER['DOCUMENT_ROOT'] . '/init.php');

login();

head('Agenda', 5);

?>

<div class="section">
    <div class="container">
        <div class="row">
                <h1>Agenda</h1>
                <?php if ($_SESSION['class'] == 'docent') {
    echo '<a href="/admin/agenda" class="waves-effect waves-light btn color-secondary--background">Voeg agendaitem toe</a>';
} ?>
            <?php agenda(); ?>
        </div>
    </div>
</div>

<?php footer(); ?>
