<?php namespace App\Product\Job\ActiveOrder;

use App\Invoice\Library\OrderStatus;
use App\LogActivity\Model\LogActivityModel as LogActivityModel;
use App\Product\ProductFactory as ProductFactory;
use App\Product\Job\OrderCompletedInProvider;
use App\Product\Library\Provider\TranResponse;
use App\Product\Model\OrderModel as OrderModel;
use App\Product\Model\ProductModel as ProductModel;
use Closure;

class Factory extends \Core\Base\Job
{
	/**
	 * Doi tuong OrderModel
	 *
	 * @var OrderModel
	 */
	protected $order;

	/**
	 * Options
	 *
	 * @var array
	 */
	protected $options;

	/**
	 * Doi tuong Handler
	 *
	 * @var Handler
	 */
	protected $handler;


	public static function _t()
	{
		$order = OrderModel::find(4);

		$me = new static($order);

		$me->handle();

		pr($me, 0);
	}

	/**
	 * Khoi tao doi tuong
	 *
	 * @param OrderModel $order
	 * @param array      $options
	 * 		$options = [
	 * 			'owner' => null,
	 * 		];
	 */
	public function __construct(OrderModel $order, array $options = [])
	{
		$this->order = $order;

		$this->options = $options;

		$this->handler = $this->makeHandler();
	}

	/**
	 * Tao doi tuong Handler
	 *
	 * @return Handler
	 */
	protected function makeHandler()
	{
		$type = studly_case($this->getOrder()->type);

		$class = 'App\Product\Job\ActiveOrder\Handler\\'.$type;

		return new $class($this);
	}

	/**
	 * Thuc hien xu ly
	 *
	 * @throws ActiveOrderException
	 */
	public function handle()
	{
		$this->logActivity('active');

		$this->updateStatusProcessing();

		// Neu order da hoan thanh truoc do
		if ($this->orderCompletedInProvider())
		{
		    return $this->completed();
		}

		// Thuc hien request moi
		$response = $this->request();

		return $response->status
			? $this->success($response)
			: $this->error($response);
	}

	/**
	 * Cap nhat trang thai processing cho order
	 */
	protected function updateStatusProcessing()
	{
		if ($this->order->status == OrderStatus::PENDING)
		{
		    $this->order->updateStatus(OrderStatus::PROCESSING);
		}
	}

	/**
	 * Kiem tra order da hoan thanh ben nha cung cap hay chua
	 *
	 * @return bool
	 */
	protected function orderCompletedInProvider()
	{
		return (new OrderCompletedInProvider($this->order))->handle();
	}

	/**
	 * Gui request
	 *
	 * @return TranResponse
	 */
	protected function request()
	{
		return $this->handler->request();
	}

	/**
	 * Xu ly response error
	 *
	 * @param TranResponse $response
	 * @throws ActiveOrderException
	 */
	protected function error(TranResponse $response)
	{
		$this->handler->error($response);

		throw new ActiveOrderException($response->error);
	}

	/**
	 * Xu ly response success
	 *
	 * @param TranResponse $response
	 */
	protected function success(TranResponse $response)
	{
		$this->completeOrder(function() use ($response)
		{
			$this->handler->success($response);
		});
	}

	/**
	 * Xu ly du lieu khi order da hoan thanh truoc do
	 */
	protected function completed()
	{
		$this->completeOrder(function()
		{
			$this->handler->completed();
		});
	}

	/**
	 * Hoan thanh order
	 *
	 * @param Closure $handler
	 */
	protected function completeOrder(Closure $handler)
	{
		$this->order->updateStatus(OrderStatus::COMPLETED);

		$this->logActivity('completed');

		$handler();

		$this->sendEmail();
	}

	/**
	 * Gui email thong bao
	 */
	protected function sendEmail()
	{
		ProductFactory::order()->email($this->order, 'product_order_completed');
	}

	/**
	 * Log activity
	 *
	 * @param string $action
	 * @return LogActivityModel
	 */
	protected function logActivity($action)
	{
		return ProductFactory::order()->logActivity($action, $this->order, $this->getOption('owner'));
	}

	/**
	 * Lay thong tin order
	 *
	 * @return OrderModel
	 */
	public function getOrder()
	{
		return $this->order;
	}

	/**
	 * Lay thong tin product
	 *
	 * @return ProductModel
	 */
	public function getProduct()
	{
		return $this->order->product;
	}

	/**
	 * Lay option
	 *
	 * @param string $key
	 * @param mixed  $default
	 * @return mixed
	 */
	public function getOption($key = null, $default = null)
	{
		return array_get($this->options, $key, $default);
	}
}