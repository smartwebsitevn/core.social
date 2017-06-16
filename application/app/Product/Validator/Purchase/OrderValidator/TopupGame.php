<?php namespace App\Product\Validator\Purchase\OrderValidator;

use App\Product\Validator\Purchase\PurchaseException;
use App\Product\Validator\Purchase\Base\OrderValidator;

class TopupGame extends OrderValidator
{
	/**
	 * Thuc hien validate
	 *
	 * @throws PurchaseException
	 */
	public function validate()
	{
		$this->factory->validateAccount($this->getAccount());
	}

	/**
	 * Lay account
	 *
	 * @return string
	 */
	protected function getAccount()
	{
		return $this->getOptions()->get('account');
	}

}