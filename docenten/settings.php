<?php
require($_SERVER['DOCUMENT_ROOT'] . '/init.php');

login_docent();

head('Settings', 0);

if (isset($_GET['logout'])) {
    $query =
        "DELETE FROM
            tokens
        WHERE
            user = '{$_SESSION['id']}' AND type = 'remember_me'";

    sql_query($query, false);

    redirect('/leerlingen/settings', 'U bent overal uitgelogd');
}

$query =
    "SELECT
        first_name,
        last_name
    FROM
        docenten
    WHERE
        id='{$_SESSION['id']}'";

$user = sql_query($query, true);

?>

<div class="section">
    <div class="row">
        <div class="col 12">
            <h3><?= $user['first_name'] ?> <?= $user['last_name'] ?></h3>
        </div>
    </div>
    <div class="row">
        <div class="col s12 margin-top-5">
            <a href="/auth/change" class="waves-effect waves-light btn color-primary--background">Verander
                wachtwoord</a>
        </div>
        <div class="col s12 margin-top-5">
            <a href="/docenten/settings?logout" class="waves-effect waves-light btn color-primary--background">Log uw
                account overal uit</a>
        </div>
    </div>
</div>

<?php footer(); ?>
