<?php
require($_SERVER['DOCUMENT_ROOT'] . "/init.php");

login();

head('Wachtwoord vergeten', 5);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_val($_POST['CSRFtoken']);

    if (empty($_POST['email'])) {
        redirect('/auth/change', 'Vul aub alle velden in');
    }

    $email = clean_data($_POST['email']);
}
?>

<div class="row">
    <div class="col s12 m4 offset-m4">
        <div class="card login">
            <div class="card-action color-primary--background hover-disable white-text">
                <h3>Wachtwoord vergeten</h3>
            </div>
            <div class="card-content">
                <form action="/auth/change.php" method="post">
                    <div class="row">
                        <div class="input-field col s12">
                            <label for="email">Uw email adress</label>
                            <input type="email" id="email" name="email" required="" />
                        </div>
                    </div>
                    <div class="row">
                        <input type="hidden" name="CSRFtoken" value="<?= csrf_gen() ?>" />
                        <button type="submit" class="waves-effect waves-light btn color-primary--background width-full">Vraag nieuw wachtwoord aan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php footer(); ?>
