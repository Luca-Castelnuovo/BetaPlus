<?php
require($_SERVER['DOCUMENT_ROOT'] . "/init.php");

login_docent();

head('Settings', 0);

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
        <div class="col s12">
            <a href="/auth/change" class="waves-effect waves-light btn color-primary--background">Change Password</a>
        </div>
    </div>
</div>

<?php footer(); ?>
