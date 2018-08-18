<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/scripts/functions.php");

if (!$_SESSION['allow_register']) {
    $_SESSION['alert'] = "Deze link is reeds gebruikt of de URL is ongeldig!";
    header("location: /login/");
    exit;
} else {
    unset($_SESSION['allow_register']);
    $_SESSION['allow_register_confirm'] = 1;
}
?>
<!DOCTYPE html>
<html lang="nl">

<?php head('Registreer'); ?>

<body>
<img class="logo" src="/images/beta-sterren-logo.png">
<div class="form" style="margin-top:14px; margin-bottom:14px;">
    <?php alert(); ?>
    <div class="tab-content">
        <button class="button button-block active" style="background: #7A0036;">Registreer</button>
        <br><br>
        <div id="signup" style="display: block;">
            <form action="register" method="post" autocomplete="off">
                <div class="field-wrap">
                    <div style="display: inline-block;">
                        <input type="text" required  name='first_name' placeholder="Voornaam"
                               size="15" maxlength="15"/>
                    </div>
                    <div style="display: inline-block;">
                        <input type="text" required  name='last_name' placeholder="Achternaam"
                               size="30" maxlength="30"/>
                    </div>
                </div>
                <div class="field-wrap">
                    <label>
                        Email Adres<span class="req">*</span>
                    </label>
                    <input type="email" required  name='email'
                           value="<?= $_GET["email"]; ?>"/>
                </div>
                <div class="field-wrap">
                    <label class="textarea">
                        Over Mij<span class="req"></span>
                    </label>
                    <textarea  maxlength="5000" size="5000" name='over_mij' rows="5"
                              cols="20"></textarea>
                </div>
                <div class="field-wrap">
                    <label class="textarea">
                        Opleiding<span class="req"></span>
                    </label>
                    <textarea  maxlength="5000" size="5000" name='opleiding' rows="5"
                              cols="20"></textarea>
                </div>
                <div class="field-wrap">
                    <label class="textarea">
                        Werkervaring<span class="req"></span>
                    </label>
                    <textarea  maxlength="5000" size="5000" name='werk_ervaring' rows="5"
                              cols="20"></textarea>
                </div>
                <div class="field-wrap">
                    <label class="textarea">
                        Interesses<span class="req"></span>
                    </label>
                    <textarea  maxlength="5000" size="5000" name='interesses' rows="5"
                              cols="20"></textarea>
                </div>
                <div class="field-wrap">
                    <label>
                        Telefoon nummer
                    </label>
                    <input type="number"  size="10" maxlength="10" name='phone_number'/>
                </div>
                <div class="field-wrap">
                    <label>Klas</label><br>
                    <div class="radio-button">
                        <input type="radio" name="klas" value="4havo">4 Havo<br>
                        <input type="radio" name="klas" value="4vwo">4 Vwo<br>
                        <input type="radio" name="klas" value="5havo">5 Havo<br>
                        <input type="radio" name="klas" value="5vwo">5 Vwo<br>
                        <input type="radio" name="klas" value="6vwo">6 Vwo
                    </div>
                </div>
                <div class="field-wrap">
                    <label>Mentor</label><br>
                    <div class="radio-button">
                        <input type="radio" name="mentor-beta" value="bot">Mw. ir. H.E. Bot<br>
                        <input type="radio" name="mentor-beta" value="jacobs">Dhr. H.M. Jacobs<br>
                        <input type="radio" name="mentor-beta" value="kovel">Dhr. B. Kovel<br>
                        <input type="radio" name="mentor-beta" value="oost">Mw. K. Oost, MSc<br>
                        <input type="radio" name="mentor-beta" value="oosterbaan">Mw drs. J.M.A. Oosterbaan <br>
                        <input type="radio" name="mentor-beta" value="else" id="other_radio"><input type="text"
                                                                                                    name="mentor-else"

                                                                                                    placeholder="Anders"
                                                                                                    id="other_text"
                                                                                                    onFocus="if(this.value=='Anders') this.value='';document.getElementByID('other_radio').checked=true"
                                                                                                    onBlur="if(this.value=='') this.value='Anders';"/>
                    </div>
                </div>
                <div class="field-wrap">
                    <label>
                        Leerling nummer<span class="req">*</span>
                    </label>
                    <input type="number" required  size="6" maxlength="6" name='user_number'/>
                </div>
                <div class="field-wrap">
                    <label>
                        Kies een wachtwoord<span class="req">*</span>
                    </label>
                    <input type="password" required  name='password'/>
                </div>
                <div class="field-wrap">
                    <label>
                        Bevestig uw wachtwoord<span class="req">*</span>
                    </label>
                    <input type="password" required  name='password2'/>
                </div>
                <input type="hidden" name="token" value="<?= csrf_gen(); ?>"/>
                <button type="submit" class="button button-block" name="submit">Registreer</button>
            </form>
        </div>
    </div>
</div>
<script src='/js/jquery.min.js'></script>
<script src="/js/radioselect.js"></script>
<script src="/js/login-index.js"></script>
</body>

</html>
