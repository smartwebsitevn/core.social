<?php namespace App\Product\Service;

use App\Invoice\Library\OrderStatus;
use App\Product\ProductFactory as ProductFactory;
use App\Product\Job\RetakeOrderCards;
use App\Product\Model\ProductModel as ProductModel;
use App\Product\Model\OrderModel as OrderModel;
use App\Product\Model\OrderCardsModel as OrderCardsModel;
use App\Product\Job\ActiveOrder\Factory as ActiveOrder;
use App\Product\Job\ActiveOrder\ActiveOrderException;
use App\LogActivity\LogActivityFactory as LogActivityFactory;
use App\LogActivity\Library\ActivityLogger as ActivityLogger;
use App\LogActivity\Library\ActivityOwner as ActivityOwner;
use App\LogActivity\Model\LogActivityModel as LogActivityModel;
use App\User\UserFactory as UserFactory;
use TF\Support\Collection;

class OrderService
{
	/**
	 * Kich hoat order
	 *
	 * @param OrderModel $order
	 * @param array      $options
	 * 		$options = [
	 * 			'owner' => null,
	 * 		];
	 * @throws ActiveOrderException
	 */
	public function active(OrderModel $order, array $options = [])
	{
		(new ActiveOrder($order, $options))->handle();
	}

	/**
	 * Hoan thanh order
	 *
	 * @param OrderModel $order
	 * @param array      $options
	 * 		$options = [
	 * 			'owner' => null,
	 * 		];
	 */
	public function complete(OrderModel $order, array $options = [])
	{
		$order->updateStatus(OrderStatus::COMPLETED);

		$this->logActivity('completed', $order, array_get($options, 'owner'));

		$this->email($order, 'product_order_completed');
	}

	/**
	 * Huy bo order
	 *
	 * @param OrderModel $order
	 * @param array      $options
	 * 		$options = [
	 * 			'owner' => null,
	 * 		];
	 */
	public function cancel(OrderModel $order, array $options = [])
	{
		$order->updateStatus(OrderStatus::CANCELED);

		$this->logActivity('canceled', $order, array_get($options, 'owner'));

		$this->email($order, 'product_order_canceled');
	}

	/**
	 * Lay setting
	 *
	 * @param string $key
	 * @param mixed  $default
	 * @return mixed
	 */
	public function setting($key = null, $default = null)
	{
		$setting = module_get_setting('product_order');

		return array_get($setting, $key, $default);
	}

	/**
	 * Gui email thong bao
	 *
	 * @param OrderModel $order
	 * @param string     $email_key
	 * @return bool
	 */
	public function email(OrderModel $order, $email_key)
	{
		$contact = $order->invoice->info_contact;

		if ( ! $to = array_get($contact, 'email')) return false;

		return mod('email')->send($email_key, $to, [
			'order_id' => $order->invoice_order_id,
		]);
	}

	/**
	 * Lay doi tuong ActivityLogger
	 *
	 * @return ActivityLogger
	 */
	public function activityLogger()
	{
		return LogActivityFactory::logger('ProductOrder');
	}

	/**
	 * Log activity
	 *
	 * @param string        $action
	 * @param OrderModel    $order
	 * @param ActivityOwner $owner
	 * @param array         $context
	 * @return LogActivityModel
	 */
	public function logActivity($action, OrderModel $order, ActivityOwner $owner = null, array $context = [])
	{
		$context['order'] = $order->getAttributes();

		return $this->activityLogger()->log($action, $order->id, $owner, $context);
	}

	/**
	 * Lay danh sach cards cua order
	 *
	 * @param OrderModel $order
	 * @return Collection
	 */
	public function getCards(OrderModel $order)
	{
		return OrderCardsModel::listOfOrder($order->id);
	}

	/**
	 * Lay danh sach cards cua order cho user
	 *
	 * @param OrderModel $order
	 * @return Collection
	 */
	public function getCardsForUser(OrderModel $order)
	{
		return $this->getCards($order)->take($order->quantity);
	}

	/**
	 * Lay lai ma the cua order tu nha cung cap
	 *
	 * @return array
	 */
	public function retakeCards(OrderModel $order)
	{
		return (new RetakeOrderCards($order))->handle();
	}

	/**
	 * Xu ly order amount
	 *
	 * @param array $args
	 *  $args = [
	 *		'product' 	=> ***,
	 *		'quantity' 	=> ***,
	 *		'user' 		=> '',
	 * 	];
	 * @return array
	 *  [
	 * 		'discount' 	=> ***,
	 * 		'price' 	=> ***,
	 * 		'quantity' 	=> ***,
	 * 		'amount' 	=> ***,
	 * 	]
	 */
	public function makeOrderAmount(array $args)
	{
		$product 	= $args['product'];
		$quantity	= $args['quantity'];
		$user 		= array_get($args, 'user', UserFactory::auth()->user());

		$discount = ProductFactory::product()->getDiscount($product, $user->user_group);

		$price = $product->price * (100 - $discount) * 0.01;

		$amount = $price * $quantity;

		$profit = ($price - $product->price_original) * $quantity;

		$amount_par = $product->price_par * $quantity;

		return compact('discount', 'price', 'quantity', 'amount', 'profit', 'amount_par');
	}
}