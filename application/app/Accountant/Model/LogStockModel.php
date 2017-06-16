<?php namespace App\Accountant\Model;

class LogStockModel extends \Core\Base\Model
{
	protected $table = 'log_stock';

	protected $casts = [
		'amount'         => 'float',
		'inventory'      => 'float',
		'reason_options' => 'array',
	];

	protected $defaults = [
		'reason_options' => [],
	];

	protected $formats = [
		'amount'    => 'amount',
		'inventory' => 'amount',
		'created'   => 'date',
	];
}