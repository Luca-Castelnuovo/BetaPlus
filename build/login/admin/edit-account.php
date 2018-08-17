<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/scripts/functions.php");

login_admin();

$user_number = escape_data($_GET['user'], 100, '/login/admin/');
$result = $mysqli->query("SELECT * FROM users WHERE user_number='$user_number'");
$row = $result->fetch_assoc();
$email = $row['email'];
$first_name = $row["first_name"];
$last_name = $row["last_name"];
$class = $row['class'];
$phone_number = $row['phone_number'];
$over_mij = $row['over_mij'];
$opleiding = $row['opleiding'];
$werk_ervaring = $row['werk_ervaring'];
$interesses = $row['interesses'];
$mentor = $row['mentor'];
$utalent = $row['utalent'];
$user_type = $row['user_type'];
$active = $row['active'];
switch ($class) {
    case '4havo':
        $checked1 = 'checked';
        break;
    case '4vwo':
        $checked2 = 'checked';
        break;
    case '5havo':
        $checked3 = 'checked';
        break;
    case '5vwo':
        $checked4 = 'checked';
        break;
    case '6vwo':
        $checked5 = 'checked';
        break;
    case 'docent':
        $checked16 = 'checked';
        break;
    case 'toa':
        $checked17 = 'checked';
        break;
}
switch ($mentor) {
    case 'bot':
        $checked6 = 'checked';
        break;
    case 'kovel':
        $checked8 = 'checked';
        break;
    case 'jacobs':
        $checked7 = 'checked';
        break;
    case 'oost':
        $checked9 = 'checked';
        break;
    case 'oosterbaan':
        $checked10 = 'checked';
        break;
    default:
        $mentor_default = $mentor;
        $checked11 = 'checked';
}
switch ($user_type) {
    case '1':
        $checked13 = 'checked';
        break;
    case '0':
        $checked12 = 'checked';
        break;
}
switch ($active) {
    case '1':
        $checked14 = 'checked';
        break;
    case '0':
        $checked15 = 'checked';
        break;
}
switch ($utalent) {
    case '*':
        $checked20 = 'checked';
        break;
    case '':
        $checked21 = 'checked';
        break;
    default:
        $checked21 = 'checked';
}
$randompassword = gen(6);
?>
<!DOCTYPE html>
<html lang="">

<?php head('Admin - Edit Account'); ?>

<body>
<div id="Menu">
    <ul>
        <li><a href="select-account" class="hoverable"><span class="normal">Edit Account</span><span class="hover">Terug naar select account</span></a>
        </li>
    </ul>
