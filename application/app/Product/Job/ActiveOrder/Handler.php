<?php namespace App\Product\Job\ActiveOrder;

use App\Product\Library\Provider\TranResponse;
use App\Product\ProductFactory as ProductFactory;

abstract class Handler
{
	/**
	 * Doi tuong Factory
	 *
	 * @var Factory
	 */
	protected $factory;

	/**
	 * Doi tuong Dispatcher
	 *
	 * @var Dispatcher
	 */
	protected $dispatcher;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param Factory $factory
	 */
	public function __construct(Factory $factory)
	{
		$this->factory = $factory;

		$this->dispatcher = $this->makeDispatcher();
	}

	/**
	 * Thuc hien request den provider
	 *
	 * @return TranResponse
	 */
	abstract public function request();

	/**
	 * Xu ly response success
	 *
	 * @param TranResponse $response
	 */
	public function success(TranResponse $response){}

	/**
	 * Xu ly response error
	 *
	 * @param TranResponse $response
	 */
	public function error(TranResponse $response){}

	/**
	 * Xu ly du lieu khi order da hoan thanh truoc do
	 */
	public function completed(){}

	/**
	 * Thuc hien command
	 *
	 * @param string $command
	 * @param array  $args
	 * @return TranResponse
	 */
	public function dispatch($command, array $args)
	{
		return $this->dispatcher->dispatch($command, $args);
	}

	/**
	 * Thuc hien command topup
	 *
	 * @param string $command
	 * @param array  $args
	 * @return TranResponse
	 */
	public function dispatchCommandTopup($command, array $args = [])
	{
		$args = array_merge([
			'key_connection' => $this->getProduct()->provider_key_connection,
			'account'        => $this->getOrder()->account,
		], $args);

		return $this->dispatch($command, $args);
	}

	/**
	 * Tao doi tuong Dispatcher
	 *
	 * @return Dispatcher
	 */
	protected function makeDispatcher()
	{
		$provider_key = $this->getProduct()->provider_key;

		$provider_service = ProductFactory::providerService($provider_key);

		$logger = new Logger($this->getOrder());

		return new Dispatcher($provider_service, $logger);
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
	 * Lay doi tuong Order
	 *
	 * @return \App\Product\Model\OrderModel
	 */
	public function getOrder()
	{
		return $this->getFactory()->getOrder();
	}

	/**
	 * Lay doi tuong Product
	 *
	 * @return \App\Product\Model\ProductModel
	 */
	public function getProduct()
	{
		return $this->getFactory()->getProduct();
	}

}