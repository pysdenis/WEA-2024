<?php
class Author {
	private $conn;
	private $table_name = "authors";

	public $id;
	public $firstName;
	public $lastName;
	public $email;
	public $phoneNumber;
	public $content;
	public $image;
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
		$this->firstName = $row['firstName'];
		$this->lastName = $row['lastName'];
		$this->email = $row['email'];
		$this->phoneNumber = $row['phoneNumber'];
		$this->content = $row['content'];
		$this->image = $row['image'];
		$this->urlSlug = $row['urlSlug'];
	}

	public function create() {
		$query = "INSERT INTO " . $this->table_name . " SET firstName=:firstName, lastName=:lastName, email=:email, phoneNumber=:phoneNumber, content=:content, image=:image, urlSlug=:urlSlug";
		$stmt = $this->conn->prepare($query);

		if (empty($this->firstName) || empty($this->lastName) || empty($this->email) || empty($this->phoneNumber) || empty($this->content) || empty($this->urlSlug)) {
			http_response_code(400);
			echo json_encode(["message" => "Unable to create author. Data is incomplete."]);
			return;
		}

		$this->firstName = htmlspecialchars(strip_tags($this->firstName));
		$this->lastName = htmlspecialchars(strip_tags($this->lastName));
		$this->email = htmlspecialchars(strip_tags($this->email));
		$this->phoneNumber = htmlspecialchars(strip_tags($this->phoneNumber));

		$stmt->bindParam(":firstName", $this->firstName);
		$stmt->bindParam(":lastName", $this->lastName);
		$stmt->bindParam(":email", $this->email);
		$stmt->bindParam(":phoneNumber", $this->phoneNumber);
		$stmt->bindParam(":content", $this->content);
		$stmt->bindParam(":image", $this->image);
		$stmt->bindParam(":urlSlug", $this->urlSlug);

		try {
			if ($stmt->execute()) {
				http_response_code(201);
				echo json_encode(["message" => "Author was created", "ok" => 1]);
			} else {
				http_response_code(500);
				echo json_encode(["message" => "Unable to create author"]);
			}
		} catch (Exception $e) {
			http_response_code(500);
			echo json_encode(["message" => $e->getMessage()]);
		}
	}

	public function update() {
		$query = "UPDATE " . $this->table_name . " SET firstName=:firstName, lastName=:lastName, email=:email, phoneNumber=:phoneNumber, content=:content, image=:image, urlSlug=:urlSlug WHERE id=:id";
		$stmt = $this->conn->prepare($query);

		if (empty($this->firstName) || empty($this->lastName) || empty($this->email) || empty($this->phoneNumber) || empty($this->content) || empty($this->urlSlug)) {
			http_response_code(400);
			echo json_encode(["message" => "Unable to update author. Data is incomplete."]);
			return;
		}

		$this->id = htmlspecialchars(strip_tags($this->id));
		$this->firstName = htmlspecialchars(strip_tags($this->firstName));
		$this->lastName = htmlspecialchars(strip_tags($this->lastName));
		$this->email = htmlspecialchars(strip_tags($this->email));
		$this->phoneNumber = htmlspecialchars(strip_tags($this->phoneNumber));

		$stmt->bindParam(":id", $this->id);
		$stmt->bindParam(":firstName", $this->firstName);
		$stmt->bindParam(":lastName", $this->lastName);
		$stmt->bindParam(":email", $this->email);
		$stmt->bindParam(":phoneNumber", $this->phoneNumber);
		$stmt->bindParam(":content", $this->content);
		$stmt->bindParam(":image", $this->image);
		$stmt->bindParam(":urlSlug", $this->urlSlug);

		try {
			if ($stmt->execute()) {
				http_response_code(200);
				echo json_encode(["message" => "Author was updated", "ok" => 1]);
			} else {
				http_response_code(500);
				echo json_encode(["message" => "Unable to update author"]);
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
				echo json_encode(["message" => "Author was deleted", "ok" => 1]);
			} else {
				http_response_code(500);
				echo json_encode(["message" => "Unable to delete author"]);
			}
		} catch (Exception $e) {
			http_response_code(500);
			echo json_encode(["message" => $e->getMessage()]);
		}
	}
}
?>
