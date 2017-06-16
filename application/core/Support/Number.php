<?php namespace Core\Support;

class Number
{
	/**
	 * Kiem tra amount theo limit
	 *
	 * @param float $amount
	 * @param float $amount_min
	 * @param float $amount_max
	 * @return bool
	 */
	public static function validAmountLimit($amount, $amount_min, $amount_max = null)
	{
		if ($amount < $amount_min) return false;

		if ( ! is_null($amount_max) && $amount > $amount_max) return false;

		return true;
	}

	/**
	 * Lay fee
	 *
	 * @param float $amount
	 * @param array $setting
	 * @return float
	 */
	public static function getFee($amount, array $setting)
	{
		$amount 		= (float) $amount;
		$fee_constant 	= (float) array_get($setting, 'constant');
		$fee_percent 	= (float) array_get($setting, 'percent');
		$fee_min 		= (float) array_get($setting, 'min');
		$fee_max 		= (float) array_get($setting, 'max');

		$fee = $fee_constant + ($amount * $fee_percent * 0.01);

		$fee = max($fee_min, $fee);

		if ($fee_max)
		{
			$fee = min($fee, $fee_max);
		}

		return $fee;
	}

	/**
	 * Xu ly amount input
	 *
	 * @param string|float $amount
	 * @param array        $options
	 * 		$options = [
	 *        	'natural' => false,
	 *        	'decimal' => 2,
	 * 		];
	 * @return float
	 */
	public static function handleAmountInput($amount, array $options = [])
	{
		$amount = str_replace(',', '', $amount);

		$amount = floatval($amount);

		if (array_get($options, 'natural'))
		{
			$amount = max(0, $amount);
		}

		if ( ! is_null($decimal = array_get($options, 'decimal')))
		{
			$amount = round($amount, (int) $decimal);
		}

		return $amount;
	}

}