<?php
require($_SERVER['DOCUMENT_ROOT'] . "/init.php");

login_leerling();

head('Settings', 0);

$query =
"SELECT
    first_name,
    last_name,
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
            <img src="<?= $user['profile_url'] ?>" alt="Profiel Foto" class="responsive-img" onerror="this.src='https://cdn.lucacastelnuovo.nl/images/betasterren/default_profile.png'">
        </div>
        <div class="col s0 m10"></div>
    </div>
    <div class="row">
        <div class="col 12">
            <h3><?= $user['first_name'] ?> <?= $user['last_name'] ?></h3>
        </div>
    </div>
    <div class="row">
        <div class="col s12">
            <a href="/general/upload/leerling_profile/" class="waves-effect waves-light btn color-primary--background">Change Profile Picture</a>
            <a href="/auth/change" class="waves-effect waves-light btn color-primary--background">Change Password</a>
        </div>
    </div>
</div>

<?php footer(); ?>
