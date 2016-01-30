<?php namespace Larsson\Library\Core;

class AutoLoader {
	static private $types;

	static public function __initialize($types) {
		self::$types = $types;
	}

	static public function load($class) {
		$classPreExtension = "class";
		$classFull = PATH_MEGA_ROOT . DIRECTORY_SEPARATOR . str_replace("\\", DIRECTORY_SEPARATOR, $class);
		$classSplit = explode(DIRECTORY_SEPARATOR, $classFull);
		$className = array_pop($classSplit);
		$classFileName = $className;
		$classDirectory = strtolower(implode(DIRECTORY_SEPARATOR, $classSplit));
		foreach (self::$types as $preExtension => $type) {
			$matches = [];
			if (preg_match("/(.*)" . $type . "\$/", $className, $matches)) {
				$classFileName = $matches[1];
				$classPreExtension = $preExtension;
				break;
			}
		}
		$classPath = $classDirectory . DIRECTORY_SEPARATOR . $classFileName . "." . $classPreExtension . ".php";
		if (file_exists($classPath)) {
			require_once($classPath);
		}
		return class_exists($class);
	}
}
AutoLoader::__initialize([]);
?>
