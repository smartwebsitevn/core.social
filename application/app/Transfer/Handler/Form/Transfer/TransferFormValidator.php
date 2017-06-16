<?php namespace App\Transfer\Handler\Form\Transfer;

use App\Transfer\Command\TransferCommand;
use App\Transfer\Validator\Transfer\TransferException;
use App\Transfer\Validator\Transfer\TransferValidator;

class TransferFormValidator
{
	/**
	 * Doi tuong TransferFormRequest
	 *
	 * @var TransferFormRequest
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
	 * @param TransferFormRequest $request
	 */
	public function __construct(TransferFormRequest $request)
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

			'sender_purse_number' => [
				'label' => lang('sender_purse'),
				'rules' => 'required',
			],

			'receiver_purse_number' => [
				'label' => lang('receiver_purse'),
				'rules' => 'required',
			],

			'amount' => [
				'label' => lang('transfer_amount'),
				'rules' => 'required',
			],

			'desc' => [
				'label' => lang('transfer_desc'),
				'rules' => 'required',
			],

		];

		if ($this->request->isPageConfirm())
		{
			$rules[mod('user_security')->param()] = mod('user_security')->rules();
		}
		else
		{
			$rules['security_code'] = 'required|captcha';
		}

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
		if ( ! $this->checkTransfer($error))
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
	 * Kiem tra thong tin transfer
	 *
	 * @param string $error
	 * @return bool
	 */
	protected function checkTransfer(&$error = null)
	{
		$command = new TransferCommand([
			'sender'         => $this->request->getSender(),
			'sender_purse'   => $this->request->getSenderPurse(),
			'receiver_purse' => $this->request->getReceiverPurse(),
			'amount'         => $this->request->get('amount'),
			'desc'           => $this->request->get('desc'),
		]);

		$validator = new TransferValidator($command);

		try
		{
			$validator->validate();

			return true;
		}
		catch (TransferException $e)
		{
			$error = $e->getMessage();

			return false;
		}
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