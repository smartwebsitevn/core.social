<?php namespace App\Deposit\Command;

use App\Deposit\Library\CardDeposit;
use App\Purse\Model\PurseModel;
use Core\Support\OptionsAccess;

class DepositCardCommand extends OptionsAccess
{
	protected $config = [

		/**
		 * Purse can nap
		 *
		 * @var PurseModel
		 */
		'purse' => [
			'required' => true,
		],

		/**
		 * So tien can nap (tinh theo tien te cua vi)
		 *
		 * @var float
		 */
		'amount' => [
			'required' => true,
			'cast' => 'float',
		],

		/**
		 * Phi phan tram
		 *
		 * @var float
		 */
		'fee' => [
			'required' => true,
			'cast' => 'float',
		],

		/**
		 * Thong tin the nap
		 *
		 * @var CardDeposit
		 */
		'card' => [
			'required' => true,
		],

		/**
		 * Nha cung cap
		 */
		'provider' => [
			'cast' => 'string',
		],

		/**
		 * Thong tin deposit_card
		 *
		 * @var array
		 */
		'data' => [
			'default' => [],
			'allowed_types' => 'array',
		],

	];

	/**
	 * Lay provider
	 *
	 * @param string $value
	 * @return string
	 */
	protected function getProviderOption($value)
	{
		return $value ?: $this->getCard()->type->provider;
	}

	/**
	 * Lay profit_amount
	 *
	 * @return mixed
	 */
	protected function getProfitAmountOption()
	{
		$amount = $this->getCard()->amount;

		$profit = $this->getCard()->profit;

		return $amount * $profit * 0.01;
	}

	/**
	 * Lay purse
	 *
	 * @return PurseModel
	 */
	public function getPurse()
	{
		return $this->get('purse');
	}

	/**
	 * Lay card
	 *
	 * @return CardDeposit
	 */
	public function getCard()
	{
		return $this->get('card');
	}

}