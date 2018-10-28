<?php

function steropdrachten_list($done)
{
    $status = $done ? ">= '3'" : "= '2'";

    $query =
        "SELECT
            id,
            project_name,
            leerling_id,
            buddy_id,
            image_url,
            status
        FROM
            steropdrachten
        WHERE
            status {$status}
        ORDER BY
            created DESC";

    $result = sql_query($query, false);
    if ($result->num_rows > 0) {
        echo <<<END
        <div class="section white">
            <div class="row">
END;
        while ($steropdracht = $result->fetch_assoc()) {
            if ($steropdracht['leerling_id'] == $_SESSION['id'] && isset($_SESSION['admin']) && $steropdracht['status'] <= 2) {
                $extra = "<li class=\"btn waves-effect waves-light color-secondary--background\"><a href=\"/ster-opdrachten/edit/{$steropdracht['id']}/\">Edit Opdracht</a></li>";
            } elseif ($steropdracht['status'] <= 2 && empty($steropdracht['buddy_id'])) {
                $extra = "<li class=\"btn waves-effect waves-light color-secondary--background\"><a href=\"/ster-opdrachten/join/{$steropdracht['id']}/{$_SESSION['id']}/\">Join Opdracht</a></li>";
            }

            echo <<<END
            <div class="col s12 m6 l4 xl3" id="{$steropdracht['id']}">
                <div class="card medium hoverable">
                    <div class="card-image waves-effect waves-block waves-light">
                        <img class="activator responsive-img" src="{$steropdracht['image_url']}" onerror="this.src='https://cdn.lucacastelnuovo.nl/images/betasterren/logo.png'">
                    </div>
                    <div class="card-content"><span class="card-title activator grey-text text-darken-4 center">{$steropdracht['project_name']}</span></div>
                    <div class="card-reveal">
                        <span class="card-title grey-text text-darken-4">Opties<i class="material-icons right">close</i></span>
                        <ul class="align-center card-reveal--links">
                            <li class="btn waves-effect waves-light color-secondary--background">
                                <a href="/ster-opdrachten/view/{$steropdracht['id']}">Bekijk Opdracht</a>
                            </li>
                            {$extra}
                        </ul>
                    </div>
                </div>
            </div>
END;
        }
        echo <<<END
    </div>
</div>
END;
    } else {
        echo '<p>Er zijn op dit moment geen steropdrachten in deze categorie.</p>';
    }
}

function steropdrachten_list_my($done)
{
    $status = $done ? ">= '3'" : "BETWEEN '0' AND '2'";

    $query =
        "SELECT
            id,
            project_name,
            leerling_id,
            image_url,
            status
        FROM
            steropdrachten
        WHERE
            status {$status} AND (leerling_id = '{$_SESSION['id']}' OR buddy_id = '{$_SESSION['id']}')
        ORDER BY
            created DESC";

    $result = sql_query($query, false);
    if ($result->num_rows > 0) {
        echo <<<END
        <div class="section white">
            <div class="row">
END;
        while ($steropdracht = $result->fetch_assoc()) {
            if ($steropdracht['status'] <= 2) {
                $edit = "<li class=\"btn waves-effect waves-light color-secondary--background\"><a href=\"/ster-opdrachten/edit/{$steropdracht['id']}/\">Edit Opdracht</a></li>";
            }
            echo <<<END
            <div class="col s12 m6 l4 xl3">
                <div class="card medium hoverable">
                    <div class="card-image waves-effect waves-block waves-light">
                        <img class="activator responsive-img" src="{$steropdracht['image_url']}" onerror="this.src='https://cdn.lucacastelnuovo.nl/images/betasterren/logo.png'">
                    </div>
                    <div class="card-content"><span class="card-title activator grey-text text-darken-4 center">{$steropdracht['project_name']}</span></div>
                    <div class="card-reveal">
                        <span class="card-title grey-text text-darken-4">Opties<i class="material-icons right">close</i></span>
                        <ul class="align-center card-reveal--links">
                            <li class="btn waves-effect waves-light color-secondary--background">
                                <a href="/ster-opdrachten/view/{$steropdracht['id']}">Bekijk Opdracht</a>
                            </li>
                            {$edit}
                        </ul>
                    </div>
                </div>
            </div>
END;
        }
        echo <<<END
    </div>
</div>
END;
    } else {
        echo '<p>Er doen op dit moment geen steropdrachten in deze categorie.</p>';
    }
}

