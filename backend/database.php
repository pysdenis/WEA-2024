<?php
class Database {
	private $connection;
	private $servername = "localhost"; //sql.endora.cz:3307 - for remote database
	private $username = "spseipysg6cz";
	private $password = "denispys";
	private $dbname = "weapysdenis";

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
