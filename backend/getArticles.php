<?php //TODO upravit
include 'database.php';

$sql = "SELECT * FROM articles";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
	$articles = array();
	while($row = $result->fetch_assoc()) {
		$articles[] = $row;
	}
	echo json_encode($articles);
} else {
	echo "Žádné články nebyly nalezeny";
}
$conn->close();
