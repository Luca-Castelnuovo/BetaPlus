<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/scripts/functions.php");

if ($_SESSION['user_type'] != 1) {
    $_SESSION["alert"] = 'Deze pagina is alleen zichtbaar voor docenten!';
    header("location: /leerlingen/profile");
    exit;
}

$result = $mysqli->query("SELECT * FROM agenda WHERE DATE(date) >= DATE(NOW()) ORDER BY date LIMIT 0, 25;");
if ($result->num_rows > 0) {
    echo '<form method="post" style="display: inline;" action="/login/admin/confirm-delete-agenda"><table id="myTable" style="margin-left: auto; margin-right:auto; width:280px;">';
    while ($row = $result->fetch_assoc()) {
        $name = $row["name"];
        $date = $row["date"];
        $link = $row["link"];
        $id = $row["id"];
        echo '
	<tr>
		<td style="padding-bottom: 5px; padding-top: 5px;">
			<a style="color: #FFF;" href="' . $link . '">' . $date . ': ' . $name . '</a>
				<button type="submit" name="id" value=' . $id . ' class="close">X</button>
		</td>
	</tr>';
    }
    echo '</table></form>';
} else {
    echo "<p style=\"margin-left:0; margin-right:0; margin-bottom:0;\">Er zijn op dit moment geen buitenschoolse BetaSterren activiteiten.</p>";
}