function steropdrachten_list_docenten($type)
{
    switch ($type) {
        case '0':
            //0 = go/no go
            $status = "< 2 AND (docent_id IS NULL OR docent_id = '{$_SESSION['id']}')";
            break;
        case '1':
            //1 = feedback requested
            $status = "= 2 AND feedback_requested = 1 AND (docent_id IS NULL OR docent_id = '{$_SESSION['id']}')";
            break;
        case '2':
            //2 = beoordeling (sterren, en abcd)
            $status = "= 3 AND (docent_id IS NULL OR docent_id = '{$_SESSION['id']}')";
            break;
        case '3':
            //3 = lopend
            $status = '= 2 AND feedback_requested = 0';
            break;
        case '4':
            //4 = afgerond
            $status = '= 4';
            break;

        default:
            redirect('/?reset', 'Oeps er ging iets fout');
            break;
    }

    $query =
        "SELECT
            id,
            project_name,
            subject,
            image_url,
            status
        FROM
            steropdrachten
        WHERE
            status {$status}
        ORDER BY
            created ASC";

    $result = sql_query($query, false);
    if ($result->num_rows > 0) {
        echo <<<END
        <div class="section white">
            <div class="row">
END;
        while ($steropdracht = $result->fetch_assoc()) {
            $extra = null;

            $CSRFtoken = csrf_gen();

            switch ($steropdracht['status']) {
                case '0':
                    $extra = <<<END
                        <li class="btn waves-effect waves-light color-secondary--background">
                            <a href="/ster-opdrachten/process/{$steropdracht['id']}/go/{$CSRFtoken}">Go</a>
                        </li>
                        <li class="btn waves-effect waves-light color-secondary--background">
                            <a href="/ster-opdrachten/process/{$steropdracht['id']}/nogo/{$CSRFtoken}">No Go</a>
                        </li>
END;
                    break;
                case '1':
                    $extra = <<<END
                        <li class="btn waves-effect waves-light color-secondary--background">
                            <a href="/ster-opdrachten/process/{$steropdracht['id']}/go/{$CSRFtoken}">Go</a>
                        </li>
END;
                    break;
                case '3':
                    $extra = <<<END
                        <li class="btn waves-effect waves-light color-secondary--background">
                            <a href="/ster-opdrachten/abcd/{$steropdracht['id']}">Beoordeel Opdracht</a>
                        </li>
END;
                    break;

                default:
                    $extra = null;
                    break;
            }

            echo <<<END
            <div class="col s12 m6 l4 xl3">
                <div class="card medium hoverable">
                    <div class="card-image waves-effect waves-block waves-light">
                        <img class="activator responsive-img" src="{$steropdracht['image_url']}" onerror="this.src='https://cdn.lucacastelnuovo.nl/images/betasterren/logo.png'">
                    </div>
                    <div class="card-content"><span class="card-title activator grey-text text-darken-4 center">{$steropdracht['project_name']} - {$steropdracht['subject']}</span></div>
                    <div class="card-reveal">
                        <span class="card-title grey-text text-darken-4">Opties<i class="material-icons right">close</i></span>
                        <ul class="align-center card-reveal--links">
                            <li class="btn waves-effect waves-light color-secondary--background">
                                <a href="/ster-opdrachten/view/{$steropdracht['id']}">Bekijk Opdracht</a>
                            </li>
                            <li class="btn waves-effect waves-light color-secondary--background">
                                <a href="/ster-opdrachten/view/{$steropdracht['id']}/feedback">Geef Feedback</a>
                            </li>
                            {$extra}
                        </ul>
                    </div>
                </div>
            </div>
END;
        }
        echo <<<END
    </div>
</div>
END;
    } else {
        echo '<p>Er doen op dit moment geen steropdrachten in deze categorie.</p>';
    }
}

function steropdrachten_counter()
{
    $query =
        "SELECT
            SUM(sterren)
        FROM
            steropdrachten
        WHERE leerling_id = '{$_SESSION['id']}' OR buddy_id = '{$_SESSION['id']}'";

    $aantal = sql_query($query, true);
    echo $aantal['SUM(sterren)'];
}

