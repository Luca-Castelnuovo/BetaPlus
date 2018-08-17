<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/scripts/functions.php");

if ($_SESSION['user_type'] != 1) {
    $_SESSION["alert"] = 'Deze pagina is alleen zichtbaar voor docenten!';
    header("location: /leerlingen/profile");
    exit;
}

if ($_SESSION['class'] == 'admin') {
    header("Location: index");
    exit;
}

?>
<!DOCTYPE html>
<html lang="nl">

<?php head('Agenda'); ?>

<body>

<?php menu('Agenda'); ?><h1>Agenda</h1>
<div class="admin">
    <div class="wrapper-agenda bar">
        <div class="wrapper-agenda-1">
            <div class="form send-code" style="margin-top:0; margin-bottom:0;">
                <div class="tab-content">
                    <div id="send-code" style="display: block;">
                        <form action="agenda" method="post" autocomplete="off" enctype="multipart/form-data">
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
                                        <input type="radio" name='agendatype' value="link"><span class="bijlagetext">Link</span><br>
                                        <input type="radio" name='agendatype' value="pdf"><span
                                                class="bijlagetext">Pdf</span><br>
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
                                    <?php include_once 'delete-agenda.php'; ?>
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
