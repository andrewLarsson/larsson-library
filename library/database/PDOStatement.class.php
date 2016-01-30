<?php namespace Larsson\Library\Database;

class PDOStatement
extends \PDOStatement {
	protected function __construct() {
	}

	public function execute($inputParameters = []) {
		return (
			(
				((empty($inputParameters))
					? parent::execute()
					: parent::execute($inputParameters)
				)
			)
			? $this
			: false
		);
	}
}
?>
