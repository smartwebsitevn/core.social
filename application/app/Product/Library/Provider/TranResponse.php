<?php namespace App\Product\Library\Provider;

use Core\Support\OptionsResponseAccess;

class TranResponse extends OptionsResponseAccess
{
	/**
	 * Khoi tao config
	 */
	protected function initConfig()
	{
		parent::initConfig();

		$this->config = array_merge($this->config, [

			/*
			 * Ma giao dich phat sinh ben nha cung cap
			 */
			'provider_tran_id' => [
				'cast' => 'string',
			],

			/*
			 * Thong tin giao dich phat sinh ben nha cung cap
			 */
			'provider_tran' => [
				'default' => [],
				'allowed_types' => 'array',
			],

			/*
			 * So du sau giao dich
			 */
			'balance' => [
				'cast' => 'float',
			],

		]);
	}

}