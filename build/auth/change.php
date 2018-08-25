<?php
require($_SERVER['DOCUMENT_ROOT'] . "/init.php");

login();

head('Verander wachtwoord', 5);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_val($_POST['CSRFtoken']);
    if (empty($_POST['password_old']) || empty($_POST['password_new'])  || empty($_POST['password_new2'])) {
        redirect('/auth/change', 'Vul aub alle velden in');
    }

    $password_old = clean_data($_POST['password_old']);
    $password_new = clean_data($_POST['password_new']);
    $password_new2 = clean_data($_POST['password_new2']);

    $class = ($_SESSION['class'] == 'docent') ? 'docenten': 'leerlingen';

    $query =
        "SELECT
            password
        FROM
            {$class}
        WHERE
            id='{$_SESSION['id']}'";

    $user = sql_query($query, true);

    if (password_verify($password_old, $user['password'])) {
        if ($password_new === $password_new2) {
            $password = password_hash($password_new, PASSWORD_BCRYPT);

            $query =
            "UPDATE
                {$class}
            SET
                password = '{$password}'
            WHERE
                id='{$_SESSION['id']}'";

            sql_query($query, false);
            log_action($_SESSION['id'] . ' ' . $_SESSION['class'], 'success: password change');
            redirect('/general/home', 'Uw wachtwoord is gewijzigd');
        } else {
            redirect('/auth/change', 'De nieuwe wachtwoorden komen niet overeen');
        }
    } else {
        redirect('/auth/change', 'Het oude wachtwoord is incorrect, probeer opnieuw of vraag een nieuw wachtwoord aan');
    }
}
?>

<div class="row">
    <div class="col s12 m8 offset-m2 l4 offset-l4">
        <div class="card login">
            <div class="card-action color-primary--background hover-disable white-text">
                <h3>Verander wachtwoord</h3>
            </div>
            <div class="card-content">
                <form action="/auth/change.php" method="post">
                    <div class="row">
                        <div class="input-field col s12">
                            <label for="password_old">Oude wachtwoord</label>
                            <input type="password" id="password_old" name="password_old" required="" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <label for="password_new">Nieuwe wachtwoord</label>
                            <input type="password" id="password_new" name="password_new" required="" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <label for="password_new2">Bevestig Nieuwe wachtwoord</label>
                            <input type="password" id="password_new2" name="password_new2" required="" />
                        </div>
                    </div>
                    <div class="row">
                        <input type="hidden" name="CSRFtoken" value="<?= csrf_gen() ?>" />
                        <button type="submit" class="waves-effect waves-light btn color-primary--background width-full">Verstuur</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php footer(); ?>
