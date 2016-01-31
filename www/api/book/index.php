<?php
require_once("/opt/larsson/library/core.php");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json");

function APICreate($request, $requestBody) {
	Larsson\Library\Utilities\SQLHelper::prepareInsert(
		Larsson\Library\Database\Databases::LarssonLibrary(),
		new Larsson\Library\Models\BookModel([
			"ISBN13" => $requestBody->ISBN13,
			"AuthorID" => $requestBody->AuthorID,
			"BookSeriesID" => ((!empty($requestBody->BookSeriesID))
				? $requestBody->BookSeriesID
				: null
			),
			"Title" => $requestBody->Title,
			"Synopsis" => $requestBody->Synopsis
		])
	)->execute();
	return Larsson\Library\Utilities\SQLHelper::prepareSelect(
		Larsson\Library\Database\Databases::LarssonLibrary(),
		new Larsson\Library\Models\BookModel([
			"BookID" => Larsson\Library\Database\Databases::LarssonLibrary()->lastInsertId(),
		])
	)->execute()->fetchObject("Larsson\\Library\\Models\\BookModel");
}

function APIRead($request, $requestBody) {
	return ((isset($request["BookID"]))
		? Larsson\Library\Utilities\SQLHelper::prepareSelect(
			Larsson\Library\Database\Databases::LarssonLibrary(),
			new Larsson\Library\Models\BookModel([
				"BookID" => $request["BookID"],
			])
		)->execute()->fetchObject("Larsson\\Library\\Models\\BookModel")
		: Larsson\Library\Utilities\SQLHelper::prepareSearch(
			Larsson\Library\Database\Databases::LarssonLibrary(),
			new Larsson\Library\Models\BookModel([
			])
		)->execute()->fetchAll(PDO::FETCH_CLASS, "Larsson\\Library\\Models\\BookModel")
	);
}

function APIUpdate($request, $requestBody) {
	Larsson\Library\Utilities\SQLHelper::prepareUpdate(
		Larsson\Library\Database\Databases::LarssonLibrary(),
		new Larsson\Library\Models\BookModel([
			"BookID" => $requestBody->BookID,
			"ISBN13" => $requestBody->ISBN13,
			"AuthorID" => $requestBody->AuthorID,
			"BookSeriesID" => $requestBody->BookSeriesID,
			"Title" => $requestBody->Title,
			"Synopsis" => $requestBody->Synopsis
		])
	)->execute();
	return Larsson\Library\Utilities\SQLHelper::prepareSelect(
		Larsson\Library\Database\Databases::LarssonLibrary(),
		new Larsson\Library\Models\BookModel([
			"BookID" => $requestBody->BookID,
		])
	)->execute()->fetchObject("Larsson\\Library\\Models\\BookModel");
}

function APIDelete($request, $requestBody) {
	Larsson\Library\Utilities\SQLHelper::prepareDelete(
		Larsson\Library\Database\Databases::LarssonLibrary(),
		new Larsson\Library\Models\BookModel([
			"BookID" => $request["BookID"],
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
