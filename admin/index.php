<?php
$admin_require = true; require($_SERVER['DOCUMENT_ROOT'] . "/init.php");

login_admin();

head('Users || Admin', 5, 'Users');
?>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col s12">
                <ul class="tabs tabs-fixed-width z-depth-1">
                    <li class="tab col s3"><a class="active" href="#users">Users</a></li>
                    <li class="tab col s3"><a href="#logs">Logs</a></li>
                    <li class="tab col s3"><a href="#generate">Generate</a></li>
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
            <div id="generate" class="col s12">
                Generete register code
            </div>
        </div>
    </div>
</div>
<?php footer("<script>var instance = M.Tabs.init(document.querySelector('.tabs'), {});</script>"); ?>
