<?php
require($_SERVER['DOCUMENT_ROOT'] . '/init.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_val($_POST['CSRFtoken']);

    api_captcha($_POST['g-recaptcha-response'], '/auth/forgot');

    $email = clean_data($_POST['email']);

    is_empty([$email], '/auth/forgot');

    $queryDocent =
        "SELECT
            id
        FROM
            docenten
        WHERE
            email='{$email}'";

    $queryLeerling =
        "SELECT
            id
        FROM
            leerlingen
        WHERE
            email='{$email}'";

    $userDocent = sql_query($queryDocent, false);
    $userLeerling = sql_query($queryLeerling, false);

    if ($userDocent->num_rows == 0 && $userLeerling->num_rows == 0) {
        redirect('/?reset', 'Check uw email for een reset link');
    }

    if ($userDocent->num_rows > 0) {
        $aditional = json_encode(['docent' => '1']);
    } else {
        $aditional = json_encode(['docent' => '0']);
    }

    $token = gen(256);
    $date = current_date(true);
    $ip = ip();

    $query =
        "INSERT INTO
        tokens
            (token,
            type,
            created,
            days_valid,
            user,
            ip,
            additional)
        VALUES
            ('{$token}',
            'password_reset',
            '{$date}',
            '1',
            '{$email}',
            '{$ip}',
            '{$aditional}')";

    sql_query($query, false);

    $body = <<<END
    <!doctype html><html xmlns=http://www.w3.org/1999/xhtml xmlns:o=urn:schemas-microsoft-com:office:office xmlns:v=urn:schemas-microsoft-com:vml><title></title><!--[if !mso]><!-- --><meta content="IE=edge"http-equiv=X-UA-Compatible><!--<![endif]--><meta content="text/html; charset=UTF-8"http-equiv=Content-Type><meta content="width=device-width,initial-scale=1"name=viewport><style>#outlook a{padding:0}.ReadMsgBody{width:100%}.ExternalClass{width:100%}.ExternalClass *{line-height:100%}body{margin:0;padding:0;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%}table,td{border-collapse:collapse;mso-table-lspace:0;mso-table-rspace:0}img{border:0;height:auto;line-height:100%;outline:0;text-decoration:none;-ms-interpolation-mode:bicubic}p{display:block;margin:13px 0}</style><!--[if !mso]><!--><style>@media only screen and (max-width:480px){@-ms-viewport{width:320px}@viewport{width:320px}}</style><!--<![endif]--><!--[if mso]><xml><o:officedocumentsettings><o:allowpng><o:pixelsperinch>96</o:pixelsperinch></o:officedocumentsettings></xml><![endif]--><!--[if lte mso 11]><style>.outlook-group-fix{width:100%!important}</style><![endif]--><!--[if !mso]><!--><link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700"rel=stylesheet><style>@import url(https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700);</style><!--<![endif]--><style>@media only screen and (min-width:480px){.mj-column-per-100{width:100%!important}}</style><body style=background:#fff><div style=background-color:#fff class=mj-container><!--[if mso | IE]><table border=0 cellpadding=0 cellspacing=0 role=presentation align=center style=width:600px width=600><tr><td style=line-height:0;font-size:0;mso-line-height-rule:exactly><![endif]--><div style="margin:0 auto;max-width:600px"><table border=0 cellpadding=0 cellspacing=0 role=presentation align=center style=font-size:0;width:100%><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0;padding:9px 0 9px 0"><!--[if mso | IE]><table border=0 cellpadding=0 cellspacing=0 role=presentation><tr><td style=vertical-align:top;width:600px><![endif]--><div style=vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100% class="mj-column-per-100 outlook-group-fix"><table border=0 cellpadding=0 cellspacing=0 role=presentation width=100%><tr><td style=word-wrap:break-word;font-size:0;padding:0 align=right><table border=0 cellpadding=0 cellspacing=0 role=presentation align=right style=border-collapse:collapse;border-spacing:0><tr><td style=width:240px><a href={$GLOBALS['config']->app->url} target=_blank><img alt="BetaSterren Logo"height=auto src={$GLOBALS['config']->cdn->images->logo} style=border:none;border-radius:0;display:block;font-size:13px;outline:0;text-decoration:none;width:100%;height:auto width=240></a></table><tr><td style="word-wrap:break-word;font-size:0;padding:0 20px 0 20px"align=left><div style=cursor:auto;color:#000;font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px;text-align:left><p><span style=font-size:16px>Beste docent/leerling,</span><p><span style=font-size:16px>Iemand heeft een wachtwoord reset voor uw account aangevraagd.</span><p><span style=font-size:16px>Als u dit was klik op de link, anders verwijder de mail.</span></div><tr><td style="word-wrap:break-word;font-size:0;padding:0 20px 0 20px"align=center><div style=cursor:auto;color:#000;font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px;text-align:center><p><span style=font-size:16px><a href="{$GLOBALS['config']->app->url}/auth/reset?token={$token}">Reset mijn wachtwoord</a></span></div><tr><td style="word-wrap:break-word;font-size:0;padding:0 20px 0 20px"align=left><div style=cursor:auto;color:#000;font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px;text-align:left><p><span style=font-size:16px>Met vriendelijke groet,</span><p><span style=font-size:16px>Het Baarnsch Lyceum</span></div></table></div><!--[if mso | IE]><![endif]--></table></div><!--[if mso | IE]><![endif]--></div>
END;

    api_mail($email, 'Nieuw wachtwoord verzoek ||  BetaSterren', $body);

    log_action('user.forgot_password', $email);

    redirect('/?reset', 'Check uw email for een reset link');
}

head('Wachtwoord vergeten', 10);

?>

<div class="row">
    <div class="col s12 m8 offset-m2 l4 offset-l4">
        <div class="card login">
            <div class="card-action color-primary--background hover-disable white-text">
                <h3>Wachtwoord vergeten</h3>
            </div>
            <div class="card-content">
                <form action="/auth/forgot.php" method="post">
                    <div class="row">
                        <div class="input-field col s12">
                            <label for="email">Uw email adress</label>
                            <input type="email" id="email" name="email" required=""/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="g-recaptcha" data-sitekey="<?= $GLOBALS['config']->api->recaptcha->public ?>"></div>
                    </div>
                    <div class="row">
                        <input type="hidden" name="CSRFtoken" value="<?= csrf_gen() ?>"/>
                        <button type="submit" class="waves-effect waves-light btn color-primary--background width-full">
                            Vraag nieuw wachtwoord aan
                        </button>
                    </div>
                    <a href="/" class="right">Terug naar login</a>
                </form>
            </div>
        </div>
    </div>
</div>

<!--Import Materialize JavaScript-->
<script src="<?= $GLOBALS['config']->cdn->js->materialize->library ?>"></script>
<?php alert_display(); ?>
<!--Import Recaptcha JavaScript-->
<script src="<?= $GLOBALS['config']->api->recaptcha->library ?>"></script>
<!--Import Partciles.JS JavaScript-->
<script src="<?= $GLOBALS['config']->cdn->js->particle->library ?>"></script>
<canvas class="background"></canvas>
<script src="<?= $GLOBALS['config']->cdn->js->particle->init ?>"></script>
</body>

</html>
