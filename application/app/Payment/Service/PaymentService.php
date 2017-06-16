<?php namespace App\Payment\Service;

use App\Invoice\InvoiceFactory;
use App\Invoice\Library\ServiceType;
use App\Invoice\Model\InvoiceModel;
use Core\Support\Number;
use App\Payment\Model\PaymentModel as PaymentModel;
use App\User\UserFactory as UserFactory;
use App\User\Model\UserModel as UserModel;

class PaymentService extends \Core\Base\ServiceModelMutator
{
	/**
	 * Them moi
	 *
	 * @param array $data
	 * @return PaymentModel
	 */
	public function add(array $data)
	{
		$data = array_add($data, 'sort_order', now());

		return PaymentModel::create($data);
	}

	/**
	 * Lay fee
	 *
	 * @param PaymentModel $payment
	 * @param float        $amount So tien da quy doi sang currency cua payment
	 * @return float
	 */
	public function getFee(PaymentModel $payment, $amount)
	{
		$setting = [];

		foreach (['constant', 'percent', 'min', 'max'] as $key)
		{
			$setting[$key] = array_get($payment->options, 'fee_'.$key);
		}

		return Number::getFee($amount, $setting);
	}

	/**
	 * Kiem tra user co duoc phep su dung payment hay khong
	 *
	 * @param PaymentModel $payment
	 * @param UserModel    $user
	 * @return bool
	 */
	public function canUseByUser(PaymentModel $payment, UserModel $user)
	{
		if ($payment->paymentByBalance() && $user->isGuest()) return false;

		return (
			$payment->status
			&& UserFactory::userGroup()->canUsePayment($user->user_group, $payment)
		);
	}

	/**
	 * Kiem tra co the su dung payment cho invoice hay khong
	 *
	 * @param PaymentModel $payment
	 * @param InvoiceModel $invoice
	 * @return bool
	 */
	public function canUseForInvoice(PaymentModel $payment, InvoiceModel $invoice)
	{
		$is_invoice_type = function($type) use ($invoice)
		{
			return InvoiceFactory::invoice()->hasInvoiceOrderOfServiceType($invoice, $type);
		};

		if ($is_invoice_type(ServiceType::ORDER) && ! $payment->can('payment')) return false;

		if ($is_invoice_type(ServiceType::DEPOSIT) && ! $payment->can('deposit')) return false;

		if ($is_invoice_type(ServiceType::WITHDRAW) && ! $payment->can('withdraw')) return false;

		return true;
	}

}