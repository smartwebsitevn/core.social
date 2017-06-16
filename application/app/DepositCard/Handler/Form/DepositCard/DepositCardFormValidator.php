<?php namespace App\DepositCard\Handler\Form\DepositCard;

use App\DepositCard\Command\DepositCardCommand;
use App\DepositCard\Validator\DepositCard\DepositCardException;
use App\DepositCard\Validator\DepositCard\DepositCardValidator;

class DepositCardFormValidator
{
	/**
	 * Doi tuong DepositCardFormRequest
	 *
	 * @var DepositCardFormRequest
	 */
	protected $request;

	/**
	 * Form errors
	 *
	 * @var array
	 */
	protected $errors = [];


	/**
	 * Khoi tao doi tuong
	 *
	 * @param DepositCardFormRequest $request
	 */
	public function __construct(DepositCardFormRequest $request)
	{
		$this->request = $request;
	}

	/**
	 * Lay rules
	 *
	 * @return array
	 */
	public function rules()
	{

		$rules = [

			'type' => [
				'label' => lang('required|trim|callback__check_type'),
				'rules' => 'required',
			],

			'code' => [
				'label' => lang('card_code'),
				'rules' => 'required|trim|xss_clean',
			],

			'serial' => [
				'label' => lang('card_serial'),
				'rules' => 'required|trim|xss_clean',
			],

			'card' => [
				'label' => lang('card'),
				'rules' => 'callback__check_card',
			],

		];

		return $rules;
	}

	/**
	 * Thuc hien validate
	 *
	 * @return bool
	 */
	public function validate()
	{
		if ( ! $this->request->getSenderPurse())
		{
			$this->errors['sender_purse_number'] = lang('notice_value_not_exist', lang('purse'));

			return false;
		}

		if ( ! $this->request->getReceiverPurse())
		{
			$this->errors['receiver_purse_number'] = lang('notice_value_not_exist', lang('purse'));

			return false;
		}

		$error = null;
		if ( ! $this->checkDepositCard($error))
		{
			$this->errors['transfer'] = $error;

			return false;
		}

		if ($this->request->isPageConfirm())
		{
			if ( ! $this->checkUserSecurity())
			{
				$this->errors[mod('user_security')->param()] = mod('user_security')->getErrorMessage();

				return false;
			}
		}

		return true;
	}


	/**
	 * Kiem tra user_security
	 *
	 * @return bool
	 */
	protected function checkUserSecurity()
	{
		$param = mod('user_security')->param();

		$value = $this->request->get($param);

		return mod('user_security')->valid('transfer', $value);
	}

	/**
	 * Lay error
	 *
	 * @param string $key
	 * @return string
	 */
	public function error($key = null)
	{
		return array_get($this->errors, $key);
	}

}