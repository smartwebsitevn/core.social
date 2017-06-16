<?php namespace App\StockCard\Handler\Form;

use App\Admin\AdminFactory;
use App\StockCard\StockCardFactory;
use Core\Base\FormHandler;
use App\StockCard\Model\StockCardModel;

class AuthCardFormHandler extends FormHandler
{
	/**
	 * Thong tin card
	 *
	 * @var StockCardModel
	 */
	protected $card;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param StockCardModel $card
	 * @param array          $input
	 */
	public function __construct(StockCardModel $card, array $input = null)
	{
		parent::__construct($input);

		$this->card = $card;
	}

	/**
	 * Lay rules
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'password' => 'required',
		];
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

		StockCardFactory::auth()->set($this->card);

		return $this->input('url_result') ?: $this->card->adminUrl('view');
	}

	/**
	 * Validate form
	 *
	 * @return bool
	 */
	protected function validateForm()
	{
		if ( ! $this->checkPasword())
		{
			$this->errors['password'] = lang('notice_value_incorrect', lang('password'));

			return false;
		}

		return true;
	}

	/**
	 * Kiem tra password
	 *
	 * @return bool
	 */
	protected function checkPasword()
	{
		return AdminFactory::auth()->checkPasword($this->input('password'));
	}

}