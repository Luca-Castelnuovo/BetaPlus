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
                active,
                first_name,
                last_name,
                utalent,
                leerling_nummer,
                profile_url
            FROM
                leerlingen
            WHERE
                class = '{$class}'
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
                        <img class="activator responsive-img" src="{$user['profile_url']}" onerror="this.src='https://cdn.lucacastelnuovo.nl/images/betasterren/default_profile.png'">
                    </div>
                    <div class="card-content"><span class="card-title activator grey-text text-darken-4 center truncate">{$user['first_name']} {$user['last_name']}</span></div>
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
                                <a href="/admin/process/{$CSRFtoken}/delete/{$user['id']}/{$class}/" onclick="return confirm('Weet je het zeker?')">Delete User</a>
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
            priority,
            date
        FROM
            logs
        ORDER BY
            date DESC
        LIMIT
            250";

    $result = sql_query($query, false);

    if ($result->num_rows > 0) {
        $CSRFtoken = csrf_gen();
        echo <<<END
        <div class="row margin-top-5">
            <div class="col s12"><input type="search" id="filter" class="light-table-filter" data-table="order-table" placeholder="Filter"></div>
        </div>
        <table class="striped centered responsive-table order order-table">
            <thead>
              <tr>
                    <th>Priority</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>IP</th>
                    <th>Date</th>
              </tr>
            </thead>

            <tbody>
END;
        while ($entry = $result->fetch_assoc()) {
            switch ($entry['priority']) {
                case '0':
                    $priority = 'LOW';
                    break;

                case '1':
                    $priority = 'MEDIUM';
                    break;

                case '2':
                    $priority = 'HIGH';
                    break;

                default:
                    $priority = 'unknown';
                    break;
            }

            echo <<<END
            <tr>
                <td>{$priority}</td>
                <td>{$entry['user']}</td>
                <td>{$entry['action']}</td>
                <td>{$entry['ip']}</td>
                <td>{$entry['date']}</td>
            </tr>
END;
        }
        echo <<<END
        </tbody>
      </table>
      <br />
      <a href="/admin/process/{$CSRFtoken}/log_clear/id/class/state" class="waves-effect waves-light btn color-primary--background" onclick="return confirm('Weet je het zeker?')">Clear Log</a>
END;
    } else {
        echo 'There are no log entry\'s';
    }
}
