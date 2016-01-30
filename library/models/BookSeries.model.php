<?php namespace Larsson\Library\Models;

class BookSeriesModel
extends \Larsson\Library\Core\ModelAbstract {
	const TABLE = "BookSeries";
	const PRIMARY_KEY = "BookSeriesID";

	public $BookSeriesID;
	public $Title;
}
?>
