<?php
class Category {
	private $conn;
	private $table_name = "categories";

	public $id;
	public $content;
	public $image;
	public $inMenu;
	public $name;
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
		$this->content = $row['content'];
		$this->image = $row['image'];
		$this->inMenu = $row['inMenu'];
		$this->name = $row['name'];
		$this->urlSlug = $row['urlSlug'];
	}

	public function readSingleBySlug() {
		$query = "SELECT * FROM " . $this->table_name . " WHERE urlSlug = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->urlSlug);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->id = $row['id'];
		$this->content = $row['content'];
		$this->image = $row['image'];
		$this->inMenu = $row['inMenu'];
		$this->name = $row['name'];
		$this->urlSlug = $row['urlSlug'];
	}

	public function create() {
		$query = "INSERT INTO " . $this->table_name . " SET content=:content, image=:image, inMenu=:inMenu, name=:name, urlSlug=:urlSlug";
		$stmt = $this->conn->prepare($query);

		if (empty($this->content) || empty($this->name) || empty($this->urlSlug)) {
			http_response_code(400);
			echo json_encode(["message" => "Unable to create category. Data is incomplete."]);
			return;
		}

		$this->name = htmlspecialchars(strip_tags($this->name));

		$stmt->bindParam(":content", $this->content);
		$stmt->bindParam(":image", $this->image);
		$stmt->bindParam(":inMenu", $this->inMenu);
		$stmt->bindParam(":name", $this->name);
		$stmt->bindParam(":urlSlug", $this->urlSlug);

		try {
			if ($stmt->execute()) {
				http_response_code(201);
				echo json_encode(["message" => "Category was created", "ok" => 1]);
			} else {
				http_response_code(500);
				echo json_encode(["message" => "Unable to create category"]);
			}
		} catch (Exception $e) {
			http_response_code(500);
			echo json_encode(["message" => $e->getMessage()]);
		}
	}

	public function update() {
		$query = "UPDATE " . $this->table_name . " SET content = :content, image = :image, inMenu = :inMenu, name = :name, urlSlug = :urlSlug WHERE id = :id";
		$stmt = $this->conn->prepare($query);

		if (empty($this->content) || empty($this->name) || empty($this->urlSlug)) {
			http_response_code(400);
			echo json_encode(["message" => "Unable to update category. Data is incomplete."]);
			return;
		}

		$this->name = htmlspecialchars(strip_tags($this->name));

		$stmt->bindParam(":content", $this->content);
		$stmt->bindParam(":image", $this->image);
		$stmt->bindParam(":inMenu", $this->inMenu);
		$stmt->bindParam(":name", $this->name);
		$stmt->bindParam(":urlSlug", $this->urlSlug);
		$stmt->bindParam(":id", $this->id);

		try {
			if ($stmt->execute()) {
				http_response_code(200);
				echo json_encode(["message" => "Category was updated", "ok" => 1]);
			} else {
				http_response_code(500);
				echo json_encode(["message" => "Unable to update category"]);
			}
		} catch (Exception $e) {
			http_response_code(500);
			echo json_encode(["message" => $e->getMessage()]);
		}
	}

	public function delete() {
		$query = "DELETE FROM articles WHERE authorId = ?";
		$stmt = $this->conn->prepare($query);

		$this->id = htmlspecialchars(strip_tags($this->id));
		$stmt->bindParam(1, $this->id);
		$stmt->execute();

		try {
			$query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
			$stmt = $this->conn->prepare($query);
			$stmt->bindParam(1, $this->id);

			if ($stmt->execute()) {
				http_response_code(200);
				echo json_encode(["message" => "Category was deleted", "ok" => 1]);
			} else {
				http_response_code(500);
				echo json_encode(["message" => "Unable to delete category"]);
			}
		} catch (Exception $e) {
			http_response_code(500);
			echo json_encode(["message" => $e->getMessage()]);
		}
	}
}
?>
