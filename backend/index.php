<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once 'config/Database.php';
include_once 'objects/Article.php';
// Include other table classes

$database = new Database();
$db = $database->getConnection();

$article = new Article($db);
// Instantiate other table objects

$request_method = $_SERVER["REQUEST_METHOD"];
$path = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));

file_put_contents('php://stderr', print_r($path, TRUE));

if (count($path) < 1) {
	http_response_code(404);
	echo json_encode(array("message" => "Resource not found."));
	exit();
}

switch($path[0]) {
	case 'articles':
		switch($request_method) {
			case 'GET':
				if (isset($path[1])) {
					// Code to get a single article (e.g., /articles/{id})
					$article->id = $path[1];
					$article->readSingle();
					$article_item = array(
						"id" => $article->id,
						"title" => $article->title,
						"createdAt" => $article->createdAt,
						"publishedAt" => $article->publishedAt,
						"categoryId" => $article->categoryId,
						"authorId" => $article->authorId,
						"image" => $article->image,
						"content" => $article->content,
						"perex" => $article->perex,
						"urlSlug" => $article->urlSlug
					);
				} else {
					$stmt = $article->read();
					$articles_arr = array();
					while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
						extract($row);
						$article_item = array(
							"id" => $id,
							"title" => $title,
							"createdAt" => $createdAt,
							"publishedAt" => $publishedAt,
							"categoryId" => $categoryId,
							"authorId" => $authorId,
							"image" => $image,
							"content" => $content,
							"perex" => $perex,
							"urlSlug" => $urlSlug
						);
						array_push($articles_arr, $article_item);
					}
					echo json_encode($articles_arr);
				}
				break;
			case 'POST':
				$data = json_decode(file_get_contents("php://input"));
				$article->title = $data->title;
				$article->createdAt = $data->createdAt;
				$article->publishedAt = $data->publishedAt;
				$article->categoryId = $data->categoryId;
				$article->authorId = $data->authorId;
				$article->image = $data->image;
				$article->content = $data->content;
				$article->perex = $data->perex;
				$article->urlSlug = $data->urlSlug;
				if ($article->create()) {
					http_response_code(201);
					echo json_encode(array("message" => "Article was created."));
				} else {
					http_response_code(503);
					echo json_encode(array("message" => "Unable to create article."));
				}
				break;
			case 'PUT':
				$data = json_decode(file_get_contents("php://input"));
				$article->id = $data->id;
				$article->title = $data->title;
				$article->createdAt = $data->createdAt;
				$article->publishedAt = $data->publishedAt;
				$article->categoryId = $data->categoryId;
				$article->authorId = $data->authorId;
				$article->image = $data->image;
				$article->content = $data->content;
				$article->perex = $data->perex;
				$article->urlSlug = $data->urlSlug;
				if ($article->update()) {
					http_response_code(200);
					echo json_encode(array("message" => "Article was updated."));
				} else {
					http_response_code(503);
					echo json_encode(array("message" => "Unable to update article."));
				}
				break;
			case 'DELETE':
				if (isset($path[1])) {
					$article->id = $path[1];
					if ($article->delete()) {
						http_response_code(200);
						echo json_encode(array("message" => "Article was deleted."));
					} else {
						http_response_code(503);
						echo json_encode(array("message" => "Unable to delete article."));
					}
				} else {
					http_response_code(400);
					echo json_encode(array("message" => "Missing article ID."));
				}
				break;
			default:
				http_response_code(405);
				echo json_encode(array("message" => "Method not allowed."));
				break;
		}
		break;
	// Similar cases for authors, categories, users_admin
	default:
		http_response_code(404);
		echo json_encode(array("message" => "Resource not found."));
		break;
}
?>
