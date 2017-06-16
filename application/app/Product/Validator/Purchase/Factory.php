<?php namespace App\Product\Validator\Purchase;

use App\Product\ProductFactory as ProductFactory;
use App\Product\Model\ProductModel;
use App\Product\Validator\Purchase\Job\ValidatePhone;
use App\Product\Validator\Purchase\Job\ValidateQuantity;
use App\Product\Validator\Purchase\Base\OrderValidator;
use Core\Support\Number;

class Factory
{
	/**
	 * Doi tuong Product
	 *
	 * @var ProductModel
	 */
	protected $product;

	/**
	 * Options
	 *
	 * @var Options
	 */
	protected $options;

	/**
	 * Doi tuong validator theo loai order
	 *
	 * @var OrderValidator
	 */
	protected $order_validator;


	public static function _t()
	{
		$product_id = 258;
		$product_id = 262;
		$product_id = 248;
		$product_id = 261;
		$product_id = 121;
		$product = ProductModel::find($product_id);

		$options = new Options([
			'quantity' => 1,
//			'ship' => [],
			'amount' => '10000',
			'phone' => '01678456789',
		]);

		$me = new static($product, $options);

		$v = $me->validate();

		pr($me);
	}

	/**
	 * Factory constructor.
	 *
	 * @param ProductModel $product
	 * @param Options $options
	 */
	public function __construct(ProductModel $product, Options $options)
	{
		$this->product = $product;

		$this->options = $options;

		$this->order_validator = $this->makeOrderValidator();
	}

	/**
	 * Tao doi tuong OrderValidator
	 *
	 * @return OrderValidator
	 */
	protected function makeOrderValidator()
	{
		$type = studly_case($this->product->type);

		$class = 'App\Product\Validator\Purchase\OrderValidator\\'.$type;

		return new $class($this);
	}

	/**
	 * Thuc hien validate
	 *
	 * @throws PurchaseException
	 */
	public function validate()
	{
		if ( ! $this->product->can('buy'))
		{
		    $this->throwException(Error::CAN_NOT_BUY_PRODUCT);
		}

		$this->getOrderValidator()->validate();
	}

	/**
	 * Validate phone
	 *
	 * @param string $phone
	 * @throws PurchaseException
	 */
	public function validatePhone($phone)
	{
		(new ValidatePhone($this, $phone))->handle();
	}

	/**
	 * Validate phone
	 *
	 * @param int $quantity
	 * @throws PurchaseException
	 */
	public function validateQuantity($quantity)
	{
		(new ValidateQuantity($this, $quantity))->handle();
	}

	/**
	 * Kiem tra amount
	 *
	 * @param float $amount
	 * @param float $amount_min
	 * @param float $amount_max
	 * @throws PurchaseException
	 */
	public function validateAmount($amount, $amount_min, $amount_max)
	{
		if (
			$amount <= 0
			|| ! Number::validAmountLimit($amount, $amount_min, $amount_max ?: null)
		)
		{
			$this->throwException(Error::AMOUNT_INVALID);
		}
	}

	/**
	 * Kiem tra account
	 *
	 * @param string $account
	 * @throws PurchaseException
	 */
	public function validateAccount($account)
	{
		if ( ! $account)
		{
		    $this->throwException(Error::ACCOUNT_INVALID);
		}
	}

	/**
	 * Kiem tra thong tin ship
	 *
	 * @param array $ship
	 * @throws PurchaseException
	 */
	public function validateShip(array $ship)
	{
		if (empty($ship))
		{
		    $this->throwException(Error::SHIP_INVALID);
		}
	}

	/**
	 * Throw exception
	 *
	 * @param string $error
	 * @param array  $replace
	 * @throws PurchaseException
	 */
	public function throwException($error, $replace = [])
	{
		$message = ProductFactory::service()->errorLang($error, $replace);

		throw new PurchaseException($error, $message);
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
	 * Lay options
	 *
	 * @return Options
	 */
	public function getOptions()
	{
		return $this->options;
	}

	/**
	 * Lay doi tuong OrderValidator
	 *
	 * @return OrderValidator
	 */
	public function getOrderValidator()
	{
		return $this->order_validator;
	}

}