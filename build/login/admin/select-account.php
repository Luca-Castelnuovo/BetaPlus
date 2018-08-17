<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/scripts/functions.php");
login_admin();
?>
<!DOCTYPE html>
<html lang="">

<?php head('Admin - Select Account'); ?>

<body>
<div id="Menu">
    <ul>
        <li><a href="index" class="hoverable"><span class="normal">Select Account</span><span class="hover">Terug naar admin panel</span></a>
        </li>
    </ul>
</div>
<?php alert(); ?>
<img id="logo" src="/images/beta-sterren-logo.png">
<h2>Select account:</h2>
<div class="edit" style="margin-left:auto; margin-right: auto;">
    <div class="form send-code edit" style="margin-top:14px; margin-bottom:14px;">
        <div class="tab-content" style="width:100%;">
            <div id="send-code" style="display: block; width:100%;">
                <div class="top-row" style="width:100%;">
                    <div class="field-wrap" style="margin-bottom: 0; width:100%;">
                        <input type="text" id="myInput2" onkeyup="myFunction()" placeholder="Zoek:">
                        <?php include_once "account-list.php"; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/js/search-table.js"></script>
</body>

</html>
