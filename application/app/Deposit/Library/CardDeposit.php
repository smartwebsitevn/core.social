<?php namespace App\Deposit\Library;

use App\Deposit\Model\CardTypeModel;
use Core\Support\OptionsAccess;

class CardDeposit extends OptionsAccess
{
	protected $config = [

		/**
		 * Thong tin card_type
		 *
		 * @var CardTypeModel
		 */
		'type' => [
			'required' => true,
		],

		/**
		 * Ma the
		 */
		'code' => [
			'required' => true,
			'cast' => 'string',
		],

		/**
		 * Serial
		 */
		'serial' => [
			'required' => true,
			'cast' => 'string',
		],

		/**
		 * Menh gia the
		 */
		'amount' => [
			'required' => true,
			'cast' => 'float',
		],

		/**
		 * Phan tram loi nhuan
		 */
		'profit' => [
			'required' => true,
			'cast' => 'float',
		],

	];

	/**
	 * Lay card_type
	 *
	 * @return CardTypeModel
	 */
	public function getType()
	{
		return $this->get('type');
	}
}