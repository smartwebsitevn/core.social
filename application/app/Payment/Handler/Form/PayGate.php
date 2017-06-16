<?php namespace App\Payment\Handler\Form;

use Core\Base\FormHandler;
use App\Payment\PaymentFactory as PaymentFactory;
use App\Payment\Model\PayGateModel as PayGateModel;

class PayGate extends FormHandler
{
	/**
	 * Doi tuong PayGateModel
	 *
	 * @var PayGateModel
	 */
	protected $paygate;

	/**
	 * Khoi tao doi tuong
	 *
	 * @param PayGateModel $paygate
	 * @param array          $input
	 */
	public function __construct(PayGateModel $paygate, array $input = null)
	{
		$this->paygate = $paygate;

		parent::__construct($input);
	}

	/**
	 * Lay params
	 *
	 * @return array
	 */
	protected function params()
	{
		return ['name', 'desc', 'status'];
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
			'desc' => 'required',
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
	 */
	public function submit()
	{
		$paygate = $this->paygate;

		$data = $this->inputOnly($this->params());

		if ($paygate->getKey())
		{
			PaymentFactory::paygate()->edit($paygate, $data);
		}
		else
		{
			PaymentFactory::paygate()->install($paygate->key, $data);
		}
	}

	/**
	 * Lay form view data
	 *
	 * @return array
	 */
	public function form()
	{
		$paygate = $this->paygate;

		$title = $paygate->getKey() ? 'edit' : 'install';
		$title = lang('title_paygate_'.$title);

		return compact('paygate', 'title');
	}

}