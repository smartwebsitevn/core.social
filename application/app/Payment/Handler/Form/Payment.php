<?php namespace App\Payment\Handler\Form;

use App\Payment\PaymentFactory as PaymentFactory;
use App\Payment\Library\PayGateFactory;
use App\Payment\Model\PaymentModel as PaymentModel;
use App\Currency\CurrencyFactory as CurrencyFactory;
use Core\Base\FormHandler;
use Core\Support\Number;

class Payment extends FormHandler
{
	/**
	 * Doi tuong PaymentModel
	 *
	 * @var PaymentModel
	 */
	protected $payment;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param PaymentModel $payment
	 * @param array        $input
	 */
	public function __construct(PaymentModel $payment, array $input = null)
	{
		$this->payment = $payment;

		parent::__construct($input);
	}

	/**
	 * Lay params
	 *
	 * @return array
	 */
	protected function params()
	{
		$params = ['name', 'desc', 'currency_id', 'status', 'options', 'setting'];

		if ( ! $this->payment->getKey())
		{
		    array_push($params, 'key');
		}

		return $params;
	}

	/**
	 * Lay rules
	 *
	 * @return array
	 */
	protected function rules()
	{
		$id = (int) $this->payment->getKey();

		$rules = [
			'key'  => 'required|alpha_dash|is_unique[payment,key,'.$id.']',
			'name' => 'required',
			'desc' => 'required',
			'currency_id' => 'required',
		];

		$setting_rules = $this->paygateInstance()->settingRules('setting');

		return array_merge($rules, $setting_rules);
	}

	/**
	 * Gan rules validate
	 *
	 * @return array
	 */
	public function validation()
	{
		$rules = $this->rules();

		if ( ! in_array('key', $this->params()))
		{
			unset($rules['key']);
		}

		$this->setValidationRules($rules);

		return array_keys($rules);
	}

	/**
	 * Kiem tra setting
	 *
	 * @param null $error
	 * @return bool
	 */
	protected function validateSetting(&$error = null)
	{
		$setting = $this->input('setting', []);

		if ( ! $this->paygateInstance()->validateSetting($setting, $error))
		{
			$error = $error ?: lang('notice_value_invalid', lang('setting'));

			return false;
		}

		return true;
	}

	/**
	 * Xu ly form khi du lieu hop le
	 *
	 * @return mixed
	 */
	public function submit()
	{
		if ( ! $this->validateSetting($error))
		{
			return [
				'complete' => false,
				'setting'  => $error,
			];
		}

		$payment = $this->payment;

		$data = $this->data();

		if ($payment->getKey())
		{
			PaymentFactory::payment()->edit($payment, $data);
		}
		else
		{
			$data['paygate_key'] = $payment->paygate_key;

			PaymentFactory::payment()->add($data);
		}
	}

	/**
	 * Lay form data
	 *
	 * @return array
	 */
	protected function data()
	{
		$data = $this->inputOnly($this->params());

		$data['options'] = $this->getOptionsInput();

		return $data;
	}

	/**
	 * Lay options input
	 *
	 * @return array
	 */
	protected function getOptionsInput()
	{
		$keys = [
			'fee_constant', 'fee_percent', 'fee_min', 'fee_max',
			'amount_min', 'amount_max',
			'can_payment', 'can_deposit', 'can_withdraw',
		];

		$options = [];

		foreach ($keys as $key)
		{
			$value = $this->input('options.'.$key);

			if (in_array($key, ['fee_constant', 'fee_percent', 'fee_min', 'fee_max', 'amount_min', 'amount_max']))
			{
				$value = Number::handleAmountInput($value, ['natural' => true]);
			}

			$options[$key] = $value;
		}

		return $options;
	}

	/**
	 * Lay form view data
	 *
	 * @return array
	 */
	public function form()
	{
		$payment = $this->payment;

		$title = $payment->getKey() ? 'edit' : 'add';
		$title = lang('title_payment_'.$title);

		$paygate = $this->paygateInstance();

		$setting_config = $paygate->settingFormConfig('setting', $payment->setting);

		$setting_form = $paygate->settingForm($setting_config);

		$currencies = CurrencyFactory::currency()->lists();

		$payment_services = $this->getPaymentServices();

		return compact('payment', 'title', 'setting_config', 'setting_form', 'currencies', 'payment_services');
	}

	/**
	 * Lay cac dich vu duoc ho tro cua payment
	 *
	 * @return array
	 */
	protected function getPaymentServices()
	{
		$services = [];

		$paygate = $this->payment->paygateServiceInstance();

		if ($paygate->canPayment())
		{
		    array_push($services, 'payment');
		}

		if ( ! $paygate->useBalance() && $paygate->canPayment())
		{
		    array_push($services, 'deposit');
		}

		if ( ! $paygate->useBalance() && $paygate->canWithdraw())
		{
		    array_push($services, 'withdraw');
		}

		return $services;
	}

	/**
	 * Lay doi tuong PayGateFactory
	 *
	 * @return PayGateFactory
	 */
	protected function paygateInstance()
	{
		return PaymentFactory::makePaygate($this->payment->paygate_key);
	}

}