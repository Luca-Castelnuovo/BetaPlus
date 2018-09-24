<?php

function leerlingen_list($class)
{
    $query =
        "SELECT
            id,
            leerling_nummer,
            first_name,
            last_name,
            utalent,
            profile_url
        FROM
            leerlingen
        WHERE
            class = '{$class}' AND active = '1'
        ORDER BY
            last_name";

    $result = sql_query($query, false);
    if ($result->num_rows > 0) {
        echo <<<END
        <div class="section white">
            <div class="row">
END;
        while ($student = $result->fetch_assoc()) {
            echo <<<END
            <div class="col s12 m6 l4 xl3" id="{$student['id']}">
                <div class="card medium hoverable">
                    <div class="card-image waves-effect waves-block waves-light">
                        <img class="activator responsive-img" src="{$student['profile_url']}" onerror="this.src='https://cdn.lucacastelnuovo.nl/images/betasterren/default_profile.png'">
                    </div>
                    <div class="card-content"><span class="card-title activator grey-text text-darken-4 center truncate">{$student['first_name']} {$student['last_name']}</span></div>
                    <div class="card-reveal">
                        <span class="card-title grey-text text-darken-4">Recente Opdrachten<i class="material-icons right">close</i></span>
END;
            steropdrachten_list_individual_recent($student['id']);
            echo <<<END
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
        echo "<p>Er doen op dit moment geen {$class} leerlingen mee aan het BetaSterren project.</p>";
    }
}

function steropdrachten_list_individual_recent($leerling_id)
{
    $query =
        "SELECT
        id,
        project_name
    FROM
        steropdrachten
    WHERE
        leerling_id='{$leerling_id}' AND status >= '2'
    ORDER BY
        created DESC
    LIMIT 3";

    $result = sql_query($query, false);
    if ($result->num_rows > 0) {
        echo '<ul class="align-center card-reveal--links">';
        while ($steropdracht = $result->fetch_assoc()) {
            echo <<<END
        <li class="btn waves-effect waves-light color-secondary--background">
            <a href="/ster-opdrachten/view/{$steropdracht['id']}">{$steropdracht['project_name']}</a>
        </li>
END;
        }
        echo '</ul>';
    } else {
        echo '<p>Deze leerling heeft geen steropdrachten</p>';
    }
}
