<?php

//Load configuration
function config_load()
{
    return parse_ini_file('/var/www/config.ini');
}


//Get clients ip
function ip()
{
    return $_SERVER['REMOTE_ADDR'];
}

//Get current date (and time)
function current_date($andTime = false)
{
    if ($andTime) {
        return date('Y-m-d H:i:s');
    } else {
        return date('Y-m-d');
    }
}


//Log actions
function log_action($user, $action, $priority)
{
    $date = current_date(true);
    $ip = ip();
    $query = "INSERT INTO logs (date, user, action, ip, priority) VALUES ('{$date}', '{$user}', '{$action}', '{$ip}', '{$priority}')";
    sql_query($query, false);
}


//Redirect and set alert if specified
function redirect($to, $alert = null)
{
    !isset($alert) ?: alert_set($alert);
    header('location: ' . $to);
    exit;
}


//Alert system
function alert_set($alert)
{
    $_SESSION['alert'] = $alert;
}

function alert_display()
{
    if (isset($_SESSION['alert']) && !empty($_SESSION['alert'])) {
        echo "<script>M.toast({html: '{$_SESSION['alert']}'})</script>";
        unset($_SESSION['alert']);
    }
}

//Check if required vars are not empty
function is_empty($vars, $redirect, $alert = null)
{
    foreach ($vars as $var) {
        if (empty($var)) {
            $alert = isset($alert) ? $alert : 'Vul aub alle velden in';
            redirect($redirect, $alert);
        }
    }
}

//Output agenda items
function agenda()
{
    $query =
        "SELECT
            id,
            title,
            link,
            date
        FROM
            agenda
        WHERE
            DATE(date) >= DATE(NOW())
        ORDER BY
            date ASC";

    $items = sql_query($query, false);

    if ($items->num_rows > 0) {
        while ($item = $items->fetch_assoc()) {
            echo <<<END
            <div class="col s12">
                <div class="card">
                    <div class="card-content">
                      <span class="card-title">{$item['title']}</span>
                      <p>{$item['date']}</p>
                    </div>
END;
            if (!empty($item['link']) || $_SESSION['class'] == 'docent') {
                echo '<div class="card-action">';
                if (!empty($item['link'])) {
                    echo "<a href=\"{$item['link']}\" class=\"waves-effect waves-light btn color-primary--background\" target=\"_blank\">Link</a>";
                }

                if ($_SESSION['class'] == 'docent') {
                    echo "<a href=\"/admin/agenda/{$item['id']}\" class=\"waves-effect waves-light btn color-secondary--background\" onclick=\"return confirm('Weet u het zeker?')\">Verwijder</a>";
                }
                echo '</div>';
            }
            echo <<<END
                </div>
            </div>
END;
        }
    } else {
        echo '<p>Er zijn op dit moment geen agenda items.</p> ';
    }
}
