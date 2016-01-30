<?php
require_once("/opt/larsson/library/core.php");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

function APICreate($request, $requestBody) {
	Larsson\Library\Utilities\SQLHelper::prepareInsert(
		Larsson\Library\Database\Databases::LarssonLibrary(),
		new Larsson\Library\Models\UserModel([
			"DisplayName" => $requestBody->DisplayName,
			"FirstName" => $requestBody->FirstName,
			"LastName" => $requestBody->LastName
		])
	)->execute();
	return Larsson\Library\Utilities\SQLHelper::prepareSelect(
		Larsson\Library\Database\Databases::LarssonLibrary(),
		new Larsson\Library\Models\UserModel([
			"UserID" => Larsson\Library\Database\Databases::LarssonLibrary()->lastInsertId(),
		])
	)->execute()->fetchObject("Larsson\\Library\\Models\\UserModel");
}

function APIRead($request, $requestBody) {
	return ((isset($request["UserID"]))
		? Larsson\Library\Utilities\SQLHelper::prepareSelect(
			Larsson\Library\Database\Databases::LarssonLibrary(),
			new Larsson\Library\Models\UserID([
				"UserID" => $request["UserID"],
			])
		)->execute()->fetchObject("Larsson\\Library\\Models\\UserModel")
		: Larsson\Library\Utilities\SQLHelper::prepareSearch(
			Larsson\Library\Database\Databases::LarssonLibrary(),
			new Larsson\Library\Models\UserModel([
			])
		)->execute()->fetchAll(PDO::FETCH_CLASS, "Larsson\\Library\\Models\\UserModel")
	);
}

function APIUpdate($request, $requestBody) {
	Larsson\Library\Utilities\SQLHelper::prepareUpdate(
		Larsson\Library\Database\Databases::LarssonLibrary(),
		new Larsson\Library\Models\UserModel([
			"UserID" => $requestBody->UserID,
			"DisplayName" => $requestBody->DisplayName,
			"FirstName" => $requestBody->FirstName,
			"LastName" => $requestBody->LastName
		])
	)->execute();
	return Larsson\Library\Utilities\SQLHelper::prepareSelect(
		Larsson\Library\Database\Databases::LarssonLibrary(),
		new Larsson\Library\Models\UserModel([
			"UserID" => $requestBody->UserID,
		])
	)->execute()->fetchObject("Larsson\\Library\\Models\\UserModel");
}

function APIDelete($request, $requestBody) {
	Larsson\Library\Utilities\SQLHelper::prepareDelete(
		Larsson\Library\Database\Databases::LarssonLibrary(),
		new Larsson\Library\Models\UserModel([
			"UserID" => $request["UserID"],
		])
	)->execute();
	return (new stdClass());
}

$_REQUEST_BODY = json_decode(file_get_contents("php://input"));

switch($_SERVER["REQUEST_METHOD"]) {
	case "POST":
		echo(json_encode(APICreate($_REQUEST, $_REQUEST_BODY)));
		break;
	case "GET":
		echo(json_encode(APIRead($_REQUEST, $_REQUEST_BODY)));
		break;
	case "PUT":
		echo(json_encode(APIUpdate($_REQUEST, $_REQUEST_BODY)));
		break;
	case "DELETE":
		echo(json_encode(APIDelete($_REQUEST, $_REQUEST_BODY)));
		break;
}
?>
