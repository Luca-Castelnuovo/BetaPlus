<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/scripts/functions.php");

login_admin();

if (count(get_included_files()) == ((version_compare(PHP_VERSION, '5.0.0', '>=')) ? 1 : 0)) {
    $_SESSION['alert'] = 'Deze pagina is alleen toegankelijk door de server!';
    header("location: /login/admin/");
    exit;
}

$result = $mysqli->query("SELECT * FROM users ORDER BY last_name");
if ($result->num_rows > 0) {
    echo '<div id="myTableWrapper"><table id="myTable" style="margin-left: 0; width: 100%;">';
    while ($row = $result->fetch_assoc()) {
        $first_name = $row["first_name"];
        $last_name = $row["last_name"];
        $user_number = $row["user_number"];
        echo '<tr>
				<td style="padding-bottom: 0; padding-top: 5px;">
				    <span class="naam"><a class="userlistitem" href="edit-account?user=' . $user_number . '">' . $first_name . ' ' . $last_name . '</a></span>
				</td>
            </tr>';
    }
    echo '</table></div>';
} else {
    echo "<p>Er doen op dit moment geen leerlingen mee aan het BetaSterren programma.</p>";
}
