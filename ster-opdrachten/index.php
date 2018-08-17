<?php
require($_SERVER['DOCUMENT_ROOT'] . "/init.php");

login();

head('Ster Opdrachten', 2);
?>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h3>Lopende ster opdrachten</h3>
                <?php steropdrachten_list(0); ?>
                <h3>Afgeronde ster opdrachten</h3>
                <?php steropdrachten_list(1); ?>
            </div>
        </div>
    </div>
</div>

<?php footer(); ?>
