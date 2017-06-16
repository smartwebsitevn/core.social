<?php namespace App\Transaction\Library;

use Core\Support\OptionsAccess;
use App\Invoice\Model\InvoiceModel as InvoiceModel;
use App\Payment\Model\PaymentModel as PaymentModel;

class CreateTranOptions extends OptionsAccess
{
	protected $config = [

		/**
		 * Thong tin invoice
		 *
		 * @var InvoiceModel
		 */
		'invoice' => [
			'required' => true,
		],

		/**
		 * Thong tin payment
		 *
		 * @var PaymentModel
		 */
		'payment' => [],

	];

	/**
	 * Khoi tao config
	 */
	protected function initConfig()
	{
		$this->config = array_merge($this->config, [

			'user_ip' => [
				'default' => t('input')->ip_address(),
			],

			'user_agent' => [
				'default' => t('input')->user_agent(),
			],

			'user_referer' => [
				'default' => t('input')->server('HTTP_REFERER'),
			],

			'session_id' => [
				'default' => session_id(),
			],

		]);
	}
}