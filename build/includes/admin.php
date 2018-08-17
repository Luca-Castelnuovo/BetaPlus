<?php

function accounts_list($class)
{
    if ($class === 'docenten') {
        $query =
        "SELECT
            id,
            active,
            failed_login,
            first_name,
            last_name
        FROM
            docenten
        ORDER BY
            last_name";
    } else {
        $query =
        "SELECT
            id,
            admin,
            active,
            failed_login,
            first_name,
            last_name,
            utalent
        FROM
            leerlingen
        WHERE
            class = '$class'
        ORDER BY
            last_name";
    }

    $result = sql_query($query, false);
    if ($result->num_rows > 0) {
        echo <<<END
        <div class="section white">
            <div class="row">
END;
        while ($user = $result->fetch_assoc()) {
            echo <<<END
            <div class="col s12 m6 l4 xl3">
                <div class="card medium hoverable">
                    <div class="card-image waves-effect waves-block waves-light">
                        <img class="activator" src="/files/leerlingen/{$student['leerling_nummer']}.png" onerror="this.src='https://cdn.lucacastelnuovo.nl/images/betasterren/default_profile.png'">
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
