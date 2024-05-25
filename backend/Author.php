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
	}

	public function create() {
		$query = "INSERT INTO " . $this->table_name . " SET firstName=:firstName, lastName=:lastName, email=:email, phoneNumber=:phoneNumber, content=:content, image=:image";
		$stmt = $this->conn->prepare($query);

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

		if ($stmt->execute()) {
			return true;
		}
		return false;
	}

	public function update() {
		$query = "UPDATE " . $this->table_name . " SET firstName=:firstName, lastName=:lastName, email=:email, phoneNumber=:phoneNumber, content=:content, image=:image WHERE id=:id";
		$stmt = $this->conn->prepare($query);

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
