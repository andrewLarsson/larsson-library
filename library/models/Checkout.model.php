<?php namespace Larsson\Library\Models;

class CheckoutModel
extends \Larsson\Library\Core\ModelAbstract {
	const TABLE = "Checkout";
	const PRIMARY_KEY = "CheckoutID";

	public $CheckoutID;
	public $BookID;
	public $UserID;
	public $OutDate;
	public $InDate;
}
?>
