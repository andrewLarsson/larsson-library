<?php namespace Larsson\Library\Utilities;

use \ArrayAccess;
use \Iterator;
use \Exception;

class INIParser implements ArrayAccess, Iterator {
	private $_data;

	function __construct($directory, $objectType = null) {
		$this->_data = [];
		if (!file_exists($directory)) {
			throw new Exception("INI file path \"" . $directory . "\" does not exist.");
		}
		$files = scandir($directory);
		foreach ($files as $file) {
			if (preg_match("/\.ini$/", $file)) {
				$key = preg_replace("/\.ini/", "", $file);
				$iniArray = parse_ini_file($directory . DIRECTORY_SEPARATOR . $file);
				$this->_data[$key] = ((isset($objectType))
					? \Larsson\Library\Utilities\Functions::array_to_object($iniArray, $objectType)
					: $iniArray
				);
			}
		}
	}

	public function offsetGet($offset) {
		return ((isset($this->_data[$offset]))
			? $this->_data[$offset]
			: null
		);
	}

	public function offsetSet($offset, $value) {
		return null;
	}

	public function offsetExists($offset) {
		return isset($this->_data[$offset]);
	}

	public function offsetUnset($offset) {
		return null;
	}

	public function key() {
		return key($this->_data);
	}

	public function current() {
		return current($this->_data);
	}

	public function next() {
		next($this->_data);
	}

	public function rewind() {
		reset($this->_data);
	}

	public function valid() {
		return key($this->_data) !== null;
	}
}
?>
