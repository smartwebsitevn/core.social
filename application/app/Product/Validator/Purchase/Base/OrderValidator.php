<?php namespace App\Product\Validator\Purchase\Base;

use App\Product\Validator\Purchase\PurchaseException;

abstract class OrderValidator extends FactoryAccessor
{
	/**
	 * Thuc hien validate
	 *
	 * @throws PurchaseException
	 */
	abstract public function validate();

}