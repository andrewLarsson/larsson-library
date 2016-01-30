<?php
require_once("/opt/larsson/library/library/core.php");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

function APICreate($request, $requestBody) {
	Larsson\Library\Utilities\SQLHelper::prepareInsert(
		Larsson\Library\Database\Databases::LarssonLibrary(),
		new Larsson\Library\Models\BookSeriesModel([
			"Title" => $requestBody->Title
		])
	)->execute();
	return Larsson\Library\Utilities\SQLHelper::prepareSelect(
		Larsson\Library\Database\Databases::LarssonLibrary(),
		new Larsson\Library\Models\BookSeriesModel([
			"BookSeriesID" => Larsson\Library\Database\Databases::LarssonLibrary()->lastInsertId(),
		])
	)->execute()->fetchObject("Larsson\\Library\\Models\\BookSeriesModel");
}

function APIRead($request, $requestBody) {
	return ((isset($request["BookSeriesID"]))
		? Larsson\Library\Utilities\SQLHelper::prepareSelect(
			Larsson\Library\Database\Databases::LarssonLibrary(),
			new Larsson\Library\Models\BookSeriesModel([
				"BookSeriesID" => $request["BookSeriesID"],
			])
		)->execute()->fetchObject("Larsson\\Library\\Models\\BookSeriesModel")
		: Larsson\Library\Utilities\SQLHelper::prepareSearch(
			Larsson\Library\Database\Databases::LarssonLibrary(),
			new Larsson\Library\Models\BookSeriesModel([
			])
		)->execute()->fetchAll(PDO::FETCH_CLASS, "Larsson\\Library\\Models\\BookSeriesModel")
	);
}

function APIUpdate($request, $requestBody) {
	Larsson\Library\Utilities\SQLHelper::prepareUpdate(
		Larsson\Library\Database\Databases::LarssonLibrary(),
		new Larsson\Library\Models\BookSeriesModel([
			"BookSeriesID" => $requestBody->BookSeriesID,
			"Title" => $requestBody->Title
		])
	)->execute();
	return Larsson\Library\Utilities\SQLHelper::prepareSelect(
		Larsson\Library\Database\Databases::LarssonLibrary(),
		new Larsson\Library\Models\BookSeriesModel([
			"BookSeriesID" => $requestBody->BookSeriesID,
		])
	)->execute()->fetchObject("Larsson\\Library\\Models\\BookSeriesModel");
}

function APIDelete($request, $requestBody) {
	Larsson\Library\Utilities\SQLHelper::prepareDelete(
		Larsson\Library\Database\Databases::LarssonLibrary(),
		new Larsson\Library\Models\BookSeriesModel([
			"BookSeriesID" => $request["BookSeriesID"],
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
