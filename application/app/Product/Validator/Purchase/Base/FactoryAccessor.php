<?php namespace App\Product\Validator\Purchase\Base;

use App\Product\Model\ProductModel;
use App\Product\Validator\Purchase\Factory;
use App\Product\Validator\Purchase\Options;
use App\Product\Validator\Purchase\PurchaseException;

class FactoryAccessor
{
	/**
	 * Doi tuong Factory
	 *
	 * @var Factory
	 */
	protected $factory;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param Factory $factory
	 */
	public function __construct(Factory $factory)
	{
		$this->factory = $factory;
	}

	/**
	 * Lay doi tuong Factory
	 *
	 * @return Factory
	 */
	public function getFactory()
	{
		return $this->factory;
	}

	/**
	 * Lay doi tuong Product
	 *
	 * @return ProductModel
	 */
	public function getProduct()
	{
		return $this->factory->getProduct();
	}

	/**
	 * Lay options
	 *
	 * @return Options
	 */
	public function getOptions()
	{
		return $this->factory->getOptions();
	}

	/**
	 * Throw exception
	 *
	 * @param string $error
	 * @param array  $replace
	 * @throws PurchaseException
	 */
	public function throwException($error, $replace = [])
	{
		$this->factory->throwException($error, $replace);
	}

}