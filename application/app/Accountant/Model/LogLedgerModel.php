<?php namespace App\Accountant\Model;

class LogLedgerModel extends \Core\Base\Model
{
	protected $table = 'log_ledger';

	protected $casts = [
		'amount'       => 'float',
		'profit'       => 'float',
		'inventory'    => 'float',
		'balance'      => 'float',
		'total_assets' => 'float',
		'options'      => 'array',
	];

	protected $defaults = [
		'options' => [],
	];

	protected $formats = [
		'amount'       => 'amount',
		'profit'       => 'amount',
		'inventory'    => 'amount',
		'balance'      => 'amount',
		'total_assets' => 'amount',
		'created'      => 'date',
	];
}