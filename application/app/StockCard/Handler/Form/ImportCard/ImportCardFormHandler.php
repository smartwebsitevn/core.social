<?php namespace App\StockCard\Handler\Form\ImportCard;

use App\Admin\AdminFactory;
use App\Product\Library\ProductType;
use App\Product\Model\ProductModel;
use App\StockCard\Job\ImportCards;
use Core\Base\FormHandler;
use TF\Support\Collection;

abstract class ImportCardFormHandler extends FormHandler
{
	/**
	 * Lay rules
	 *
	 * @return array
	 */
	abstract protected function rules();

	/**
	 * Submit page confirm
	 *
	 * @return string
	 */
	abstract protected function submitConfirm();

	/**
	 * Submit page form
	 *
	 * @return string
	 */
	abstract protected function submitForm();

	/**
	 * Xu ly form confirm
	 *
	 * @return array
	 */
	abstract protected function formConfirm();

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

		return $this->isPageConfirm()
			? $this->submitConfirm()
			: $this->submitForm();
	}

	/**
	 * Validate form
	 *
	 * @return bool
	 */
	protected function validateForm()
	{
		return true;
	}

	/**
	 * Lay form view data
	 *
	 * @return array
	 */
	public function form()
	{
		return $this->isPageConfirm()
			? $this->formConfirm()
			: $this->formForm();
	}

	/**
	 * Xu ly form nhap du lieu
	 *
	 * @return array
	 */
	protected function formForm()
	{
		$products = $this->getProducts();

		return compact('products');
	}

	/**
	 * Thuc hien import cards
	 */
	protected function importCards()
	{
		$product_id = $this->input('product_id');

		$product = ProductModel::find($product_id);

		$cards = $this->input('cards', []);

		$admin = AdminFactory::auth()->user();

		$data = $this->inputOnly('desc');

		(new ImportCards($product, $cards, $admin, $data))->handle();
	}

	/**
	 * Lay danh sach products
	 *
	 * @return Collection
	 */
	protected function getProducts()
	{
		$list = model('product')->filter_get_list([
			'type' => ProductType::CARD,
		], [
			'order' => ['name', 'asc'],
		]);

		return ProductModel::makeCollection($list);
	}

	/**
	 * Kiem tra page hien tai co phai confirm hay khong
	 *
	 * @return bool
	 */
	protected function isPageConfirm()
	{
		return $this->input('page') == 'confirm';
	}

}