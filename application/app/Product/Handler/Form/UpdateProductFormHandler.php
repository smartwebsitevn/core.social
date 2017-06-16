<?php namespace App\Product\Handler\Form;

use App\Currency\CurrencyFactory;
use App\Product\Model\ProductModel;
use App\Product\ProductFactory;
use App\User\UserFactory;
use Core\Base\FormHandler;
use Core\Support\Number;

class UpdateProductFormHandler extends FormHandler
{
	/**
	 * Doi tuong ProductModel
	 *
	 * @var ProductModel
	 */
	protected $product;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param ProductModel $product
	 * @param array        $input
	 */
	public function __construct(ProductModel $product, array $input = null)
	{
		parent::__construct($input);

		$this->product = $product;

		$this->handleInput();
	}

	/**
	 * Xu ly input
	 */
	protected function handleInput()
	{
		foreach ($this->input as $key => &$value)
		{
			if (in_array($key, ['price', 'price_original']))
			{
				$value = Number::handleAmountInput($value);
			}
		}
	}

	/**
	 * Lay rules
	 *
	 * @return array
	 */
	public function rules()
	{
		$id = (int) $this->product->id;

		return [
			'key' => 'required|alpha_dash|is_unique[product,key,'.$id.']',
			'price' => 'required',
			'price_original' => 'trim',
		];
	}

	/**
	 * Lay cac bien cho phep update
	 *
	 * @return array
	 */
	public function allowedParams()
	{
		return ['key', 'price', 'price_original', 'prices_currency', 'discounts', 'keys_connection'];
	}

	/**
	 * Lay cac bien can xu ly
	 *
	 * @return array
	 */
	public function params()
	{
		return array_intersect($this->allowedParams(), array_keys($this->input));
	}

	/**
	 * Kiem tra param co can xu ly hay khong
	 *
	 * @param $param
	 * @return bool
	 */
	public function hasParams($param)
	{
		return in_array($param, $this->params());
	}

	/**
	 * Gan rules validate
	 *
	 * @return array
	 */
	public function validation()
	{
		$rules = array_only($this->rules(), $this->params());

		if ($this->hasParams('prices_currency'))
		{
			$rules = array_merge($rules, $this->makePricesCurrencyRules());
		}

		if ($this->hasParams('discounts'))
		{
			$rules = array_merge($rules, $this->makeDiscountsRules());
		}

		if ($this->hasParams('keys_connection'))
		{
		    $rules = array_merge($rules, $this->makeKeysConnectionRules());
		}

		$this->setValidationRules($rules);

		return array_keys($rules);
	}

	/**
	 * Tao rules cho prices_currency
	 *
	 * @return array
	 */
	protected function makePricesCurrencyRules()
	{
		$rules = [];

		$currency_ids = CurrencyFactory::currency()->lists()->lists('id');

		$currency_ids = array_intersect($currency_ids, array_keys($this->input('prices_currency')));

		foreach ($currency_ids as $id)
		{
			$rules["prices_currency[{$id}]"] = [
				'label' => lang('price'),
				'rules' => 'trim',
			];
		}

		return $rules;
	}

	/**
	 * Tao rules cho discounts
	 *
	 * @return array
	 */
	protected function makeDiscountsRules()
	{
		$rules = [];

		$user_group_ids = UserFactory::userGroup()->lists()->lists('id');

		$user_group_ids = array_intersect($user_group_ids, array_keys($this->input('discounts')));

		foreach ($user_group_ids as $id)
		{
			$rules["discounts[{$id}]"] = [
				'label' => lang('discount'),
				'rules' => 'greater_than[-1]|less_than[100]',
			];
		}

		return $rules;
	}

	/**
	 * Tao rules cho keys_connection
	 *
	 * @return array
	 */
	protected function makeKeysConnectionRules()
	{
		$rules = [];

		$provider_keys = ProductFactory::providerManager()->listInstalled()->lists('key');

		$provider_keys = array_intersect($provider_keys, array_keys($this->input('keys_connection')));

		foreach ($provider_keys as $provider_key)
		{
			$rules["keys_connection[{$provider_key}]"] = [
				'label' => lang('key'),
				'rules' => 'required|alpha_dash',
			];
		}

		return $rules;
	}

	/**
	 * Xu ly form khi du lieu hop le
	 *
	 * @return mixed
	 */
	public function submit()
	{
		if ( ! $this->validateForm())
		{
			return array_merge(
				$this->errors,
				['complete' => false]
			);
		}

		if (count($data = $this->data()))
		{
			ProductFactory::product()->edit($this->product, $data);
		}
	}

	/**
	 * Validate form
	 *
	 * @return bool
	 */
	protected function validateForm()
	{
		if ($this->hasParams('price') && $this->input('price') <= 0)
		{
			$this->errors['price'] = lang('notice_value_invalid', lang('price'));

			return false;
		}

		return true;
	}

	/**
	 * Lay form data
	 *
	 * @return array
	 */
	public function data()
	{
		$data = $this->inputOnly($this->params());

		if (array_key_exists('price_original', $data))
		{
			$data['price_original'] = $this->getPriceOriginalValue();
		}

		if (array_key_exists('prices_currency', $data))
		{
			$data['prices_currency'] = array_filter($this->mergeValue(
				$this->product->prices_currency,
				$this->getPricesCurrencyValue()
			));
		}

		if (array_key_exists('discounts', $data))
		{
			$data['discounts'] = array_filter($this->mergeValue(
				$this->product->discounts,
				$this->getDiscountsValue()
			));
		}

		if (array_key_exists('keys_connection', $data))
		{
			$data['keys_connection'] = $this->mergeValue(
				$this->product->keys_connection,
				$data['keys_connection']
			);
		}

		return $data;
	}

	/**
	 * Lay gia tri cua price_original
	 *
	 * @return float
	 */
	protected function getPriceOriginalValue()
	{
		$price_original = $this->input('price_original');

		return $price_original > 0 ? $price_original : $this->product->price;
	}

	/**
	 * Lay prices_currency
	 *
	 * @return array
	 */
	protected function getPricesCurrencyValue()
	{
		$list = $this->input('prices_currency', []);

		foreach ($list as &$value)
		{
			$value = max(0, Number::handleAmountInput($value));
		}

		return $list;
	}

	/**
	 * Lay discounts
	 *
	 * @return array
	 */
	protected function getDiscountsValue()
	{
		$list = $this->input('discounts', []);

		foreach ($list as &$value)
		{
			$value = min(max(0, (float) $value), 100);
		}

		return $list;
	}

	/**
	 * Gop gia tri
	 *
	 * @param array $old
	 * @param array $new
	 * @return array
	 */
	protected function mergeValue(array $old, array $new)
	{
		foreach ($new as $key => $value)
		{
			$old[$key] = $value;
		}

		return $old;
	}

}