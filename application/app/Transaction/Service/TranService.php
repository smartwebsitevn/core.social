<?php namespace App\Transaction\Service;

use App\Invoice\InvoiceFactory as InvoiceFactory;
use App\Invoice\Model\InvoiceModel as InvoiceModel;
use App\LogActivity\LogActivityFactory as LogActivityFactory;
use App\LogActivity\Library\ActivityOwner as ActivityOwner;
use App\LogActivity\Model\LogActivityModel as LogActivityModel;
use App\Payment\PaymentFactory as PaymentFactory;
use App\Payment\Model\PaymentModel as PaymentModel;
use App\Transaction\Job\CreateTran;
use App\Transaction\Library\CreateTranOptions;
use App\Transaction\Library\TranStatus;
use App\Transaction\Model\TranModel as TranModel;
use App\User\Model\UserModel;

class TranService
{
	/**
	 * Tao tran moi
	 *
	 * @param CreateTranOptions $options
	 * @return TranModel
	 */
	public function create(CreateTranOptions $options)
	{
		return (new CreateTran($options))->handle();
	}

	/**
	 * Tao tran success
	 *
	 * @param InvoiceModel $invoice
	 * @param PaymentModel $payment
	 * @param array        $options
	 *    $options = [
	 * 		'payment_tran_id' => '',
	 * 		'payment_tran' => [],
	 * 		...
	 *    ]
	 * @return TranModel
	 */
	public function createTranSuccess(InvoiceModel $invoice, PaymentModel $payment, array $options = [])
	{
		$payment_tran = array_pull($options, 'payment_tran');

		$options = array_merge($options, [
			'invoice'    => $invoice,
			'payment'    => $payment,
			'pay_at'     => now(),
			'success_at' => now(),
		]);

		$tran = $this->create(new CreateTranOptions($options));

		if ($payment_tran)
		{
		    $tran->updateTranInfo(compact('payment_tran'));
		}

		$this->active($tran);

		return $tran;
	}

	/**
	 * Kich hoat giao dich
	 *
	 * @param TranModel $tran
	 * @param array     $options
	 * 		$options = [
	 * 			'owner' => null,
	 * 		];
	 */
	public function active(TranModel $tran, array $options = [])
	{
		$tran->update(['status' => TranStatus::SUCCESS]);

		$this->logActivity('active', $tran, array_get($options, 'owner'));

		InvoiceFactory::invoice()->active($tran->invoice);
	}

	/**
	 * Giao dich that bai
	 *
	 * @param TranModel $tran
	 * @param array     $options
	 * 		$options = [
	 * 			'owner' => null,
	 * 		];
	 */
	public function fail(TranModel $tran, array $options = [])
	{
		$tran->update(['status' => TranStatus::FAILED]);

		$this->logActivity('fail', $tran, array_get($options, 'owner'));
	}

	/**
	 * Huy giao dich
	 *
	 * @param TranModel $tran
	 * @param array     $options
	 * 		$options = [
	 * 			'owner' => null,
	 * 		];
	 */
	public function cancel(TranModel $tran, array $options = [])
	{
		$tran->update(['status' => TranStatus::CANCELED]);

		$this->logActivity('cancel', $tran, array_get($options, 'owner'));
	}

	/**
	 * Log activity
	 *
	 * @param string        $action
	 * @param TranModel     $tran
	 * @param ActivityOwner $owner
	 * @param array         $context
	 * @return LogActivityModel
	 */
	public function logActivity($action, TranModel $tran, ActivityOwner $owner = null, array $context = [])
	{
		$logger = LogActivityFactory::logger('Tran');

		$context['tran'] = $tran->getAttributes();

		return $logger->log($action, $tran->id, $owner, $context);
	}

	/**
	 * Tao thong tin tran tuong ung voi payment
	 *
	 * @param InvoiceModel $invoice
	 * @param PaymentModel $payment
	 * @return array
	 */
	public function makeTranDataWithPayment(InvoiceModel $invoice, PaymentModel $payment)
	{
		$currency = $payment->currency;

		$payment_amount = InvoiceFactory::invoice()->getAmountCurrency($invoice, $currency->id);

		$payment_fee = PaymentFactory::payment()->getFee($payment, $payment_amount);

		$payment_net = $payment_amount + $payment_fee;

		$amount = currency_convert_amount_default($payment_net, $currency->id);

		return [
			'amount'         => $amount,
			'payment_id'     => $payment->id,
			'payment_key'    => $payment->key,
			'payment_amount' => $payment_amount,
			'payment_fee'    => $payment_fee,
			'payment_net'    => $payment_net,
			'currency_id'    => $currency->id,
			'currency_code'  => $currency->code,
			'currency_value' => $currency->value,
		];
	}

	/**
	 * Xu ly xem thong tin giao dich phat sinh ben cong thanh toan
	 *
	 * @param TranModel $tran
	 * @return array|string
	 */
	public function viewPaymentTran(TranModel $tran)
	{
		$payment_tran = $tran->tran_info ? $tran->tran_info->payment_tran : [];

		if ($tran->payment_key)
		{
			$payment_tran = PaymentFactory::makePaygateService($tran->payment_key)->viewPaymentTran($payment_tran);
		}

		if (is_array($payment_tran))
		{
			foreach ($payment_tran as $key => &$value)
			{
				if (is_array($value) || is_object($value))
				{
					$value = var_export($value, true);
				}
			}
		}

		return $payment_tran;
	}

	/**
	 * Lay tong so tien da thanh toan thanh cong qua payment cua user trong ngay hom nay
	 *
	 * @param PaymentModel $payment
	 * @param UserModel    $user
	 * @return float
	 */
	public function totalPaidAmountByPaymentOfUserInToday(PaymentModel $payment, UserModel $user)
	{
		$range = get_time_between(get_date());

		$result = t('db')->select_sum('amount')->where([
			'status'     => TranStatus::SUCCESS,
			'payment_id' => $payment->id,
			'user_id'    => $user->id,
			'created >=' => $range[0],
			'created <'  => $range[1],
		])->get('tran')->row();

		return (float) $result->amount;
	}

}