<?php namespace Larsson\Library\Utilities;

use \Exception;

class Functions {
	static public function array_clone($array) {
		return array_map(function($element) {
			return ((is_array($element))
				? call_user_func(__FUNCTION__, $element)
				: ((is_object($element))
					? clone $element
					: $element
				)
			);
		}, $array);
	}

	static public function mexplode($delimiters, $string) {
		return explode($delimiters[0], str_replace($delimiters, $delimiters[0], $string));
	}

	static public function array_to_object($array, $objectType) {
		if (!class_exists($objectType)) {
			throw new Exception("Class \"" . $objectType . "\" does not exist.");
		}
		$object = new $objectType();
		foreach ($array as $key => $value) {
			$object->$key = $value;
		}
		return $object;
	}
}
?>
