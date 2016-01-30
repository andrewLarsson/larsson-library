<?php namespace Larsson\Library\Models;

class AuthorModel
extends \Larsson\Library\Core\ModelAbstract {
	const TABLE = "Author";
	const PRIMARY_KEY = "AuthorID";

	public $AuthorID;
	public $DisplayName;
	public $FirstName;
	public $LastName;
}
?>
