<?php namespace Larsson\Library\Database;

use \PDO;

class Database implements DatabaseInterface {
	private $connection;

	function __construct(DatabaseConfig $config) {
		$this->connection = new PDO(
			$config->driver . ":" . "dbname=" . $config->schema . ";" . "host=" . $config->host . ";charset=" . ($config->charset ?? "utf8mb4"),
			$config->user,
			$config->password,
			[
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			]
		);
	}

	public function exec($statement) {
		$this->connection->exec($statement);
	}

	public function prepare($statement) {
		return new PDOStatement($this->connection->prepare($statement));
	}

	public function query($statement) {
		return new PDOStatement($this->connection->query($statement));
	}

	public function beginTransaction() {
		$this->connection->beginTransaction();
	}

	public function commit() {
		$this->connection->commit();
	}

	public function rollBack() {
		$this->connection->rollBack();
	}

	public function lastInsertId($name = "") {
		return $this->connection->lastInsertId($name);
	}

	public function __getRawConnection() {
		return $this->connection;
	}
}
?>
