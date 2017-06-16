<?php namespace App\Product\Library\Provider;

use Core\Support\OptionsResponseAccess;

class GetBalanceResponse extends OptionsResponseAccess
{
	/**
	 * Khoi tao config
	 */
	protected function initConfig()
	{
		parent::initConfig();

		$this->config = array_merge($this->config, [

			'balance' => [
				'cast' => 'float',
			],

		]);
	}

	/**
	 * Tao response success
	 *
	 * @param float $balance
	 * @return static
	 */
	public static function success($balance)
	{
		return new static([
			'status'  => true,
			'balance' => $balance,
		]);
	}

}