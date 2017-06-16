<?php namespace App\Invoice\Handler\Request;

use App\Transaction\Library\CreateTranOptions;
use Core\Base\RequestHandler;
use App\Invoice\InvoiceFactory as InvoiceFactory;
use App\Invoice\Model\InvoiceModel as InvoiceModel;
use App\Payment\PaymentFactory as PaymentFactory;
use App\Payment\Model\PaymentModel as PaymentModel;
use App\Transaction\TranFactory as TranFactory;
use App\Transaction\Model\TranModel as TranModel;
use App\User\UserFactory as UserFactory;

class InvoicePayment extends RequestHandler
{
	/**
	 * Data
	 *
	 * @var array
	 */
	protected $data = [];


	/**
	 * Thuc hien xu ly
	 *
	 * @return mixed
	 */
	public function handle()
	{
		try
		{
			$this->validate();
		}
		catch (\Exception $e)
		{
			return $this->error($e->getMessage());
		}

		if ($this->isPaymentPay())
		{
		    return $this->paymentPay();
		}

		return $this->paymentList();
	}

	/**
	 * Validate du lieu
	 *
	 * @throws \Exception
	 */
	protected function validate()
	{
		if ( ! $this->getInvoice())
		{
			throw new \Exception(lang('notice_value_not_exist', lang('invoice')));
		}

		if ( ! $this->getInvoice()->can('pay'))
		{
			throw new \Exception(lang('notice_can_not_do'));
		}

		if ( ! $this->checkToken())
		{
			throw new \Exception(lang('notice_value_invalid', lang('token')));
		}

		if ( ! $this->checkAccess())
		{
			throw new \Exception(lang('notice_do_not_have_permission'));
		}
	}

	/**
	 * Kiem tra token
	 *
	 * @return bool
	 */
	protected function checkToken()
	{
		return $this->getInvoice()->token('payment') === $this->input('token');
	}

	/**
	 * Kiem tra quyen truy cap
	 *
	 * @return bool
	 */
	protected function checkAccess()
	{
		return UserFactory::auth()->checkAccess([
			'user_id' => $this->getInvoice()->user_id,
			'ip'      => $this->getInvoice()->user_ip,
		]);
	}

	/**
	 * Xu ly trang list payments
	 *
	 * @return array
	 */
	protected function paymentList()
	{
		return [
			'invoice'  => $this->getInvoice(),
			'payments' => $this->makePayments(),
		];
	}

	/**
	 * Tao danh sach payment
	 *
	 * @return array
	 */
	protected function makePayments()
	{
		$payments = PaymentFactory::paymentManager()->listActive();

		$list = [];

		foreach ($payments as $payment)
		{
			if ( ! $this->canUsePayment($payment)) continue;

			$amount = $this->getPaymentAmount($payment);

			$list[] = [
				'payment'       => $payment,
				'amount'        => $amount,
				'url_pay'       => $this->getInvoice()->url('payment').'&payment_id='.$payment->id,
				'format_amount' => currency_format_amount($amount, $payment->currency_id),
			];
		}

		return $list;
	}

	/**
	 * Kiem tra co the su dung payment de thanh toan hay khong
	 *
	 * @param PaymentModel $payment
	 * @return bool
	 */
	protected function canUsePayment(PaymentModel $payment)
	{
		$user = UserFactory::auth()->user();

		return (
			$payment->can('payment')
			&& PaymentFactory::payment()->canUseByUser($payment, $user)
			&& PaymentFactory::payment()->canUseForInvoice($payment, $this->getInvoice())
		);
	}

	/**
	 * Lay so tien can thanh toan cua invoice tuong ung voi payment
	 *
	 * @param PaymentModel $payment
	 * @return float
	 */
	protected function getPaymentAmount(PaymentModel $payment)
	{
		$amount = InvoiceFactory::invoice()->getAmountCurrency($this->getInvoice(), $payment->currency_id);

		$fee = PaymentFactory::payment()->getFee($payment, $amount);

		return $amount + $fee;
	}

	/**
	 * Xu ly trang payment pay
	 */
	protected function paymentPay()
	{
		$payment = $this->getPayment();

		if ( ! $payment || ! $this->canUsePayment($payment))
		{
			return $this->error(lang('notice_value_invalid', lang('payment')));
		}
		$payment_params = $this->getPaymentParams();
		$tran = $this->getInvoiceTran();

		$url_pay = PaymentFactory::service()->urlPaymentPay($tran, $payment,$payment_params);

		redirect($url_pay);
	}

	/**
	 * Kiem tra request hien tai co phai trang payment pay hay khong
	 *
	 * @return bool
	 */
	public function isPaymentPay()
	{
		return $this->input('payment_id') > 0;
	}

	/**
	 * Lay tran cua invoice
	 *
	 * @return TranModel
	 */
	protected function getInvoiceTran()
	{
		$trans = $this->getInvoice()->trans;

		return $trans->count() ? $trans->last() : $this->createInvoiceTran();
	}

	/**
	 * Tao tran cho invoice
	 *
	 * @return TranModel
	 */
	protected function createInvoiceTran()
	{
		$options = new CreateTranOptions([
			'invoice' => $this->getInvoice(),
		]);

		return TranFactory::tran()->create($options);
	}

	/**
	 * Xu ly response error
	 *
	 * @param string $error
	 */
	protected function error($error)
	{
		set_message($error);

		redirect();
	}

	/**
	 * Lay thong tin invoice
	 *
	 * @return InvoiceModel|null
	 */
	public function getInvoice()
	{
		if ( ! array_key_exists('invoice', $this->data))
		{
			$this->data['invoice'] = InvoiceModel::find($this->input('invoice_id'));
		}

		return $this->data['invoice'];
	}

	/**
	 * Lay thong tin payment
	 *
	 * @return PaymentModel|null
	 */
	public function getPayment()
	{
		if ( ! array_key_exists('payment', $this->data))
		{
			$this->data['payment'] = PaymentFactory::paymentManager()->findById($this->input('payment_id'));
		}

		return $this->data['payment'];
	}

	// lay cac tham so rieng cua cong thanh toan
	public function getPaymentParams()
	{
		$url = parse_url(current_url(true));
		if(!isset($url['query']) || !$url['query'])
			return '';
		parse_str($url['query'], $query);
		// loai bo cac param yeu cau
		$not_include_key =array('payment_id','token');
		if($query ){
			foreach($query as $k=>$v){
				if(in_array($k,$not_include_key)){
					unset($query[$k]);
				}
			}
		}
		//pr($query);
		return $query;

	}
}