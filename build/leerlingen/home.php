<?php
require($_SERVER['DOCUMENT_ROOT'] . "/init.php");

login_leerling();

head('Home', 0);
?>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h2>Mijn Ster Opdrachten</h2>
                <a href="/ster-opdrachten/new" class="waves-effect waves-light btn-large color-secondary--background"><i class="material-icons left">add_circle_outline</i>Nieuw</a>
            </div>
        </div>
    </div>
</div>

<?php footer(); ?>
