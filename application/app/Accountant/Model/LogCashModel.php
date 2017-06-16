<?php namespace App\Accountant\Model;

class LogCashModel extends \Core\Base\Model
{
	protected $table = 'log_cash';

	protected $casts = [
		'amount'         => 'float',
		'profit'         => 'float',
		'reason_options' => 'array',
		'customer'       => 'array',
	];

	protected $defaults = [
		'reason_options' => [],
		'customer'       => [],
	];

	protected $formats = [
		'amount'  => 'amount',
		'profit'  => 'amount',
		'created' => 'date',
	];
}