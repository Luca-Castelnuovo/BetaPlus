<?php
require($_SERVER['DOCUMENT_ROOT'] . "/init.php");

login();

head('Leerlingen', 1);
?>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h3>4 HAVO</h3>
                <?php leerlingen_list('4havo'); ?>
                <h3>4 VWO</h3>
                <?php leerlingen_list('4vwo'); ?>
                <h3>5 HAVO</h3>
                <?php leerlingen_list('5havo'); ?>
                <h3>5 VWO</h3>
                <?php leerlingen_list('5vwo'); ?>
                <h3>6 VWO</h3>
                <?php leerlingen_list('6vwo'); ?>
            </div>
        </div>
    </div>
</div>

<?php footer(); ?>
