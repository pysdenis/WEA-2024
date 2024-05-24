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
	public $image;
	public $content;
	public $perex;
	public $urlSlug;

	public function __construct($db) {
		$this->conn = $db;
	}

	public function read() {
		$query = "SELECT * FROM " . $this->table_name;
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		return $stmt;
	}

	public function readSingle() {
		$query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->title = $row['title'];
		$this->createdAt = $row['createdAt'];
		$this->publishedAt = $row['publishedAt'];
		$this->categoryId = $row['categoryId'];
		$this->authorId = $row['authorId'];
		$this->image = $row['image'];
		$this->content = $row['content'];
		$this->perex = $row['perex'];
		$this->urlSlug = $row['urlSlug'];
	}

	public function create() {
		$query = "INSERT INTO " . $this->table_name . " SET title=:title, createdAt=:createdAt, publishedAt=:publishedAt, categoryId=:categoryId, authorId=:authorId, image=:image, content=:content, perex=:perex, urlSlug=:urlSlug";
		$stmt = $this->conn->prepare($query);

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

		if ($stmt->execute()) {
			return true;
		}
		return false;
	}

	public function update() {
		$query = "UPDATE " . $this->table_name . " SET title = :title, createdAt = :createdAt, publishedAt = :publishedAt, categoryId = :categoryId, authorId = :authorId, image = :image, content = :content, perex = :perex, urlSlug = :urlSlug WHERE id = :id";
		$stmt = $this->conn->prepare($query);

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

		if ($stmt->execute()) {
			return true;
		}
		return false;
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
