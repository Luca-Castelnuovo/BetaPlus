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
                $extra = "<li class=\"btn waves-effect waves-light color-secondary--background\"><a href=\"/ster-opdrachten/join/{$steropdracht['id']}/\">Join Opdracht</a></li>";
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
        echo '<p>Er doen op dit moment geen steropdrachten in deze categorie.</p>';
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
    //0 = go/no go
    //1 = feedback requested
    //2 = beoordeling (sterren, en abcd)
    //3 = lopend
    echo $type;
}
