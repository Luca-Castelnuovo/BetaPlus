<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/scripts/functions.php");
login_admin();
?>
<!DOCTYPE html>
<html lang="nl">

<?php head('Administrator'); ?>

<body>
<div id="Menu">
    <ul>
        <li><a href="/" class="hoverable"><span class="normal">Admin Panel</span><span class="hover">Log Out</span></a>
        </li>
    </ul>
</div>
<?php alert(); ?>
<h1>Admin panel</h1>
<div class="admin">
    <div class="bar-wrapper">
        <div class="bar">
            <div class="form send-code">
                <div class="tab-content">
                    <form action="send-code" method="post" autocomplete="off">
                        <div class="top-row">
                            <div class="field-wrap">
                                <input type="email" required autocomplete="off" placeholder="Email Adres"
                                       name='emailcode'/>
                            </div>
                            <input type="hidden" name="token" value="<?= csrf_gen(); ?>"/>
                            <button type="submit" class="button button-block" name="send-code">Stuur Registratie Link
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="form send-code" style="padding-top:14px; padding-bottom:14px;">
                <div class="tab-content ">
                    <form>
                        <div class="top-row ">
                            <div class="field-wrap" style="margin-bottom: 0; margin-right: 0; text-align: center;">
                                <a class="admin-link" href="verify-register-leerling">Add Leerling</a>
                                <a class="admin-link" href="verify-register-docent">Add Docent</a><br><br>
                                <a class="admin-link" href="select-account">Edit User</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="form send-code" style="padding-top:14px; padding-bottom:14px;">
                <div class="tab-content ">
                    <form>
                        <div class="top-row ">
                            <div class="field-wrap" style="margin-bottom: 0; margin-right: 0; text-align: center;">
                                <a class="admin-link" href="ip-login">IP-Login</a>
                                <a class="admin-link" href="ip-register">IP-Register</a><br><br>
                                <a class="admin-link" href="ip-forgot">IP-Forgot</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="wrapper-agenda bar">
            <div class="wrapper-agenda-1">
                <div class="form send-code" style="margin-top:0; margin-bottom:0;">
                    <div class="tab-content">
                        <div id="send-code" style="display: block;">
                            <form action="agenda-admin" method="post" autocomplete="off" enctype="multipart/form-data">
                                <div class="top-row">
                                    <div class="field-wrap">
                                        <input type="text" required placeholder="Titel Agenda Item" autocomplete="off"
                                               name='agendaname'/>
                                    </div>
                                    <div class="field-wrap">
                                        <label>Bijlage:</label><br>
                                        <div class="radio-button">
                                            <input type="radio" name='agendatype' value="geen" checked><span
                                                    class="bijlagetext">Geen</span><br>
                                            <input type="radio" name='agendatype' value="link"><span
                                                    class="bijlagetext">Link</span><br>
                                            <input type="radio" name='agendatype' value="pdf"><span class="bijlagetext">Pdf</span><br>
                                        </div>
                                    </div>
                                    <div class="field-wrap" id="link" style="display: none;">
                                        <input type="text" style="color: #0000EE; text-decoration: underline;"
                                               placeholder="Link" autocomplete="off" name='agendalink'/>
                                    </div>
                                    <div class="field-wrap" id="pdf" style="display: none;">
                                        <input type="file" name='file' accept=".pdf">
                                    </div>
                                    <div class="field-wrap">
                                        <input type="date" placeholder="Datum" required autocomplete="off"
                                               name='agendadate'/>
                                    </div>
                                    <input type="hidden" name="token" value="<?= csrf_gen(); ?>"/>
                                    <button type="submit" class="button button-block" name="send-code">Voeg agenda item
                                        toe
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bar agenda-items">
            <div class="wrapper-agenda-2">
                <div class="edit delete-agenda">
                    <div class="form send-code edit" style="margin-top:0; margin-bottom:0; margin-left: 10px;">
                        <div class="tab-content">
                            <div id="send-code" style="display: block;">
                                <div class="top-row">
                                    <div class="field-wrap" style="margin-bottom: 0;">
                                        <?php require_once 'delete-agenda.php'; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/js/jquery.min.js"></script>
<script>
    $("input[name='agendatype']").click(function () {
        $("#link").css("display", "link" === $(this).val() ? "block" : "none")
    }), $("input[name='agendatype']").click(function () {
        $("#pdf").css("display", "pdf" === $(this).val() ? "block" : "none")
    });

</script>
</body>

</html>
