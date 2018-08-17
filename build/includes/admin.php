<?php

function admin_accounts_list($class)
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
            utalent,
            leerling_nummer
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
            $CSRFtoken = csrf_gen();

            $opposite_enable_disable_state_binary = $user['active'] ? 0 : 1;
            $opposite_enable_disable_state_text = $user['active'] ? 'Disable' : 'Enable';

            if ($class === 'docenten') {
                $utalent = null;
                $user['leerling_nummer'] = null;
            } else {
                $opposite_utalent_state_binary = $user['utalent'] ? 0 : 1;
                $opposite_utalent_state_text = $user['utalent'] ? 'Disable' : 'Enable';
                $utalent = "<li class=\"btn waves-effect waves-light color-secondary--background\"><a href=\"/admin/process/{$CSRFtoken}/utalent/{$user['id']}/{$class}/{$opposite_utalent_state_binary}\">{$opposite_utalent_state_text} Utalent</a></li>";
            }

            echo <<<END
            <div class="col s12 m6 l4 xl3">
                <div class="card medium hoverable">
                    <div class="card-image waves-effect waves-block waves-light">
                        <img class="activator" src="/files/leerlingen/{$user['leerling_nummer']}.png" onerror="this.src='https://cdn.lucacastelnuovo.nl/images/betasterren/default_profile.png'">
                    </div>
                    <div class="card-content"><span class="card-title activator grey-text text-darken-4 center">{$user['first_name']} {$user['last_name']}</span></div>
                    <div class="card-reveal">
                        <span class="card-title grey-text text-darken-4">Opties<i class="material-icons right">close</i></span>
                        <ul class="align-center card-reveal--links">
                            <li class="btn waves-effect waves-light color-secondary--background">
                                <a href="/admin/process/{$CSRFtoken}/active/{$user['id']}/{$class}/{$opposite_enable_disable_state_binary}">{$opposite_enable_disable_state_text} User</a>
                            </li>
                            {$utalent}
                            <li class="btn waves-effect waves-light color-secondary--background">
                                <a href="/admin/process/{$CSRFtoken}/unblock/{$user['id']}/{$class}/">Unblock Password</a>
                            </li>
                            <li class="btn waves-effect waves-light color-secondary--background">
                                <a href="/admin/process/{$CSRFtoken}/reset/{$user['id']}/{$class}/">Reset Password</a>
                            </li>
                            <li class="btn waves-effect waves-light disabled color-secondary--background">
                                <a href="/admin/process/{$CSRFtoken}/delete/{$user['id']}/{$class}/">Delete User</a>
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
        echo "<p>Er doen op dit moment geen users in deze ({$class}) categorie.</p>";
    }
}


function admin_log_list()
{
    $query =
    "SELECT
        user,
        action,
        ip,
        date
    FROM
        logs
    ORDER BY
        date DESC";

    $result = sql_query($query, false);
    if ($result->num_rows > 0) {
        echo <<<END
        <table class="striped centered highlight responsive-table">
            <thead>
              <tr>
                    <th>IP</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Date</th>
              </tr>
            </thead>

            <tbody>
END;
        while ($entry = $result->fetch_assoc()) {
            echo <<<END
            <tr>
                <td>{$entry['ip']}</td>
                <td>{$entry['user']}</td>
                <td>{$entry['action']}</td>
                <td>{$entry['date']}</td>
            </tr>
END;
        }
        echo <<<END
        </tbody>
      </table>
END;
    } else {
        echo 'There are no log entry\'s';
    }
}
