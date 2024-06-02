<?php
class Article {
	private $conn;
	private $table_name = "articles";

	public $id;
	public $title;
	public $createdAt;
	public $publishedAt;
	public $categoryId;
	public $authorId;
	public $categoryName;
	public $authorName;
	public $authorUrlSlug;
	public $image;
	public $content;
	public $perex;
	public $urlSlug;

	public function __construct($db) {
		$this->conn = $db;
	}

	public function read() {
		$query = "SELECT
					a.id,
					a.title,
					a.createdAt,
					a.publishedAt,
					c.name AS categoryName,
					CONCAT(u.firstName, ' ', u.lastName) AS authorName,
					a.image,
					a.categoryId,
					a.authorId,
					a.content,
					a.perex,
					a.urlSlug
				  FROM
					" . $this->table_name . " a
				  LEFT JOIN
					categories c ON a.categoryId = c.id
				  LEFT JOIN
					authors u ON a.authorId = u.id";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		return $stmt;
	}

	public function readSingle() {
		$query = "SELECT
					a.id,
					a.title,
					a.createdAt,
					a.publishedAt,
					c.name AS categoryName,
					CONCAT(u.firstName, ' ', u.lastName) AS authorName,
					a.categoryId,
					a.authorId,
					a.image,
					a.content,
					a.perex,
					a.urlSlug
				FROM
					" . $this->table_name . " a
				LEFT JOIN
					categories c ON a.categoryId = c.id
				LEFT JOIN
					authors u ON a.authorId = u.id
					WHERE a.id = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->title = $row['title'];
		$this->createdAt = $row['createdAt'];
		$this->publishedAt = $row['publishedAt'];
		$this->categoryName = $row['categoryName'];
		$this->authorName = $row['authorName'];
		$this->categoryId = $row['categoryId'];
		$this->authorId = $row['authorId'];
		$this->image = $row['image'];
		$this->content = $row['content'];
		$this->perex = $row['perex'];
		$this->urlSlug = $row['urlSlug'];
	}

	public function readSingleBySlug() {
		$query = "SELECT
					a.id,
					a.title,
					a.createdAt,
					a.publishedAt,
					c.name AS categoryName,
					CONCAT(u.firstName, ' ', u.lastName) AS authorName,
					u.urlSlug,
					a.categoryId,
					a.authorId,
					a.image,
					a.content,
					a.perex,
					a.urlSlug
				FROM
					" . $this->table_name . " a
				LEFT JOIN
					categories c ON a.categoryId = c.id
				LEFT JOIN
					authors u ON a.authorId = u.id
					WHERE a.urlSlug = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->urlSlug);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->id = $row['id'];
		$this->title = $row['title'];
		$this->createdAt = $row['createdAt'];
		$this->publishedAt = $row['publishedAt'];
		$this->categoryName = $row['categoryName'];
		$this->authorName = $row['authorName'];
		$this->categoryId = $row['categoryId'];
		$this->authorId = $row['authorId'];
		$this->image = $row['image'];
		$this->content = $row['content'];
		$this->perex = $row['perex'];
		$this->urlSlug = $row['urlSlug'];
		$this->authorUrlSlug = $row['urlSlug'];
	}

	public function create() {
		$query = "INSERT INTO " . $this->table_name . " SET title=:title, createdAt=:createdAt, publishedAt=:publishedAt, categoryId=:categoryId, authorId=:authorId, image=:image, content=:content, perex=:perex, urlSlug=:urlSlug";
		$stmt = $this->conn->prepare($query);

		if (empty($this->title) || empty($this->content) || empty($this->perex) || empty($this->urlSlug)) {
			http_response_code(400);
			echo json_encode(["message" => "Unable to create article. Data is incomplete."]);
			return;
		}

		$this->title = htmlspecialchars(strip_tags($this->title));

		$stmt->bindParam(":title", $this->title);
		$stmt->bindParam(":createdAt", $this->createdAt);
		$stmt->bindParam(":publishedAt", $this->publishedAt);
		$stmt->bindParam(":categoryId", $this->categoryId);
		$stmt->bindParam(":authorId", $this->authorId);
		$stmt->bindParam(":image", $this->image);
		$stmt->bindParam(":content", $this->content);
		$stmt->bindParam(":perex", $this->perex);
		$stmt->bindParam(":urlSlug", $this->urlSlug);

		try {
			if ($stmt->execute()) {
				http_response_code(201);
				echo json_encode(["message" => "Article was created", "ok" => 1]);
			} else {
				http_response_code(500);
				echo json_encode(["message" => "Unable to create article"]);
			}
		} catch (Exception $e) {
			http_response_code(500);
			echo json_encode(["message" => "Unable to create article", "error" => $e->getMessage()]);
		}
	}

	public function update() {
		$query = "UPDATE " . $this->table_name . " SET title = :title, createdAt = :createdAt, publishedAt = :publishedAt, categoryId = :categoryId, authorId = :authorId, image = :image, content = :content, perex = :perex, urlSlug = :urlSlug WHERE id = :id";
		$stmt = $this->conn->prepare($query);

		if (empty($this->title) || empty($this->content) || empty($this->perex) || empty($this->urlSlug)) {
			http_response_code(400);
			echo json_encode(["message" => "Unable to update article. Data is incomplete."]);
			return;
		}

		$this->title = htmlspecialchars(strip_tags($this->title));

		$stmt->bindParam(":title", $this->title);
		$stmt->bindParam(":id", $this->id);
		$stmt->bindParam(":createdAt", $this->createdAt);
		$stmt->bindParam(":publishedAt", $this->publishedAt);
		$stmt->bindParam(":categoryId", $this->categoryId);
		$stmt->bindParam(":authorId", $this->authorId);
		$stmt->bindParam(":image", $this->image);
		$stmt->bindParam(":content", $this->content);
		$stmt->bindParam(":perex", $this->perex);
		$stmt->bindParam(":urlSlug", $this->urlSlug);

		try {
			if ($stmt->execute()) {
				http_response_code(200);
				echo json_encode(["message" => "Article was updated", "ok" => 1]);
			} else {
				http_response_code(500);
				echo json_encode(["message" => "Unable to update article"]);
			}
		} catch (Exception $e) {
			http_response_code(500);
			echo json_encode(["message" => "Unable to update article", "error" => $e->getMessage()]);
		}
	}

	public function delete() {
		$query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
		$stmt = $this->conn->prepare($query);

		$this->id = htmlspecialchars(strip_tags($this->id));
		$stmt->bindParam(1, $this->id);

		if ($stmt->execute()) {
			return true;
		}
		return false;
	}
}
?>
