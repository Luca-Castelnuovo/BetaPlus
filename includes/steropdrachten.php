<?php

function steropdrachten_list($done)
{
    $query =
    "SELECT
        id,
        project_name,
        leerling_id
    FROM
        steropdrachten
    WHERE
        reviewed = '1' AND approved = '1' AND done='$done'
    ORDER BY
        created DESC";

    $result = sql_query($query, false);
    if ($result->num_rows > 0) {
        echo <<<END
        <div class="section white">
            <div class="row">
END;
        while ($steropdracht = $result->fetch_assoc()) {
            echo <<<END
            <div class="col s12 m6 l4 xl3">
                <div class="card medium hoverable">
                    <div class="card-image waves-effect waves-block waves-light">
                        <img class="activator" src="https://via.placeholder.com/400x400?text={$steropdracht['project_name']}">
                    </div>
                    <div class="card-content"><span class="card-title activator grey-text text-darken-4 center">{$steropdracht['project_name']}</span></div>
                    <div class="card-reveal">
                        <span class="card-title grey-text text-darken-4">Opties<i class="material-icons right">close</i></span>
                        <ul class="align-center card-reveal--links">
                            <li class="btn waves-effect waves-light color-secondary--background">
                                <a href="/ster-opdrachten/view/{$steropdracht['id']}">Bekijk Opdracht</a>
                            </li>
                            <li class="btn waves-effect waves-light color-secondary--background">
                                <a href="/ster-opdrachten/join/{$steropdracht['id']}">Join Opdracht</a>
                            </li>
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
