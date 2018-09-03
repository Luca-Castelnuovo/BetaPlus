<?php

require($_SERVER['DOCUMENT_ROOT'] . "/init.php");

$ip = ip();

$token_get = clean_data($_GET['token']);

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

if (!($token['created'] >= $token['valid'])) {
    redirect('/?reset', 'Deze link is al gebruikt of niet geldig');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_val($_POST['CSRFtoken']);

    if ($_POST['password'] !== $_POST['password2']) {
        redirect('/auth/reset?my=true&token=' . $token, 'De wachtwoorden komen niet overeen');
    }

    $class = $token['additional']['docent'] ? 'docenten' : 'leerlingen';
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
        "UPDATE
            tokens
        SET
            used='1',
            use_ip='{$ip}'
        WHERE
            token='{$token_get}'";

    sql_query($query, false);

    log_action($token['user'], 'Reset Password', 1);

    redirect('/?reset', 'Uw wachtwoord is gewijzigd');
} else {
    $my = clean_data($_GET['my']);

    if (!$my) {
        $query =
            "UPDATE
                tokens
            SET
                used='1',
                type='FAKE password_reset',
                use_ip='{$ip}'
            WHERE
                token='{$token}' AND type='password_reset'";

        sql_query($query, false);

        log_action('UNKNOWN', 'FAKE Reset Password', 2);

        redirect('/?reset', 'De administrator is op de hoogte gesteld');
    }

    token_gen($token);
}

?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#003d14">

    <title>Wachtwoord vergeten</title>
    <meta content="INSERT DESCRIPTION" name="description">
    <meta content="Betasterren, Beta Sterren, Het Baarnsch Lyceum, beta+, beta hbl" name="keywords">

    <!-- Tells Google not to provide a translation for this document -->
    <meta name="google" content="notranslate">

    <!-- Control the behavior of search engine crawling and indexing -->
    <meta name="robots" content="index,follow">
    <meta name="googlebot" content="index,follow">

    <!-- Favicons/Icons -->
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="icon" sizes="192x192" href="/favicon.png">
    <link rel="apple-touch-icon" href="/favicon.png">
    <link rel="mask-icon" href="/favicon.png" color="green">

    <!--Import Materialize CSS-->
    <link rel="preconnect" href="https://cdnjs.cloudflare.com/" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/css/materialize.min.css">

    <!--Import Custom CSS-->
    <link rel="stylesheet" href="/css/style.css">

    <!--Import Google Icon Font-->
    <link rel="preconnect" href="https://fonts.googleapis.com/" crossorigin>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/js/materialize.min.js"></script>
<?php alert_display(); ?>
<!--Import Partciles.JS JavaScript-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/particlesjs/2.2.2/particles.min.js"></script>
<canvas class="background"></canvas>
<script src="https://cdn.lucacastelnuovo.nl/js/betasterren/particles.js"></script>
</body>

</html>
