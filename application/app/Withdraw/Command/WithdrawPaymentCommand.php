<?php namespace App\Withdraw\Command;

use Core\Support\OptionsAccess;
use App\Purse\Model\PurseModel as PurseModel;
use App\Payment\Model\PaymentModel as PaymentModel;
use App\Withdraw\WithdrawFactory as WithdrawFactory;

class WithdrawPaymentCommand extends OptionsAccess
{
	protected $config = [

		/**
		 * Thong tin purse
		 *
		 * @var PurseModel
		 */
		'purse' => [
			'required' => true,
		],

		/**
		 * So tien (tinh theo tien te cua vi)
		 *
		 * @var float
		 */
		'amount' => [
			'required' => true,
			'cast' => 'float',
		],

		/**
		 * Thong tin payment
		 *
		 * @var PaymentModel
		 */
		'payment' => [
			'required' => true,
		],

		/**
		 * Thong tin nguoi nhan
		 *
		 * @var string
		 */
		'receiver' => [
			'required' => true,
			'allowed_types' => 'array',
		],

	];

	/**
	 * Khoi tao doi tuong
	 *
	 * @param array $options
	 */
	public function __construct(array $options)
	{
		parent::__construct($options);
       
		$this->makeOptionsAmounts();
	}

	/**
	 * Tao cac options amounts
	 */
	protected function makeOptionsAmounts()
	{
		$amounts = WithdrawFactory::withdraw()->getAmounts(
			$this->get('purse'), $this->get('amount'), $this->get('payment'), $this->get('receiver'));
   
		$this->options = array_merge($this->options, $amounts);
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
	 * Lay payment
	 *
	 * @return PaymentModel
	 */
	public function getPayment()
	{
		return $this->get('payment');
	}

}