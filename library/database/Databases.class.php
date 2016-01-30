<?php namespace Larsson\Library\Database;

use \Exception;

class Databases {
	private static $databases;
	private static $configs;

	public static function __initialize($directory = "") {
		self::$databases = [];
		self::$configs = new \Larsson\Library\Utilities\INIParser($directory, DatabaseConfig::class);
	}

	public static function __callStatic($method, $arguments) {
		$key = $method;
		foreach ($arguments as $argument) {
			$key .= $argument;
		}
		if (!self::$configs->offsetExists($key)) {
			throw new Exception("DatabaseConfig \"" . $key . "\" does not exist.");
		}
		if (!array_key_exists($key, self::$databases)) {
			self::$databases[$key] = new Database(self::$configs[$key]);
		}
		return self::$databases[$key];
	}
}
Databases::__initialize(PATH_CONF_DATABASE . DIRECTORY_SEPARATOR . "conf.d");
?>
