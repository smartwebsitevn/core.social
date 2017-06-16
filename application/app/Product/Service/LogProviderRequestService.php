<?php namespace App\Product\Service;

use App\Product\ProductFactory as ProductFactory;
use App\Product\Library\ProviderService;
use App\Product\Library\Provider\GetCardRequest;
use App\Product\Library\Provider\GetCardResponse;
use App\Product\Library\Provider\FindTranRequest;
use App\Product\Library\Provider\FindTranResponse;
use App\Product\Model\LogProviderRequestModel as LogProviderRequestModel;
use Core\Support\Arr;

class LogProviderRequestService
{
	/**
	 * Lay ma the tu nha cung cap
	 *
	 * @param LogProviderRequestModel $log
	 * @return GetCardResponse
	 */
	public function getCard(LogProviderRequestModel $log)
	{
		$request = new GetCardRequest(array_merge(
			$log->onlyAttributes(['request_id', 'provider_tran_id']),
			Arr::pick($log->input, ['key_connection', 'quantity'])
		));

		return $this->providerService($log->provider_key)->getCard($request);
	}

	/**
	 * Lay thong tin giao dich phat sinh ben nha cung cap
	 *
	 * @param LogProviderRequestModel $log
	 * @return FindTranResponse
	 */
	public function findTran(LogProviderRequestModel $log)
	{
		$request = new FindTranRequest($log->onlyAttributes([
			'request_id', 'command', 'input', 'provider_tran_id',
		]));

		return $this->providerService($log->provider_key)->findTran($request);
	}

	/**
	 * lay doi tuong ProviderService
	 *
	 * @param string $provider
	 * @return ProviderService
	 */
	protected function providerService($provider)
	{
		return ProductFactory::providerService($provider);
	}

}