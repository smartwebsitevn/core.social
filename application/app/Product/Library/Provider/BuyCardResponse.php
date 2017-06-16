<?php namespace App\Product\Library\Provider;

class BuyCardResponse extends TranResponse
{
	/**
	 * Khoi tao config
	 */
	protected function initConfig()
	{
		parent::initConfig();

		$this->config = array_merge($this->config, [

			/*
			 * Danh sach the
			 */
			'cards' => [
				'default' => [],
				'allowed_types' => 'array',
			],

		]);
	}

	/**
	 * Tao response success
	 *
	 * @param array $cards
	 * @param array $response
	 * @return static
	 */
	public static function success(array $cards, array $response = [])
	{
		return new static(array_merge($response, [
			'status' => true,
			'cards'  => $cards,
		]));
	}

}