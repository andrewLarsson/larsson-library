<?php
require_once("/opt/larsson/library/library/core.php");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

function APICreate($request, $requestBody) {
	$statement = Larsson\Library\Database\Databases::LarssonLibrary()->prepare(
		Larsson\Library\Database\Statements\SQLStatements::SELECTCheckoutWHEREInDateISNULLByBook()
	);
	$statement->bindValue(":BookID", $requestBody->BookID);
	$statement->execute();
	if (count($statement->fetchAll(PDO::FETCH_ASSOC))) {
		throw new Exception("You cannot checkout a Book that is currently checked out.");
	}
	Larsson\Library\Utilities\SQLHelper::prepareInsert(
		Larsson\Library\Database\Databases::LarssonLibrary(),
		new Larsson\Library\Models\CheckoutModel([
			"BookID" => $requestBody->BookID,
			"UserID" => $requestBody->UserID
		])
	)->execute();
	return Larsson\Library\Utilities\SQLHelper::prepareSelect(
		Larsson\Library\Database\Databases::LarssonLibrary(),
		new Larsson\Library\Models\CheckoutModel([
			"CheckoutID" => Larsson\Library\Database\Databases::LarssonLibrary()->lastInsertId(),
		])
	)->execute()->fetchObject("Larsson\\Library\\Models\\CheckoutModel");
}

function APIRead($request, $requestBody) {
	return ((isset($request["CheckoutID"]))
		? Larsson\Library\Utilities\SQLHelper::prepareSelect(
			Larsson\Library\Database\Databases::LarssonLibrary(),
			new Larsson\Library\Models\CheckoutModel([
				"CheckoutID" => $request["CheckoutID"],
			])
		)->execute()->fetchObject("Larsson\\Library\\Models\\CheckoutModel")
		: Larsson\Library\Utilities\SQLHelper::prepareSearch(
			Larsson\Library\Database\Databases::LarssonLibrary(),
			new Larsson\Library\Models\CheckoutModel([
			])
		)->execute()->fetchAll(PDO::FETCH_CLASS, "Larsson\\Library\\Models\\CheckoutModel")
	);
}

function APIUpdate($request, $requestBody) {
	Larsson\Library\Utilities\SQLHelper::prepareUpdate(
		Larsson\Library\Database\Databases::LarssonLibrary(),
		new Larsson\Library\Models\CheckoutModel([
			"CheckoutID" => $requestBody->CheckoutID,
			"BookID" => $requestBody->BookID,
			"UserID" => $requestBody->UserID,
			"OutDate" => $requestBody->OutDate,
			"InDate" => $requestBody->InDate
		])
	)->execute();
	return Larsson\Library\Utilities\SQLHelper::prepareSelect(
		Larsson\Library\Database\Databases::LarssonLibrary(),
		new Larsson\Library\Models\CheckoutModel([
			"CheckoutID" => $requestBody->CheckoutID,
		])
	)->execute()->fetchObject("Larsson\\Library\\Models\\CheckoutModel");
}

function APIDelete($request, $requestBody) {
	Larsson\Library\Utilities\SQLHelper::prepareDelete(
		Larsson\Library\Database\Databases::LarssonLibrary(),
		new Larsson\Library\Models\CheckoutModel([
			"CheckoutID" => $request["CheckoutID"],
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
