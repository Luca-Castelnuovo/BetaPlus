<?php
require($_SERVER['DOCUMENT_ROOT'] . "/init.php");

login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $CSRFtoken = clean_data($_POST['CSRFtoken']);
    $type = clean_data($_POST['type']);
    $url = clean_data($_POST['url']);
    $id = clean_data($_POST['id']);

    if (!csrf_val($CSRFtoken, true)) {
        echo json_encode(['status' => true, 'url' => '/general/toast?url=/' . $id . '/&alert=CSRF error']);
        exit;
    }

    $_SESSION['toast_set'] = true;

    switch ($type) {
        case 'leerling_profile':
            $query =
            "UPDATE
                leerlingen
            SET
                profile_url='{$url}'
            WHERE
                id = '{$_SESSION['id']}'";

            sql_query($query, false);
            echo json_encode(['status' => true, 'url' => '/general/toast?url=/leerlingen/settings&alert=Profiel foto aangepast']);
            exit;
            break;

        case 'steropdrachten_cover':
            if (!token_val($id, true)) {
                echo json_encode(['status' => true, 'url' => '/general/toast?url=/ster-opdrachten/edit/' . $id . '/&alert=Oeps er ging iets fout, propbeer opnieuw']);
                exit;
            }

            $query =
            "UPDATE
                steropdrachten
            SET
                image_url='{$url}'
            WHERE
                id = '{$id}'";

            sql_query($query, false);
            echo json_encode(['status' => true, 'url' => '/general/toast?url=/ster-opdrachten/view/' . $id . '/&alert=Cover foto aangepast']);
            exit;
            break;

        default:
            echo json_encode(['status' => true, 'url' => '/general/toast?url=/general/home&alert=Oeps er ging iets fout']);
            exit;
            break;
    }
} else {
    if (strlen($_GET['id']) < 1) {
        $id = 'undefined';
    } else {
        $id = clean_data($_GET['id']);
    }
}

head('Upload', 5, 'Upload', '<link href="https://cdn.lucacastelnuovo.nl/css/betasterren/imgur.4.css" rel="stylesheet">');

?>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col s12">
                <div class="center-align">
                    <h1>Upload Foto</h1>
                    <input type="hidden" id="CSRFtoken" name="CSRFtoken" value="<?= csrf_gen(); ?>">
                </div>
                <div class="dropzone">
                    <div class="info">
                        <div class="preloader-wrapper big hide">
                            <div class="spinner-layer spinner-blue-only">
                                <div class="circle-clipper left">
                                    <div class="circle"></div>
                                </div>
                                <div class="gap-patch">
                                    <div class="circle"></div>
                                </div>
                                <div class="circle-clipper right">
                                    <div class="circle"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php footer('<script src="https://cdn.lucacastelnuovo.nl/js/ajax.js"></script><script src="https://cdn.lucacastelnuovo.nl/js/betasterren/imgur.php.4.js?response_url=/general/upload.php&type=' . clean_data($_GET['type']) . '&id=' . $id . '&client_id=b2c72661027878c"></script>');?>
