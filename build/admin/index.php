<?php
$admin_require = true; require($_SERVER['DOCUMENT_ROOT'] . "/init.php");

login_admin();

head('Admin', 5);
?>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col s12">
                <ul class="tabs tabs-fixed-width z-depth-1">
                    <li class="tab col s3"><a class="active" href="#users">Users</a></li>
                    <li class="tab col s3"><a href="#logs">Logs</a></li>
                    <li class="tab col s3"><a href="#agenda">Agenda</a></li>
                    <li class="tab col s3"><a href="#extra">Extra</a></li>
                </ul>
            </div>
            <div id="users" class="col s12">
                <h3>4 HAVO</h3>
                <?php admin_accounts_list('4havo'); ?>
                <h3>4 VWO</h3>
                <?php admin_accounts_list('4vwo'); ?>
                <h3>5 HAVO</h3>
                <?php admin_accounts_list('5havo'); ?>
                <h3>5 VWO</h3>
                <?php admin_accounts_list('5vwo'); ?>
                <h3>6 VWO</h3>
                <?php admin_accounts_list('6vwo'); ?>
                <h3>Docenten</h3>
                <?php admin_accounts_list('docenten'); ?>
            </div>
            <div id="logs" class="col s12">
                <?php admin_log_list(); ?>
            </div>
            <div id="agenda" class="col s12">
                <h3>Agenda</h3>
            </div>
            <div id="extra" class="col s12">
                <div class="row">
                    <h3>Registration</h3>
                    <a id="gen_register" class="waves-effect waves-light btn-large color-secondary--background"><i class="material-icons left">code</i>Generate registration link</a>
                    <a id="gen_register_send" class="waves-effect waves-light btn-large color-secondary--background"><i class="material-icons left">code</i>Send registration link</a>
                </div>
                <div class="row">
                    <h3>Sessions</h3>
                    <a class="waves-effect waves-light btn-large color-secondary--background"><i class="material-icons left">code</i>Destroy all sessions</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php footer("<script>var instance = M.Tabs.init(document.querySelector('.tabs'), {});</script><script src='https://cdn.lucacastelnuovo.nl/js/betasterren/gen.js'></script>"); ?>
