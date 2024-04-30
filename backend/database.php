<?php
class Database {
	private $connection;
	private $servername = "localhost";
	private $username = "root";
	private $password = "";
	private $dbname = "wea2024";

	function getConnection() {
		$this->connection = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
		if ($this->connection->connect_error) {
			die("Connection failed: " . $this->connection->connect_error);
		}
		return $this->connection;
	}
}
