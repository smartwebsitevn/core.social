<?php namespace App\Payment\Library\Payment;

use Core\Support\OptionsAccess;
use App\Payment\PaymentFactory as PaymentFactory;
use App\Transaction\Model\TranModel as TranModel;

class PaymentResultInputResponse extends OptionsAccess
{
	protected $config = [

		'tran_id' => [
			'required' => true,
		],

		'token' => [
			'cast' => 'string',
		],

	];

	/**
	 * Lay token option
	 *
	 * @return string
	 */
	protected function getTokenOption()
	{
		$token = array_get($this->options, 'token');

		if (is_null($token))
		{
			$tran = TranModel::find($this->get('tran_id'));

			$token = $tran ? PaymentFactory::service()->makePaymentToken($tran) : '';

			$this->options['token'] = (string) $token;
		}

		return $token;
	}
}