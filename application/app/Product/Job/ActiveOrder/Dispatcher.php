<?php namespace App\Product\Job\ActiveOrder;

use App\Product\Library\ProviderService;
use App\Product\Library\Provider\TranRequest;
use App\Product\Library\Provider\TranResponse;

class Dispatcher
{
	/**
	 * Doi tuong ProviderService
	 *
	 * @var ProviderService
	 */
	protected $provider_service;

	/**
	 * Doi tuong Logger
	 *
	 * @var Logger
	 */
	protected $logger;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param ProviderService $provider_service
	 * @param Logger          $logger
	 */
	public function __construct(ProviderService $provider_service, Logger $logger)
	{
		$this->provider_service = $provider_service;
		$this->logger = $logger;
	}

	/**
	 * Thuc hien command
	 *
	 * @param string $command
	 * @param array  $args
	 * @return TranResponse
	 */
	public function dispatch($command, array $args)
	{
		$request = $this->makeRequest($command, $args);

		$log = $this->logger->logRequest($this->getProvider()->key(), $command, $request);

		$response = $this->sendRequest($command, $request);

		$this->logger->logResponse($log, $response);

		return $response;
	}

	/**
	 * Tao request
	 *
	 * @param string $command
	 * @param array  $args
	 * @return TranRequest
	 */
	protected function makeRequest($command, array $args)
	{
		$args['request_id'] = $this->getProviderService()->makeRequestId();

		$class = 'App\Product\Library\Provider\\'.studly_case($command).'Request';

		return new $class($args);
	}

	/**
	 * Gui request
	 *
	 * @param string      $command
	 * @param TranRequest $request
	 * @return TranResponse
	 */
	protected function sendRequest($command, TranRequest $request)
	{
		$method = camel_case($command);

		$service = $this->getProviderService();

		if ( ! $service->getModel()->status)
		{
			$provider = $service->getModel()->name;

		    return TranResponse::error("Provider [{$provider}] was disabled");
		}

		return $this->getProviderService()->{$method}($request);
	}

	/**
	 * Lay ProviderService
	 *
	 * @return ProviderService
	 */
	public function getProviderService()
	{
		return $this->provider_service;
	}

	/**
	 * Lay ProviderFactory
	 *
	 * @return \App\Product\Library\ProviderFactory
	 */
	public function getProvider()
	{
		return $this->getProviderService()->getFactory();
	}

}