<?php namespace App\Product\Handler\Form;

use App\Product\Job\CreateInvoiceOrder;
use App\Product\Library\ProductType;
use Core\Base\FormHandler;
use App\Product\Model\ProductModel;
use App\Product\ProductFactory as ProductFactory;
use App\Product\Library\Cart\CartSingle;
use App\Product\Validator\Purchase\Factory as PurchaseValidator;
use App\Product\Validator\Purchase\Options as PurchaseOptions;
use App\Product\Validator\Purchase\PurchaseException as PurchaseException;
use App\Invoice\Model\InvoiceModel as InvoiceModel;
use App\User\UserFactory as UserFactory;
use App\User\Model\UserModel as UserModel;

class Purchase extends FormHandler
{
	/**
	 * Doi tuong Product
	 *
	 * @var ProductModel
	 */
	protected $product;

	/**
	 * Doi tuong UserModel
	 *
	 * @var UserModel
	 */
	protected $user;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param ProductModel $product
	 * @param array   $input
	 */
	public function __construct(ProductModel $product, array $input = null)
	{
		$this->product = $product;

		$this->user = UserFactory::auth()->user();

		parent::__construct($input);
	}

	/**
	 * Gan rules validate
	 *
	 * @return array
	 */
	public function validation()
	{
		$params = $this->getParams();

		$rules = array_only($this->getRules(), $params);

		$this->setValidationRules($rules);

		return $params;
	}

	/**
	 * Lay rules
	 *
	 * @return array
	 */
	protected function getRules()
	{
		$rules = [
			'quantity' => 'required',
			'amount'   => 'required',
			'phone'    => 'required',
			'account'  => 'required',
			'ship'     => 'required',
			'security_code' => 'required|captcha[four]',
		];
		$rules=array_merge($rules, $this->makeContactRules());
		//pr($rules);
		return $rules;
	}

	/**
	 * Tao contact rules
	 *
	 * @return array
	 */
	protected function makeContactRules()
	{
		$rules = [];

		foreach ($this->customerContact()->getRules() as $key => $options)
		{
			$field = "contact[{$key}]";

			$rules[$field] = array_merge($options, compact('field'));
		}

		return $rules;
	}

	/**
	 * Lay cac bien can xu ly
	 *
	 * @return array
	 */
	protected function getParams()
	{
		return array_merge($this->getOrderParams(), $this->getContactParams());
	}

	/**
	 * Lay order params
	 *
	 * @return array
	 */
	protected function getOrderParams()
	{
		switch ($this->product->type)
		{
			case ProductType::CARD:
			{
				return ['quantity'];
			}

			case ProductType::TOPUP_MOBILE:
			{
				return ['phone'];
			}

			case ProductType::TOPUP_MOBILE_POST:
			{
				return ['phone', 'amount'];
			}

			case ProductType::TOPUP_GAME:
			{
				return ['account'];
			}

			case ProductType::SHIP:
			{
				//return ['quantity', 'ship'];
				return ['quantity'];
			}
		}

		return [];
	}

	/**
	 * Lay contact params
	 *
	 * @return array
	 */
	protected function getContactParams()
	{
		if (UserFactory::auth()->logged()) return [];

		return $this->hasInputContact() ? $this->contactParamsName() : [];
	}

	/**
	 * Kiem tra co ton tai contact trong input hay khong
	 *
	 * @return bool
	 */
	protected function hasInputContact()
	{
		return array_key_exists('contact', $this->input);
	}

	/**
	 * Lay danh sach ten cac bien contact
	 *
	 * @return array
	 */
	protected function contactParamsName()
	{
		return array_map(function($param)
		{
			return "contact[{$param}]";
		}, $this->customerContact()->getParams());
	}

	/**
	 * Xu ly form khi du lieu hop le
	 */
	public function submit()
	{
		if ( ! $this->validatePurchase($error))
		{
			return [
				'complete' => false,
				'order'    => $error,
			];
		}

		$this->makeCart();

		return $this->checkout();
	}

	/**
	 * Thuc hien validate mua hang
	 *
	 * @param string $error
	 * @return bool
	 */
	protected function validatePurchase(&$error = null)
	{
		try
		{
			$this->newPurchaseValidator()->validate();

			return true;
		}
		catch (PurchaseException $e)
		{
			$error = $e->getMessage();

			return false;
		}
	}