function steropdrachten_files($id, $show, $show_leerling, $status)
{
    $query =
        "SELECT
            id,
            name,
            random_id,
            created
        FROM
            files
        WHERE
            steropdracht_id = '{$id}'";

    $files = sql_query($query, false);

    if ($files->num_rows > 0) {
        if ($show) {
            $date_th = '<th>Datum</th>';
        } else {
            $date_th = null;
        }

        if ($show_leerling && $status < 3) {
            $delete_th = '<th>Verwijder</th>';
        } else {
            $delete_th = null;
        }

        echo <<<END
        <table class="striped centered responsive-table">
            <thead>
              <tr>
                    <th>Bestandsnaam</th>
                    <th>Bekijk</th>
                    {$delete_th}
                    {$date_th}
              </tr>
            </thead>

            <tbody>
END;
        while ($file = $files->fetch_assoc()) {
            $created = date('Y-m-d', strtotime($file['created']));

            if ($show_leerling && $status < 3) {
                $delete_td = "<td><a class=\"waves-effect waves-light btn color-secondary--background modal-trigger\" target=\"_blank\" href=\"/ster-opdrachten/files/{$id}/delete/{$file['id']}\" onclick=\"return confirm('Weet je het zeker?')\">Verwijder Bestand</a></td>";
            } else {
                $delete_td = null;
            }

            if ($show) {
                $date_td = "<td>{$created}</td>";
            } else {
                $date_td = null;
            }

            echo <<<END
            <tr>
                <td>{$file['name']}</td>
                <td><a class="waves-effect waves-light btn color-secondary--background modal-trigger" target="_blank" href="/general/pdf/{$file['random_id']}">Open Bestand</a></td>
                {$delete_td}
                {$date_td}
            </tr>
END;
        }
        echo <<<END
        </tbody>
      </table>
END;
    } else {
        echo 'Deze Ster Opdracht heeft geen bestanden.';
    }
}

