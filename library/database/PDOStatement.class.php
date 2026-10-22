<?php namespace Larsson\Library\Database;

// Preserve the application's fluent execute()->fetch*() API without overriding
// PDOStatement::execute(), whose PHP 8 return contract is boolean.
class PDOStatement {
	public function __construct(private \PDOStatement $statement) {}

	public function execute(?array $inputParameters = null): self|false {
		return $this->statement->execute($inputParameters) ? $this : false;
	}

	public function __call(string $method, array $arguments): mixed {
		return $this->statement->$method(...$arguments);
	}
}
?>
