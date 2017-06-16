<?php namespace App\Product\Validator\Purchase\OrderValidator;

use App\Product\Validator\Purchase\PurchaseException;
use App\Product\Validator\Purchase\Base\OrderValidator;

class Ship extends OrderValidator
{
	/**
	 * Thuc hien validate
	 *
	 * @throws PurchaseException
	 */
	public function validate()
	{
		$this->factory->validateQuantity($this->getQuantity());

		//$this->factory->validateShip($this->getShip());
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

	/**
	 * Lay thong tin ship
	 *
	 * @return array
	 */
	protected function getShip()
	{
		return $this->getOptions()->get('ship');
	}

}