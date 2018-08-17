<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/scripts/functions.php");
login();
?>
<!DOCTYPE html>
<html lang="nl">

<?php head('Verander Wachtwoord'); ?>

<body>
<?php menu('Verander Wachtwoord'); ?><img class="logo" src="/images/beta-sterren-logo.png">
<div class="form">
    <h1>Kies uw nieuwe wachtwoord</h1>
    <form action="reset-password2" method="post">
        <div class="field-wrap">
            <label>
                Oude Wachtwoord<span class="req">*</span>
            </label>
            <input type="password" required name="oldpassword" autocomplete="off"/>
        </div>
        <div class="field-wrap">
            <label>
                Nieuw Wachtwoord<span class="req">*</span>
            </label>
            <input type="password" required name="newpassword" autocomplete="off"/>
        </div>
        <div class="field-wrap">
            <label>
                Bevestig Nieuw Wachtwoord<span class="req">*</span>
            </label>
            <input type="password" required name="confirmpassword" autocomplete="off"/>
            <input type="hidden" name="token" value="<?= csrf_gen(); ?>"/>
        </div>
        <button class="button button-block">Bevestig</button>
    </form>
</div>
<script src="/js/jquery.min.js"></script>
<script src="/js/login-index.js"></script>
</body>

</html>
