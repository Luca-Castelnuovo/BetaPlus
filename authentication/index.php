<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/scripts/functions.php");

foreach ($_SESSION as $key => $val) {
    if (($key !== 'return_url') && ($key !== 'allow_register_confirm') && ($key !== 'alert') && ($key !== 'token')) {
        unset($_SESSION[$key]);
    }
}

?>
<!DOCTYPE html>
<html lang="nl">

<?php head('Log In'); ?>

<body>
<div id="Menu">
    <ul>
        <li><a href="javascript:void();" class="hoverable"><span class="normal" style="opacity: 0;">.</span><span
                        class="hover" style="opacity: 0;">.</span></a></li>
    </ul>
</div>
<img class="logo" src="/images/beta-sterren-logo.png">
<div class="form">
    <button class="button button-block active mb-30 background-7A0036 ">Leerlingen / Docenten</button>
    <?php alert_login(); ?>
    <div class="tab-content">
        <form action="login" method="post" autocomplete="off">
            <div class="field-wrap">
                <label>
                    Leerling nummer / Email<span class="req">*</span>
                </label>
                <input type="text" required autocomplete="off" name="user_number" autofocus/>
            </div>
            <div class="field-wrap">
                <label>
                    Wachtwoord<span class="req">*</span>
                </label>
                <input type="password" required autocomplete="off" name="password"/>
            </div>
            <input type="hidden" name="token" value="<?= csrf_gen(); ?>"/>
            <p class="forgot"><a href="forgot">Wachtwoord Vergeten?</a></p>
            <button class="button button-block">Log In</button>
        </form>
    </div>
</div>
<script src='/js/jquery.min.js'></script>
<script src="/js/login-index.js"></script>
</body>

</html>
