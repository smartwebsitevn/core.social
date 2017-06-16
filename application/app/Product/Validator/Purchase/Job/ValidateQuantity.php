<?php namespace App\Product\Validator\Purchase\Job;

use App\Product\ProductFactory as ProductFactor;
use App\Product\Validator\Purchase\Factory;
use App\Product\Validator\Purchase\Error;
use App\Product\Validator\Purchase\PurchaseException;
use App\Product\Validator\Purchase\Base\Job;

class ValidateQuantity extends Job
{
	/**
	 * Quantity
	 *
	 * @var int
	 */
	protected $quantity;

	/**
	 * Khoi tao doi tuong
	 *
	 * @param Factory $factory
	 * @param  int    $quantity
	 */
	public function __construct(Factory $factory, $quantity)
	{
		parent::__construct($factory);

		$this->quantity = $quantity;
	}

	/**
	 * Thuc hien xu ly
	 *
	 * @throws PurchaseException
	 */
	public function handle()
	{
		$quantity = $this->getQuantity();

		if ($quantity <= 0)
		{
			$this->throwException(ERROR::QUANTITY_INVALID);
		}

		if ( ! $this->getProduct()->available)
		{
			$this->throwException(ERROR::OUT_OF_STOCK);
		}

		if ($quantity > ($quantity_max = $this->getQuantityMax()))
		{
			$this->throwException(ERROR::QUANTITY_OVER, compact('quantity_max'));
		}
	}

	/**
	 * Lay quantity_max
	 *
	 * @return int
	 */
	protected function getQuantityMax()
	{
		$available = $this->getProduct()->available;

		$quantity_max = ProductFactor::order()->setting('quantity_max');

		$quantity_max = ($available >= 0) ? min($available, $quantity_max) : $quantity_max;

		return $quantity_max;
	}

	/**
	 * Lay quantity
	 *
	 * @return int
	 */
	public function getQuantity()
	{
		return $this->quantity;
	}

}