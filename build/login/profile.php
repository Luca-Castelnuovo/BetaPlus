<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/scripts/functions.php");

redirect_class('profile');
login_leerling();
alert_personal($_SESSION["user_number"]);

$user_number = $_SESSION["user_number"];

$result = $mysqli->query("SELECT first_name,last_name FROM users WHERE user_number='$user_number'");
$row = $result->fetch_assoc();
$first_name = $row["first_name"];
$last_name = $row["last_name"];

?>
<!DOCTYPE html>
<html lang="nl">

<?php head('Home'); ?>

<body>
<div id="Menu">
    <ul>
        <li><a href="/" class="hoverable"><span class="normal"><?php echo $first_name . ' ' . $last_name; ?></span><span
                        class="hover">Log Out</span></a></li>
        <div id="MenuRight">
            <li><a href="index">Leerlingen</a></li>
            <li><a href="/docenten/">Docenten</a></li>
            <li><a href="/ster-opdrachten/">Ster-Opdrachten</a></li>
            <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn" style="width: 112px;">Menu</a>
                <div class="dropdown-content">
                    <button class="tablinks" onclick="window.location.href='/leerlingen/edit-profile'">Edit Profiel
                    </button>
                    <button class="tablinks"
                            onclick="window.location.href='/leerlingen/view?user=<?php echo $user_number; ?>'">Mijn
                        Profiel
                    </button>
                    <button class="tablinks" onclick="window.open('/scripts/pdf?id=jaarschema','_blank');">Jaarschema
                    </button>
                    <button class="tablinks" onclick="window.location.href='/'">Log Out</button>
                </div>
            </li>
        </div>
    </ul>
</div>
<?php
alert();
agenda();
?>
<div style="display: inline;">
    <h2 style="margin-bottom: 0;">Mijn Ster-opdracht(en) - <a class="link" href="/ster-opdrachten/new">Nieuw</a></h2>
    <p>Aantal sterren: <?php echo ster_aantal($user_number); ?></p>
</div>
<div class="ster-opdrachten">
    <?php
    ster_my($user_number, false);
    ster_my($user_number, true);
    ?>
</div>
</body>

</html>
