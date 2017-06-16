<?php namespace App\Deposit\Service;

use App\Currency\Model\CurrencyModel;
use App\Deposit\Library\CreateDepositOptions;
use App\Deposit\Model\DepositModel as DepositModel;
use App\Invoice\Library\OrderStatus;
use App\LogActivity\LogActivityFactory as LogActivityFactory;
use App\LogActivity\Library\ActivityLogger as ActivityLogger;
use App\LogActivity\Library\ActivityOwner as ActivityOwner;
use App\LogActivity\Model\LogActivityModel as LogActivityModel;

class DepositService
{
	/**
	 * Lay setting
	 *
	 * @param string $key
	 * @param mixed  $default
	 * @return mixed
	 */
	public function setting($key = null, $default = null)
	{
		$setting = module_get_setting('deposit');

		return array_get($setting, $key, $default);
	}

	/**
	 * Lay setting tuong ung voi currency
	 *
	 * @param CurrencyModel $currency
	 * @param string        $key
	 * @param mixed         $default
	 * @return mixed
	 */
	public function settingForCurrency(CurrencyModel $currency, $key = null, $default = null)
	{
		$params = ['amount_min', 'amount_max'];

		$setting = $this->setting();

		foreach ($setting as $param => &$value)
		{
			if ( ! in_array($param, $params)) continue;

			$value = currency_convert_amount($value, $currency->id);
		}

		return array_get($setting, $key, $default);
	}

	/**
	 * Tao deposit
	 *
	 * @param CreateDepositOptions $options
	 * @return DepositModel
	 */
	public function create(CreateDepositOptions $options)
	{
		$invoice_order = $options->invoice_order;

		$purse = $options->purse;

		$data = $options->except(['invoice_order', 'purse']);

		$data = array_merge([
			'invoice_id'       => $invoice_order->invoice_id,
			'invoice_order_id' => $invoice_order->id,
			'purse_id'         => $purse->id,
			'user_id'          => $purse->user_id,
			'currency_id'      => $purse->currency_id,
		], $data);

		return DepositModel::create($data);
	}

	/**
	 * Hoan thanh deposit
	 *
	 * @param DepositModel $deposit
	 * @param array        $options
	 * 		$options = [
	 *  		'owner' => null,
	 *   	];
	 */
	public function complete(DepositModel $deposit, array $options = [])
	{
		$deposit->updateStatus(OrderStatus::COMPLETED);

		$this->logActivity('completed', $deposit, array_get($options, 'owner'));
	}

	/**
	 * Lay doi tuong ActivityLogger
	 *
	 * @return ActivityLogger
	 */
	public function activityLogger()
	{
		return LogActivityFactory::logger('Deposit');
	}

	/**
	 * Log activity
	 *
	 * @param string        $action
	 * @param DepositModel    $deposit
	 * @param ActivityOwner $owner
	 * @param array         $context
	 * @return LogActivityModel
	 */
	public function logActivity($action, DepositModel $deposit, ActivityOwner $owner = null, array $context = [])
	{
		$context['deposit'] = $deposit->getAttributes();

		return $this->activityLogger()->log($action, $deposit->id, $owner, $context);
	}

}