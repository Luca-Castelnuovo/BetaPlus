<?php

require($_SERVER['DOCUMENT_ROOT'] . '/init.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    login_docent();
    csrf_val($_POST['CSRFtoken']);
    $id = clean_data($_POST['id']);
    $feedback = clean_data($_POST['feedback'], true);
    $datetime = current_date(false);

    if (empty($id) || empty($feedback)) {
        $_SESSION['toast_set'] = true;
        echo json_encode(['url' => '/general/toast?url=/ster-opdrachten/view/' . $id . '&alert=Oeps er ging iets fout']);
        exit;
    }

    $docent = sql_query("SELECT last_name FROM docenten WHERE id='{$_SESSION['id']}'", true);

    $query =
        "SELECT
            leerling_id,
            project_name
            FROM
            steropdrachten
        WHERE
            id='{$id}'";

    $steropdracht = sql_query($query, true);

    $query =
        "SELECT
            email
        FROM
            leerlingen
        WHERE
            id='{$steropdracht['leerling_id']}'";

    $leerling = sql_query($query, true);

    $query =
        "UPDATE
            steropdrachten
        SET
            feedback = '{$feedback}',
            feedback_docent = '{$docent['last_name']}',
            feedback_date = '{$datetime}',
            feedback_requested = '0'
        WHERE
            id = '{$id}'";

    sql_query($query, false);

    $body = <<<END
    <!doctype html><html xmlns=http://www.w3.org/1999/xhtml xmlns:o=urn:schemas-microsoft-com:office:office xmlns:v=urn:schemas-microsoft-com:vml><title></title><!--[if !mso]><!-- --><meta content="IE=edge"http-equiv=X-UA-Compatible><!--<![endif]--><meta content="text/html; charset=UTF-8"http-equiv=Content-Type><meta content="width=device-width,initial-scale=1"name=viewport><style>#outlook a{padding:0}.ReadMsgBody{width:100%}.ExternalClass{width:100%}.ExternalClass *{line-height:100%}body{margin:0;padding:0;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%}table,td{border-collapse:collapse;mso-table-lspace:0;mso-table-rspace:0}img{border:0;height:auto;line-height:100%;outline:0;text-decoration:none;-ms-interpolation-mode:bicubic}p{display:block;margin:13px 0}</style><!--[if !mso]><!--><style>@media only screen and (max-width:480px){@-ms-viewport{width:320px}@viewport{width:320px}}</style><!--<![endif]--><!--[if mso]><xml><o:officedocumentsettings><o:allowpng><o:pixelsperinch>96</o:pixelsperinch></o:officedocumentsettings></xml><![endif]--><!--[if lte mso 11]><style>.outlook-group-fix{width:100%!important}</style><![endif]--><!--[if !mso]><!--><link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700"rel=stylesheet><style>@import url(https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700);</style><!--<![endif]--><style>@media only screen and (min-width:480px){.mj-column-per-100{width:100%!important}}</style><body style=background:#fff><div style=background-color:#fff class=mj-container><!--[if mso | IE]><table border=0 cellpadding=0 cellspacing=0 role=presentation align=center style=width:600px width=600><tr><td style=line-height:0;font-size:0;mso-line-height-rule:exactly><![endif]--><div style="margin:0 auto;max-width:600px"><table border=0 cellpadding=0 cellspacing=0 role=presentation align=center style=font-size:0;width:100%><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0;padding:9px 0 9px 0"><!--[if mso | IE]><table border=0 cellpadding=0 cellspacing=0 role=presentation><tr><td style=vertical-align:top;width:600px><![endif]--><div style=vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100% class="mj-column-per-100 outlook-group-fix"><table border=0 cellpadding=0 cellspacing=0 role=presentation width=100%><tr><td style=word-wrap:break-word;font-size:0;padding:0 align=right><table border=0 cellpadding=0 cellspacing=0 role=presentation align=right style=border-collapse:collapse;border-spacing:0><tr><td style=width:240px><a href={$GLOBALS['config']->app->url} target=_blank><img alt="BetaSterren Logo"height=auto src={$GLOBALS['config']->cdn->images->logo} style=border:none;border-radius:0;display:block;font-size:13px;outline:0;text-decoration:none;width:100%;height:auto width=240></a></table><tr><td style="word-wrap:break-word;font-size:0;padding:0 20px 0 20px"align=left><div style=cursor:auto;color:#000;font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px;text-align:left><p><span style=font-size:16px>Beste leerling,</span><p><span style=font-size:16px>Jouw Ster Opdracht '{$steropdracht['project_name']}' heeft feedback gekregen.</span></div><tr><td style="word-wrap:break-word;font-size:0;padding:0 20px 0 20px"align=center><div style=cursor:auto;color:#000;font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px;text-align:center><p><span style=font-size:16px><a href={$GLOBALS['config']->app->url}/ster-opdrachten/view/{$id}/feedback_view>Bekijk Feedback</a></span></div><tr><td style="word-wrap:break-word;font-size:0;padding:0 20px 0 20px"align=left><div style=cursor:auto;color:#000;font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px;text-align:left><p><span style=font-size:16px>Met vriendelijke groet,</span><p><span style=font-size:16px>Het Baarnsch Lyceum</span></div></table></div><!--[if mso | IE]><![endif]--></table></div><!--[if mso | IE]><![endif]--></div>
END;

    api_mail($leerling['email'], 'U heeft feedback ontvangen voor een Ster Opdracht ||  BetaSterren', $body);

    log_action($_SESSION['first_name'] . ' ' . $_SESSION['last_name'], 'SterOpdracht feedback sent', 0);

    $_SESSION['toast_set'] = true;
    echo json_encode(['url' => '/general/toast?url=/ster-opdrachten/view/' . $id . '&alert=Feedback verstuurd']);
    exit;
} else {
    csrf_val($_GET['CSRFtoken']);
    $id = clean_data($_GET['id']);
    $type = clean_data($_GET['type']);

    if ($type == 'request_feedback') {
        login_leerling();
    } else {
        login_docent();
    }

    switch ($type) {
        case 'go':
            $query =
                "SELECT
                    leerling_id,
                    project_name
                FROM
                    steropdrachten
                WHERE
                    id='{$id}'";

            $steropdracht = sql_query($query, true);

            $query =
                "SELECT
                    email
                FROM
                    leerlingen
                WHERE
                    id='{$steropdracht['leerling_id']}'";

            $leerling = sql_query($query, true);

            $docent = sql_query("SELECT last_name FROM docenten WHERE id='{$_SESSION['id']}'", true);

            $query =
                "UPDATE
                    steropdrachten
                SET
                    status = '2',
                    status_docent = '{$docent['last_name']}',
                    docent_id = '{$_SESSION['id']}'
                WHERE
                    id='{$id}' AND (status = '0' OR status = '1')";

            sql_query($query, false);


            $body = <<<END
            <!doctype html><html xmlns=http://www.w3.org/1999/xhtml xmlns:o=urn:schemas-microsoft-com:office:office xmlns:v=urn:schemas-microsoft-com:vml><title></title><!--[if !mso]><!-- --><meta content="IE=edge"http-equiv=X-UA-Compatible><!--<![endif]--><meta content="text/html; charset=UTF-8"http-equiv=Content-Type><meta content="width=device-width,initial-scale=1"name=viewport><style>#outlook a{padding:0}.ReadMsgBody{width:100%}.ExternalClass{width:100%}.ExternalClass *{line-height:100%}body{margin:0;padding:0;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%}table,td{border-collapse:collapse;mso-table-lspace:0;mso-table-rspace:0}img{border:0;height:auto;line-height:100%;outline:0;text-decoration:none;-ms-interpolation-mode:bicubic}p{display:block;margin:13px 0}</style><!--[if !mso]><!--><style>@media only screen and (max-width:480px){@-ms-viewport{width:320px}@viewport{width:320px}}</style><!--<![endif]--><!--[if mso]><xml><o:officedocumentsettings><o:allowpng><o:pixelsperinch>96</o:pixelsperinch></o:officedocumentsettings></xml><![endif]--><!--[if lte mso 11]><style>.outlook-group-fix{width:100%!important}</style><![endif]--><!--[if !mso]><!--><link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700"rel=stylesheet><style>@import url(https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700);</style><!--<![endif]--><style>@media only screen and (min-width:480px){.mj-column-per-100{width:100%!important}}</style><body style=background:#fff><div style=background-color:#fff class=mj-container><!--[if mso | IE]><table border=0 cellpadding=0 cellspacing=0 role=presentation align=center style=width:600px width=600><tr><td style=line-height:0;font-size:0;mso-line-height-rule:exactly><![endif]--><div style="margin:0 auto;max-width:600px"><table border=0 cellpadding=0 cellspacing=0 role=presentation align=center style=font-size:0;width:100%><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0;padding:9px 0 9px 0"><!--[if mso | IE]><table border=0 cellpadding=0 cellspacing=0 role=presentation><tr><td style=vertical-align:top;width:600px><![endif]--><div style=vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100% class="mj-column-per-100 outlook-group-fix"><table border=0 cellpadding=0 cellspacing=0 role=presentation width=100%><tr><td style=word-wrap:break-word;font-size:0;padding:0 align=right><table border=0 cellpadding=0 cellspacing=0 role=presentation align=right style=border-collapse:collapse;border-spacing:0><tr><td style=width:240px><a href={$GLOBALS['config']->app->url} target=_blank><img alt="BetaSterren Logo"height=auto src={$GLOBALS['config']->cdn->images->logo} style=border:none;border-radius:0;display:block;font-size:13px;outline:0;text-decoration:none;width:100%;height:auto width=240></a></table><tr><td style="word-wrap:break-word;font-size:0;padding:0 20px 0 20px"align=left><div style=cursor:auto;color:#000;font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px;text-align:left><p><span style=font-size:16px>Beste leerling,</span><p><span style=font-size:16px>Jouw Ster Opdracht '{$steropdracht['project_name']}' heeft een </span><span style=font-size:18px>GO gekregen.</span></div><tr><td style="word-wrap:break-word;font-size:0;padding:0 20px 0 20px"align=center><div style=cursor:auto;color:#000;font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px;text-align:center><p><span style=font-size:16px><a href={$GLOBALS['config']->app->url}/ster-opdrachten/view/{$id}>Bekijk Ster Opdracht</a></span></div><tr><td style="word-wrap:break-word;font-size:0;padding:0 20px 0 20px"align=left><div style=cursor:auto;color:#000;font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px;text-align:left><p><span style=font-size:16px>Met vriendelijke groet,</span><p><span style=font-size:16px>Het Baarnsch Lyceum</span></div></table></div><!--[if mso | IE]><![endif]--></table></div><!--[if mso | IE]><![endif]--></div>
END;

            api_mail($leerling['email'], 'Go Ster Opdracht ||  BetaSterren', $body);

            log_action($_SESSION['first_name'] . ' ' . $_SESSION['last_name'], 'SterOpdracht GO', 0);

            redirect('/general/home/', 'Go verstuurd');
            break;
        case 'nogo':
            $query =
                "SELECT
                    leerling_id,
                    project_name
                FROM
                    steropdrachten
                WHERE
                    id='{$id}'";

            $steropdracht = sql_query($query, true);

            $query =
                "SELECT
                    email
                FROM
                    leerlingen
                WHERE
                    id='{$steropdracht['leerling_id']}'";

            $leerling = sql_query($query, true);

            $docent = sql_query("SELECT last_name FROM docenten WHERE id='{$_SESSION['id']}'", true);

            $query =
                "UPDATE
                    steropdrachten
                SET
                    status = '1',
                    status_docent = '{$docent['last_name']}',
                    docent_id = '{$_SESSION['id']}'
                WHERE
                    id='{$id}' AND status = '0'";

            sql_query($query, false);

            $body = <<<END
            <!doctype html><html xmlns=http://www.w3.org/1999/xhtml xmlns:o=urn:schemas-microsoft-com:office:office xmlns:v=urn:schemas-microsoft-com:vml><title></title><!--[if !mso]><!-- --><meta content="IE=edge"http-equiv=X-UA-Compatible><!--<![endif]--><meta content="text/html; charset=UTF-8"http-equiv=Content-Type><meta content="width=device-width,initial-scale=1"name=viewport><style>#outlook a{padding:0}.ReadMsgBody{width:100%}.ExternalClass{width:100%}.ExternalClass *{line-height:100%}body{margin:0;padding:0;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%}table,td{border-collapse:collapse;mso-table-lspace:0;mso-table-rspace:0}img{border:0;height:auto;line-height:100%;outline:0;text-decoration:none;-ms-interpolation-mode:bicubic}p{display:block;margin:13px 0}</style><!--[if !mso]><!--><style>@media only screen and (max-width:480px){@-ms-viewport{width:320px}@viewport{width:320px}}</style><!--<![endif]--><!--[if mso]><xml><o:officedocumentsettings><o:allowpng><o:pixelsperinch>96</o:pixelsperinch></o:officedocumentsettings></xml><![endif]--><!--[if lte mso 11]><style>.outlook-group-fix{width:100%!important}</style><![endif]--><!--[if !mso]><!--><link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700"rel=stylesheet><style>@import url(https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700);</style><!--<![endif]--><style>@media only screen and (min-width:480px){.mj-column-per-100{width:100%!important}}</style><body style=background:#fff><div style=background-color:#fff class=mj-container><!--[if mso | IE]><table border=0 cellpadding=0 cellspacing=0 role=presentation align=center style=width:600px width=600><tr><td style=line-height:0;font-size:0;mso-line-height-rule:exactly><![endif]--><div style="margin:0 auto;max-width:600px"><table border=0 cellpadding=0 cellspacing=0 role=presentation align=center style=font-size:0;width:100%><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0;padding:9px 0 9px 0"><!--[if mso | IE]><table border=0 cellpadding=0 cellspacing=0 role=presentation><tr><td style=vertical-align:top;width:600px><![endif]--><div style=vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100% class="mj-column-per-100 outlook-group-fix"><table border=0 cellpadding=0 cellspacing=0 role=presentation width=100%><tr><td style=word-wrap:break-word;font-size:0;padding:0 align=right><table border=0 cellpadding=0 cellspacing=0 role=presentation align=right style=border-collapse:collapse;border-spacing:0><tr><td style=width:240px><a href={$GLOBALS['config']->app->url} target=_blank><img alt="BetaSterren Logo"height=auto src={$GLOBALS['config']->cdn->images->logo} style=border:none;border-radius:0;display:block;font-size:13px;outline:0;text-decoration:none;width:100%;height:auto width=240></a></table><tr><td style="word-wrap:break-word;font-size:0;padding:0 20px 0 20px"align=left><div style=cursor:auto;color:#000;font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px;text-align:left><p><span style=font-size:16px>Beste leerling,</span><p><span style=font-size:16px>Jouw Ster Opdracht '{$steropdracht['project_name']}' heeft een </span><span style=font-size:18px>NO GO gekregen.</span></div><tr><td style="word-wrap:break-word;font-size:0;padding:0 20px 0 20px"align=center><div style=cursor:auto;color:#000;font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px;text-align:center><p><span style=font-size:16px><a href={$GLOBALS['config']->app->url}/ster-opdrachten/view/{$id}>Bekijk Ster Opdracht</a></span></div><tr><td style="word-wrap:break-word;font-size:0;padding:0 20px 0 20px"align=left><div style=cursor:auto;color:#000;font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px;text-align:left><p><span style=font-size:16px>Met vriendelijke groet,</span><p><span style=font-size:16px>Het Baarnsch Lyceum</span></div></table></div><!--[if mso | IE]><![endif]--></table></div><!--[if mso | IE]><![endif]--></div>

END;

            api_mail($leerling['email'], 'No Go Ster Opdracht ||  BetaSterren', $body);

            log_action($_SESSION['first_name'] . ' ' . $_SESSION['last_name'], 'SterOpdracht NO GO', 0);

            redirect('/general/home/', 'No Go verstuurd');
            break;
        case 'abcd':
            $query =
                "SELECT
                    leerling_id,
                    project_name,
                    status
                FROM
                    steropdrachten
                WHERE
                    id='{$id}'";

            $steropdracht = sql_query($query, true);

            if ($steropdracht['status'] != 3) {
                redirect('/general/home/', 'Ster Opdracht niet af en kan dus niet worden beoordeeld');
            }

            $query =
                "SELECT
                    email
                FROM
                    leerlingen
                WHERE
                    id='{$steropdracht['leerling_id']}'";

            $leerling = sql_query($query, true);

            $date = current_date(false);
            $grade = clean_data($_GET['grade']);
            $sterren = clean_data($_GET['sterren']);

            $query =
                "UPDATE
                    steropdrachten
                SET
                    sterren = '{$sterren}',
                    grade = '{$grade}',
                    grade_docent = '{$_SESSION['last_name']}',
                    grade_date = '{$date}',
                    status = '4'
                WHERE
                    id='{$id}' AND status = '3'";

            sql_query($query, false);

            $body = <<<END
            <!doctype html><html xmlns=http://www.w3.org/1999/xhtml xmlns:o=urn:schemas-microsoft-com:office:office xmlns:v=urn:schemas-microsoft-com:vml><title></title><!--[if !mso]><!-- --><meta content="IE=edge"http-equiv=X-UA-Compatible><!--<![endif]--><meta content="text/html; charset=UTF-8"http-equiv=Content-Type><meta content="width=device-width,initial-scale=1"name=viewport><style>#outlook a{padding:0}.ReadMsgBody{width:100%}.ExternalClass{width:100%}.ExternalClass *{line-height:100%}body{margin:0;padding:0;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%}table,td{border-collapse:collapse;mso-table-lspace:0;mso-table-rspace:0}img{border:0;height:auto;line-height:100%;outline:0;text-decoration:none;-ms-interpolation-mode:bicubic}p{display:block;margin:13px 0}</style><!--[if !mso]><!--><style>@media only screen and (max-width:480px){@-ms-viewport{width:320px}@viewport{width:320px}}</style><!--<![endif]--><!--[if mso]><xml><o:officedocumentsettings><o:allowpng><o:pixelsperinch>96</o:pixelsperinch></o:officedocumentsettings></xml><![endif]--><!--[if lte mso 11]><style>.outlook-group-fix{width:100%!important}</style><![endif]--><!--[if !mso]><!--><link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700"rel=stylesheet><style>@import url(https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700);</style><!--<![endif]--><style>@media only screen and (min-width:480px){.mj-column-per-100{width:100%!important}.mj-column-per-50{width:50%!important}}</style><body style=background:#fff><div style=background-color:#fff class=mj-container><!--[if mso | IE]><table border=0 cellpadding=0 cellspacing=0 role=presentation style=width:600px align=center width=600><tr><td style=line-height:0;font-size:0;mso-line-height-rule:exactly><![endif]--><div style="margin:0 auto;max-width:600px"><table border=0 cellpadding=0 cellspacing=0 role=presentation style=font-size:0;width:100% align=center><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0;padding:9px 0 9px 0"><!--[if mso | IE]><table border=0 cellpadding=0 cellspacing=0 role=presentation><tr><td style=vertical-align:top;width:600px><![endif]--><div style=vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100% class="outlook-group-fix mj-column-per-100"><table border=0 cellpadding=0 cellspacing=0 role=presentation width=100%><tr><td style=word-wrap:break-word;font-size:0;padding:0 align=right><table border=0 cellpadding=0 cellspacing=0 role=presentation style=border-collapse:collapse;border-spacing:0 align=right><tr><td style=width:240px><a href={$GLOBALS['config']->app->url} target=_blank><img alt="BetaSterren Logo"height=auto src={$GLOBALS['config']->cdn->images->logo} style=border:none;border-radius:0;display:block;font-size:13px;outline:0;text-decoration:none;width:100%;height:auto width=240></a></table><tr><td style="word-wrap:break-word;font-size:0;padding:0 20px 0 20px"align=left><div style=cursor:auto;color:#000;font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px;text-align:left><p><span style=font-size:16px>Beste leerling,</span><p><span style=font-size:16px>Jouw Ster Opdracht '{$steropdracht['project_name']}' heeft een beoordeling gekregen.</span></div></table></div><!--[if mso | IE]><![endif]--></table></div><!--[if mso | IE]><![endif]--><!--[if mso | IE]><table border=0 cellpadding=0 cellspacing=0 role=presentation style=width:600px align=center width=600><tr><td style=line-height:0;font-size:0;mso-line-height-rule:exactly><![endif]--><table border=0 cellpadding=0 cellspacing=0 role=presentation style=font-size:0;width:100%><tr><td><div style="margin:0 auto;max-width:600px"><table border=0 cellpadding=0 cellspacing=0 role=presentation style=font-size:0;width:100% align=center><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0;padding:9px 0 9px 0"><!--[if mso | IE]><table border=0 cellpadding=0 cellspacing=0 role=presentation><tr><td style=vertical-align:top;width:300px><![endif]--><div style=vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100% class="outlook-group-fix mj-column-per-50"><table border=0 cellpadding=0 cellspacing=0 role=presentation width=100%><tr><td style="word-wrap:break-word;font-size:0;padding:0 20px 0 20px"align=left><div style=cursor:auto;color:#000;font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px;text-align:left><p><span style=font-size:18px>Cijfer</span></div></table></div><!--[if mso | IE]><td style=vertical-align:top;width:300px><![endif]--><div style=vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100% class="outlook-group-fix mj-column-per-50"><table border=0 cellpadding=0 cellspacing=0 role=presentation width=100%><tr><td style="word-wrap:break-word;font-size:0;padding:0 20px 0 20px"align=left><div style=cursor:auto;color:#000;font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px;text-align:left><p><span style=font-size:18px><b>{$grade}</b></span></div></table></div><!--[if mso | IE]><![endif]--></table></div></table><!--[if mso | IE]><![endif]--><!--[if mso | IE]><table border=0 cellpadding=0 cellspacing=0 role=presentation style=width:600px align=center width=600><tr><td style=line-height:0;font-size:0;mso-line-height-rule:exactly><![endif]--><table border=0 cellpadding=0 cellspacing=0 role=presentation style=font-size:0;width:100%><tr><td><div style="margin:0 auto;max-width:600px"><table border=0 cellpadding=0 cellspacing=0 role=presentation style=font-size:0;width:100% align=center><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0;padding:9px 0 9px 0"><!--[if mso | IE]><table border=0 cellpadding=0 cellspacing=0 role=presentation><tr><td style=vertical-align:top;width:300px><![endif]--><div style=vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100% class="outlook-group-fix mj-column-per-50"><table border=0 cellpadding=0 cellspacing=0 role=presentation width=100%><tr><td style="word-wrap:break-word;font-size:0;padding:0 20px 0 20px"align=left><div style=cursor:auto;color:#000;font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px;text-align:left><p><span style=font-size:18px>Aantal Sterren</span></div></table></div><!--[if mso | IE]><td style=vertical-align:top;width:300px><![endif]--><div style=vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100% class="outlook-group-fix mj-column-per-50"><table border=0 cellpadding=0 cellspacing=0 role=presentation width=100%><tr><td style="word-wrap:break-word;font-size:0;padding:0 20px 0 20px"align=left><div style=cursor:auto;color:#000;font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px;text-align:left><p><span style=font-size:18px><b>{$sterren}</b></span></div></table></div><!--[if mso | IE]><![endif]--></table></div></table><!--[if mso | IE]><![endif]--><!--[if mso | IE]><table border=0 cellpadding=0 cellspacing=0 role=presentation style=width:600px align=center width=600><tr><td style=line-height:0;font-size:0;mso-line-height-rule:exactly><![endif]--><table border=0 cellpadding=0 cellspacing=0 role=presentation style=font-size:0;width:100%><tr><td><div style="margin:0 auto;max-width:600px"><table border=0 cellpadding=0 cellspacing=0 role=presentation style=font-size:0;width:100% align=center><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0;padding:9px 0 9px 0"><!--[if mso | IE]><table border=0 cellpadding=0 cellspacing=0 role=presentation><tr><td style=vertical-align:top;width:600px><![endif]--><div style=vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100% class="outlook-group-fix mj-column-per-100"><table border=0 cellpadding=0 cellspacing=0 role=presentation width=100%><tr><td style="word-wrap:break-word;font-size:0;padding:0 20px 0 20px"align=center><div style=cursor:auto;color:#000;font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px;text-align:center><p><span style=font-size:16px><a href={$GLOBALS['config']->app->url}/ster-opdrachten/view/{$id}/feedback_view>Bekijk Ster Opdracht</a></span></div><tr><td style="word-wrap:break-word;font-size:0;padding:0 20px 0 20px"align=left><div style=cursor:auto;color:#000;font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px;text-align:left><p><span style=font-size:16px>Met vriendelijke groet,</span><p><span style=font-size:16px>Het Baarnsch Lyceum</span></div></table></div><!--[if mso | IE]><![endif]--></table></div></table><!--[if mso | IE]><![endif]--></div>
END;

            api_mail($leerling['email'], 'Beoordeling Ster Opdracht ||  BetaSterren', $body);

            log_action($_SESSION['first_name'] . ' ' . $_SESSION['last_name'], 'SterOpdracht beoordeeld', 0);

            redirect('/ster-opdrachten/view/' . $id . '/', 'Beoordeling verstuurd');
            break;
        case 'request_feedback':
            $query =
                "SELECT
                    leerling_id
                FROM
                    steropdrachten
                WHERE
                    id='{$id}'";

            $steropdracht = sql_query($query, true);

            if ($steropdracht['leerling_id'] == $_SESSION['id']) {
                $query =
                    "UPDATE
                        steropdrachten
                    SET
                        feedback_requested = '1'
                    WHERE
                        id='{$id}'";

                sql_query($query, false);

                steropdrachten_notify($id, $_SESSION['id'], 'Ster Opdracht heeft feedback nodig.');

                log_action($_SESSION['first_name'] . ' ' . $_SESSION['last_name'], 'SterOpdracht feedback request', 0);

                redirect('/ster-opdrachten/view/' . $id . '/', 'Feedback aangevraagd');
            } else {
                redirect('/ster-opdrachten/view/' . $id . '/', 'U hebt geen toestemming om deze Ster Opdracht aan te passen');
            }
            break;

        default:
            redirect('/general/home', 'Oeps er ging iets fout');
            break;
    }
}
