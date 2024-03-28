<?php //TODO upravit
$servername = "localhost";
$username = "uzivatelske_jmeno";
$password = "heslo";
$dbname = "nazev_databaze";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
	die("Spojení s databází selhalo: " . $conn->connect_error);
}
