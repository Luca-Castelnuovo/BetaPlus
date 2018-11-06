<?php
require($_SERVER['DOCUMENT_ROOT'] . '/init.php');

login_leerling();

head('Settings', 0);

if (isset($_GET['logout'])) {
    $query =
        "DELETE FROM
            tokens
        WHERE
            user = '{$_SESSION['id']}' AND type = 'remember_me'";

    sql_query($query, false);

    log_action($_SESSION['first_name'] . ' ' . $_SESSION['last_name'], 'Revoked all remember_me', 1);

    redirect('/leerlingen/settings', 'U bent overal uitgelogd');
}

$query =
    "SELECT
        profile_url
    FROM
        leerlingen
    WHERE
        id='{$_SESSION['id']}'";

$user = sql_query($query, true);

?>

<div class="section">
    <div class="row">
        <div class="col s12 m2">
            <img src="<?= $user['profile_url'] ?>" alt="Profiel Foto" class="responsive-img" onerror="this.src='<?= $config->cdn->images->default_profile ?>'">
        </div>
        <div class="col s0 m10"></div>
    </div>
    <div class="row">
        <div class="col 12">
            <h3><?= $_SESSION['first_name'] ?> <?= $_SESSION['last_name'] ?></h3>
        </div>
    </div>
    <div class="row">
        <div class="col s12 margin-top-5">
            <a href="/general/upload/leerling_profile/" class="waves-effect waves-light btn color-primary--background">Verander profiel foto</a>
        </div>
        <div class="col s12 margin-top-5">
            <a href="/auth/change" class="waves-effect waves-light btn color-primary--background">Verander wachtwoord</a>
        </div>
        <div class="col s12 margin-top-5">
            <a href="/leerlingen/settings?logout" class="waves-effect waves-light btn color-primary--background">Log uw account overal uit</a>
        </div>
    </div>
</div>

<?php footer(); ?>
