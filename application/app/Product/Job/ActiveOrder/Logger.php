<?php namespace App\Product\Job\ActiveOrder;

use App\Product\Model\OrderModel as OrderModel;
use App\Product\Library\Provider\TranRequest;
use App\Product\Library\Provider\TranResponse;
use App\Product\Model\LogProviderRequestModel;
use Core\Support\Arr;

class Logger
{
	/**
	 * Doi tuong OrderModel
	 *
	 * @var OrderModel
	 */
	protected $order;


	/**
	 * Logger constructor.
	 *
	 * @param OrderModel $order
	 */
	public function __construct(OrderModel $order)
	{
		$this->order = $order;
	}

	/**
	 * Log request
	 *
	 * @param string      $provider_key
	 * @param string      $command
	 * @param TranRequest $request
	 * @return LogProviderRequestModel
	 */
	public function logRequest($provider_key, $command, TranRequest $request)
	{
		$log = LogProviderRequestModel::create([
			'provider_key'    => $provider_key,
			'command'         => $command,
			'request_id'      => $request->request_id,
			'input'           => $request->except('request_id'),
			'invoice_order_id' => $this->getOrder()->invoice_order_id,
		]);

		$this->order->update(['last_provider_request_id' => $log->id]);

		return $log;
	}

	/**
	 * Log response
	 *
	 * @param LogProviderRequestModel $log
	 * @param TranResponse       $response
	 * @return LogProviderRequestModel
	 */
	public function logResponse(LogProviderRequestModel $log, TranResponse $response)
	{
		$data = $this->makeLogResponseData($response);

		$log->update($data);

		return $log;
	}

	/**
	 * Tao log response data
	 *
	 * @param TranResponse $response
	 * @return array
	 */
	protected function makeLogResponseData(TranResponse $response)
	{
		$keys = ['status', 'provider_tran_id', 'provider_tran', 'error', 'balance'];

		$data = array_merge($response->only($keys), [
			'output'    => $response->except($keys),
			'completed' => now(),
		]);

		return $data;
	}

	/**
	 * Lay OrderModel
	 *
	 * @return OrderModel
	 */
	public function getOrder()
	{
		return $this->order;
	}
}