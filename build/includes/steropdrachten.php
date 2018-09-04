<?php

function steropdrachten_list($done)
{
    $status = $done ? ">= '3'" : "= '2'";

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
            } elseif ($steropdracht['status'] <= 2) {
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
            status {$status} AND leerling_id='{$_SESSION['id']}'
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
            $status = '< 2';
            break;
        case '1':
            //1 = feedback requested
            $status = '= 2 AND feedback_requested = 1';
            break;
        case '2':
            //2 = beoordeling (sterren, en abcd)
            $status = '= 3';
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
