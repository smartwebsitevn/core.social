<?php namespace App\Product\Job\ViewOrder;

use App\Product\ProductFactory as ProductFactory;
use App\Product\Model\OrderModel as OrderModel;
use App\Product\Model\LogProviderRequestModel as LogProviderRequestModel;
use TF\Support\Collection;

class Factory extends \Core\Base\Job
{
	/**
	 * Doi tuong OrderModel
	 *
	 * @var OrderModel
	 */
	protected $order;

	/**
	 * Doi tuong Handler
	 *
	 * @var Handler
	 */
	protected $handler;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param OrderModel $order
	 */
	public function __construct(OrderModel $order)
	{
		$this->order = $order;

		$this->handler = $this->makeHandler();
	}

	/**
	 * Tao doi tuong Handler
	 *
	 * @return Handler|null
	 */
	protected function makeHandler()
	{
		$type = studly_case($this->getOrder()->type);

		$class = 'App\Product\Job\ViewOrder\Handler\\'.$type;

		return class_exists($class) ? new $class($this) : null;
	}

	/**
	 * Goi Handler
	 *
	 * @return array
	 */
	protected function dispatchHandler()
	{
		return $this->handler ? $this->handler->handle() : [];
	}

	/**
	 * Thuc hien xu ly
	 *
	 * @return array
	 */
	public function handle()
	{
		$result = $this->dispatchHandler();

		$result['order'] = $this->order;

		if (get_area() == 'admin')
		{
			$result['log_activities'] = $this->listLogActivities();

			$result['log_provider_requests'] = $this->listLogProviderRequests();
		}

		return $result;
	}

	/**
	 * Lay danh sach LogActivity
	 *
	 * @return Collection
	 */
	protected function listLogActivities()
	{
		return ProductFactory::order()->activityLogger()->listLogs([
			'key' => $this->order->id,
		]);
	}

	/**
	 * Lay danh sach LogProviderRequest
	 *
	 * @return Collection
	 */
	protected function listLogProviderRequests()
	{
		return LogProviderRequestModel::listOfInvoiceOrder($this->order->invoice_order_id);;
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