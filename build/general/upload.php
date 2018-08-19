<?php
require($_SERVER['DOCUMENT_ROOT'] . "/init.php");

login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_val($_POST['CSRFtoken']);
    $type = clean_data($_POST['type']);
    $url = clean_data($_POST['url']);

    $_SESSION['toast_set'] = true;

    switch ($type) {
        case 'leerling_profile_picture':
            $query =
            "UPDATE
                leerlingen
            SET
                profile_url='{$url}'
            WHERE
                id = '{$_SESSION['leerling_id']}'";

            sql_query($query, false);
            echo json_encode(['status' => true, 'url' => '/general/toast?url=/leerlingen/&alert=Profiel foto succesvol aangepast']);
            exit;
            break;

        default:
            echo json_encode(['status' => false, 'url' => '/general/toast?url=/general/home&alert=Oeps er ging iets fout']);
            exit;
            break;
    }
}

head('Upload', 5, 'Upload', '<link href="https://cdn.lucacastelnuovo.nl/css/betasterren/imgur.4.css" rel="stylesheet">');

?>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col s12">
                <div class="center-align">
                    <h1>Upload Profile Picture</h1>
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
<?php footer('<script src="https://cdn.lucacastelnuovo.nl/js/ajax.js"></script><script src="https://cdn.lucacastelnuovo.nl/js/betasterren/imgur.php.js?response_url=/general/upload.php&type=' . clean_data($_GET['type']) . '&client_id=b2c72661027878c"></script>');?>
