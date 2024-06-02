<?php
class Database {
	private $connection;
	private $servername = "localhost";
	private $username = "id22260045_denis";
	private $password = "rychleAzbesile5555@";
	private $dbname = "id22133666_wea";

	public function getConnection() {
		$this->connection = null;
		try {
			$this->connection = new PDO("mysql:host=" . $this->servername . ";dbname=" . $this->dbname, $this->username, $this->password);
			$this->connection->exec("set names utf8");
		} catch (PDOException $exception) {
			echo "connection error: " . $exception->getMessage();
		}
		return $this->connection;
	}
}
