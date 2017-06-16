<?php namespace App\Product\Job;

use App\Invoice\Library\CreateInvoiceOptions;
use App\Invoice\Library\CreateInvoiceOrderOptions;
use App\Invoice\Library\OrderStatus;
use App\Product\Library\Cart\Cart;
use App\Product\Library\Cart\Item as CartItem;
use App\Product\Model\OrderModel as OrderModel;
use App\User\Model\UserModel as UserModel;
use App\Invoice\InvoiceFactory as InvoiceFactory;
use App\Invoice\Model\InvoiceModel as InvoiceModel;
use App\Invoice\Model\InvoiceOrderModel as InvoiceOrderModel;

class CreateInvoiceOrder extends \Core\Base\Job
{
	/**
	 * Doi tuong Cart
	 *
	 * @var Cart
	 */
	protected $cart;

	/**
	 * Thong tin contact
	 *
	 * @var array
	 */
	protected $contact;

	/**
	 * Doi tuong UserModel
	 *
	 * @var UserModel
	 */
	protected $user;


	public static function _t()
	{
		$cart_storage = new \App\Product\Library\Cart\Storage();

		$cart = new Cart($cart_storage);
		$cart->add(new CartItem([
			'product' => \App\Product\Model\ProductModel::find(60),
			'price' => 58000,
			'quantity' => 2,
			'amount' => 116000,
		]));
		$cart->add(new CartItem([
			'product' => \App\Product\Model\ProductModel::find(4),
			'price' => 55000,
			'quantity' => 1,
			'amount' => 55000,
			'options' => ['account' => '0123456789'],
		]));

		$user = UserModel::find(1);

		$me = new static($cart, $user);

		$v = $me->handle();

		pr($v, 0);
	}

	/**
	 * Khoi tao doi tuong
	 *
	 * @param Cart      $cart
	 * @param UserModel $user
	 * @param array     $contact
	 */
	public function __construct(Cart $cart, array $contact, UserModel $user)
	{
		$this->cart = $cart;
		$this->contact = $contact;
		$this->user = $user;
	}

	/**
	 * Thuc hien xu ly
	 *
	 * @return array
	 */
	public function handle()
	{
		$orders = [];

		$invoice = $this->createInvoice();

		foreach ($this->cart->items() as $cart_item)
		{
			$invoice_order = $this->createInvoiceOrder($invoice, $cart_item);

			$orders[] = $this->createOrder($invoice_order, $cart_item);
		}

		return compact('invoice', 'orders');
	}

	/**
	 * Tao invoice
	 *
	 * @return InvoiceModel
	 */
	protected function createInvoice()
	{
		$options = new CreateInvoiceOptions([
			'amount'       => $this->getInvoiceAmount(),
			'info_contact' => $this->contact,
			'user_id'      => $this->user->id,
		]);

		return InvoiceFactory::invoice()->create($options);
	}

	/**
	 * Tao invoice_order
	 *
	 * @param InvoiceModel $invoice
	 * @param CartItem     $cart_item
	 * @return InvoiceOrderModel
	 */
	protected function createInvoiceOrder(InvoiceModel $invoice, CartItem $cart_item)
	{
		$option = [
				'invoice'    	=> $invoice,
				'service_key'   => $this->makeInvoiceServiceKey($cart_item->getProduct()->type),
				'amount'        => $cart_item->getAmount(),
				'profit'        => $cart_item->getProfit(),
				'amount_par'	=> $cart_item->amount_par,
				'order_status'  => OrderStatus::PENDING,
				'order_options' => $this->makeInvoiceOrderOptions($cart_item),
		];
		// sua doithem cot product_id va qty de biet san pham va so luong de thong ke

		if(isset($cart_item->getProduct()->id)) {
			$option['product_id'] = $cart_item->getProduct()->id;
			$option['qty'] = $cart_item->quantity ? $cart_item->quantity : 1;
		}
		$options = new CreateInvoiceOrderOptions($option);

		return InvoiceFactory::invoiceOrder()->create($options);
	}

	/**
	 * Tao order
	 *
	 * @param InvoiceOrderModel $invoice_order
	 * @param CartItem          $cart_item
	 * @return OrderModel
	 */
	protected function createOrder(InvoiceOrderModel $invoice_order, CartItem $cart_item)
	{
		return OrderModel::create([
			'invoice_id'       => $invoice_order->invoice_id,
			'invoice_order_id' => $invoice_order->id,
			'type'             => $cart_item->getProduct()->type,
			'product_id'       => $cart_item->getProduct()->id,
			'price_original'   => $cart_item->getProduct()->price_original,
			'price_par'        => $cart_item->getProduct()->price_par,
			'price'            => $cart_item->getPrice(),
			'quantity'         => $cart_item->getQuantity(),
			'amount'           => $cart_item->getAmount(),
			'profit'           => $cart_item->getProfit(),
			'amount_par'       => $cart_item->amount_par,
			'account'          => $cart_item->getOption('account'),
			'status'           => OrderStatus::PENDING,
			'user_id'          => $this->user->id,
		]);
	}

	/**
	 * Lay invoice amount
	 *
	 * @return float
	 */
	protected function getInvoiceAmount()
	{
		return $this->cart->total();
	}

	/**
	 * Tao key cua InvoiceService
	 *
	 * @param string $type
	 * @return string
	 */
	protected function makeInvoiceServiceKey($type)
	{
		return 'ProductOrder'.studly_case($type);
	}

	/**
	 * Tao order_options luu tru trong invoice_order
	 *
	 * @param CartItem $cart_item
	 * @return array
	 */
	protected function makeInvoiceOrderOptions(CartItem $cart_item)
	{
		return [
			'type'         => $cart_item->getProduct()->type,
			'product_id'   => $cart_item->getProduct()->id,
			'product_name' => $cart_item->getProduct()->name,
			'quantity'     => $cart_item->getQuantity(),
			'account'      => $cart_item->getOption('account'),
		];
	}
}