	/**
	 * Tao doi tuong PurchaseValidator
	 *
	 * @return PurchaseValidator
	 */
	protected function newPurchaseValidator()
	{
		$options = new PurchaseOptions($this->input);

		return new PurchaseValidator($this->product, $options);
	}

	/**
	 * Tao Cart
	 *
	 * @return CartSingle
	 */
	protected function makeCart()
	{
		$quantity = 1;
		$options = [];

		$this->makeCartData($quantity, $options);

		$this->shopping()->cart()->destroy();

		$this->shopping()->addProduct($this->product, $quantity, $options);
	}

	/**
	 * Tao cart data
	 *
	 * @param int   $quantity
	 * @param array $options
	 */
	protected function makeCartData(&$quantity, &$options)
	{
		switch ($this->product->type)
		{
			case ProductType::CARD:
			{
				$quantity = (int) $this->input('quantity');
				return;
			}

			case ProductType::TOPUP_MOBILE:
			{
				$quantity 	= 1;
				$options 	= ['account' => $this->input('phone')];
				return;
			}

			case ProductType::TOPUP_MOBILE_POST:
			{
				$quantity 	= currency_handle_input($this->input('amount'));
				$options 	= ['account' => $this->input('phone')];
				return;
			}

			case ProductType::TOPUP_GAME:
			{
				$quantity 	= 1;
				$options 	= ['account' => $this->input('account')];
				return;
			}

			case ProductType::SHIP:
			{
				$quantity 	= (int) $this->input('quantity');
				$options 	= ['ship' => $this->input('ship')];
				return;
			}
		}
	}

	/**
	 * Thuc hien checkout
	 *
	 * @return string
	 */
	protected function checkout()
	{
		if (count($contact = $this->checkoutContact()))
		{
			$invoice = $this->createOrder($contact);

			$this->shopping()->cart()->destroy();

			return $invoice->url('payment');
		}

		return site_url('product_order/checkout');
	}

	/**
	 * Xu ly checkout contact
	 *
	 * @return array
	 */
	protected function checkoutContact()
	{
		// Guest
		if (count($this->getContactParams()))
		{
			$contact = $this->getContactData();

			$this->customerContact()->updateContact($contact);

			return $contact;
		}

		// User
		if ($this->user->id)
		{
			$params = $this->customerContact()->getParams();

		    return $this->user->onlyAttributes($params);
		}

		return [];
	}

	/**
	 * Lay contact data
	 *
	 * @return array
	 */
	protected function getContactData()
	{
		return array_only($this->input('contact'), $this->customerContact()->getParams());
	}

	/**
	 * Tao order
	 *
	 * @param array $contact
	 * @return InvoiceModel
	 */
	protected function createOrder(array $contact)
	{
		$cart = $this->shopping()->cart();

		$res = (new CreateInvoiceOrder($cart, $contact, $this->user))->handle();

		return $res['invoice'];
	}

	/**
	 * Lay order amount
	 *
	 * @return array
	 */
	public function orderAmount()
	{
		$quantity = 1;
		$options = [];

		$this->makeCartData($quantity, $options);

		return ProductFactory::order()->makeOrderAmount([
			'product'  => $this->product,
			'quantity' => $quantity,
			'user'     => $this->user,
		]);
	}

	/**
	 * Lay form view data
	 *
	 * @return array
	 */
	public function form()
	{
		$product = $this->product;

		$quantity_max = ProductFactory::order()->setting('quantity_max');

		$url_purchase = site_url('product_order/purchase/'.$product->id);

		$contact_required = ! $this->user->id;
		$contact = ProductFactory::customerContact()->getContact();

		$url_load_amount = site_url('product_order/api_order_amount');

		return compact(
			'product', 'quantity_max', 'url_purchase',
			'contact_required', 'contact', 'url_load_amount'
		);
	}

	/**
	 * Lay doi tuong Product
	 *
	 * @return ProductModel
	 */
	public function getProduct()
	{
		return $this->product;
	}

	/**
	 * Lay doi tuong CustomerContact
	 *
	 * @return \App\Product\Service\CustomerContactService
	 */
	protected function customerContact()
	{
		return ProductFactory::customerContact();
	}

	/**
	 * Lay doi tuong Shopping
	 *
	 * @return \App\Product\Service\ShoppingService
	 */
	protected function shopping()
	{
		return ProductFactory::shopping();
	}

}