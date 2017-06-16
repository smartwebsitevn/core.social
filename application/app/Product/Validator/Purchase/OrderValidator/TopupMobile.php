<?php namespace App\Product\Validator\Purchase\OrderValidator;

use App\Product\Validator\Purchase\PurchaseException;
use App\Product\Validator\Purchase\Base\OrderValidator;

class TopupMobile extends OrderValidator
{
	/**
	 * Thuc hien validate
	 *
	 * @throws PurchaseException
	 */
	public function validate()
	{
		$this->factory->validatePhone($this->getPhone());
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

}