<?php
require($_SERVER['DOCUMENT_ROOT'] . "/init.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_val($_POST['CSRFtoken']);
    api_captcha($_POST['g-recaptcha-response']);

    if (empty($_POST['email'])) {
        redirect('/auth/change', 'Vul aub alle velden in');
    }

    $email = clean_data($_POST['email']);

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

    $token = gen(128);
    $date = date('Y-m-d H:i:s');

    $query =
    "INSERT INTO
        tokens
            (token,
            type,
            created,
            days_valid,
            user)
    VALUES
        ('{$token}',
        'password_reset',
        '{$date}',
        '7',
        '{$email}')";

    sql_query($query, false);

    $body = <<<END
    <!DOCTYPE html><html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office"><head> <title></title> <meta http-equiv="X-UA-Compatible" content="IE=edge"> <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><style type="text/css"> #outlook a{padding: 0;}.ReadMsgBody{width: 100%;}.ExternalClass{width: 100%;}.ExternalClass *{line-height:100%;}body{margin: 0; padding: 0; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;}table, td{border-collapse:collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;}img{border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;}p{display: block; margin: 13px 0;}</style><style type="text/css"> @media only screen and (max-width:480px){@-ms-viewport{width:320px;}@viewport{width:320px;}}</style> <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700" rel="stylesheet" type="text/css"> <style type="text/css"> @import url(https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700); </style> <style type="text/css"> @media only screen and (min-width:480px){.mj-column-per-100{width:100%!important;}.mj-column-per-50{width:50%!important;}}</style></head><body style="background: #FFFFFF;"> <div class="mj-container" style="background-color:#FFFFFF;"><div style="margin:0px auto;max-width:600px;"><table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" align="center" border="0"><tbody><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:9px 0px 9px 0px;"><div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-wrap:break-word;font-size:0px;padding:0px 0px 0px 0px;" align="right"><table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:collapse;border-spacing:0px;" align="right" border="0"><tbody><tr><td style="width:240px;"><a href="https://betasterren.lucacastelnuovo.nl" target="_blank"><img alt="BetaSterren Logo" title="" height="auto" src="https://cdn.lucacastelnuovo.nl/images/betasterren/logo.png" style="border:none;border-radius:0px;display:block;font-size:13px;outline:none;text-decoration:none;width:100%;height:auto;" width="240"></a></td></tr></tbody></table></td></tr><tr><td style="word-wrap:break-word;font-size:0px;padding:0px 20px 0px 20px;" align="left"><div style="cursor:auto;color:#000000;font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:11px;line-height:22px;text-align:left;"><p><span style="font-size:14px;">Beste leerling/docent,</span></p><p><span style="font-size:14px;">Iemand heeft een wachtwoord reset voor uw account aangevraagd.</span></p></div></td></tr></tbody></table></div></td></tr></tbody></table></div><table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" border="0"><tbody><tr><td><div style="margin:0px auto;max-width:600px;"><table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" align="center" border="0"><tbody><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:9px 0px 9px 0px;"><div class="mj-column-per-50 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-wrap:break-word;font-size:0px;padding:0px 0px 0px 0px;padding-top:10px;padding-left:25px;" align="center"><table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:separate;" align="center" border="0"><tbody><tr><td style="border:none;border-radius:24px;color:#fff;cursor:auto;padding:10px 25px;" align="center" valign="middle" bgcolor="#003D14"><a href="https://betasterren.lucacastelnuovo.nl/auth/reset?my=true&token={$token}" style="text-decoration:none;background:#003D14;color:#fff;font-family:Arial, sans-serif;font-size:14px;font-weight:normal;line-height:120%;text-transform:none;margin:0px;" target="_blank">Dat was ik</a></td></tr></tbody></table></td></tr></tbody></table></div><div class="mj-column-per-50 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-wrap:break-word;font-size:0px;padding:0px 0px 0px 0px;padding-top:10px;padding-left:25px;" align="center"><table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:separate;" align="center" border="0"><tbody><tr><td style="border:none;border-radius:24px;color:#fff;cursor:auto;padding:10px 25px;" align="center" valign="middle" bgcolor="#7A0036"><a href="https://betasterren.lucacastelnuovo.nl/auth/reset?my=false&token={$token}" style="text-decoration:none;background:#7A0036;color:#fff;font-family:Arial, sans-serif;font-size:14px;font-weight:normal;line-height:120%;text-transform:none;margin:0px;" target="_blank">Dat was ik NIET</a></td></tr></tbody></table></td></tr></tbody></table></div></td></tr></tbody></table></div></td></tr></tbody></table> <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" border="0"><tbody><tr><td><div style="margin:0px auto;max-width:600px;"><table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" align="center" border="0"><tbody><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:9px 0px 9px 0px;"><div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-wrap:break-word;font-size:0px;"><div style="font-size:1px;line-height:25px;white-space:nowrap;">&#xA0;</div></td></tr></tbody></table></div></td></tr></tbody></table></div></td></tr></tbody></table> <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" border="0"><tbody><tr><td><div style="margin:0px auto;max-width:600px;"><table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" align="center" border="0"><tbody><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:9px 0px 9px 0px;"><div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-wrap:break-word;font-size:0px;padding:0px 20px 0px 20px;" align="left"><div style="cursor:auto;color:#000000;font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:11px;line-height:22px;text-align:left;"><p><span style="font-size: 14px;">Met vriendelijke groet,</span></p><p><span style="font-size: 14px;">Een server in amsterdam</span></p></div></td></tr></tbody></table></div></td></tr></tbody></table></div></td></tr></tbody></table></div></body></html>
END;

    api_mail($email, 'Nieuw wachtwoord aanvragen ||  BetaSterren', $body);

    // TODO: log action

    redirect('/?reset', 'Check uw email for een reset link');
}
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#003d14">

    <title>Wachtwoord vergeten</title>
    <meta content="INSERT DESCRIPTION" name="description">
    <meta content="Betasterren, Beta Sterren, Het Baarnsch Lyceum, beta+, beta hbl" name="keywords">

    <!-- Tells Google not to provide a translation for this document -->
    <meta name="google" content="notranslate">

    <!-- Control the behavior of search engine crawling and indexing -->
    <meta name="robots" content="index,follow">
    <meta name="googlebot" content="index,follow">

    <!-- Favicons/Icons -->
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="icon" sizes="192x192" href="https://betasterren.lucacastelnuovo.nl/favicon.png">
    <link rel="apple-touch-icon" href="https://betasterren.lucacastelnuovo.nl/favicon.png">
    <link rel="mask-icon" href="https://betasterren.lucacastelnuovo.nl/favicon.png" color="green">

    <!--Import Materialize CSS-->
    <link rel="preconnect" href="https://cdnjs.cloudflare.com/" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/css/materialize.min.css">

    <!--Import Custom CSS-->
    <link rel="stylesheet" href="/css/style.css">

    <!--Import Google Icon Font-->
    <link rel="preconnect" href="https://fonts.googleapis.com/" crossorigin>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body class="bg-image">
    <div class="row">
        <div class="col s12 m4 offset-m4">
            <div class="card login">
                <div class="card-action color-primary--background hover-disable white-text">
                    <h3>Wachtwoord vergeten</h3>
                </div>
                <div class="card-content">
                    <form action="/auth/forgot.php" method="post">
                        <div class="row">
                            <div class="input-field col s12">
                                <label for="email">Uw email adress</label>
                                <input type="email" id="email" name="email" required="" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="g-recaptcha" data-sitekey="6LfdLGQUAAAAAOpA5HxwP6Q6Q2XfsA7s8qpDCRVG"></div>
                        </div>
                        <div class="row">
                            <input type="hidden" name="CSRFtoken" value="<?= csrf_gen() ?>" />
                            <button type="submit" class="waves-effect waves-light btn color-primary--background width-full">Vraag nieuw wachtwoord aan</button>
                        </div>
                        <a href="/" class="right">Terug naar login</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--Import Materialize JavaScript-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/js/materialize.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <?php alert_display(); ?>
</body>

</html>
