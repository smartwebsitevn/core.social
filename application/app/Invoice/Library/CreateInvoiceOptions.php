<?php namespace App\Invoice\Library;

use Core\Support\OptionsAccess;
use App\Invoice\InvoiceFactory as InvoiceFactory;
use App\User\Model\UserModel as UserModel;

class CreateInvoiceOptions extends OptionsAccess
{
	protected $config = [

		'amount' => [
			'required' => true,
			'cast' => 'float',
		],

		'status' => [
			'default' => InvoiceStatus::UNPAID,
		],

		'user_id' => [
			'default' => 0,
		],

		'fee_shipping' => [
			'cast' => 'float',
		],

		'fee_tax' => [
			'cast' => 'float',
		],

		'payment_due' => [
			'cast' => 'int',
		],

	];

	/**
	 * Khoi tao config
	 */
	protected function initConfig()
	{
		foreach ([
			'info_contact', 'info_shipping', /*'info_payment',*/'info_pay_to', 'info_system',
			'params', 'amounts_currency', 'invoice_orders',
		] as $key)
		{
			$this->config[$key] = [
				'default' => [],
				'allowed_types' => 'array',
			];
		}

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

	/**
	 * Lay info_contact
	 *
	 * @param array $value
	 * @return array
	 */
	protected function getInfoContactOption($value)
	{
		return count($value) ? $value : $this->getUserContact();
	}

	/**
	 * Lay user contact
	 *
	 * @return array
	 */
	public function getUserContact()
	{
		if ( ! array_key_exists('user_contact', $this->additional))
		{
			$user_id = $this->get('user_id');

			$user = $user_id ? UserModel::find($this->get('user_id')) : null;

			$this->additional['user_contact'] = $user
				? $user->onlyAttributes(['email', 'username', 'name', 'phone', 'address'])
				: [];
		}

		return $this->additional['user_contact'];
	}

	/**
	 * Lay payment_due
	 *
	 * @param int $value
	 * @return int
	 */
	protected function getPaymentDueOption($value)
	{
		return $value ?: InvoiceFactory::service()->getPaymentDueDefault();
	}

}