//Send supervising teacher email on update
function steropdrachten_notify($steropdracht_id, $leerling_id, $leerling_action)
{
    // query teacher id
    $query =
        "SELECT
            docent_id,
            project_name
        FROM
            steropdrachten
        WHERE
            id='{$steropdracht_id}'";

    $steropdracht = sql_query($query, true);

    // query teacher
    $query =
        "SELECT
            email,
            first_name,
            last_name
        FROM
            docenten
        WHERE
            id='{$steropdracht['docent_id']}'";

    $docent = sql_query($query, true);

    // query leerling
    $query =
        "SELECT
            first_name,
            last_name
        FROM
            leerlingen
        WHERE
            id='{$leerling_id}'";

    $leerling = sql_query($query, true);

    //build email
    $body = <<<END
    <!doctype html><html xmlns=http://www.w3.org/1999/xhtml xmlns:o=urn:schemas-microsoft-com:office:office xmlns:v=urn:schemas-microsoft-com:vml><title></title><!--[if !mso]><!-- --><meta content="IE=edge"http-equiv=X-UA-Compatible><!--<![endif]--><meta content="text/html; charset=UTF-8"http-equiv=Content-Type><meta content="width=device-width,initial-scale=1"name=viewport><style>#outlook a{padding:0}.ReadMsgBody{width:100%}.ExternalClass{width:100%}.ExternalClass *{line-height:100%}body{margin:0;padding:0;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%}table,td{border-collapse:collapse;mso-table-lspace:0;mso-table-rspace:0}img{border:0;height:auto;line-height:100%;outline:0;text-decoration:none;-ms-interpolation-mode:bicubic}p{display:block;margin:13px 0}</style><!--[if !mso]><!--><style>@media only screen and (max-width:480px){@-ms-viewport{width:320px}@viewport{width:320px}}</style><!--<![endif]--><!--[if mso]><xml><o:officedocumentsettings><o:allowpng><o:pixelsperinch>96</o:pixelsperinch></o:officedocumentsettings></xml><![endif]--><!--[if lte mso 11]><style>.outlook-group-fix{width:100%!important}</style><![endif]--><!--[if !mso]><!--><link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700"rel=stylesheet><style>@import url(https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700);</style><!--<![endif]--><style>@media only screen and (min-width:480px){.mj-column-per-100{width:100%!important}.mj-column-per-50{width:50%!important}}</style><body style=background:#fff><div style=background-color:#fff class=mj-container><!--[if mso | IE]><table border=0 cellpadding=0 cellspacing=0 role=presentation style=width:600px align=center width=600><tr><td style=line-height:0;font-size:0;mso-line-height-rule:exactly><![endif]--><div style="margin:0 auto;max-width:600px"><table border=0 cellpadding=0 cellspacing=0 role=presentation style=font-size:0;width:100% align=center><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0;padding:9px 0 9px 0"><!--[if mso | IE]><table border=0 cellpadding=0 cellspacing=0 role=presentation><tr><td style=vertical-align:top;width:600px><![endif]--><div style=vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100% class="outlook-group-fix mj-column-per-100"><table border=0 cellpadding=0 cellspacing=0 role=presentation width=100%><tr><td style=word-wrap:break-word;font-size:0;padding:0 align=right><table border=0 cellpadding=0 cellspacing=0 role=presentation style=border-collapse:collapse;border-spacing:0 align=right><tr><td style=width:240px><a href=https://betasterren.hetbaarnschlyceum.nl target=_blank><img alt="BetaSterren Logo"height=auto src=https://cdn.lucacastelnuovo.nl/images/betasterren/logo.png style=border:none;border-radius:0;display:block;font-size:13px;outline:0;text-decoration:none;width:100%;height:auto width=240></a></table><tr><td style="word-wrap:break-word;font-size:0;padding:0 20px 0 20px"align=left><div style=cursor:auto;color:#000;font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px;text-align:left><p><span style=font-size:16px>Beste {$docent['first_name']} {$docent['last_name']},</span><p><span style=font-size:16px>Een van de Ster Opdrachten toegewezen aan u is gewijzigd.</span></div></table></div><!--[if mso | IE]><![endif]--></table></div><!--[if mso | IE]><![endif]--><!--[if mso | IE]><table border=0 cellpadding=0 cellspacing=0 role=presentation style=width:600px align=center width=600><tr><td style=line-height:0;font-size:0;mso-line-height-rule:exactly><![endif]--><table border=0 cellpadding=0 cellspacing=0 role=presentation style=font-size:0;width:100%><tr><td><div style="margin:0 auto;max-width:600px"><table border=0 cellpadding=0 cellspacing=0 role=presentation style=font-size:0;width:100% align=center><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0;padding:9px 0 9px 0"><!--[if mso | IE]><table border=0 cellpadding=0 cellspacing=0 role=presentation><tr><td style=vertical-align:top;width:300px><![endif]--><div style=vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100% class="outlook-group-fix mj-column-per-50"><table border=0 cellpadding=0 cellspacing=0 role=presentation width=100%><tr><td style="word-wrap:break-word;font-size:0;padding:0 20px 0 20px"align=left><div style=cursor:auto;color:#000;font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px;text-align:left><p><span style=font-size:18px>Ster Opdracht</span></div></table></div><!--[if mso | IE]><td style=vertical-align:top;width:300px><![endif]--><div style=vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100% class="outlook-group-fix mj-column-per-50"><table border=0 cellpadding=0 cellspacing=0 role=presentation width=100%><tr><td style="word-wrap:break-word;font-size:0;padding:0 20px 0 20px"align=left><div style=cursor:auto;color:#000;font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px;text-align:left><p><span style=font-size:18px><b>{$steropdracht['project_name']}</b></span></div></table></div><!--[if mso | IE]><![endif]--></table></div></table><!--[if mso | IE]><![endif]--><!--[if mso | IE]><table border=0 cellpadding=0 cellspacing=0 role=presentation style=width:600px align=center width=600><tr><td style=line-height:0;font-size:0;mso-line-height-rule:exactly><![endif]--><table border=0 cellpadding=0 cellspacing=0 role=presentation style=font-size:0;width:100%><tr><td><div style="margin:0 auto;max-width:600px"><table border=0 cellpadding=0 cellspacing=0 role=presentation style=font-size:0;width:100% align=center><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0;padding:9px 0 9px 0"><!--[if mso | IE]><table border=0 cellpadding=0 cellspacing=0 role=presentation><tr><td style=vertical-align:top;width:300px><![endif]--><div style=vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100% class="outlook-group-fix mj-column-per-50"><table border=0 cellpadding=0 cellspacing=0 role=presentation width=100%><tr><td style="word-wrap:break-word;font-size:0;padding:0 20px 0 20px"align=left><div style=cursor:auto;color:#000;font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px;text-align:left><p><span style=font-size:18px>Actie</span></div></table></div><!--[if mso | IE]><td style=vertical-align:top;width:300px><![endif]--><div style=vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100% class="outlook-group-fix mj-column-per-50"><table border=0 cellpadding=0 cellspacing=0 role=presentation width=100%><tr><td style="word-wrap:break-word;font-size:0;padding:0 20px 0 20px"align=left><div style=cursor:auto;color:#000;font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px;text-align:left><p><span style=font-size:18px><b>{$leerling_action}</b></span></div></table></div><!--[if mso | IE]><![endif]--></table></div></table><!--[if mso | IE]><![endif]--><!--[if mso | IE]><table border=0 cellpadding=0 cellspacing=0 role=presentation style=width:600px align=center width=600><tr><td style=line-height:0;font-size:0;mso-line-height-rule:exactly><![endif]--><table border=0 cellpadding=0 cellspacing=0 role=presentation style=font-size:0;width:100%><tr><td><div style="margin:0 auto;max-width:600px"><table border=0 cellpadding=0 cellspacing=0 role=presentation style=font-size:0;width:100% align=center><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0;padding:9px 0 9px 0"><!--[if mso | IE]><table border=0 cellpadding=0 cellspacing=0 role=presentation><tr><td style=vertical-align:top;width:300px><![endif]--><div style=vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100% class="outlook-group-fix mj-column-per-50"><table border=0 cellpadding=0 cellspacing=0 role=presentation width=100%><tr><td style="word-wrap:break-word;font-size:0;padding:0 20px 0 20px"align=left><div style=cursor:auto;color:#000;font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px;text-align:left><p><span style=font-size:18px>Door</span></div></table></div><!--[if mso | IE]><td style=vertical-align:top;width:300px><![endif]--><div style=vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100% class="outlook-group-fix mj-column-per-50"><table border=0 cellpadding=0 cellspacing=0 role=presentation width=100%><tr><td style="word-wrap:break-word;font-size:0;padding:0 20px 0 20px"align=left><div style=cursor:auto;color:#000;font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px;text-align:left><p><span style=font-size:18px><b>{$leerling['first_name']} {$leerling['last_name']}</b></span></div></table></div><!--[if mso | IE]><![endif]--></table></div></table><!--[if mso | IE]><![endif]--><!--[if mso | IE]><table border=0 cellpadding=0 cellspacing=0 role=presentation style=width:600px align=center width=600><tr><td style=line-height:0;font-size:0;mso-line-height-rule:exactly><![endif]--><table border=0 cellpadding=0 cellspacing=0 role=presentation style=font-size:0;width:100%><tr><td><div style="margin:0 auto;max-width:600px"><table border=0 cellpadding=0 cellspacing=0 role=presentation style=font-size:0;width:100% align=center><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0;padding:9px 0 9px 0"><!--[if mso | IE]><table border=0 cellpadding=0 cellspacing=0 role=presentation><tr><td style=vertical-align:top;width:600px><![endif]--><div style=vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100% class="outlook-group-fix mj-column-per-100"><table border=0 cellpadding=0 cellspacing=0 role=presentation width=100%><tr><td style="word-wrap:break-word;font-size:0;padding:0 20px 0 20px"align=center><div style=cursor:auto;color:#000;font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px;text-align:center><p><span style=font-size:16px><a href=https://betasterren.hetbaarnschlyceum.nl/ster-opdrachten/view/{$steropdracht_id}>Bekijk Ster Opdracht</a></span></div><tr><td style="word-wrap:break-word;font-size:0;padding:0 20px 0 20px"align=left><div style=cursor:auto;color:#000;font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px;text-align:left><p><span style=font-size:16px>Met vriendelijke groet,</span><p><span style=font-size:16px>Het Baarnsch Lyceum</span></div></table></div><!--[if mso | IE]><![endif]--></table></div></table><!--[if mso | IE]><![endif]--></div>
END;

    // send email
    api_mail($docent['email'], 'Update Ster Opdracht ||  BetaSterren', $body);
}
