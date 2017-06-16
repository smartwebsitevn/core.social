<?php namespace App\Product\Validator\Purchase\OrderValidator;

use App\Product\ProductFactory as ProductFactor;
use App\Product\Validator\Purchase\PurchaseException;
use App\Product\Validator\Purchase\Base\OrderValidator;

class TopupMobilePost extends OrderValidator
{
	/**
	 * Thuc hien validate
	 *
	 * @throws PurchaseException
	 */
	public function validate()
	{
		$this->factory->validatePhone($this->getPhone());

		$this->factory->validateAmount(
			$this->getAmount(),
			$this->setting('amount_min'),
			$this->setting('amount_max')
		);
	}

	/**
	 * Lay setting
	 *
	 * @param string $key
	 * @param mixed  $default
	 * @return mixed
	 */
	protected function setting($key, $default = null)
	{
		return ProductFactor::order()->setting('topup_mobile_post_'.$key, $default);
	}

	/**
	 * Lay phone
	 *
	 * @return string
	 */
	protected function getPhone()
	{
		return $this->getOptions()->get('phone');
	}

	/**
	 * Lay amount
	 *
	 * @return float
	 */
	protected function getAmount()
	{
		return $this->getOptions()->get('amount');
	}
}