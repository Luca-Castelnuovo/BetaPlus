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
        <div class="section">
            <ul class="collection">
END;
        while ($user = $result->fetch_assoc()) {
            $CSRFtoken = csrf_gen();

            $opposite_enable_disable_state_binary = $user['active'] ? 0 : 1;
            $opposite_enable_disable_state_text = $user['active'] ? 'Disable' : 'Enable';

            $status_class = 'red';

            if ($user['failed_login'] > 4 && !$user['active']) {
                $status = 'disabled & blocked';
            } elseif (!$user['active']) {
                $status = 'disabled';
            } elseif ($user['failed_login'] > 4) {
                $status = 'blocked';
            } else {
                $status = 'active';
                $status_class = null;
            }

            if ($user['failed_login'] > 4) {
                $unblock = <<<END
                <li tabindex="0">
                    <a href="/admin/process/{$CSRFtoken}/unblock/{$user['id']}/{$class}/"><span class="black-text">Unblock</span></a>
                </li>
END;
            } else {
                $unblock = null;
            }

            echo <<<END
            <li class="collection-item avatar {$status_class}">
                <img src="{$user['profile_url']}" onerror="this.src='{$GLOBALS['config']->cdn->images->default_profile}'" class="circle">
                <span class="title">{$user['first_name']} {$user['last_name']}</span>
                <p>Status: {$status}</p>
                <a href="#!" class="secondary-content dropdown-trigger-user-settings" data-target='user-settings-{$user['id']}'><span class="black-text"><i class="material-icons">more_vert</i></span></a>

                <ul class="dropdown-content" id="user-settings-{$user['id']}" tabindex="0" style="">
                    <li tabindex="0">
                        <a href="/admin/message/{$user['id']}/{$class}"><span class="black-text">Message</span></a>
                    </li>
                    <li tabindex="0">
                        <a href="/admin/edit/{$user['id']}/{$class}" target="_blank"><span class="black-text">Edit</span></a>
                    </li>
                    {$unblock}
                    <li class="divider" tabindex="0"></li>
                    <li tabindex="0">
                        <a href="/admin/process/{$CSRFtoken}/active/{$user['id']}/{$class}/{$opposite_enable_disable_state_binary}"><span class="black-text">{$opposite_enable_disable_state_text}</span></a>
                    </li>
                    <li tabindex="0">
                        <a href="/admin/process/{$CSRFtoken}/delete/{$user['id']}/{$class}/" onclick="return confirm('Weet je het zeker?')"><span class="black-text">Delete</span></a>
                    </li>
                </ul>
            </li>

END;
        }
        echo <<<END
            </ul>
        </div>
END;
    } else {
        echo "<p>Er doen op dit moment geen gebruikers in '{$class}'.</p>";
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
        $CSRFtoken = csrf_gen();
        echo <<<END
        <div class="row margin-top-5 input-field">
            <div class="col s12 m10">
                <input type="search" id="filter" class="table-logs" data-table="list-logs" placeholder="Filter">
            </div>
            <div class="col s12 m2">
                <a href="/admin/process/{$CSRFtoken}/log_clear/id/class/state" class="waves-effect waves-light btn color-primary--background" onclick="return confirm('Weet je het zeker?')">Clear Log</a>
            </div>
        </div>
        <table class="striped centered responsive-table list-logs">
            <thead>
              <tr>
                    <th>Action</th>
                    <th>IP</th>
                    <th>User</th>
                    <th>Date</th>
              </tr>
            </thead>

            <tbody>
END;
        while ($entry = $result->fetch_assoc()) {
            echo <<<END
            <tr>
                <td>{$entry['action']}</td>
                <td>{$entry['ip']}</td>
                <td>{$entry['user']}</td>
                <td>{$entry['date']}</td>
            </tr>
END;
        }
        echo <<<END
        </tbody>
      </table>
END;
    } else {
        echo 'Er zijn geen logs.';
    }
}
