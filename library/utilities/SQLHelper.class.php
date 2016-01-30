<?php namespace Larsson\Library\Utilities;

use \Exception;
use \PDOStatement;

class SQLHelper {
	public static function prepareInsert(\Larsson\Library\Database\DatabaseInterface $database, \Larsson\Library\Core\ModelAbstract $model) {
		$statement = "
			INSERT INTO
				`" . $model::TABLE . "`
			(
		";
		$columns = [];
		$modelProperties = get_object_vars($model);
		foreach ($modelProperties as $modelProperty => $value) {
			if (!is_null($value)) {
				$columns[] = $modelProperty;
			}
		}
		$statement .= "`" . implode("`, `", $columns) . "`";
		$statement .= "
			) VALUES (
		";
		$values = [];
		foreach ($modelProperties as $modelProperty => $value) {
			if (!is_null($value)) {
				$values[] = ":" . $modelProperty;
			}
		}
		$statement .= implode(", ", $values);
		$statement .= "
			);
		";
		$preparedStatement = $database->prepare($statement);
		foreach ($modelProperties as $modelProperty => $value) {
			if (!is_null($value)) {
				$preparedStatement->bindValue(":" . $modelProperty, $value);
			}
		}
		return $preparedStatement;
	}

	public static function prepareSelect(\Larsson\Library\Database\DatabaseInterface $database, \Larsson\Library\Core\ModelAbstract $model, Array $columns = []) {
		$statement = "
			SELECT
		";
		$statement .= ((empty($columns))
			? "*"
			: "`" . implode("`, `", $columns) . "`"
		);
		$statement .= "
			FROM
				`" . $model::TABLE . "`
			WHERE
				`" . $model::PRIMARY_KEY . "` = :" . $model::PRIMARY_KEY . "
			;
		";
		$preparedStatement = $database->prepare($statement);
		$preparedStatement->bindValue(":" . $model::PRIMARY_KEY, $model->{$model::PRIMARY_KEY});
		return $preparedStatement;
	}

	public static function prepareSearch(\Larsson\Library\Database\DatabaseInterface $database, \Larsson\Library\Core\ModelAbstract $model, Array $columns = []) {
		$statement = "
			SELECT
		";
		$statement .= ((empty($columns))
			? "*"
			: "`" . implode("`, `", $columns) . "`"
		);
		$statement .= "
			FROM
				`" . $model::TABLE . "`
			WHERE
		";
		$sets = [];
		$modelProperties = get_object_vars($model);
		foreach ($modelProperties as $modelProperty => $value) {
			if (
				!is_null($value)
				&& ($modelProperty != $model::PRIMARY_KEY)
			) {
				$sets[] = "`" . $modelProperty . "` = :" . $modelProperty;
			}
		}
		$statement .= ((count($sets))
			? implode(" AND ", $sets)
			: "TRUE = TRUE"
		);
		$statement .= "
			;
		";
		$preparedStatement = $database->prepare($statement);
		foreach ($modelProperties as $modelProperty => $value) {
			if (
				!is_null($value)
				&& ($modelProperty != $model::PRIMARY_KEY)
			) {
				$preparedStatement->bindValue(":" . $modelProperty, $value);
			}
		}
		return $preparedStatement;
	}

	public static function prepareUpdate(\Larsson\Library\Database\DatabaseInterface $database, \Larsson\Library\Core\ModelAbstract $model) {
		$statement = "
			UPDATE
				`" . $model::TABLE . "`
			SET
		";
		$sets = [];
		$modelProperties = get_object_vars($model);
		foreach ($modelProperties as $modelProperty => $value) {
			if (
				!is_null($value)
				&& ($modelProperty != $model::PRIMARY_KEY)
			) {
				$sets[] = "`" . $modelProperty . "` = :" . $modelProperty;
			}
		}
		$statement .= implode(", ", $sets);
		$statement .= "
			WHERE
				`" . $model::PRIMARY_KEY . "` = :" . $model::PRIMARY_KEY . "
			;
		";
		$preparedStatement = $database->prepare($statement);
		foreach ($modelProperties as $modelProperty => $value) {
			if (
				!is_null($value)
				&& ($modelProperty != $model::PRIMARY_KEY)
			) {
				$preparedStatement->bindValue(":" . $modelProperty, $value);
			}
		}
		$preparedStatement->bindValue(":" . $model::PRIMARY_KEY, $model->{$model::PRIMARY_KEY});
		return $preparedStatement;
	}

	public static function prepareDelete(\Larsson\Library\Database\DatabaseInterface $database, \Larsson\Library\Core\ModelAbstract $model) {
		$statement = "
			DELETE FROM
				`" . $model::TABLE . "`
			WHERE
				`" . $model::PRIMARY_KEY . "` = :" . $model::PRIMARY_KEY . "
			;
		";
		$preparedStatement = $database->prepare($statement);
		$preparedStatement->bindValue(":" . $model::PRIMARY_KEY, $model->{$model::PRIMARY_KEY});
		return $preparedStatement;
	}

	public static function mapResults(PDOStatement $statement, $classType, callable $function) {
		if (!class_exists($objectType)) {
			throw new Exception("Class \"" . $objectType . "\" does not exist.");
		}
		$object = null;
		while ($object = $statement->fetchObject($classType)) {
			$function($object);
		}
	}
}
?>
