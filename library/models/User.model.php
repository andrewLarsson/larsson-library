<?php namespace Larsson\Library\Models;

class UserModel
extends \Larsson\Library\Core\ModelAbstract {
	const TABLE = "User";
	const PRIMARY_KEY = "UserID";

	public $UserID;
	public $DisplayName;
	public $FirstName;
	public $LastName;
}
?>