</div>
<?php alert(); ?>
<img id="logo" src="/images/beta-sterren-logo.png">
<h2>Edit user account</h2>
<div class="edit">
    <ul>
        <div class="form send-code edit" id="savatar">
            <div class="tab-content">
                <div id="send-code" style="display: block;">
                    <form action="/login/admin/confirm-account?user=<?= $user_number ?>" method="post"
                          autocomplete="off" enctype="multipart/form-data">
                        <div class="top-row">
                            <div class="field-wrap">
                                <input type="file" name="edit-avatar" accept=".png">
                            </div>
                            <input type="hidden" name="token" value="<?= csrf_gen(); ?>"/>
                            <button type="submit" class="button" name="edit-submit">Confirm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="form send-code edit" id="snaam">
            <div class="tab-content">
                <div id="send-code" style="display: block;">
                    <form action="/login/admin/confirm-account?user=<?= $user_number ?>" method="post"
                          autocomplete="off">
                        <div class="top-row">
                            <div class="field-wrap">
                                <input type="text" required value="<?= $first_name ?>" placeholder="Voor Naam"
                                       autocomplete="off" name='edit-first-name'/>
                            </div>
                            <input type="hidden" name="token" value="<?= csrf_gen(); ?>"/>
                            <button type="submit" class="button" name="edit-submit">Confirm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="form send-code edit">
            <div class="tab-content">
                <div id="send-code" style="display: block;">
                    <form action="/login/admin/confirm-account?user=<?= $user_number ?>" method="post"
                          autocomplete="off">
                        <div class="top-row">
                            <div class="field-wrap">
                                <input type="text" required value="<?= $last_name ?>" placeholder="Achter Naam"
                                       autocomplete="off" name='edit-last-name'/>
                            </div>
                            <input type="hidden" name="token" value="<?= csrf_gen(); ?>"/>
                            <button type="submit" class="button" name="edit-submit">Confirm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="form send-code edit" id="semail">
            <div class="tab-content">
                <div id="send-code" style="display: block;">
                    <form action="/login/admin/confirm-account?user=<?= $user_number ?>" method="post"
                          autocomplete="off">
                        <div class="top-row">
                            <div class="field-wrap">
                                <input type="text" required value="<?= $email ?>" placeholder="E-Mail"
                                       autocomplete="off" name='edit-email'/>
                            </div>
                            <input type="hidden" name="token" value="<?= csrf_gen(); ?>"/>
                            <button type="submit" class="button" name="edit-submit">Confirm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="form send-code edit" id="sphone">
            <div class="tab-content">
                <div id="send-code" style="display: block;">
                    <form action="/login/admin/confirm-account?user=<?= $user_number ?>" method="post"
                          autocomplete="off">
                        <div class="top-row">
                            <div class="field-wrap">
                                <input type="text" required value="<?= $phone_number ?>" placeholder="Telefoon Nummer"
                                       autocomplete="off" name='edit-phone-number'/>
                            </div>
                            <input type="hidden" name="token" value="<?= csrf_gen(); ?>"/>
                            <button type="submit" class="button" name="edit-submit">Confirm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="form send-code edit">
            <div class="tab-content" id="sovermij">
                <div id="send-code" style="display: block;">
                    <form action="/login/admin/confirm-account?user=<?= $user_number ?>" method="post"
                          autocomplete="off">
                        <div class="top-row">
                            <div class="field-wrap">
                                <textarea required autocomplete="off" placeholder="Over Mij"
                                          name='edit-over-mij' rows="10" cols="100"><?= $over_mij ?></textarea>
                                <input type="hidden" name="token" value="<?= csrf_gen(); ?>"/>
                                <button type="submit" class="button" name="edit-submit">Confirm</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="form send-code edit">
            <div class="tab-content">
                <div id="send-code" style="display: block;">
                    <form action="/login/admin/confirm-account?user=<?= $user_number ?>" method="post"
                          autocomplete="off">
                        <div class="top-row">
                            <div class="field-wrap">
                                <textarea required autocomplete="off" placeholder="Opleiding"
                                          name='edit-opleiding' rows="10" cols="100"><?= $opleiding ?></textarea>
                                <input type="hidden" name="token" value="<?= csrf_gen(); ?>"/>
                                <button type="submit" class="button" name="edit-submit">Confirm</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="form send-code edit">
            <div class="tab-content">
                <div id="send-code" style="display: block;">
                    <form action="/login/admin/confirm-account?user=<?= $user_number ?>" method="post"
                          autocomplete="off">
                        <div class="top-row">
                            <div class="field-wrap">
                                <textarea required autocomplete="off" placeholder="Werk Ervaring"
                                          name='edit-werk-ervaring' rows="10"
                                          cols="100"><?= $werk_ervaring ?></textarea>
                                <input type="hidden" name="token" value="<?= csrf_gen(); ?>"/>
                                <button type="submit" class="button" name="edit-email-submit">Confirm</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="form send-code edit">
            <div class="tab-content">
                <div id="send-code" style="display: block;">
                    <form action="/login/admin/confirm-account?user=<?= $user_number ?>" method="post"
                          autocomplete="off">
                        <div class="top-row">
                            <div class="field-wrap">
                                <textarea required autocomplete="off" placeholder="Interesses"
                                          name='edit-interesses' rows="10" cols="100"><?= $interesses ?></textarea>
                                <input type="hidden" name="token" value="<?= csrf_gen(); ?>"/>
                                <button type="submit" class="button" name="edit-submit">Confirm</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="form send-code edit">
            <div class="tab-content">
                <div id="send-code" style="display: block;">
                    <form action="/login/admin/confirm-account?user=<?= $user_number ?>" method="post"
                          autocomplete="off">
                        <div class="top-row">
                            <div class="field-wrap">
                                <label>Klas</label><br>
                                <div class="radio-button">
                                    <input type="radio" name="edit-class" value="4havo" <?= $checked1 ?>>4 Havo<br>
                                    <input type="radio" name="edit-class" value="4vwo" <?= $checked2 ?>>4 Vwo<br>
                                    <input type="radio" name="edit-class" value="5havo" <?= $checked3 ?>>5 Havo<br>
                                    <input type="radio" name="edit-class" value="5vwo" <?= $checked4 ?>>5 Vwo<br>
                                    <input type="radio" name="edit-class" value="6vwo" <?= $checked5 ?>>6 Vwo<br>
                                    <input type="radio" name="edit-class" value="docent" <?= $checked16 ?>>Docent<br>
                                    <input type="radio" name="edit-class" value="toa" <?= $checked17 ?>>Toa
                                </div>
                            </div>
                            <input type="hidden" name="token" value="<?= csrf_gen(); ?>"/>
                            <button type="submit" class="button" name="edit-email-submit">Confirm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="form send-code edit">
            <div class="tab-content">
                <div id="send-code" style="display: block;">
                    <form action="/login/admin/confirm-account?user=<?= $user_number ?>" method="post"
                          autocomplete="off">
                        <div class="top-row">
                            <div class="field-wrap">
                                <div class="radio-button">
                                    <input type="radio" name="edit-mentor" value="bot" <?= $checked6 ?>>Mw. ir. H.E. Bot<br>
                                    <input type="radio" name="edit-mentor" value="jacobs" <?= $checked7 ?>>Dhr. H.M.
                                    Jacobs<br>
                                    <input type="radio" name="edit-mentor" value="kovel" <?= $checked8 ?>>Dhr. B.
                                    Kovel<br>
                                    <input type="radio" name="edit-mentor" value="oost" <?= $checked9 ?>>Mw. K. Oost,
                                    MSc<br>
                                    <input type="radio" name="edit-mentor" value="oosterbaan" <?= $checked10 ?>>Mw drs.
                                    J.M.A. Oosterbaan<br>
                                    <input type="radio" name="edit-mentor" <?= $checked11 ?> value="else"
                                           id="other_radio"><input
                                            style="display:inline-block !important; max-width: 200px; padding: 0;"
                                            type="text" name="edit-mentor-else" value="<?= $mentor_default ?>"
                                            autocomplete="off" placeholder="Anders" id="other_text"
                                            onFocus="if(this.value=='Anders') this.value='';document.getElementByID('other_radio').checked=true"
                                            onBlur="if(this.value=='') this.value='Anders';"/>
                                </div>
                            </div>
                            <input type="hidden" name="token" value="<?= csrf_gen(); ?>"/>
                            <button type="submit" class="button" name="edit-submit">Confirm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="form send-code edit">
            <div class="tab-content">
                <div id="send-code" style="display: block;">
                    <form action="/login/admin/confirm-account?user=<?= $user_number ?>" method="post"
                          autocomplete="off">
                        <div class="top-row">
                            <div class="field-wrap">
                                <textarea required autocomplete="off" placeholder="Alert to user"
                                          name='edit-alert' rows="10" cols="100"></textarea>
                                <input type="hidden" name="token" value="<?= csrf_gen(); ?>"/>
                                <button type="submit" class="button" name="edit-submit">Confirm</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="form send-code edit">
            <div class="tab-content">
                <div id="send-code" style="display: block;">
                    <form action="/login/admin/confirm-account?user=<?= $user_number ?>" method="post"
                          autocomplete="off">
                        <div class="top-row">
                            <div class="field-wrap">
                                <label>User Type</label><br>
                                <div class="radio-button">
                                    <input type="radio" name="edit-type" value="user" <?= $checked12 ?>>User<br>
                                    <input type="radio" name="edit-type" value="admin" <?= $checked13 ?>>Admin<br>
                                </div>
                            </div>
                            <input type="hidden" name="token" value="<?= csrf_gen(); ?>"/>
                            <button type="submit" class="button" name="edit-email-submit">Confirm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="form send-code edit">
            <div class="tab-content">
                <div id="send-code" style="display: block;">
                    <form action="/login/admin/confirm-account?user=<?= $user_number ?>" method="post"
                          autocomplete="off">
                        <div class="top-row">
                            <div class="field-wrap">
                                <label>Activatie</label><br>
                                <div class="radio-button">
                                    <input type="radio" name="edit-active" value="active" <?= $checked14 ?>>Actief<br>
                                    <input type="radio" name="edit-active"
                                           value="inactive" <?= $checked15 ?>>Inactief<br>
                                </div>
                            </div>
                            <input type="hidden" name="token" value="<?= csrf_gen(); ?>"/>
                            <button type="submit" class="button" name="edit-email-submit">Confirm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="form send-code edit">
            <div class="tab-content">
                <div id="send-code" style="display: block;">
                    <form action="/login/admin/confirm-account?user=<?= $user_number ?>" method="post"
                          autocomplete="off">
                        <div class="top-row">
                            <div class="field-wrap">
                                <label>U-Talent</label><br>
                                <div class="radio-button">
                                    <input type="radio" name="edit-utalent" value="*" <?= $checked20 ?>>Ja<br>
                                    <input type="radio" name="edit-utalent" value="remove" <?= $checked21 ?>>Nee<br>
                                </div>
                            </div>
                            <input type="hidden" name="token" value="<?= csrf_gen(); ?>"/>
                            <button type="submit" class="button" name="edit-email-submit">Confirm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="form send-code edit" id="snaam">
            <div class="tab-content">
                <div id="send-code" style="display: block;">
                    <form action="/login/admin/confirm-account?user=<?= $user_number ?>" method="post"
                          autocomplete="off">
                        <div class="top-row" style="text-align:center;">
                            <input type="hidden" name="edit-password" value="<?= $randompassword ?>">
                            <input type="hidden" name="token" value="<?= csrf_gen(); ?>"/>
                            <button style="float:none;" type="submit" class="button" value="password"
                                    name="edit-password-active"
                                    onclick="return confirm('Weet je zeker dat je het wachtwoord wilt veranderen.\n PIN:	<?= $randompassword ?>');">
                                Reset Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="form send-code edit" id="snaam">
            <div class="tab-content">
                <div id="send-code" style="display: block;">
                    <form action="/login/admin/confirm-account?user=<?= $user_number ?>" method="post"
                          autocomplete="off">
                        <div class="top-row" style="text-align:center;">
                            <input type="hidden" name="token" value="<?= csrf_gen(); ?>"/>
                            <button style="float:none;" type="submit" class="button" value="unblock"
                                    name="edit-unblock">Unblock Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="form send-code edit" id="snaam">
            <div class="tab-content">
                <div id="send-code" style="display: block;">
                    <form action="/login/admin/confirm-account?user=<?= $user_number ?>" method="post"
                          autocomplete="off">
                        <div class="top-row" style="text-align:center;">
                            <input type="hidden" name="token" value="<?= csrf_gen(); ?>"/>
                            <button style="float:none;" type="submit" class="button" value="delete" name="edit-delete"
                                    onclick="return confirm('Weet je zeker dat je dit account wilt deleten?');">Delete
                                Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </ul>
</div>
<script src="/js/radioselect.js"></script>
</body>

</html>
