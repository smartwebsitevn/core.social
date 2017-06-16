<?php namespace App\Product\Library\Provider;

class TranTopupRequest extends TranRequest
{
	/**
	 * Khoi tao config
	 */
	protected function initConfig()
	{
		parent::initConfig();

		$this->config = array_merge($this->config, [

			/*
			 * Key ket noi
			 */
			'key_connection' => [
				'required' => true,
				'cast' => 'string',
			],

			/*
			 * Tai khoan can nap
			 */
			'account' => [
				'required' => true,
				'cast' => 'string',
			],

		]);
	}
}