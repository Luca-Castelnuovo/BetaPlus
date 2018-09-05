<?php require($_SERVER['DOCUMENT_ROOT'] . "/init.php"); ?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#003d14">

    <title>Login</title>
    <meta content="BetaSterren" name="description">
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

    <?php
    if (isset($_GET['reset'])) {
        $alert = $_SESSION['alert'];
        $return_url = $_SESSION['return_url'];
        session_destroy();
        session_start();
        $_SESSION['return_url'] = $return_url;
        redirect('/', $alert);
    }

    if (isset($_GET['logout'])) {
        log_action($_SESSION['first_name'] . ' ' . $_SESSION['last_name'], 'Logout', '0');
        session_destroy();
        session_start();
        redirect('/', 'U bent uitgelogd');
    }
    ?>
</head>

<body>
<div class="row">
    <div class="col s12 m8 offset-m2 l4 offset-l4">
        <div class="card login">
            <div class="card-action color-primary--background hover-disable white-text">
                <h3>Login Form</h3>
            </div>
            <div class="card-content">
                <form action="/auth/login.php" method="post">
                    <div class="row">
                        <div class="input-field col s12">
                            <label for="username">Leerling nummer of email</label>
                            <input type="text" id="username" name="username" required=""/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <label for="password">Wachtwoord</label>
                            <input type="password" id="password" name="password" required=""/>
                        </div>
                    </div>
                    <div class="row">
                        <label>
                            <input type="checkbox" class="filled-in" name="remember" value="true" />
                            <span>Remember Me</span>
                        </label>
                    </div>
                    <div class="row">
                        <input type="hidden" name="CSRFtoken" value="<?= csrf_gen() ?>"/>
                        <button type="submit" class="waves-effect waves-light btn color-primary--background width-full">
                            Login
                        </button>
                    </div>
                    <a href="/auth/forgot" class="right">Wachtwoord vergeten?</a>
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
