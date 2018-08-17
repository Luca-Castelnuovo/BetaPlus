<?php
require($_SERVER['DOCUMENT_ROOT'] . "/init.php");

login_leerling();

head('Home', 2);
?>

<div style="display: inline;">
    <h2 style="margin-bottom: 0;">Mijn Ster-opdracht(en) - <a class="link" href="/ster-opdrachten/new">Nieuw</a></h2>
    <p>Aantal sterren: <?php echo ster_aantal($user_number); ?></p>
</div>
<div class="ster-opdrachten">
    <?php
    ster_my($user_number, false);
    ster_my($user_number, true);
    ?>
</div>
</body>

</html>
