<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/scripts/functions.php"); ?>
<!DOCTYPE html>
<html lang="nl">

<?php head('Wachtwoord Vergeten', "<script src='https://www.google.com/recaptcha/api.js'></script>"); ?>

<body>
<div id="Menu">
    <ul>
        <li><a href="/login/" class="hoverable">Terug naar Log In</a></li>
    </ul>
</div>
<?php alert(); ?>
<img class="logo" src="/images/beta-sterren-logo.png">
<div class="form">
    <h1>Reset Je Wachtwoord</h1>
    <form action="forgot-confirm" method="post">
        <div class="field-wrap" style="margin-bottom: 30px;">
            <label>
                Email Adres<span class="req">*</span>
            </label>
            <input type="email" required autocomplete="off" name="email_reset_password"/>
        </div>
        <div class="g-recaptcha" data-sitekey="6LfdLGQUAAAAAOpA5HxwP6Q6Q2XfsA7s8qpDCRVG"
             style="margin-bottom: 20px;"></div>
        <input type="hidden" name="token" value="<?= csrf_gen(); ?>"/>
        <button class="button button-block">Reset</button>
    </form>
</div>
<script src="/js/jquery.min.js"></script>
<script src="/js/login-index.js"></script>
</body>

</html>
