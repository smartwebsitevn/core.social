<?php namespace App\Product\Library\Provider;

use Core\Support\OptionsResponseAccess;

class GetCardResponse extends OptionsResponseAccess
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
	 * @return static
	 */
	public static function success(array $cards)
	{
		return new static([
			'status' => true,
			'cards'  => $cards,
		]);
	}

}