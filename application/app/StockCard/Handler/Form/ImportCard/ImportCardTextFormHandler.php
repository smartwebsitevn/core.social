<?php namespace App\StockCard\Handler\Form\ImportCard;

use App\StockCard\Library\CardImportParser;

class ImportCardTextFormHandler extends ImportCardFormHandler
{
	/**
	 * Cac bien can xu ly
	 *
	 * @return array
	 */
	protected function params()
	{
		return ['product_id', 'desc', 'import_text'];
	}

	/**
	 * Lay rules
	 *
	 * @return array
	 */
	protected function rules()
	{
		$rules = [

			'product_id' => [
				'label' => lang('product'),
				'rules' => 'required',
			],

		];

		if ( ! $this->isPageConfirm())
		{
		    $rules['import_text'] = [
				'label' => lang('list_cards'),
				'rules' => 'required',
			];
		}

		return $rules;
	}

	/**
	 * Validate form
	 *
	 * @return bool
	 */
	protected function validateForm()
	{
		if ( ! $this->isPageConfirm())
		{
			if ( ! $this->checkImportText())
			{
				$this->errors['import_text'] = lang('notice_value_invalid', lang('list_cards'));

				return false;
			}
		}

		return true;
	}

	/**
	 * Kiem tra import_text
	 *
	 * @return bool
	 */
	protected function checkImportText()
	{
		$text = $this->input('import_text');

		$cards = (new CardImportParser('text'))->parse($text);

		return count($cards) > 0;
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

		return admin_url('stock_card/import_text_confirm');
	}

	/**
	 * Xu ly form confirm
	 *
	 * @return array
	 */
	protected function formConfirm()
	{
		if ( ! $form = ImportCardForm::get())
		{
			return redirect_admin('stock_card/import_text');
		}

		$cards = (new CardImportParser('text'))->parse($form->import_text);

		return compact('form', 'cards');
	}

}