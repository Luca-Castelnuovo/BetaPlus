<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/scripts/functions.php");

if (!isset($_GET['email']) || !isset($_GET['code'])) {
    $_SESSION['alert'] = "U heeft een ongeldige URL ingevoerd of de link is niet meer geldig!";
    header("location: /login/");
    exit;
}
?>
<!DOCTYPE html>
<html lang="nl">

<?php head('Reset Wachtwoord'); ?>

<body>
<div id="Menu">
    <ul>
        <li><a href="/login/" class="hoverable"><span class="normal">Reset Wachtwoord</span><span class="hover">Terug naar Log In</span></a>
        </li>
        <div id="MenuRight">
            <li><a href="/login/">Log In</a></li>
        </div>
    </ul>
</div>
<?php alert(); ?>
<img class="logo" src="/images/beta-sterren-logo.png">
<div class="form">
    <h1>Kies uw nieuwe wachtwoord</h1>
    <form action="reset-password" method="post">
        <div class="field-wrap">
            <label>Nieuw Wachtwoord<span class="req">*</span></label>
            <input type="password" required name="newpassword" autocomplete="off"/>
        </div>
        <div class="field-wrap">
            <label>Bevestig Nieuw Wachtwoord<span class="req">*</span></label>
            <input type="password" required name="confirmpassword" autocomplete="off"/>
        </div>
        <input type="hidden" name="email" value="<?= $_GET['email']; ?>">
        <input type="hidden" name="code" value="<?= $_GET['code'];; ?>">
        <input type="hidden" name="token" value="<?= csrf_gen(); ?>"/>
        <button class="button button-block">Bevestig</button>
    </form>
</div>
<script src="/js/jquery.min.js"></script>
<script src="/js/login-index.js"></script>
</body>

</html>
