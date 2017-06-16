<?php namespace App\Product\Validator\Purchase;

use Core\Support\OptionsAccess;

class Options extends OptionsAccess
{
	protected $config = [

		'quantity' => [
			'cast' => 'int',
		],

		'amount' => [
			'cast' => 'float',
		],

		'phone' => [
			'cast' => 'string',
		],

		'account' => [
			'cast' => 'string',
		],

		'ship' => [
			'default' => [],
			'allowed_types' => 'array',
		],

	];

	/**
	 * Lay amount
	 *
	 * @param float $value
	 * @return float
	 */
	protected function getAmountOption($value)
	{
		return currency_handle_input($value);
	}

}