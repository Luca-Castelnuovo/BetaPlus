<?php
$admin_require = true;
require($_SERVER['DOCUMENT_ROOT'] . '/init.php');

login_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_val($_POST['CSRFtoken']);

    switch ($_POST['type']) {
        case 'gen':
            if (empty($_POST['user'])) {
                echo json_encode(['status' => false]);
                exit;
            }

            $token = gen(128);
            $current_date = current_date(true);
            $user = clean_data($_POST['user']);
            $ip = ip();

            $query =
                "INSERT INTO
                    tokens (
                        token,
                        type,
                        created,
                        days_valid,
                        user,
                        ip
                    )
                VALUES
                    (
                        '{$token}',
                        'register',
                        '{$current_date}',
                        '7',
                        '{$user}',
                        '{$ip}'
                    )
                ";

            sql_query($query, false);

            $body = <<<END
            <!doctype html><html xmlns=http://www.w3.org/1999/xhtml xmlns:o=urn:schemas-microsoft-com:office:office xmlns:v=urn:schemas-microsoft-com:vml><title></title><!--[if !mso]><!-- --><meta content="IE=edge"http-equiv=X-UA-Compatible><!--<![endif]--><meta content="text/html; charset=UTF-8"http-equiv=Content-Type><meta content="width=device-width,initial-scale=1"name=viewport><style>#outlook a{padding:0}.ReadMsgBody{width:100%}.ExternalClass{width:100%}.ExternalClass *{line-height:100%}body{margin:0;padding:0;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%}table,td{border-collapse:collapse;mso-table-lspace:0;mso-table-rspace:0}img{border:0;height:auto;line-height:100%;outline:0;text-decoration:none;-ms-interpolation-mode:bicubic}p{display:block;margin:13px 0}</style><!--[if !mso]><!--><style>@media only screen and (max-width:480px){@-ms-viewport{width:320px}@viewport{width:320px}}</style><!--<![endif]--><!--[if mso]><xml><o:officedocumentsettings><o:allowpng><o:pixelsperinch>96</o:pixelsperinch></o:officedocumentsettings></xml><![endif]--><!--[if lte mso 11]><style>.outlook-group-fix{width:100%!important}</style><![endif]--><!--[if !mso]><!--><link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700"rel=stylesheet><style>@import url(https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700);</style><!--<![endif]--><style>@media only screen and (min-width:480px){.mj-column-per-100{width:100%!important}}</style><body style=background:#fff><div style=background-color:#fff class=mj-container><!--[if mso | IE]><table border=0 cellpadding=0 cellspacing=0 role=presentation align=center style=width:600px width=600><tr><td style=line-height:0;font-size:0;mso-line-height-rule:exactly><![endif]--><div style="margin:0 auto;max-width:600px"><table border=0 cellpadding=0 cellspacing=0 role=presentation align=center style=font-size:0;width:100%><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0;padding:9px 0 9px 0"><!--[if mso | IE]><table border=0 cellpadding=0 cellspacing=0 role=presentation><tr><td style=vertical-align:top;width:600px><![endif]--><div style=vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100% class="mj-column-per-100 outlook-group-fix"><table border=0 cellpadding=0 cellspacing=0 role=presentation width=100%><tr><td style=word-wrap:break-word;font-size:0;padding:0 align=right><table border=0 cellpadding=0 cellspacing=0 role=presentation align=right style=border-collapse:collapse;border-spacing:0><tr><td style=width:240px><a href=https://betasterren.hetbaarnschlyceum.nl target=_blank><img alt="BetaSterren Logo"height=auto src=https://cdn.lucacastelnuovo.nl/images/betasterren/logo.png style=border:none;border-radius:0;display:block;font-size:13px;outline:0;text-decoration:none;width:100%;height:auto width=240></a></table><tr><td style="word-wrap:break-word;font-size:0;padding:0 20px 0 20px"align=left><div style=cursor:auto;color:#000;font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px;text-align:left><p><span style=font-size:16px>Beste leerling,</span><p><span style=font-size:16px>U kunt uw BetaSterren account aanmaken met de volgende link.</span></div><tr><td style="word-wrap:break-word;font-size:0;padding:0 20px 0 20px"align=center><div style=cursor:auto;color:#000;font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px;text-align:center><p><span style=font-size:16px><a href="https://betasterren.hetbaarnschlyceum.nl/auth/register/{$token}">Registreer Account</a></span></div><tr><td style="word-wrap:break-word;font-size:0;padding:0 20px 0 20px"align=left><div style=cursor:auto;color:#000;font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px;text-align:left><p><span style=font-size:16px>Met vriendelijke groet,</span><p><span style=font-size:16px>Het Baarnsch Lyceum</span></div></table></div><!--[if mso | IE]><![endif]--></table></div><!--[if mso | IE]><![endif]--></div>
END;
            if (api_mail($user, 'Registreer uw account ||  BetaSterren', $body)) {
                echo json_encode(['status' => true]);
                log_action($_SESSION['first_name'] . ' ' . $_SESSION['last_name'], 'Admin registration token sent', 2);
                exit;
            } else {
                echo json_encode(['status' => false]);
                exit;
            }
            break;

        default:
            redirect('/admin', 'Wrong type was passed!');
            break;
    }

    redirect('/admin', 'Success');
} else {
    csrf_val($_GET['CSRFtoken']);

    $type = clean_data($_GET['type']);
    $user_id = clean_data($_GET['id']);
    $user_class = clean_data($_GET['class']);
    $state = clean_data($_GET['state']) ?? 0;

    is_empty([$type, $user_id, $user_class], '/admin');

    $sql_table = ($user_class === 'docent') ? 'docenten' : 'leerlingen';

    $value = $state;

    $customQuery = false;

    switch ($type) {
        case 'active':
            $set = 'active';
            break;

        case 'unblock':
            $set = 'failed_login';
            $value = 0;
            break;

        case 'delete':
            $customQuery = true;
            $query =
                "DELETE FROM
                    leerlingen
                WHERE
                    id = '{$user_id}'";
            sql_query($query, false);
            $query =
                "DELETE FROM
                    steropdrachten
                WHERE
                    leerling_id = '{$user_id}'";

            log_action($_SESSION['first_name'] . ' ' . $_SESSION['last_name'], 'Admin delete user', 2);
            break;

        case 'log_clear':
            $customQuery = true;
            $query =
                "DELETE FROM
                    logs";
            break;

        case 'remember':
            $customQuery = true;
            $query =
                "DELETE FROM
                tokens
            WHERE
                type = 'remember_me'";
            log_action($_SESSION['first_name'] . ' ' . $_SESSION['last_name'], 'Admin deleted remember_me tokens', 1);
            break;

        default:
            redirect('/admin', 'Wrong type was passed!');
            break;
    }

    if (!$customQuery) {
        $query =
            "UPDATE
            {$sql_table}
        SET
            {$set}='{$value}'
        WHERE
            id='{$user_id}'";
    }

    sql_query($query, false);

    redirect('/admin', 'Success');
}
