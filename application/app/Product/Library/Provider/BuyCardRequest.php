<?php namespace App\Product\Library\Provider;

use App\Product\Model\OrderModel;

class BuyCardRequest extends TranRequest
{
	/**
	 * Khoi tao config
	 */
	protected function initConfig()
	{
		parent::initConfig();

		$this->config = array_merge($this->config, [

			/**
			 * Key ket noi
			 */
			'key_connection' => [
				'required' => true,
				'cast' => 'string',
			],

			/**
			 * So luong
			 */
			'quantity' => [
				'required' => true,
				'cast' => 'int',
			],

			/**
			 * Thong tin order
			 *
			 * @var OrderModel
			 */
			'order' => [
				'required' => true,
			],

		]);
	}

}