<?php
require($_SERVER['DOCUMENT_ROOT'] . "/init.php");

login_docent();

head('Home', 0);

?>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h1>Ster Opdrachten</h1>
                <h3>Wachtend op Go/No Go</h3>
                <?php steropdrachten_list_docenten(0); ?>
                <h3>Wachtend op Feedback</h3>
                <?php steropdrachten_list_docenten(1); ?>
                <h3>Wachtend op Beoordeling</h3>
                <?php steropdrachten_list_docenten(2); ?>
                <h3>Lopend</h3>
                <?php steropdrachten_list_docenten(3); ?>
                <a href="/ster-opdrachten/#afgerond" class="waves-effect waves-light btn-large color-primary--background">Afgerond</a>
            </div>
        </div>
    </div>
</div>

<?php footer(); ?>
