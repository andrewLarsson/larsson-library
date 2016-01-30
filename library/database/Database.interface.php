<?php namespace Larsson\Library\Database;

interface DatabaseInterface {
	public function exec($statement);
	public function prepare($statement);
	public function beginTransaction();
	public function commit();
	public function rollBack();
	public function lastInsertId($name);
}
?>
