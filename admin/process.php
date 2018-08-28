<?php
$admin_require = true; require($_SERVER['DOCUMENT_ROOT'] . "/init.php");

login_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_val($_POST['CSRFtoken']);

    switch ($_POST['type']) {
        case 'gen':
            switch ($_POST['gen']) {
                case 'register_code':
                    $token = gen(128);
                    $current_date = current_date(true);
                    $user = clean_data($_POST['user']);
                    $ip = ip();

                    $query =
                    "INSERT INTO
                        tokens
                            ('token','type','created', 'days_valid','gen_ip')
                    VALUES
                        ('{$token}','register','$current_date', '7','$ip')";

                    echo json_encode(['status' => true, 'url' => 'https://betasterren.hetbaarnschlyceum.nl/auth/register?token=' . $token]);
                    break;

                case 'register_send':
                    $token = gen(128);
                    $current_date = current_date(true);
                    $user = clean_data($_POST['user']);
                    $ip = ip();

                    $query =
                    "INSERT INTO
                        tokens
                            ('token','type','created', 'days_valid','user','gen_ip')
                    VALUES
                        ('{$token}','register','$current_date', '7','$user','$ip')";

                    $body = <<<END
                    <!doctype html><html xmlns=http://www.w3.org/1999/xhtml xmlns:o=urn:schemas-microsoft-com:office:office xmlns:v=urn:schemas-microsoft-com:vml><title></title><!--[if !mso]><!-- --><meta content="IE=edge"http-equiv=X-UA-Compatible><!--<![endif]--><meta content="text/html; charset=UTF-8"http-equiv=Content-Type><meta content="width=device-width,initial-scale=1"name=viewport><style>#outlook a{padding:0}.ReadMsgBody{width:100%}.ExternalClass{width:100%}.ExternalClass *{line-height:100%}body{margin:0;padding:0;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%}table,td{border-collapse:collapse;mso-table-lspace:0;mso-table-rspace:0}img{border:0;height:auto;line-height:100%;outline:0;text-decoration:none;-ms-interpolation-mode:bicubic}p{display:block;margin:13px 0}</style><!--[if !mso]><!--><style>@media only screen and (max-width:480px){@-ms-viewport{width:320px}@viewport{width:320px}}</style><!--<![endif]--><!--[if mso]><xml><o:officedocumentsettings><o:allowpng><o:pixelsperinch>96</o:pixelsperinch></o:officedocumentsettings></xml><![endif]--><!--[if lte mso 11]><style>.outlook-group-fix{width:100%!important}</style><![endif]--><!--[if !mso]><!--><link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700"rel=stylesheet><style>@import url(https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700);</style><!--<![endif]--><style>@media only screen and (min-width:480px){.mj-column-per-100{width:100%!important}}</style><body style=background:#fff><div style=background-color:#fff class=mj-container><!--[if mso | IE]><table border=0 cellpadding=0 cellspacing=0 role=presentation style=width:600px align=center width=600><tr><td style=line-height:0;font-size:0;mso-line-height-rule:exactly><![endif]--><div style="margin:0 auto;max-width:600px"><table border=0 cellpadding=0 cellspacing=0 role=presentation style=font-size:0;width:100% align=center><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0;padding:9px 0 9px 0"><!--[if mso | IE]><table border=0 cellpadding=0 cellspacing=0 role=presentation><tr><td style=vertical-align:top;width:600px><![endif]--><div style=vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100% class="mj-column-per-100 outlook-group-fix"><table border=0 cellpadding=0 cellspacing=0 role=presentation width=100%><tr><td style=word-wrap:break-word;font-size:0;padding:0 align=right><table border=0 cellpadding=0 cellspacing=0 role=presentation style=border-collapse:collapse;border-spacing:0 align=right><tr><td style=width:240px><a href=https://betasterren.hetbaarnschlyceum.nl target=_blank><img alt="BetaSterren Logo"height=auto src=https://cdn.lucacastelnuovo.nl/images/betasterren/logo.png style=border:none;border-radius:0;display:block;font-size:13px;outline:0;text-decoration:none;width:100%;height:auto width=240></a></table><tr><td style="word-wrap:break-word;font-size:0;padding:0 20px 0 20px"align=left><div style=cursor:auto;color:#000;font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px;text-align:left><p><span style=font-size:16px>Beste leerling,</span><p><span style=font-size:16px>U kunt uw BetaSterren account aanmaken met de volgende link.</span></div></table></div><!--[if mso | IE]><![endif]--></table></div><!--[if mso | IE]><![endif]--><!--[if mso | IE]><table border=0 cellpadding=0 cellspacing=0 role=presentation style=width:600px align=center width=600><tr><td style=line-height:0;font-size:0;mso-line-height-rule:exactly><![endif]--><table border=0 cellpadding=0 cellspacing=0 role=presentation style=font-size:0;width:100%><tr><td><div style="margin:0 auto;max-width:600px"><table border=0 cellpadding=0 cellspacing=0 role=presentation style=font-size:0;width:100% align=center><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0;padding:9px 0 9px 0"><!--[if mso | IE]><table border=0 cellpadding=0 cellspacing=0 role=presentation><tr><td style=vertical-align:top;width:600px><![endif]--><div style=vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100% class="mj-column-per-100 outlook-group-fix"><table border=0 cellpadding=0 cellspacing=0 role=presentation width=100%><tr><td style="word-wrap:break-word;font-size:0;padding:10px 25px 10px 25px;padding-top:10px;padding-left:25px"align=center><table border=0 cellpadding=0 cellspacing=0 role=presentation style=border-collapse:separate align=center><tr><td style="border:none;border-radius:24px;color:#fff;cursor:auto;padding:10px 25px"align=center bgcolor=#003d14 valign=middle><a href="https://betasterren.hetbaarnschlyceum.nl/auth/register?token={$token}"target=_blank style=text-decoration:none;background:#003d14;color:#fff;font-family:Ubuntu,Helvetica,Arial,sans-serif,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:120%;text-transform:none;margin:0>Maak mijn account aan</a></table><tr><td style="word-wrap:break-word;font-size:0;padding:0 20px 0 20px"align=left><div style=cursor:auto;color:#000;font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px;text-align:left><p><span style=font-size:16px>Met vriendelijke groet,</span><p><span style=font-size:16px>Een server in amsterdam</span></div></table></div><!--[if mso | IE]><![endif]--></table></div></table><!--[if mso | IE]><![endif]--></div>
END;

                    api_mail($user, 'Registreer uw account ||  BetaSterren', $body);

                    echo json_encode(['status' => true]);
                    break;

                default:
                    redirect('/admin', 'Wrong type was passed!');
                    break;
            }
            break;

        default:
            redirect('/admin', 'Wrong type was passed!');
            break;
    }

    redirect('/admin', 'Success');
} else {
    csrf_val($_GET['CSRFtoken']);

    if (empty($_GET['type']) || empty($_GET['id']) || empty($_GET['class'])) {
        redirect('/admin', 'Not all required vars were passed!');
    }

    $type = clean_data($_GET['type']);
    $user_id = clean_data($_GET['id']);
    $user_class = clean_data($_GET['class']);
    $state = clean_data($_GET['state']) ?? 0;

    $sql_table = ($user_class === 'docent') ? 'docenten' : 'leerlingen';

    $value = $state;

    $customQuery = false;

    switch ($type) {
        case 'active':
            $set = 'active';
            break;

        case 'utalent':
            $set = 'utalent';
            break;

        case 'unblock':
            $set = 'failed_login';
            $value = 0;
            break;

        case 'reset':
            //TODO: create reset process (disable account and set password recovery email)
            redirect('/admin', 'Reset function not yet implemented!');
            break;

        case 'delete':
            //TODO: create delete process (delete account and delete steropdrachten)
            redirect('/admin', 'Delete function not yet implemented!');
            break;

        case 'log_clear':
            $customQuery = true;
            $query = "DELETE FROM logs";
            break;

        default:
            redirect('/admin', 'Wrong type was passed!');
            break;
    }

    if (!$customQuery) {
        $query = "UPDATE {$sql_table} SET {$set}='{$value}' WHERE id='{$user_id}'";
    }

    sql_query($query, false);

    redirect('/admin', 'Success');
}
