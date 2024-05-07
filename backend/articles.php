<?php //TODO: Implementovat metody PUT a DELETE a implementovat CORS pro produkční nasazení a zabezpečení proti SQL injection a implementovat správné vkládání dat do databáze
include 'database.php';
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

$db = new Database();
$connection = $db->getConnection();

$request_method = $_SERVER["REQUEST_METHOD"];
switch ($request_method) {
	case 'GET':
		if (isset($_GET['id'])) {
			$id = $_GET['id'];
			getArticle($id);
		} else {
			getArticles();
		}
		break;
	case 'POST':
		postArticle();
		break;
	case 'PUT':
		header("HTTP/1.0 405 Method Not Implemented");
		break;
	case 'DELETE':
		header("HTTP/1.0 405 Method Not Implemented");
		break;
	default:
		header("HTTP/1.0 405 Method Not Implemented");
		break;
}

function getArticles() {
	global $connection;
	$sql = "SELECT * FROM articles";
	$result = $connection->query($sql);
	if ($result->num_rows > 0) {
		$articles = array();
		while($row = $result->fetch_assoc()) {
			$articles[] = $row;
		}
		echo json_encode($articles);
	} else {
		echo "Žádné články nebyly nalezeny";
	}
}

function getArticle($id) {
	global $connection;
	$sql = "SELECT * FROM articles WHERE id = $id";
	$result = $connection->query($sql);
	if ($result->num_rows > 0) {
		$article = $result->fetch_assoc();
		echo json_encode($article);
	} else {
		echo "Článek nebyl nalezen";
	}
}

function postArticle() {
	global $connection;
	$data = json_decode(file_get_contents('php://input'), true);
	$title = $data['title'];
	$content = $data['content'];
	$sql = "INSERT INTO articles (title, content) VALUES ('$title', '$content')";
	if ($connection->query($sql) === TRUE) {
		echo "Článek byl úspěšně vytvořen";
	} else {
		echo "Chyba: " . $sql . "<br>" . $connection->error;
	}
}



$connection->close();
