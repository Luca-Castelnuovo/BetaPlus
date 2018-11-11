<?php

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
function log_action($action, $user = null)
{
    if (empty($user)) {
        if (!empty($_SESSION['first_name'])) {
            $user = $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
        } else {
            $user = 'Unknown';
        }
    }

    $date = current_date(true);
    $ip = ip();
    $query = "INSERT INTO logs (date, user, action, ip) VALUES ('{$date}', '{$user}', '{$action}', '{$ip}')";
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
                        <p>{$item['date']}</p>
                        <span class="card-title">{$item['title']}</span>
                    </div>
END;
            if (!empty($item['link']) || $_SESSION['class'] == 'docent') {
                echo '<div class="card-action">';
                if (!empty($item['link'])) {
                    echo "<a href=\"{$item['link']}\" class=\"waves-effect waves-light btn color-primary--background\" target=\"_blank\" rel=\"noopener noreferrer\">Link</a>";
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

//Set User message
function message_set($user_id, $user_class, $message)
{
    if ($user_class) {
        $table = 'docenten';
    } else {
        $table = 'leerlingen';
    }

    $query =
        "UPDATE
            {$table}
        SET
            message = {$message}
        WHERE
            id = '{$user_id}'";

    sql_query($query, false);
}

//Read User message
function message_read()
{
    $user_id = $_SESSION['id'];

    if ($_SESSION['class'] == 'docent') {
        $table = 'docenten';
    } else {
        $table = 'leerlingen';
    }

    $query =
        "SELECT
            message
        FROM
            {$table}
        WHERE
            id = '{$user_id}'";

    $result = sql_query($query, true);

    if (!empty($result['message'])) {
        require($_SERVER['DOCUMENT_ROOT'] . '/libs/Parsedown.php');
        $parsedown = new Parsedown();
        $parsedown->setSafeMode(true);

        $message = $parsedown->text($result['message']);

        echo <<<END
        <div id="message" class="modal">
            <div class="modal-content">
                {$message}
            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-close waves-effect waves-light color-secondary--background btn">Close</a>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                for(var p=document.querySelectorAll("p"),i=0;i<p.length;i++)p[i].classList.add("flow-text");for(var ul=document.querySelectorAll("ul"),i=0;i<ul.length;i++)ul[i].classList.add("browser-default");for(var li=document.querySelectorAll("ul li"),i=0;i<li.length;i++)li[i].classList.add("browser-default");

                var elems = document.querySelectorAll('.modal');
                var instances = M.Modal.init(elems, {dismissible: false});

                setTimeout(function () {
                    M.Modal.getInstance(document.querySelector('#message')).open();
                }, 100);
            });
        </script>
END;

        $query =
            "UPDATE
                {$table}
            SET
                message = NULL
            WHERE
                id = '{$user_id}'";

        sql_query($query, false);
    }
}
