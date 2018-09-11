<?php
require($_SERVER['DOCUMENT_ROOT'] . "/init.php");

login_leerling();

$id = clean_data($_GET['id']);

if (!isset($_GET['request_id'])) {
    redirect('/ster-opdrachten/view/' . $id, 'Deze link is niet geldig');
} else {
    $request_id = clean_data($_GET['request_id']);
}

$query =
    "SELECT
        id,
        leerling_id,
        status,
        buddy_id,
        project_name
    FROM
        steropdrachten
    WHERE
        id='{$id}'";

$steropdracht = sql_query($query, true);

if ($steropdracht['status'] >= 3) {
    redirect('/ster-opdrachten/view/' . $id, 'Deze Ster Opdracht is klaar, u kunt hem niet meer aanpassen');
}

if ($_SESSION['id'] == $steropdracht['leerling_id']) {
    if (!isset($_GET['accept'])) {
        redirect('/ster-opdrachten/view/' . $id, 'Deze link is niet geldig');
    } else {
        $accept = clean_data($_GET['accept']);
    }

    if ($_GET['accept']) {
        array_push($steropdracht['buddy_id'], $request_id);

        $query =
            "UPDATE
                steropdrachten
            SET
                buddy_id = '{$steropdracht['buddy_id']}'
            WHERE
                id='{$request_id}'";

        sql_query($query, false);

        $query =
            "SELECT
                email
            FROM
                leerlingen
            WHERE
                id='{$request_id}'";

        $leerling = sql_query($query, true);

        $body = <<<END
        <!doctype html><html xmlns=http://www.w3.org/1999/xhtml xmlns:o=urn:schemas-microsoft-com:office:office xmlns:v=urn:schemas-microsoft-com:vml><title></title><!--[if !mso]><!-- --><meta content="IE=edge"http-equiv=X-UA-Compatible><!--<![endif]--><meta content="text/html; charset=UTF-8"http-equiv=Content-Type><meta content="width=device-width,initial-scale=1"name=viewport><style>#outlook a{padding:0}.ReadMsgBody{width:100%}.ExternalClass{width:100%}.ExternalClass *{line-height:100%}body{margin:0;padding:0;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%}table,td{border-collapse:collapse;mso-table-lspace:0;mso-table-rspace:0}img{border:0;height:auto;line-height:100%;outline:0;text-decoration:none;-ms-interpolation-mode:bicubic}p{display:block;margin:13px 0}</style><!--[if !mso]><!--><style>@media only screen and (max-width:480px){@-ms-viewport{width:320px}@viewport{width:320px}}</style><!--<![endif]--><!--[if mso]><xml><o:officedocumentsettings><o:allowpng><o:pixelsperinch>96</o:pixelsperinch></o:officedocumentsettings></xml><![endif]--><!--[if lte mso 11]><style>.outlook-group-fix{width:100%!important}</style><![endif]--><!--[if !mso]><!--><link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700"rel=stylesheet><style>@import url(https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700);</style><!--<![endif]--><style>@media only screen and (min-width:480px){.mj-column-per-100{width:100%!important}}</style><body style=background:#fff><div style=background-color:#fff class=mj-container><!--[if mso | IE]><table border=0 cellpadding=0 cellspacing=0 role=presentation align=center style=width:600px width=600><tr><td style=line-height:0;font-size:0;mso-line-height-rule:exactly><![endif]--><div style="margin:0 auto;max-width:600px"><table border=0 cellpadding=0 cellspacing=0 role=presentation align=center style=font-size:0;width:100%><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0;padding:9px 0 9px 0"><!--[if mso | IE]><table border=0 cellpadding=0 cellspacing=0 role=presentation><tr><td style=vertical-align:top;width:600px><![endif]--><div style=vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100% class="mj-column-per-100 outlook-group-fix"><table border=0 cellpadding=0 cellspacing=0 role=presentation width=100%><tr><td style=word-wrap:break-word;font-size:0;padding:0 align=right><table border=0 cellpadding=0 cellspacing=0 role=presentation align=right style=border-collapse:collapse;border-spacing:0><tr><td style=width:240px><a href=https://betasterren.hetbaarnschlyceum.nl target=_blank><img alt="BetaSterren Logo"height=auto src=https://cdn.lucacastelnuovo.nl/images/betasterren/logo.png style=border:none;border-radius:0;display:block;font-size:13px;outline:0;text-decoration:none;width:100%;height:auto width=240></a></table><tr><td style="word-wrap:break-word;font-size:0;padding:0 20px 0 20px"align=left><div style=cursor:auto;color:#000;font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px;text-align:left><p><span style=font-size:16px>Beste leerling,</span><p><span style=font-size:16px>Je bent toegevoegd aan de Ster Opdracht '{$steropdracht['project_name']}'</span></div><tr><td style="word-wrap:break-word;font-size:0;padding:0 20px 0 20px"align=center><div style=cursor:auto;color:#000;font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px;text-align:center><p><span style=font-size:16px><a href=https://betasterren.hetbaarnschlyceum.nl/ster-opdrachten/view/{$id}/feedback_view>Bekijk Ster Opdracht</a></span></div><tr><td style="word-wrap:break-word;font-size:0;padding:0 20px 0 20px"align=left><div style=cursor:auto;color:#000;font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px;text-align:left><p><span style=font-size:16px>Met vriendelijke groet,</span><p><span style=font-size:16px>Het Baarnsch Lyceum</span></div></table></div><!--[if mso | IE]><![endif]--></table></div><!--[if mso | IE]><![endif]--></div>
END;

        api_mail($leerling['email'], 'Je bent toegevoegd aan een Ster Opdracht ||  BetaSterren', $body);

        redirect('/ster-opdrachten/view/' . $id, 'Verzoek geaccepteerd');
    } else {
        redirect('/ster-opdrachten/view/' . $id, 'Verzoek afgewezen');
    }
} else {
    $query =
        "SELECT
            first_name,
            last_name
        FROM
            leerlingen
        WHERE
            id='{$_SESSION['id']}'";

    $request = sql_query($query, true);

    $query =
        "SELECT
            email
        FROM
            leerlingen
        WHERE
            id='{$steropdracht['leerling_id']}'";

    $owner = sql_query($query, true);

    $body = <<<END
    <!doctype html><html xmlns=http://www.w3.org/1999/xhtml xmlns:o=urn:schemas-microsoft-com:office:office xmlns:v=urn:schemas-microsoft-com:vml><title></title><!--[if !mso]><!-- --><meta content="IE=edge"http-equiv=X-UA-Compatible><!--<![endif]--><meta content="text/html; charset=UTF-8"http-equiv=Content-Type><meta content="width=device-width,initial-scale=1"name=viewport><style>#outlook a{padding:0}.ReadMsgBody{width:100%}.ExternalClass{width:100%}.ExternalClass *{line-height:100%}body{margin:0;padding:0;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%}table,td{border-collapse:collapse;mso-table-lspace:0;mso-table-rspace:0}img{border:0;height:auto;line-height:100%;outline:0;text-decoration:none;-ms-interpolation-mode:bicubic}p{display:block;margin:13px 0}</style><!--[if !mso]><!--><style>@media only screen and (max-width:480px){@-ms-viewport{width:320px}@viewport{width:320px}}</style><!--<![endif]--><!--[if mso]><xml><o:officedocumentsettings><o:allowpng><o:pixelsperinch>96</o:pixelsperinch></o:officedocumentsettings></xml><![endif]--><!--[if lte mso 11]><style>.outlook-group-fix{width:100%!important}</style><![endif]--><!--[if !mso]><!--><link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700"rel=stylesheet><style>@import url(https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700);</style><!--<![endif]--><style>@media only screen and (min-width:480px){.mj-column-per-100{width:100%!important}.mj-column-per-50{width:50%!important}}</style><body style=background:#fff><div style=background-color:#fff class=mj-container><!--[if mso | IE]><table border=0 cellpadding=0 cellspacing=0 role=presentation style=width:600px align=center width=600><tr><td style=line-height:0;font-size:0;mso-line-height-rule:exactly><![endif]--><div style="margin:0 auto;max-width:600px"><table border=0 cellpadding=0 cellspacing=0 role=presentation style=font-size:0;width:100% align=center><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0;padding:9px 0 9px 0"><!--[if mso | IE]><table border=0 cellpadding=0 cellspacing=0 role=presentation><tr><td style=vertical-align:top;width:600px><![endif]--><div style=vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100% class="outlook-group-fix mj-column-per-100"><table border=0 cellpadding=0 cellspacing=0 role=presentation width=100%><tr><td style=word-wrap:break-word;font-size:0;padding:0 align=right><table border=0 cellpadding=0 cellspacing=0 role=presentation style=border-collapse:collapse;border-spacing:0 align=right><tr><td style=width:240px><a href=https://betasterren.hetbaarnschlyceum.nl target=_blank><img alt="BetaSterren Logo"height=auto src=https://cdn.lucacastelnuovo.nl/images/betasterren/logo.png style=border:none;border-radius:0;display:block;font-size:13px;outline:0;text-decoration:none;width:100%;height:auto width=240></a></table><tr><td style="word-wrap:break-word;font-size:0;padding:0 20px 0 20px"align=left><div style=cursor:auto;color:#000;font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px;text-align:left><p><span style=font-size:16px>Beste leerling,</span><p><span style=font-size:16px>{$request['first_name']} {$request['last_name']} wil mee doen aan de Ster Opdracht '{$steropdracht['project_name']}'</span></div></table></div><!--[if mso | IE]><![endif]--></table></div><!--[if mso | IE]><![endif]--><!--[if mso | IE]><table border=0 cellpadding=0 cellspacing=0 role=presentation style=width:600px align=center width=600><tr><td style=line-height:0;font-size:0;mso-line-height-rule:exactly><![endif]--><table border=0 cellpadding=0 cellspacing=0 role=presentation style=font-size:0;width:100%><tr><td><div style="margin:0 auto;max-width:600px"><table border=0 cellpadding=0 cellspacing=0 role=presentation style=font-size:0;width:100% align=center><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0;padding:9px 0 9px 0"><!--[if mso | IE]><table border=0 cellpadding=0 cellspacing=0 role=presentation><tr><td style=vertical-align:top;width:300px><![endif]--><div style=vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100% class="outlook-group-fix mj-column-per-50"><table border=0 cellpadding=0 cellspacing=0 role=presentation width=100%><tr><td style="word-wrap:break-word;font-size:0;padding:0 20px 0 20px"align=center><div style=cursor:auto;color:#000;font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px;text-align:center><p><span style=font-size:16px><a href=https://betasterren.hetbaarnschlyceum.nl/ster-opdrachten/join/{$id}/{$request_id}/true>Accepteer</a></span></div></table></div><!--[if mso | IE]><td style=vertical-align:top;width:300px><![endif]--><div style=vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100% class="outlook-group-fix mj-column-per-50"><table border=0 cellpadding=0 cellspacing=0 role=presentation width=100%><tr><td style="word-wrap:break-word;font-size:0;padding:0 20px 0 20px"align=center><div style=cursor:auto;color:#000;font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px;text-align:center><p><span style=font-size:16px><a href=https://betasterren.hetbaarnschlyceum.nl/ster-opdrachten/join/{$id}/{$request_id}/false>Weiger</a></span></div></table></div><!--[if mso | IE]><![endif]--></table></div></table><!--[if mso | IE]><![endif]--><!--[if mso | IE]><table border=0 cellpadding=0 cellspacing=0 role=presentation style=width:600px align=center width=600><tr><td style=line-height:0;font-size:0;mso-line-height-rule:exactly><![endif]--><table border=0 cellpadding=0 cellspacing=0 role=presentation style=font-size:0;width:100%><tr><td><div style="margin:0 auto;max-width:600px"><table border=0 cellpadding=0 cellspacing=0 role=presentation style=font-size:0;width:100% align=center><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0;padding:9px 0 9px 0"><!--[if mso | IE]><table border=0 cellpadding=0 cellspacing=0 role=presentation><tr><td style=vertical-align:top;width:600px><![endif]--><div style=vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100% class="outlook-group-fix mj-column-per-100"><table border=0 cellpadding=0 cellspacing=0 role=presentation width=100%><tr><td style="word-wrap:break-word;font-size:0;padding:0 20px 0 20px"align=left><div style=cursor:auto;color:#000;font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px;text-align:left><p><span style=font-size:16px>Met vriendelijke groet,</span><p><span style=font-size:16px>Het Baarnsch Lyceum</span></div></table></div><!--[if mso | IE]><![endif]--></table></div></table><!--[if mso | IE]><![endif]--></div>
END;

    if (api_mail($owner['email'], 'Iemand wil meedoen aan je Ster Opdracht ||  BetaSterren', $body)) {
        redirect('/ster-opdrachten/view/' . $id, 'Verzoek verstuurd');
    } else {
        redirect('/ster-opdrachten/view/' . $id, 'Oeps verstuurd');
    }
}
