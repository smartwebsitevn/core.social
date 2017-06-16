<?php namespace App\Product\Handler\Form;

use Core\Base\FormHandler;
use App\Product\ProductFactory as ProductFactory;
use App\Product\Model\CatModel as CatModel;

class Cat extends FormHandler
{
	/**
	 * Doi tuong CatModel
	 *
	 * @var CatModel
	 */
	protected $cat;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param CatModel $cat
	 * @param array    $input
	 */
	public function __construct(CatModel $cat, array $input = null)
	{
		$this->cat = $cat;

		parent::__construct($input);
	}

	/**
	 * Lay params
	 *
	 * @return array
	 */
	protected function params()
	{
		return ['name', 'parent_id', 'sort_order', 'status'];
	}

	/**
	 * Lay rules
	 *
	 * @return array
	 */
	protected function rules()
	{
		return [
			'name' => 'required',
		];
	}

	/**
	 * Gan rules validate
	 *
	 * @return array
	 */
	public function validation()
	{
		$rules = $this->rules();

		$this->setValidationRules($rules);

		return array_keys($rules);
	}

	/**
	 * Xu ly form khi du lieu hop le
	 *
	 * @return mixed
	 */
	public function submit()
	{
		$cat = $this->cat;

		$data = $this->data();

		if ($cat->getKey())
		{
			ProductFactory::cat()->edit($cat, $data);
		}
		else
		{
			ProductFactory::cat()->add($data);
		}

		return admin_url('cat').'?cat_id='.$data['parent_id'];
	}

	/**
	 * Lay form data
	 *
	 * @return array
	 */
	protected function data()
	{
		return $this->inputOnly($this->params());
	}

	/**
	 * Lay form view data
	 *
	 * @return array
	 */
	public function form()
	{
		$cat = $this->cat;

		$title = $cat->getKey() ? 'edit' : 'add';
		$title = lang('title_cat_'.$title);

		$cats = ProductFactory::cat()->roots();

		return compact('cat', 'title', 'cats');
	}

}