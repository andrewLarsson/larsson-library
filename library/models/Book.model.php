<?php namespace Larsson\Library\Models;

class BookModel
extends \Larsson\Library\Core\ModelAbstract {
	const TABLE = "Book";
	const PRIMARY_KEY = "BookID";

	public $BookID;
	public $ISBN13;
	public $AuthorID;
	public $BookSeriesID;
	public $Title;
	public $Synopsis;
}
?>
