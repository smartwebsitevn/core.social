<?php namespace App\Invoice\InvoiceService;

use App\Invoice\Library\InvoiceService;
use App\Invoice\Library\ServiceType;
use App\Invoice\Model\InvoiceOrderModel as InvoiceOrderModel;
use App\Payment\Model\PaymentModel;
use App\Payment\PaymentFactory as PaymentFactory;
use App\Withdraw\WithdrawFactory as WithdrawFactory;
use App\Withdraw\Model\WithdrawModel as WithdrawModel;

class WithdrawPayment extends InvoiceService
{
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		t('lang')->load('modules/withdraw/common');
	}

	/**
	 * Lay loai dich vu (lay theo ServiceType::***)
	 *
	 * @return string
	 */
	public function type()
	{
		return ServiceType::WITHDRAW;
	}

	/**
	 * Lay thong tin
	 *
	 * @return array
	 */
	public function info()
	{
		return [
			'name' => 'Rút tiền',
		];
	}

	/**
	 * Lay mo ta cua order
	 *
	 * @param InvoiceOrderModel $invoice_order
	 * @return null|string|array
	 */
	public function getOrderDesc(InvoiceOrderModel $invoice_order)
	{
		$options = $invoice_order->order_options;

		$amount = array_get($options, 'amount');

		$currency_id = array_get($options, 'currency_id');

		$payment_id = array_get($options, 'payment_id');

		$payment = PaymentFactory::paymentManager()->findById($payment_id);

		return lang('order_desc_withdraw_payment', [
			'purse'   => array_get($options, 'purse_number'),
			'amount'  => currency_format_amount($amount, $currency_id),
			'payment' => $payment->name,
		]);
	}

	/**
	 * View invoice
	 *
	 * @param InvoiceOrderModel $invoice_order
	 * @return string|null
	 */
	public function view(InvoiceOrderModel $invoice_order)
	{
		$withdraw = WithdrawModel::findByInvoiceOrder($invoice_order->id);

		if ( ! $withdraw) return null;

		$withdraw->invoice_order = $invoice_order;
       
		$receiver = $this->viewWithdrawReceiver($withdraw->payment, $withdraw->receiver);
        
		$data = compact('withdraw', 'receiver');

		if (get_area() == 'admin')
		{
			$data['log_activities'] = WithdrawFactory::withdraw()->activityLogger()->listLogs([
				'key' => $withdraw->id,
			]);
		}

		return view('tpl::withdraw/view', $data, true);
	}

	/**
	 * Tao du lieu view withdraw receiver
	 *
	 * @param PaymentModel $payment
	 * @param array        $receiver
	 * @return array|string
	 */
	protected function viewWithdrawReceiver(PaymentModel $payment, array $receiver)
	{
		return $payment->paygateServiceInstance()->withdraw()->view($receiver);
	}

}