<?php

function steropdrachten_list($done)
{
    $status = $done ? ">= '3'" : "= '2'";

    $query =
    "SELECT
        id,
        project_name,
        leerling_id
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
            // TODO: remove true for production
            if ($steropdracht['leerling_id'] === $_SESSION['leerling_id'] || true) {
                $extra = "<li class=\"btn waves-effect waves-light color-secondary--background\"><a href=\"/ster-opdrachten/edit/{$steropdracht['id']}\">Edit Opdracht</a></li>";
            } else {
                $extra = "<li class=\"btn waves-effect waves-light color-secondary--background\"><a href=\"/ster-opdrachten/join/{$steropdracht['id']}\">Join Opdracht</a></li>";
            }

            echo <<<END
            <div class="col s12 m6 l4 xl3">
                <div class="card medium hoverable">
                    <div class="card-image waves-effect waves-block waves-light">
                        <img class="activator responsive-img" src="/files/steropdrachten/{$steropdracht['id']}.png" onerror="this.src='https://cdn.lucacastelnuovo.nl/images/betasterren/default_profile.png'">
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
