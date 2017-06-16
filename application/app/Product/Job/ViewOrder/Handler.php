<?php namespace App\Product\Job\ViewOrder;

abstract class Handler
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
	 * Thuc hien xu ly
	 *
	 * @return array
	 */
	abstract public function handle();

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
	 * Lay doi tuong Order
	 *
	 * @return \App\Product\Model\OrderModel
	 */
	public function getOrder()
	{
		return $this->getFactory()->getOrder();
	}
}