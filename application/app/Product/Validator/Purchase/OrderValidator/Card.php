<?php namespace App\Product\Validator\Purchase\OrderValidator;

use App\Product\Validator\Purchase\PurchaseException;
use App\Product\Validator\Purchase\Base\OrderValidator;

class Card extends OrderValidator
{
	/**
	 * Thuc hien validate
	 *
	 * @throws PurchaseException
	 */
	public function validate()
	{
		$this->factory->validateQuantity($this->getQuantity());
	}

	/**
	 * Lay quantity
	 *
	 * @return int
	 */
	protected function getQuantity()
	{
		return $this->getOptions()->get('quantity');
	}

}