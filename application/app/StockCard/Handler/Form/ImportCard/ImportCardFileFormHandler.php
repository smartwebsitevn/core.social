<?php namespace App\StockCard\Handler\Form\ImportCard;

use App\File\Model\FileModel;
use App\StockCard\Library\CardImportParser;

class ImportCardFileFormHandler extends ImportCardFormHandler
{
	/**
	 * Cac bien can xu ly
	 *
	 * @return array
	 */
	protected function params()
	{
		return ['product_id', 'desc'];
	}

	/**
	 * Lay rules
	 *
	 * @return array
	 */
	protected function rules()
	{
		return [
			'product_id' => [
				'label' => lang('product'),
				'rules' => 'required',
			],
		];
	}

	/**
	 * Validate form
	 *
	 * @return bool
	 */
	protected function validateForm()
	{
		if ( ! $this->getFileImport())
		{
			$this->errors['import_file'] = lang('form_validation_required', ['field' => lang('file')]);

			return false;
		}

		return true;
	}

	/**
	 * Lay thong tin file import
	 *
	 * @return FileModel|null
	 */
	protected function getFileImport()
	{
		return FileModel::findWhere([
			'table'       => $this->getTable(),
			'table_id'    => $this->getFakeId(),
			'table_field' => $this->getFileField(),
		]);
	}

	/**
	 * Submit page confirm
	 *
	 * @return string
	 */
	protected function submitConfirm()
	{
		ImportCardForm::delete();

		$this->importCards();

		file_del_table($this->getTable(), $this->getFakeId(), $this->getFileField());
	}

	/**
	 * Submit page form
	 *
	 * @return string
	 */
	protected function submitForm()
	{
		$data = $this->inputOnly($this->params());

		(new ImportCardForm($data))->save();

		return admin_url('stock_card/import_confirm');
	}

	/**
	 * Xu ly form confirm
	 *
	 * @return array
	 */
	protected function formConfirm()
	{
		if (
			! ($form = ImportCardForm::get())
			|| ! ($file = $this->getFileImport())
		)
		{
			return redirect_admin('stock_card/import');
		}

		$cards = (new CardImportParser('file'))->parse($file->path);

		return compact('form', 'cards');
	}

	/**
	 * Xu ly form nhap du lieu
	 *
	 * @return array
	 */
	protected function formForm()
	{
		return array_merge(parent::formForm(), [
			'upload_file' => $this->makeUploadFileConfig(),
		]);
	}

	/**
	 * Tao config upload
	 *
	 * @return array
	 */
	protected function makeUploadFileConfig()
	{
		return [
			'mod'           => 'single',
			'file_type'     => 'file',
			'allowed_types' => 'xls|csv',
			'status'        => config('file_private', 'main'),
			'server'        => false,
			'table'         => $this->getTable(),
			'table_id'      => $this->getFakeId(),
			'table_field'   => $this->getFileField(),
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
		return 'stock_card';
	}

	/**
	 * Lay file field
	 *
	 * @return string
	 */
	protected function getFileField()
	{
		return 'import';
	}

}