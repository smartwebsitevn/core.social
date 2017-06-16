<?php namespace App\User\Handler\Form;

use Core\Base\FormHandler;
use App\User\UserFactory as UserFactory;
use App\User\Model\UserGroupModel as UserGroupModel;
use App\Payment\PaymentFactory as PaymentFactory;
use App\Currency\CurrencyFactory as CurrencyFactory;
use Core\Support\Number;

class UserGroup extends FormHandler
{
	/**
	 * Doi tuong UserGroupModel
	 *
	 * @var UserGroupModel
	 */
	protected $user_group;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param UserGroupModel $user_group
	 * @param array          $input
	 */
	public function __construct(UserGroupModel $user_group, array $input = null)
	{
		$this->user_group = $user_group;

		parent::__construct($input);
	}

	/**
	 * Lay params
	 *
	 * @return array
	 */
	protected function params()
	{
		return ['name', 'desc', 'discount', 'balance_send_amount_daily', 'payments', 'status'];
	}

	/**
	 * Lay rules
	 *
	 * @return array
	 */
	protected function rules()
	{
		return [
			'name'     => 'required',
			'desc'     => 'required',
			//'discount' => 'required',
			'balance_send_amount_daily' => 'required',
//			'payments' => 'required',
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
		$user_group = $this->user_group;

		$data = $this->data();

		if ($user_group->getKey())
		{
			UserFactory::userGroup()->edit($user_group, $data);
		}
		else
		{
			UserFactory::userGroup()->add($data);
		}
	}

	/**
	 * Lay form data
	 *
	 * @return array
	 */
	protected function data()
	{
		return array_merge($this->inputOnly($this->params()), [
			'discount' => $this->getDiscountValue(),
			'balance_send_amount_daily' => Number::handleAmountInput($this->input('balance_send_amount_daily')),
			'payments' => $this->getPaymentsInput(),
		]);
	}

	/**
	 * Lay discount input
	 *
	 * @return mixed
	 */
	protected function getDiscountValue()
	{
		$discount = Number::handleAmountInput($this->input('discount'));

		return min(max(0, $discount), 100);
	}

	/**
	 * Tao payments input
	 *
	 * @return array
	 */
	protected function getPaymentsInput()
	{
		$payments = $this->input('payments', []);

		$payments = collect($payments)->whereLoose('status', true)->all();

		foreach ($payments as &$row)
		{
			$row = [
				'amount_daily' => Number::handleAmountInput($row['amount_daily']),
			];
		}

		return $payments;
	}

	/**
	 * Lay form view data
	 *
	 * @return array
	 */
	public function form()
	{
		$user_group = $this->user_group;

		$title = $user_group->getKey() ? 'edit' : 'add';
		$title = lang('title_user_group_'.$title);

		$payments = PaymentFactory::paymentManager()->lists();

		$currency = CurrencyFactory::currency()->getDefault();

		return compact('user_group', 'title', 'payments', 'currency');
	}

}