<?php
session_start();
if ($_SESSION['allow_register_docent'] != 1) {
    $_SESSION['alert'] = "Deze link is reeds gebruikt of de URL is ongeldig!";
    header("location: index");
} else {
    unset($_SESSION['allow_register_docent']);
    $_SESSION['allow_register_docent_confirm'] = 1;
}
?>
<!DOCTYPE html>
<html lang="nl">


<?php head('Registreer - Docent'); ?>

<body>
<img class="logo" src="/images/beta-sterren-logo.png">
<div class="form" style="margin-top:14px; margin-bottom:14px;">
    <?php alert(); ?>
    <div class="tab-content">
        <button class="button button-block active" style="background: #7A0036;">Registreer</button>
        <br><br>
        <div id="signup" style="display: block;">
            <form action="new-docent-register" method="post" autocomplete="off">
                <div class="field-wrap">
                    <div style="display: inline-block;">
                        <input type="text" required autocomplete="off" name='first_name' placeholder="Voornaam"
                               size="15" maxlength="15"/>
                    </div>
                    <div style="display: inline-block;">
                        <input type="text" required autocomplete="off" name='last_name' placeholder="Achternaam"
                               size="30" maxlength="30"/>
                    </div>
                </div>
                <div class="field-wrap">
                    <label>
                        Email Adres<span class="req">*</span>
                    </label>
                    <input type="email" required autocomplete="off" name='email_register'/>
                </div>
                <div class="field-wrap">
                    <label class="textarea">
                        Over Mij<span class="req"></span>
                    </label>
                    <textarea autocomplete="off" name='over_mij' rows="5" cols="20" size="5000"
                              maxlength="5000"></textarea>
                </div>
                <div class="field-wrap">
                    <label class="textarea">
                        Opleiding<span class="req"></span>
                    </label>
                    <textarea autocomplete="off" name='opleiding' rows="5" cols="20" size="5000"
                              maxlength="5000"></textarea>
                </div>
                <div class="field-wrap">
                    <label class="textarea">
                        Werkervaring<span class="req"></span>
                    </label>
                    <textarea autocomplete="off" name='werk_ervaring' rows="5" cols="20" size="5000"
                              maxlength="5000"></textarea>
                </div>
                <div class="field-wrap">
                    <label class="textarea">
                        Interesses<span class="req"></span>
                    </label>
                    <textarea autocomplete="off" name='interesses' rows="5" cols="20" size="5000"
                              maxlength="5000"></textarea>
                </div>
                <div class="field-wrap">
                    <label>
                        Telefoon nummer
                    </label>
                    <input type="number" autocomplete="off" name='phone_number' size="10" maxlength="10"/>
                </div>
                <div class="field-wrap">
                    <label>Type</label><br>
                    <div class="radio-button">
                        <input type="radio" name="class" value="docent" checked>Docent<br>
                        <input type="radio" name="class" value="toa">Toa
                    </div>
                </div>
                <div class="field-wrap">
                    <label>
                        Kies een wachtwoord<span class="req">*</span>
                    </label>
                    <input type="password" required autocomplete="off" name='password'/>
                </div>
                <input type="hidden" name="token" value="<?= csrf_gen(); ?>"/>
                <button type="submit" class="button button-block" name="register">Registreer</button>
            </form>
        </div>
    </div>
</div>
<script src='/js/jquery.min.js'></script>
<script src="/js/login-index.js"></script>
</body>

</html>
