<?php
header("Access-Control-Allow-Origin: https://thecap.thatrichard.space"); //http://localhost:5173
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
	header("HTTP/1.1 200 OK");
	exit();
}

include_once './Article.php';
include_once './Category.php';
include_once './Author.php';
include_once './Admin.php';
include_once './database.php';

$database = new Database();
$db = $database->getConnection();

$article = new Article($db);
$category = new Category($db);
$author = new Author($db);
$admin = new Admin($db);

$request_method = $_SERVER["REQUEST_METHOD"];
$path = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));

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
					if (is_numeric($path[1])) {
						$article->id = $path[1];
						$article->readSingle();
						$article_item = array(
							"id" => $article->id,
							"title" => $article->title,
							"createdAt" => $article->createdAt,
							"publishedAt" => $article->publishedAt,
							"categoryName" => $article->categoryName,
							"authorName" => $article->authorName,
							"categoryId" => $article->categoryId,
							"authorId" => $article->authorId,
							"image" => $article->image,
							"content" => $article->content,
							"perex" => $article->perex,
							"urlSlug" => $article->urlSlug
						);
						echo json_encode($article_item);
					} else {
						$article->urlSlug = $path[1];
						$article->readSingleBySlug();
						$article_item = array(
							"id" => $article->id,
							"title" => $article->title,
							"createdAt" => $article->createdAt,
							"publishedAt" => $article->publishedAt,
							"categoryName" => $article->categoryName,
							"authorName" => $article->authorName,
							"categoryId" => $article->categoryId,
							"authorId" => $article->authorId,
							"image" => $article->image,
							"content" => $article->content,
							"perex" => $article->perex,
							"urlSlug" => $article->urlSlug
						);
						echo json_encode($article_item);
					}
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
							"categoryName" => $categoryName,
							"authorName" => $authorName,
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
				$article->create();
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
				$article->update();
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
	case 'categories':
		switch($request_method) {
			case 'GET':
				if (isset($path[1])) {
					if (is_numeric($path[1])) {
						$category->id = $path[1];
						$category->readSingle();
						$category_item = array(
							"id" => $category->id,
							"content" => $category->content,
							"image" => $category->image,
							"inMenu" => $category->inMenu,
							"name" => $category->name,
							"urlSlug" => $category->urlSlug
						);
						echo json_encode($category_item);
					} else {
						$category->urlSlug = $path[1];
						$category->readSingleBySlug();
						$category_item = array(
							"id" => $category->id,
							"content" => $category->content,
							"image" => $category->image,
							"inMenu" => $category->inMenu,
							"name" => $category->name,
							"urlSlug" => $category->urlSlug
						);
						echo json_encode($category_item);
					}
				} else {
					$stmt = $category->read();
					$categories_arr = array();
					while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
						extract($row);
						$category_item = array(
							"id" => $id,
							"content" => $content,
							"image" => $image,
							"inMenu" => $inMenu,
							"name" => $name,
							"urlSlug" => $urlSlug
						);
						array_push($categories_arr, $category_item);
					}
					echo json_encode($categories_arr);
				}
				break;
			case 'POST':
				$data = json_decode(file_get_contents("php://input"));
				$category->content = $data->content;
				$category->image = $data->image;
				$category->inMenu = $data->inMenu;
				$category->name = $data->name;
				$category->urlSlug = $data->urlSlug;
				$category->create();
				break;
			case 'PUT':
				$data = json_decode(file_get_contents("php://input"));
				$category->id = $data->id;
				$category->content = $data->content;
				$category->image = $data->image;
				$category->inMenu = $data->inMenu;
				$category->name = $data->name;
				$category->urlSlug = $data->urlSlug;
				$category->update();
				break;
			case 'DELETE':
				if (isset($path[1])) {
					$category->id = $path[1];
					$category->delete();
				} else {
					http_response_code(400);
					echo json_encode(array("message" => "Missing category ID."));
				}
				break;
			default:
				http_response_code(405);
				echo json_encode(array("message" => "Method not allowed."));
				break;
		}
		break;
	case 'authors':
		switch($request_method) {
			case 'GET':
				if (isset($path[1])) {
					$author->id = $path[1];
					$author->readSingle();
					$author_item = array(
						"id" => $author->id,
						"firstName" => $author->firstName,
						"lastName" => $author->lastName,
						"email" => $author->email,
						"phoneNumber" => $author->phoneNumber,
						"content" => $author->content,
						"image" => $author->image,
						"urlSlug" => $author->urlSlug
					);
					echo json_encode($author_item);
				} else {
					$stmt = $author->read();
					$authors_arr = array();
					while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
						extract($row);
						$author_item = array(
							"id" => $id,
							"firstName" => $firstName,
							"lastName" => $lastName,
							"email" => $email,
							"phoneNumber" => $phoneNumber,
							"content" => $content,
							"image" => $image,
							"urlSlug" => $urlSlug
						);
						array_push($authors_arr, $author_item);
					}
					echo json_encode($authors_arr);
				}
				break;
			case 'POST':
				$data = json_decode(file_get_contents("php://input"));
				$author->firstName = $data->firstName;
				$author->lastName = $data->lastName;
				$author->email = $data->email;
				$author->phoneNumber = $data->phoneNumber;
				$author->content = $data->content;
				$author->image = $data->image;
				$author->urlSlug = $data->urlSlug;

				$author->create();
				break;
			case 'PUT':
				$data = json_decode(file_get_contents("php://input"));
				$author->id = $data->id;
				$author->firstName = $data->firstName;
				$author->lastName = $data->lastName;
				$author->email = $data->email;
				$author->phoneNumber = $data->phoneNumber;
				$author->content = $data->content;
				$author->image = $data->image;
				$author->urlSlug = $data->urlSlug;

				$author->update();
				break;
			case 'DELETE':
				if (isset($path[1])) {
					$author->id = $path[1];
					$author->delete();
				} else {
					http_response_code(400);
					echo json_encode(array("message" => "Missing author ID."));
				}
				break;
			default:
				http_response_code(405);
				echo json_encode(array("message" => "Method not allowed."));
				break;
		}
		break;
	case 'users_admin':
		switch($request_method) {
			case 'GET':
				if (isset($path[1])) {
					$admin->id = $path[1];
					$admin->readSingle();
					$admin_item = array(
						"id" => $admin->id,
						"email" => $admin->email,
						"loginName" => $admin->loginName,
						"loginPassword" => $admin->loginPassword,
					);
					echo json_encode($admin_item);
				} else {
					$stmt = $admin->read();
					$admins_arr = array();
					while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
						extract($row);
						$admin_item = array(
							"id" => $id,
							"email" => $email,
							"loginName" => $loginName,
							"loginPassword" => $loginPassword,
						);
						array_push($admins_arr, $admin_item);
					}
					echo json_encode($admins_arr);
				}
				break;
			case 'POST':
				$data = json_decode(file_get_contents("php://input"));
				$admin->email = $data->email;
				$admin->loginName = $data->loginName;
				$admin->loginPassword = $data->loginPassword;

				if ($admin->create()) {
					http_response_code(201);
					echo json_encode(array("message" => "Admin was created."));
				} else {
					http_response_code(503);
					echo json_encode(array("message" => "Unable to create admin."));
				}
				break;
			case 'PUT':
				$data = json_decode(file_get_contents("php://input"));
				$admin->id = $data->id;
				$admin->email = $data->email;
				$admin->loginName = $data->loginName;
				$admin->loginPassword = $data->loginPassword;

				if ($admin->update()) {
					http_response_code(200);
					echo json_encode(array("message" => "Admin was updated."));
				} else {
					http_response_code(503);
					echo json_encode(array("message" => "Unable to update admin."));
				}
				break;
			case 'DELETE':
				if (isset($path[1])) {
					$admin->id = $path[1];
					if ($admin->delete()) {
						http_response_code(200);
						echo json_encode(array("message" => "Admin was deleted."));
					} else {
						http_response_code(503);
						echo json_encode(array("message" => "Unable to delete admin."));
					}
				} else {
					http_response_code(400);
					echo json_encode(array("message" => "Missing admin ID."));
				}
				break;
			default:
				http_response_code(405);
				echo json_encode(array("message" => "Method not allowed."));
				break;
		}
		break;
	case 'login':
		if ($request_method === 'POST') {
			$data = json_decode(file_get_contents("php://input"));
			$email = htmlspecialchars(strip_tags($data->email));
			$loginPassword = htmlspecialchars(strip_tags($data->loginPassword));

			$userAdmin = new Admin($db);
			$isValid = $userAdmin->login($email, $loginPassword);

			if ($isValid) {
				http_response_code(200);
				echo json_encode(["message" => "Login successful", "token" => $isValid]);
			} else {
				http_response_code(401);
				echo json_encode(["message" => "Invalid credentials"]);
			}
		} else {
			http_response_code(405);
			echo json_encode(["message" => "Method not allowed"]);
		}
		break;
	case 'register':
		if ($request_method === 'POST') {
			$data = json_decode(file_get_contents("php://input"));
			$admin->email = $data->email;
			$admin->loginName = $data->loginName;
			$admin->loginPassword = $data->loginPassword;

			$admin->register();
		} else {
			http_response_code(405);
			echo json_encode(["message" => "Method not allowed"]);
		}
		break;
	case 'upload-image':
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
				$fileTmpPath = $_FILES['image']['tmp_name'];
				$fileName = $_FILES['image']['name'];
				$fileSize = $_FILES['image']['size'];
				$fileType = $_FILES['image']['type'];
				$fileNameCmps = explode(".", $fileName);
				$fileExtension = strtolower(end($fileNameCmps));

				$allowedfileExtensions = array('jpg', 'gif', 'png', 'webp');
				if (in_array($fileExtension, $allowedfileExtensions)) {
					$uploadFileDir = './upload_images/';
					$dest_path = $uploadFileDir . $fileName;

					if (move_uploaded_file($fileTmpPath, $dest_path)) {
						$response = array("url" => '/upload_images/' . $fileName);
						echo json_encode($response);
						http_response_code(200);
					} else {
						$response = array("message" => "There was an error moving the uploaded file.");
						echo json_encode($response);
						http_response_code(500);
					}
				} else {
					$response = array("message" => "Upload failed. Allowed file types: " . implode(',', $allowedfileExtensions));
					echo json_encode($response);
					http_response_code(400);
				}
			} else {
				$response = array("message" => "No file uploaded or there was an upload error.");
				echo json_encode($response);
				http_response_code(400);
			}
		} else {
			http_response_code(405);
			echo json_encode(array("message" => "Method not allowed."));
		}
		break;
	case 'articlesByCategory':
		if ($request_method === 'GET') {
			if (isset($path[1])) {
				$category->urlSlug = $path[1];
				$category->readSingleBySlug();
				$stmt = $article->readAllByCategory($category->id);
				$articles_arr = array();
				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					extract($row);
					$article_item = array(
						"id" => $id,
						"title" => $title,
						"createdAt" => $createdAt,
						"publishedAt" => $publishedAt,
						"categoryName" => $categoryName,
						"authorName" => $authorName,
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
			} else {
				http_response_code(400);
				echo json_encode(array("message" => "Missing category URL slug."));
			}
		} else {
			http_response_code(405);
			echo json_encode(array("message" => "Method not allowed."));
		}
		break;
	default:
		http_response_code(404);
		echo json_encode(array("message" => "Resource not found."));
		break;
}
