<?php
class Admin {
	private $conn;
	private $table_name = "users_admin";

	public $id;
	public $email;
	public $loginName;
	public $loginPassword;

	public function __construct($db) {
		$this->conn = $db;
	}

	public function login($email, $loginPassword) {
		$query = "SELECT * FROM " . $this->table_name . " WHERE email = ? LIMIT 0,1";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $email);
		$stmt->execute();

		if ($stmt->rowCount() > 0) {
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			if (hash('sha256', $loginPassword) == $row['loginPassword']){
				$token = array(
					"email" => $row['email'],
					"loginName" => $row['loginName']
				);
				return $token;
			}
		}
		return false;
	}

	public function register() {
		$query = "SELECT * FROM " . $this->table_name . " WHERE email = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->email);
		$stmt->execute();

		if ($stmt->rowCount() > 0) {
			http_response_code(400);
			echo json_encode(array("message" => "Email already exists."));
			return;
		}

		$query = "INSERT INTO " . $this->table_name . " SET email=:email, loginName=:loginName, loginPassword=:loginPassword";
		$stmt = $this->conn->prepare($query);

		$this->email = htmlspecialchars(strip_tags($this->email));
		$this->loginName = htmlspecialchars(strip_tags($this->loginName));
		$this->loginPassword = hash('sha256', htmlspecialchars(strip_tags($this->loginPassword)));

		$stmt->bindParam(":email", $this->email);
		$stmt->bindParam(":loginName", $this->loginName);
		$stmt->bindParam(":loginPassword", $this->loginPassword);

		$token = array(
			"email" => $this->email,
			"loginName" => $this->loginName
		);

		if ($stmt->execute()) {
			http_response_code(201);
			echo json_encode(["message" => "User was created", "token" => $token]);
		} else {
			http_response_code(500);
			echo json_encode(array("message" => "Unable to create user."));
		}
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

		$this->id = $row['id'];
		$this->email = $row['email'];
		$this->loginName = $row['loginName'];
		$this->loginPassword = $row['loginPassword'];
	}

	public function create() {
		$query = "INSERT INTO " . $this->table_name . " SET email=:email, loginName=:loginName, loginPassword=:loginPassword";
		$stmt = $this->conn->prepare($query);

		$this->email = htmlspecialchars(strip_tags($this->email));
		$this->loginName = htmlspecialchars(strip_tags($this->loginName));
		$this->loginPassword = hash('sha256', htmlspecialchars(strip_tags($this->loginPassword)));

		$stmt->bindParam(":email", $this->email);
		$stmt->bindParam(":loginName", $this->loginName);
		$stmt->bindParam(":loginPassword", $this->loginPassword);

		if ($stmt->execute()) {
			return true;
		}
		return false;
	}

	public function update() {
		$query = "UPDATE " . $this->table_name . " SET email=:email, loginName=:loginName, loginPassword=:loginPassword WHERE id=:id";
		$stmt = $this->conn->prepare($query);

		$this->id = htmlspecialchars(strip_tags($this->id));
		$this->email = htmlspecialchars(strip_tags($this->email));
		$this->loginName = htmlspecialchars(strip_tags($this->loginName));
		$this->loginPassword = hash('sha256', htmlspecialchars(strip_tags($this->loginPassword)));

		$stmt->bindParam(":id", $this->id);
		$stmt->bindParam(":email", $this->email);
		$stmt->bindParam(":loginName", $this->loginName);
		$stmt->bindParam(":loginPassword", $this->loginPassword);

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
