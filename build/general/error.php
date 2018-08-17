<?php

$error = htmlspecialchars($_GET['code']);
$goto = $_SESSION['logged_in'] ? 'home' : 'login';
$goto_url = $_SESSION['logged_in'] ? '/general/home' : '/';

switch ($error) {
    case '403':
        $error_text = 'Toegang verboden';
        break;

    case '404':
        $error_text = 'Deze pagina bestaat niet';
        break;

    default:
        $error_text = 'Oeps er is iets fout gegaan';
        break;
}

?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/css/materialize.min.css">
    <title><?= $error ?> || Error</title>
</head>

<body>
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col s12">
                    <h1><?= $error ?> Error</h1>
                    <h3><?= $error_text ?></h3>
                    <a href="<?= $goto_url ?>" class="waves-effect waves-light btn-large">Ga naar <?= $goto ?></a>
                </div>
            </div>
        </div>
    </div>


    <!--Import Materialize JavaScript-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/js/materialize.min.js"></script>
</body>

</html>
