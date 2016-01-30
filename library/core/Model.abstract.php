<?php namespace Larsson\Library\Core;

abstract class ModelAbstract {
	private static $TABLE;
	private static $PRIMARY_KEY;

	final public function __construct(Array $properties = []) {
		self::$TABLE = static::TABLE;
		self::$PRIMARY_KEY = static::PRIMARY_KEY;
		$this->__init($properties);
	}

	final private function __init(Array $properties) {
		foreach ($properties as $property => $value) {
			if (property_exists($this, $property)) {
				$this->$property = $value;
			}
		}
	}
}
?>
