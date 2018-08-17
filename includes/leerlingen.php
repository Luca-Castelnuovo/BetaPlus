<?php

function leerlingen_list($class)
{
    $query =
    "SELECT
        id,
        first_name,
        last_name,
        utalent
    FROM
        leerlingen
    WHERE
        class = '$class' AND active = '1'
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
            <div class="col s12 m6 l4 xl3">
                <div class="card medium hoverable">
                    <div class="card-image waves-effect waves-block waves-light">
                        <img class="activator" src="https://via.placeholder.com/400x400?text={$student['first_name']}">
                    </div>
                    <div class="card-content"><span class="card-title activator grey-text text-darken-4 center">{$student['first_name']} {$student['last_name']}</span></div>
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
        projectname
    FROM
        steropdrachten
    WHERE
        leerling_id='{$leerling_id}'
    ORDER BY
        created DESC
    LIMIT
        4";

    $result = sql_query($query, false);
    echo <<<END
<ul class="align-center card-reveal--links">
END;
    if ($result->num_rows > 0) {
        while ($steropdracht = $result->fetch_assoc()) {
            echo <<<END
        <li class="btn waves-effect waves-light color-secondary--background">
            <a href="/ster-opdrachten/view/{$steropdracht['id']}">{$steropdracht['projectname']}</a>
        </li>
END;
        }
        echo <<<END
    </ul>
END;
    } else {
        echo "<p>Deze leerling heeft geen steropdrachten</p>";
    }
}
