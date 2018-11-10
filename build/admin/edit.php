<?php

require($_SERVER['DOCUMENT_ROOT'] . '/init.php');

login_admin();

$id = clean_data($_GET['id']);
$class = clean_data($_GET['class']);

is_empty([$id, $class], '/admin', 'Deze link is niet geldig');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_val($_POST['CSRFtoken']);

    $first_name = clean_data($_POST['first_name']);
    $last_name = clean_data($_POST['last_name']);
    $email = clean_data($_POST['email']);

    if ($class === 'docenten') {
        $query =
            "UPDATE
                docenten
            SET
                first_name = '{$first_name}',
                last_name = '{$last_name}',
                email = '{$email}',
            WHERE
                id='{$id}';";
    } else {
        $leerling_nummer = clean_data($_POST['leerling_nummer']);
        $profile_url = clean_data($_POST['profile_url']);
        $class_post = clean_data($_POST['class']);

        $query =
            "UPDATE
                leerlingen
            SET
                first_name = '{$first_name}',
                last_name = '{$last_name}',
                email = '{$email}',
                class = '{$class_post}',
                profile_url = '{$profile_url}',
                leerling_nummer = '{$leerling_nummer}',
                admin = '{$admin}',
            WHERE
                id='{$id}';";
    }

    sql_query($query, false);

    // log_action($_SESSION['first_name'] . ' ' . $_SESSION['last_name'], 'SterOpdrachten bestand toegevoegd');

    redirect('/admin', 'Gebruiker Aangepast');
}

$sql_table = ($class === 'docenten') ? 'docenten' : 'leerlingen';

head('Edit || Admin', 5, 'Edit');

$CSRFtoken = csrf_gen();

if ($class === 'docenten') {
    $query =
        "SELECT
            first_name,
            last_name,
            email
        FROM
            docenten
        WHERE
            id='{$id}'";

    $user = sql_query($query, true);

    echo <<<END
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col s12">
                    <div class="row">
                        <form action="/auth/register.php" method="post">
                            <div class="row">
                                <div class="input-field col s12">
                                    <label for="first_name">Voornaam</label>
                                    <input type="text" id="first_name" name="first_name" required value="{$user['first_name']}"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12">
                                    <label for="last_name">Achternaam</label>
                                    <input type="text" id="last_name" name="last_name" required value="{$user['last_name']}"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email" required value="{$user['email']}"/>
                                </div>
                            </div>
                            <div class="row">
                                <input type="hidden" name="CSRFtoken" value="{$CSRFtoken}"/>
                                <button type="submit" class="waves-effect waves-light btn color-primary--background">
                                    Bevestig
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
END;
} else {
    $query =
        "SELECT
            first_name,
            last_name,
            email,
            class,
            profile_url,
            leerling_nummer
        FROM
            leerling
        WHERE
            id='{$id}'";

    $user = sql_query($query, true);

    $class_4havo = null;
    $class_4vwo = null;
    $class_5havo= null;
    $class_5vwo = null;
    $class_6vwo = null;

    switch ($user['class']) {
        case '4havo':
            $class_4havo = 'checked';
            break;

        case '4vwo':
            $class_4vwo = 'checked';
            break;

        case '5havo':
            $class_5havo = 'checked';
            break;

        case '5vwo':
            $class_5vwo = 'checked';
            break;

        case '6vwo':
            $class_6vwo = 'checked';
            break;
    }

    echo <<<END
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col s12">
                    <div class="row">
                        <form action="/auth/register.php" method="post">
                            <div class="row">
                                <div class="input-field col s12">
                                    <label for="first_name">Voornaam</label>
                                    <input type="text" id="first_name" name="first_name" required value="{$user['first_name']}"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12">
                                    <label for="last_name">Achternaam</label>
                                    <input type="text" id="last_name" name="last_name" required value="{$user['last_name']}"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email" required value="{$user['email']}"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12">
                                    <label for="profile_url">Profile URL</label>
                                    <input type="text" id="profile_url" name="profile_url" required value="{$user['profile_url']}"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12">
                                    <label for="leerling_nummer">Leerling Nummer</label>
                                    <input type="number" id="leerling_nummer" name="leerling_nummer" required value="{$user['leerling_nummer']}"/>
                                </div>
                            </div>
                            <h4>Klas:</h4>
                            <p>
                                <label>
                                    <input name="class" type="radio" value="4havo" required {$class_4havo} />
                                    <span>4havo</span>
                                </label>
                            </p>
                            <p>
                                <label>
                                    <input name="class" type="radio" value="4vwo" required {$class_4vwo} />
                                    <span>4vwo</span>
                                </label>
                            </p>
                            <p>
                                <label>
                                    <input name="class" type="radio" value="5havo" required {$class_5havo} />
                                    <span>5havo</span>
                                </label>
                            </p>
                            <p>
                                <label>
                                    <input name="class" type="radio" value="5vwo" required {$class_5vwo} />
                                    <span>5vwo</span>
                                </label>
                            </p>
                            <p>
                                <label>
                                    <input name="class" type="radio" value="6vwo" required {$class_6vwo} />
                                    <span>6vwo</span>
                                </label>
                            </p>
                            <div class="row">
                                <input type="hidden" name="CSRFtoken" value="{$CSRFtoken}"/>
                                <button type="submit" class="waves-effect waves-light btn color-primary--background">
                                    Bevestig
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
END;
}

footer();
