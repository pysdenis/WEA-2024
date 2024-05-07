<?php
class Database {
	private $connection;
	private $servername = "localhost";
	private $username = "root"; //fastest
	private $password = ""; //rychleAzbesile5@
	private $dbname = "id22133666_wea";

	function getConnection() {
		$this->connection = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
		if ($this->connection->connect_error) {
			die("Connection failed: " . $this->connection->connect_error);
		}
		return $this->connection;
	}
}
