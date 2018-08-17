<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/scripts/functions.php");

if ($_SESSION['admin_login'] != true) {
    $_SESSION['alert'] = 'You Shall Not PASSSSSSSS!';
    header("location: /login/");
    exit;
}

?>
<!DOCTYPE html>
<html lang="nl">

<?php head('Admin - Log In', "<script src='https://www.google.com/recaptcha/api.js'></script>"); ?>

<body>
<div id="Menu">
    <ul>
        <li><a href="/" class="hoverable"><span class="normal">Log Out</span><span class="hover">Log Out</span></a></li>
    </ul>
</div>
<img class="logo" src="/images/beta-sterren-logo.png">
<div class="form">
    <button class="button button-block active mb-30 background-7A0036 ">Administrator</button>
    <?php alert(); ?>
    <div class="tab-content">
        <form action="login-confirm" method="post" autocomplete="off">
            <div style="display: inline;">
                <div class="field-wrap" style="width: 35%; float: left;">
                    <label>2FA Token<span class="req">*</span></label>
                    <input type="number" required autocomplete="off" name="code" size="6" maxlength="6" autofocus/>
                </div>
                <div class="g-recaptcha" data-sitekey="6LfdLGQUAAAAAOpA5HxwP6Q6Q2XfsA7s8qpDCRVG"
                     style="margin-bottom: 30px; margin-left: 40%;"></div>
            </div>
            <input type="hidden" name="token" value="<?= csrf_gen(); ?>"/>
            <button class="button button-block" name="login">Log In</button>
        </form>
    </div>
</div>
<script src='/js/jquery.min.js'></script>
<script src="/js/login-index.js"></script>
</body>

</html>
