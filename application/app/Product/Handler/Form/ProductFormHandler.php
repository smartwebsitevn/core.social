<?php namespace App\Product\Handler\Form;

use App\Currency\CurrencyFactory;
use App\File\Model\FileModel;
use App\Product\Library\ProductType;
use App\Product\Model\ProductModel;
use App\Product\ProductFactory;
use App\User\UserFactory;
use Core\Base\FormHandler;
use Core\Support\Number;

class ProductFormHandler extends FormHandler
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
			if (in_array($key, ['price', 'price_original', 'price_par']))
			{
				$value = Number::handleAmountInput($value);
			}
		}
	}

	/**
	 * Lay params
	 *
	 * @return array
	 */
	protected function params()
	{
		return [
			'name', 'key', 'provider_key', 'price', 'price_original', 'price_par',
			'prices_currency', 'discounts', 'cat_id', 'desc', 'status', 'sort_order',
		];
	}

	/**
	 * Lay rules
	 *
	 * @return array
	 */
	protected function rules()
	{
		$id = (int) $this->product->id;
		$rules=[

			'name' => 'required',

			/*'key' => 'required|alpha_dash|is_unique[product,key,'.$id.']',

			'provider_key' => [
				'label' => lang('provider'),
				'rules' => 'required',
			],*/

			'price' => 'required',

			'price_par' => 'required',

		];
		if($this->product->type != ProductType::SHIP){
			$rules['key']='required|alpha_dash|is_unique[product,key,'.$id.']';
			$rules['provider_key']=[
				'label' => lang('provider'),
				'rules' => 'required',
			];
		}
		//pr($rules);
		return$rules ;
	}

	/**
	 * Gan rules validate
	 *
	 * @return array
	 */
	public function validation()
	{
		$this->setValidationRules($rules = $this->rules());

		return array_keys($rules);
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

		$product = $this->product;

		$data = $this->data();

		if($this->product->type == ProductType::SHIP){
			$data['provider_key'] = 'Site';
		}

		if ($product->getKey())
		{
		    ProductFactory::product()->edit($product, $data);
		}
		else
		{
			$data['type'] = $product->type;
			$product = ProductFactory::product()->add($data);

			model('file')->update_table_id_of_mod($this->getTable(), $this->getFakeId(), $product->id);
			fake_id_del($this->getTable());
		}
	}

	/**
	 * Validate form
	 *
	 * @return bool
	 */
	protected function validateForm()
	{
		if ($this->input('price') <= 0)
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
	protected function data()
	{
		$data = $this->inputOnly($this->params());

		if ($image = $this->getImage())
		{
			$data['image_id']	= $image->id;
			$data['image_name']	= $image->file_name;
		}

		return array_merge($data, [
			'price_original'  => $this->getPriceOriginalValue(),
			'prices_currency' => $this->getPricesCurrencyValue(),
			'discounts'       => $this->getdiscountsValue(),
			'feature'         => $this->input('feature') ? now() : 0,
			'status_sell'     => !$this->input('stop_sell'),
		]);
	}

	/**
	 * Lay gia tri cua price_original
	 *
	 * @return float
	 */
	protected function getPriceOriginalValue()
	{
		$price_original = $this->input('price_original');

		return $price_original > 0 ? $price_original : $this->input('price');
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

		return array_filter($list);
	}

	/**
	 * Lay discounts
	 *
	 * @return array
	 */
	protected function getdiscountsValue()
	{
		$list = $this->input('discounts', []);

		foreach ($list as &$value)
		{
			$value = min(max(0, (float) $value), 100);
		}

		return array_filter($list);
	}

	/**
	 * Lay thong tin image
	 *
	 * @return FileModel|null
	 */
	protected function getImage()
	{
		$id = $this->product->id ?: $this->getFakeId();

		return FileModel::findWhere([
			'table'       => $this->getTable(),
			'table_id'    => $this->product->id ?: $this->getFakeId(),
			'table_field' => 'image',
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

		$title = $product->getKey() ? 'edit' : 'add';
		$title = lang('title_product_'.$title);

		$currency = CurrencyFactory::currency()->getDefault();

		$currencies = CurrencyFactory::currency()->lists();

		$cats = ProductFactory::cat()->treeList();

		$providers = ProductFactory::providerManager()->listInstalled();

		$user_groups = UserFactory::userGroup()->lists();

		$upload_image = $this->makeUploadImageConfig();

		return compact(
			'product', 'title', 'currency', 'currencies', 'cats', 'providers', 'user_groups',
			'upload_image'
		);
	}

	/**
	 * Tao upload config
	 *
	 * @return array
	 */
	protected function makeUploadImageConfig()
	{
		return [
			'mod'         => 'single',
			'file_type'   => 'image',
			'status'      => config('file_public', 'main'),
			'table'       => $this->getTable(),
			'table_id'    => $this->product->id ?: $this->getFakeId(),
			'table_field' => 'image',
			'resize'      => true,
			'thumb'       => true,
			'url_update'  => $this->product->id ? current_url().'?act=update_image' : null,
		];
	}

	/**
	 * Lay fake id
	 *
	 * @return string
	 */
	protected function getFakeId()
	{
		return fake_id_get($this->getTable());
	}

	/**
	 * Lay table hien tai
	 *
	 * @return string
	 */
	protected function getTable()
	{
		return $this->product->getTable();
	}
}