<?php

require($_SERVER['DOCUMENT_ROOT'] . '/init.php');

if (isset($_GET['reset']) && isset($_GET['preserveremember'])) {
    if ($_SESSION['logged_in']) {
        log_action('user.reset_remember');
    }

    $alert = $_SESSION['alert'];
    $return_url = $_SESSION['return_url'];
    session_destroy();
    session_start();
    $_SESSION['return_url'] = $return_url;
    redirect('/', $alert);
} elseif (isset($_GET['reset'])) {
    if ($_SESSION['logged_in']) {
        log_action('user.reset');
    }

    $alert = $_SESSION['alert'];
    $return_url = $_SESSION['return_url'];
    unset($_COOKIE['REMEMBERME']);
    setcookie('REMEMBERME', null, time() - 3600, "/", $GLOBALS['config']->app->domain, true, true);
    session_destroy();
    session_start();
    $_SESSION['return_url'] = $return_url;
    redirect('/', $alert);
}

if (isset($_GET['logout'])) {
    if ($_SESSION['logged_in']) {
        log_action('user.logout');
    }

    if (isset($_COOKIE['REMEMBERME'])) {
        list($user, $leerling, $token, $mac) = explode(':', $_COOKIE['REMEMBERME']);
        $query = "DELETE FROM tokens WHERE user='{$user}' AND token='{$token}' AND type = 'remember_me'";
        sql_query($query, false);
        unset($_COOKIE['REMEMBERME']);
        setcookie('REMEMBERME', null, time() - 3600, "/", $GLOBALS['config']->app->domain, true, true);
    }
    session_destroy();
    session_start();
    redirect('/', 'U bent uitgelogd');
}

if ($_SESSION['logged_in']) {
    redirect('/general/home');
}

if (isset($_COOKIE['REMEMBERME'])) {
    redirect('/auth/cookie');
}

head('Login', 10);

?>

<div class="row">
    <div class="col s12 m8 offset-m2 l4 offset-l4">
        <div class="card login">
            <div class="card-action color-primary--background hover-disable white-text">
                <h3>Login</h3>
            </div>
            <div class="card-content">
                <form action="/auth/login.php" method="post">
                    <div class="row">
                        <div class="input-field col s12">
                            <label for="username">Leerling nummer of email</label>
                            <input type="text" id="username" name="username" class="validate" required/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <label for="password">Wachtwoord</label>
                            <input type="password" id="password" name="password" class="validate" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12">
                            <label>
                                <input type="checkbox" class="filled-in" name="remember" value="true"/>
                                <span>Remember Me</span>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12">
                            <input type="hidden" name="CSRFtoken" value="<?= csrf_gen() ?>"/>
                            <button type="submit" class="waves-effect waves-light btn color-primary--background width-full">
                                Login
                            </button>
                        </div>
                    </div>
                    <a href="/auth/forgot" class="right">Wachtwoord vergeten?</a>
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
