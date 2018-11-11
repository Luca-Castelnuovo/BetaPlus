<?php

require($_SERVER['DOCUMENT_ROOT'] . '/init.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$_SESSION['register_post']) {
        redirect('/general/home', 'U heeft geen toegang tot deze pagina');
    } else {
        unset($_SESSION['register_post']);
    }

    csrf_val($_POST['CSRFtoken']);

    $first_name = clean_data($_POST['first_name']);
    $last_name = clean_data($_POST['last_name']);
    $class = clean_data($_POST['class']);
    $leerling_nummer = clean_data($_POST['leerling_nummer']);
    $email = clean_data($_POST['email']);
    $password = clean_data($_POST['password']);
    $password2 = clean_data($_POST['password2']);

    if (empty($first_name) || empty($last_name) || empty($class) || empty($leerling_nummer) || empty($email) || empty($password) || empty($password2)) {
        $_SESSION['register_get'] = true;
        redirect('/auth/register', 'Vul aub alle velden in');
    }

    if ($class != '4havo' && $class != '4vwo' && $class != '5havo' && $class != '5vwo' && $class != '6vwo') {
        $_SESSION['register_get'] = true;
        redirect('/auth/register', 'Vul aub een klas in.');
    }

    if ($password !== $password2) {
        $_SESSION['register_get'] = true;
        redirect('/auth/register', 'De wachtwoorden komen niet overeen');
    } else {
        $password = password_hash($password, PASSWORD_BCRYPT);
    }

    //check for existing leerling nummer
    $query =
        "SELECT
            id
        FROM
            leerlingen
        WHERE
            leerling_nummer = '{$leerling_nummer}' OR email='{$email}'";

    $leerling_existing = sql_query($query, true);

    if (isset($leerling_existing['id'])) {
        $_SESSION['register_get'] = true;
        redirect('/auth/register', 'Dit leerling nummer/email adres wordt al gebruikt');
    }

    $query =
        "INSERT INTO
            leerlingen
                (leerling_nummer,
                email,
                password,
                first_name,
                last_name,
                class)
        VALUES
            ('{$leerling_nummer}',
            '{$email}',
            '{$password}',
            '{$first_name}',
            '{$last_name}',
            '{$class}')";

    sql_query($query, false);

    log_action('user.register', $first_name . ' ' . $last_name);

    redirect('/?reset', 'Uw account is aangemaakt');
} else {
    if (!$_SESSION['register_get']) {
        $token_get = clean_data($_GET['token']);

        is_empty([$token_get], '/?reset', 'Deze link is al gebruikt of niet geldig');

        $query =
            "SELECT
                used,
                type,
                created,
                days_valid,
                user
            FROM
                tokens
            WHERE
                token='{$token_get}'";

        $token = sql_query($query, true);

        if ($token['used']) {
            redirect('/?reset', 'Deze link is al gebruikt of niet geldig');
        }

        if ($token['type'] != 'register') {
            redirect('/?reset', 'Deze link is al gebruikt of niet geldig');
        }

        if (!($token['created'] < time()-$token['days_valid']*24*60*60)) {
            redirect('/?reset', 'Deze link is al gebruikt of niet geldig');
        }

        if (isset($row)) {
            return ($token['created'] < time()-$token['days_valid']*24*60*60);
        }

        $query =
            "DELETE FROM
                tokens
            WHERE
                token='{$token_get}'";

        sql_query($query, false);
    } else {
        unset($_SESSION['register_get']);
    }

    $_SESSION['register_post'] = true;
}

head('Nieuw Account', 10);

?>

<div class="row">
    <div class="col s12 m8 offset-m2 l4 offset-l4">
        <div class="card login">
            <div class="card-action color-primary--background hover-disable white-text">
                <h3>Nieuw Account</h3>
            </div>
            <div class="card-content">
                <form action="/auth/register.php" method="post">
                    <div class="row">
                        <div class="input-field col s12">
                            <label for="first_name">Voornaam</label>
                            <input type="text" id="first_name" name="first_name" required/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <label for="last_name">Achternaam</label>
                            <input type="text" id="last_name" name="last_name" required/>
                        </div>
                    </div>
                    <h4>Klas:</h4>
                    <p>
                        <label>
                            <input name="class" type="radio" value="4havo" required/>
                            <span>4havo</span>
                        </label>
                    </p>
                    <p>
                        <label>
                            <input name="class" type="radio" value="4vwo" required/>
                            <span>4vwo</span>
                        </label>
                    </p>
                    <p>
                        <label>
                            <input name="class" type="radio" value="5havo" required/>
                            <span>5havo</span>
                        </label>
                    </p>
                    <p>
                        <label>
                            <input name="class" type="radio" value="5vwo" required/>
                            <span>5vwo</span>
                        </label>
                    </p>
                    <p>
                        <label>
                            <input name="class" type="radio" value="6vwo" required/>
                            <span>6vwo</span>
                        </label>
                    </p>
                    <div class="row">
                        <div class="input-field col s12">
                            <label for="leerling_nummer">Leerling Nummer</label>
                            <input type="number" id="leerling_nummer" name="leerling_nummer" required/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="<?= $token['user'] ?>" required/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <label for="password">Wachtwoord</label>
                            <input type="password" id="password" name="password" required/>
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
                            Bevestig
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
