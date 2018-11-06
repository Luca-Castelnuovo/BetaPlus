<?php

require($_SERVER['DOCUMENT_ROOT'] . '/init.php');

$token_get = clean_data($_GET['token']);

is_empty([$token_get], '/?reset', 'Deze link is al gebruikt of niet geldig');

$query =
    "SELECT
        used,
        type,
        created,
        days_valid,
        user,
        additional
    FROM
        tokens
    WHERE
        token='{$token_get}'";

$token = sql_query($query, true);

if ($token['used']) {
    redirect('/?reset', 'Deze link is al gebruikt of niet geldig');
}

if ($token['type'] != 'password_reset') {
    redirect('/?reset', 'Deze link is al gebruikt of niet geldig');
}

if (!($token['created'] < time()-$token['days_valid']*24*60*60)) {
    redirect('/?reset', 'Deze link is al gebruikt of niet geldig');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_val($_POST['CSRFtoken']);

    if ($_POST['password'] !== $_POST['password2']) {
        redirect('/auth/reset?my=true&token=' . $token, 'De wachtwoorden komen niet overeen');
    }

    $additional = json_decode($token['additional'], true);
    $class = $additional['docent'] ? 'docenten' : 'leerlingen';
    $password_new = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $query =
        "UPDATE
            {$class}
        SET
            password='{$password_new}'
        WHERE
            email='{$token['user']}'";

    sql_query($query, false);

    $query =
        "DELETE FROM
            tokens
        WHERE
            token='{$token_get}' AND type = 'password_reset'";

    sql_query($query, false);

    $query =
        "SELECT
            id
        FROM
            {$class}
        WHERE
            email = '{$token['user']}'";

    $user = sql_query($query, true);

    $query =
        "DELETE FROM
            tokens
        WHERE
            user='{$user['id']}' AND type = 'remember_me'";

    sql_query($query, false);

    log_action($token['user'], 'Reset Password', 1);

    redirect('/?reset', 'Uw wachtwoord is gewijzigd');
} else {
    token_gen($token);
}

head('Wachtwoord vergeten', 10);

?>

<div class="row">
    <div class="col s12 m8 offset-m2 l4 offset-l4">
        <div class="card login">
            <div class="card-action color-primary--background hover-disable white-text">
                <h3>Nieuw Wachtwoord</h3>
            </div>
            <div class="card-content">
                <form action="/auth/reset.php?token=<?= $token_get ?>" method="post">
                    <div class="row">
                        <div class="input-field col s12">
                            <label for="password">Wachtwoord</label>
                            <input type="password" id="password" name="password" required autofocus/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <label for="password2">Bevestig Wachtwoord</label>
                            <input type="password" id="password2" name="password2" required/>
                        </div>
                    </div>
                    <div class="row">
                        <input type="hidden" name="CSRFtoken" value="<?= csrf_gen() ?>"/>
                        <button type="submit" class="waves-effect waves-light btn color-primary--background width-full">
                            Bevestig nieuw wachtwoord
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!--Import Materialize JavaScript-->
<script src="<?= $GLOBALS['config']->cdn->js->materialize->library ?>"></script>
<?php alert_display(); ?>
<!--Import Partciles.JS JavaScript-->
<script src="<?= $GLOBALS['config']->cdn->js->particle->library ?>"></script>
<canvas class="background"></canvas>
<script src="<?= $GLOBALS['config']->cdn->js->particle->init ?>"></script>
</body>

</html>
