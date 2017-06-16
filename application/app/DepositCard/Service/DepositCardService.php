<?php namespace App\DepositCard\Service;

use App\Currency\Model\CurrencyModel;
use App\Purse\Model\PurseModel as PurseModel;
use Core\Support\Number;

class DepositCardService
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
		$setting = module_get_setting('transfer');

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
		$params = ['amount_min', 'amount_max', 'fee_constant', 'fee_min', 'fee_max'];

		$setting = $this->setting();

		foreach ($setting as $param => &$value)
		{
			if ( ! in_array($param, $params)) continue;

			$value = currency_convert_amount($value, $currency->id);
		}

		return array_get($setting, $key, $default);
	}

	/**
	 * Lay fee setting
	 *
	 * @param CurrencyModel $currency
	 * @param string        $key
	 * @param mixed         $default
	 * @return mixed
	 */
	public function getFeeSetting(CurrencyModel $currency, $key = null, $default = null)
	{
		$setting = $this->settingForCurrency($currency);

		$options = [];

		foreach (['constant', 'percent', 'min', 'max'] as $param)
		{
			$options[$param] = array_get($setting, 'fee_'.$param);
		}

		return array_get($options, $key, $default);
	}

	/**
	 * Tinh phi giao dich (tinh theo tien te cua purse gui)
	 *
	 * @param PurseModel $sender_purse	Purse gui
	 * @param float      $amount		So tien can chuyen (tinh theo tien te cua purse gui)
	 * @return float
	 */
	public function getFee(PurseModel $sender_purse, $amount)
	{
		$currency = $sender_purse->currency;

		$setting = $this->getFeeSetting($currency);

		return Number::getFee($amount, $setting);
	}

	/**
	 * Xu ly transfer amounts
	 *
	 * @param PurseModel $sender_purse	Purse gui
	 * @param float      $amount		So tien can chuyen (tinh theo tien te cua purse gui)
	 * @return array
	 * 	Bao gom cac key:
	 * 		'fee'				Phi chuyen tien (tinh theo tien te cua purse gui)
	 * 		'net'				Tong so tien nguoi gui phai chiu (tinh theo tien te cua purse gui)
	 * 		'send_amount'		So tien tru cua purse gui (tinh theo tien te cua purse gui)
	 * 		'receive_amount'	So tien cong cho purse nhan (tinh theo tien te cua purse nhan)
	 */
	public function getAmounts(PurseModel $sender_purse, $amount)
	{
		$fee = $this->getFee($sender_purse, $amount);

		$net = $amount + $fee;

		$send_amount = $net;

		$receive_amount = $amount;

		return compact('amount', 'fee', 'net', 'send_amount', 'receive_amount');
	}
}