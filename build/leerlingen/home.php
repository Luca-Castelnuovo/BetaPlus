<?php
require($_SERVER['DOCUMENT_ROOT'] . '/init.php');

login_leerling();

head('Home', 0);

?>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col s12">
                <a href="/ster-opdrachten/new" class="waves-effect waves-light btn-large color-secondary--background"><i
                            class="material-icons left">add_circle_outline</i>Nieuwe Ster Opdracht</a>
                <p>Aantal behaalde Sterren: <?php steropdrachten_counter(); ?></p>
                <h3>Mijn lopende Ster Opdrachten</h3>
                <?php steropdrachten_list_my(0); ?>
                <h3>Mijn afgeronde Ster Opdrachten</h3>
                <?php steropdrachten_list_my(1); ?>
            </div>
        </div
        >
    </div>
</div>

<?php footer(); ?>
